jQuery( function( $ ) {

	// Look for the div that should be filled with sync status data/controls.
	var $statusDiv = $( '.vm-sync-status' );

	// Bail if the status div isn't present.
	if ( ! $statusDiv.length ) {
		return;
	}

	var $statusText = $( '<div />' ).appendTo( $statusDiv ).hide();

	var $resumeButton = $( '<a />' ).attr( 'href', '#' ).addClass( 'button' ).appendTo( $statusDiv ).hide();
	$statusDiv.append( ' ' );
	var $startButton = $( '<a />' ).attr( 'href', '#' ).addClass( 'button' ).appendTo( $statusDiv ).hide();

	var $spinner = $( '<span />' ).addClass( 'spinner is-active' ).appendTo( $statusDiv );

	// TODO: make this a "RESET" button, instead of "RESUME".
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

			// Hide the loading spinner.
			$spinner.removeClass( 'is-active' );

			// Set status text.
			if ( 'complete' === status.stage ) {
				statusText = 'Sync is complete.';
			} else if ( status.last_active + ( 30 * 60 * 1000 ) > status.server_time ) {
				statusText = 'Sync is running.';
			} else {
				statusText = 'Sync is not running. Click "Sync Now" to begin syncing.';
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

			if ( 'added' in status && 'updated' in status && 'deleted' in status && 'skipped' in status ) {
				countText = status.added.toString() + ' added, ' + status.updated.toString() + ' updated, ' + status.deleted.toString() + ' deleted, ' + status.skipped.toString() + ' unchanged.'
			}

			// Display status text.
			$statusText.html( '<p><strong>' + statusText + '</strong><br/>' + stageText + '<br/>' + countText + '</p>' ).show();

			// Show/hide buttons depending on sync status.
			if ( ! status.stage || 'complete' === status.stage ) {
				$resumeButton.hide();
				$startButton.text( 'Sync Now' ).show();
			} else {
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
