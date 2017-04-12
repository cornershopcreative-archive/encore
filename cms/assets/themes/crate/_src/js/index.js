'use strict';

var jQuery = require('jQuery');

var serializeObject = require( 'jquery-serialize-object' );
// Extend jQuery object with functions from jquery-serialize-object (this
// doesn't happen automatically, for some reason)
jQuery.fn.serializeObject = serializeObject.FormSerializer.serializeObject;
jQuery.fn.serializeJSON = serializeObject.FormSerializer.serializeJSON;

var nav = require( 'nav' );
jQuery( nav );

var slider = require( 'slider' );
jQuery( slider );

var modals = require( 'modals' );
jQuery( modals );

var fwp_load_more = require( 'fwp-load-more' );
jQuery( fwp_load_more );

var fwp_vmatch_dropdowns = require( 'fwp-vmatch-dropdowns' );
jQuery( fwp_vmatch_dropdowns );

var volunteer_pager = require( 'volunteer-opportunities' );
jQuery( volunteer_pager );

var footer_form = require( 'footer-form' );
jQuery( footer_form );

var header_search_form = require( 'header-search-form' );
jQuery( header_search_form );

var dropdown_sections = require( 'dropdown-sections' );
jQuery( dropdown_sections );

var tickers = require( 'tickers' );
jQuery( tickers );
