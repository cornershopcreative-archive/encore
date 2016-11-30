/**
 * Super-basic localStorage polyfill.
 */

// Return local storage object, or fall back on a dummy storage object if this
// is an old browser.
module.exports = window.localStorage || {
	getItem: function() { return false; }
};
