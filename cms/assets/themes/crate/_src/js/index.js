'use strict';

var $ = require('jQuery');
var imagesLoaded = require('imagesloaded');

var slider = require('slider.js');

imagesLoaded( 'body', { background: true }, function() {
  console.log('site images loaded');
});

// Use slider script
slider();

$('body').addClass('jquery-working');