'use strict';

var $ = require('jQuery');

// example of requiring a JS package and implementing it
var imagesLoaded = require('imagesloaded');
imagesLoaded( 'body', { background: true }, function() {
  console.log('site images loaded');
});