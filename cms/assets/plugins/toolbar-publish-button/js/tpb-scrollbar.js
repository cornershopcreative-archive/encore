(function($) {

    function saveScrollbarCookies () {

        var scrollTop = 0,
            messageDivsOuterHeight = 0;


        $('[id="message"]').each( function() {
            messageDivsOuterHeight += $(this).outerHeight(true);
        });

        scrollTop = $(window).scrollTop() - messageDivsOuterHeight;

        $.cookie('TPBscrollTop', scrollTop);

        if ( typeof window.safecss_editor !== 'undefined' ) {

            var cursor = window.safecss_editor.selection.getCursor();


            $.cookie( 'TPBcurrentLineNumber', cursor[ 'row' ] );
            $.cookie( 'TPBtopLineNumber', window.safecss_editor.getFirstVisibleRow() );
        }

        if ( typeof window.acf !== 'undefined' ) {

            var acf_opened_field_groups = [];


            $('.acf-field-object.open').each( function( index ) {
                acf_opened_field_groups.push( $( this ).attr( 'data-key' ) );
            });

            $.cookie( 'TPBacfOpenFieldGroups', acf_opened_field_groups );
        }
    }


    function removeCookies () {

        $.removeCookie( 'TPBcurrentLineNumber' );
        $.removeCookie( 'TPBtopLineNumber' );
        $.removeCookie( 'TPBscrollTop' );
        $.removeCookie( 'TPBacfOpenFieldGroups' );
    }


    var button = $('input[type="submit"].button-primary, input[type="button"].button-primary'),
        draft_button = $('input[type="submit"]#save-post:visible').not('.find-box input');


    button
        .add(draft_button)
        .add('.row-actions.visible .activate a, .row-actions.visible .deactivate a')
        .click( function( event ) {
            saveScrollbarCookies();
        });


    $(window).on( 'load',function( event ) {

        var scrollTimeout = 0,
            currMessageDivOuterHeight = 0,
            currentLineNumber = $.cookie( 'TPBcurrentLineNumber' ) ? parseInt( $.cookie( 'TPBcurrentLineNumber' ) ) : 0,
            topLineNumber = $.cookie( 'TPBtopLineNumber' ) ? parseInt( $.cookie( 'TPBtopLineNumber' ) ) : 0,
            scrollTop = $.cookie( 'TPBscrollTop' ) ? parseInt( $.cookie( 'TPBscrollTop' ) ) : 0,
            n = $('.acf_wysiwyg').length ? $('.acf_wysiwyg').length : 0,
            acfOpenFieldGroups = $.cookie( 'TPBacfOpenFieldGroups' ) ? $.cookie( 'TPBacfOpenFieldGroups' ).split(/,/) : 0;


        if ( acfOpenFieldGroups && window.acf !== 'undefined' ) {

            scrollTimeout = 300;

            $.each( acfOpenFieldGroups, function( index, field_key ) {
                window.acf.field_group.open_field( $( '.acf-field-object[data-key=' + field_key + ']' ) );
            });
        }
        else {
            scrollTimeout = 15*window.n;
        }

        if ( scrollTop ) {

            setTimeout( function() {

                    $('[id="message"]').each(function() {
                        currMessageDivOuterHeight += $(this).outerHeight(true);
                    });
                    $(window).scrollTop( scrollTop + currMessageDivOuterHeight );

            }, scrollTimeout );
        }

        if ( topLineNumber && currentLineNumber && typeof window.safecss_editor !== 'undefined' ) {

            setTimeout( function() {

                window.safecss_editor.gotoLine( currentLineNumber+1 );
                window.safecss_editor.scrollToLine( topLineNumber+1, false, false, null );

            }, 1 );
        }

        removeCookies();
    });

})( jQuery );
