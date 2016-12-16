/**
 * Automatic pop-up modal, based loosely on Conditional Overlay code.
 */

var storage = require( 'localstorage.js' );

module.exports = function( $ ) {

	options = window.lightbox_opts || {};
	// var force = window.location.search.indexOf("forceoverlay");
	var alreadyOpened = false;
	var settings = $.extend({
		context : 'none',
		trigger : 'immediate',
		amount  : 1,
	}, options );

	console.log( settings );

	// do nothing if it's disabled...
	if ( 'none' === settings.context ) return;

	// ...if it's supposed to be on the homepage and we're not...
	if ( 'home' === settings.context && !$('body').hasClass('home') ) return;

	// ...or if the already-seen cookie is still around
	if ( storage.getItem( 'lightbox_seen_1' ) ) return;

	// let's see if we meet our open criteria!
	// right away
	if ( 'immediate' == settings.trigger ) {
		openLightbox();
	// delay by n seconds
	} else if ( 'delay' == settings.trigger ) {
		setTimeout( function() { openLightbox(); }, settings.amount * 1000 );
	}

	function openLightbox() {

		if ( alreadyOpened ) return;

		$.featherlight( $('#auto-lightbox-modal'), {type:'html'});
		alreadyOpened = true;

		// Remember that we've opened the lightbox.
		storage['lightbox_seen_1'] = 1;
	}

	function lightboxTimer() {
		var maxMinutes = settings.amount,
			timePassed = parseFloat($.cookie('coverlay-minutes-' + settings.id)) || 0;
		// increment every half-second (setInterval = 500)
		timePassed += 0.1;
		if ( timePassed >= maxMinutes ) {
			openLightbox();
			$.removeCookie('coverlay-minutes-' + settings.id, { path: '/' });
		} else {
			$.cookie('coverlay-minutes-' + settings.id, timePassed, { path: '/'});
		}
	}

};
