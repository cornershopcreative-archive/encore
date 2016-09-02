(function($) {
    'use strict';

    // Move header to last wrapper of page section if its added into page section. 
    var $header = $('.js-header-shortcode'),
        $parent_page_section = $header.parents('.mk-page-section'),
        $is_inside = $parent_page_section.attr('id');

    if ($is_inside) {
        $header.detach().appendTo($parent_page_section);
    }
})(jQuery);