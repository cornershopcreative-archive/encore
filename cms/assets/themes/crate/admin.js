jQuery( function( $ ) {

	// Look for the div that should be filled with sync status data/controls.
	var $statusDiv = $( '.vm-sync-status' );

	// Bail if the status div isn't present.
	if ( ! $statusDiv.length ) {
		return;
	}

	// If set to true, this will cause the loading spinner to keep spinning until
	// the result of a vmatch_sync_status AJAX call indicates that the process has
	// been paused.
	var isPausing = false;

	var $statusText = $( '<div />' ).appendTo( $statusDiv ).hide();

	var $pauseButton = $( '<a />' ).attr( 'href', '#' ).addClass( 'button' ).appendTo( $statusDiv ).hide();
	$statusDiv.append( ' ' );
	var $resumeButton = $( '<a />' ).attr( 'href', '#' ).addClass( 'button' ).appendTo( $statusDiv ).hide();
	$statusDiv.append( ' ' );
	var $startButton = $( '<a />' ).attr( 'href', '#' ).addClass( 'button' ).appendTo( $statusDiv ).hide();

	var $spinner = $( '<span />' ).addClass( 'spinner is-active' ).appendTo( $statusDiv );

	$pauseButton.click( function( e ) {
		e.preventDefault();
		isPausing = true;
		$spinner.addClass( 'is-active' );
		$.post( ajaxurl, { action: 'vmatch_sync_pause' }, showStatus );
	});

	$resumeButton.click( function( e ) {
		e.preventDefault();
		$spinner.addClass( 'is-active' );
		$.post( ajaxurl, { action: 'vmatch_sync_resume' }, showStatus );
	});

	$startButton.click( function( e ) {
		e.preventDefault();
		$spinner.addClass( 'is-active' );
		$.post( ajaxurl, { action: 'vmatch_sync_start' }, showStatus );
	});

	var showStatus = function() {
		$.get( ajaxurl, { action: 'vmatch_sync_status' }, function( status ) {

			var statusText = '', stageText = '', countText = '';

			if ( ! isPausing ) {
				// Hide the loading spinner.
				$spinner.removeClass( 'is-active' );
			} else {
				// If we're waiting for the process to pause, don't hide the spinner
				// unless the process has, in fact, paused.
				if ( status.paused ) {
					$spinner.removeClass( 'is-active' );
					isPausing = false;
				}
			}

			// Set status text.
			if ( status.paused ) {
				statusText = 'Sync is paused.';
			} else if ( 'complete' === status.stage ) {
				statusText = 'Sync is complete.';
			} else if ( status.last_active + ( 30 * 60 * 1000 ) > status.server_time ) {
				statusText = 'Sync is running.';
			} else {
				statusText = 'Sync process may have timed out. Try clicking "Resume Sync" below to resume it.';
			}

			// Set stage text.
			if ( 'get_local' === status.stage ) {
				stageText = 'Syncing location-based opportunities (step 1/3)';
				if ( status.total_pages ) {
					var progress = Math.floor( 100 * status.current_page / status.total_pages );
				 	stageText = stageText + ': ' + progress.toString() + '% complete.';
				}
			} else if ( 'get_virtual' === status.stage ) {
				stageText = 'Syncing virtual opportunities (step 2/3)';
				if ( status.total_pages ) {
					var progress = Math.floor( 100 * status.current_page / status.total_pages );
					stageText = stageText + ': ' + progress.toString() + '% complete.';
				}
			} else if ( 'cleanup' === status.stage ) {
				stageText = 'All opportunities synced; deleting old/expired posts (step 3/3).';
			} else if ( 'complete' === status.stage ) {
				stageText = 'Last sync began ' + status.start_string + ' and ended ' + status.end_string + '.';
			}

			countText = status.added.toString() + ' added, ' + status.updated.toString() + ' updated, ' + status.deleted.toString() + ' deleted, ' + status.skipped.toString() + ' unchanged.'

			// Display status text.
			$statusText.html( '<p><strong>' + statusText + '</strong><br/>' + stageText + '<br/>' + countText + '</p>' ).show();

			// Show/hide buttons depending on sync status.
			if ( status.paused ) {
				$pauseButton.text( 'Pause' ).hide();
				$resumeButton.text( 'Resume' ).show();
				$startButton.text( 'Restart' ).show();
			} else if ( 'complete' === status.stage ) {
				$pauseButton.hide();
				$resumeButton.hide();
				$startButton.text( 'Sync Now' ).show();
			} else {
				$pauseButton.text( 'Pause' ).show();
				$resumeButton.hide();
				$startButton.hide();
			}
		});
	};

	// Update status immediately.
	showStatus();
	// Update status again every four seconds.
	setInterval( showStatus, 4000 );
});
