<?php
$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

$id = Mk_Static_Files::shortcode_id();

$tabindex_1 = $id;
$tabindex_2 = $id + 1;
$tabindex_3 = $id + 2;
$tabindex_4 = $id + 3;
$tabindex_5 = $id + 4;
$tabindex_6 = $id + 5;
$tabindex_7 = $id + 6;
$tabindex_8 = $id + 7;

$fancy_title = $btn_text = $output = '';


if ( !empty( $title ) ) {
    $fancy_title = mk_get_view('global', 'shortcode-heading', true, ['title' => $title]);
}

$btn_text = ($button_text !== '') ?  __( ''.$button_text.'', 'mk_framework' ) : __( 'Submit', 'mk_framework' )  ;

if ( $style == 'classic' ) {

    $output .= $fancy_title;
    $output .= '<div class="mk-contact-form-wrapper s_contact classic-style s_classic  mk-shortcode '.$el_class.'">';
    $output .= '<form class="mk-contact-form" method="post" novalidate="novalidate" enctype="multipart/form-data">';
    $output .= '<div class="mk-form-row"><i class="mk-li-user"></i><input placeholder="'.__( 'Your Name', 'mk_framework' ).'" type="text" required="required" name="contact_name" class="text-input s_txt-input" value="" tabindex="'.$tabindex_1.'" /></div>';
    if($phone == 'true') {
        $output .= '<div class="mk-form-row"><i class="mk-li-call"></i><input placeholder="'.__( 'Your Phone Number', 'mk_framework' ).'" type="text" name="contact_phone" class="text-input s_txt-input" value="" tabindex="'.$tabindex_2.'" /></div>';
    }
    $output .= '<div class="mk-form-row"><i class="mk-li-mail"></i><input placeholder="'.__( 'Your Email', 'mk_framework' ).'" type="email" required="required" name="contact_email" class="text-input s_txt-input" value="" tabindex="'.$tabindex_3.'" /></div>';
    $output .= '<div class="mk-form-row"><i class="mk-li-pencil"></i><textarea required="required" placeholder="'.__( 'Your message', 'mk_framework' ).'" name="contact_content" class="mk-textarea s_txt-input" tabindex="'.$tabindex_4.'"></textarea></div>';

    // Upload File
   /* if($attachfile == 'true') {
    $output .= '<div class="mk-form-row">
                    <input placeholder="'.__( 'Select File', 'mk_framework' ).'" type="file" name="file" id="file" class="file-form select-input full"/>
                </div>';
    }*/

    // BUTTON TEXT


    // CAPTCHA
    if($captcha == 'true') {
    $output .= '<div class="mk-form-row">
                    <i class="mk-li-lock"></i>
                    <input placeholder="'.__( 'Enter Captcha', 'mk_framework' ).'" type="text" name="captcha" class="captcha-form text-input s_txt-input full" required="required" autocomplete="off" tabindex="'.$tabindex_5.'" />
                        <span class="captcha-image-holder"></span> <br/>
                        <a href="#" class="captcha-change-image">'.__( 'Not readable?', 'mk_framework' ).' '.__( 'Change text.', 'mk_framework' ).'</a>
                </div>';
    }

    $output .= '<div class="mk-form-row" style="float:left;">
                    <button tabindex="'.$tabindex_6.'" class="mk-progress-button mk-button contact-form-button mk-skin-button mk-button--dimension-flat text-color-light mk-button--size-medium" data-style="move-up">
                        <span class="mk-progress-button-content">'.$btn_text.'</span>
                        <span class="mk-progress">
                            <span class="mk-progress-inner"></span>
                        </span>
                        <span class="state-success"><i class="mk-moon-checkmark"></i></span>
                        <span class="state-error"><i class="mk-moon-close"></i></span>
                    </button>
                </div>';
    $output .= '<input type="hidden" value="'.antispambot($email).'" name="contact_to"/>';
    $output .= '</form>';
    $output .= '<div class="clearboth"></div></div>';

} else if($style == 'modern') {
    $output .= $fancy_title;
    $output .= '<div class="mk-contact-form-wrapper s_contact  mk-shortcode contact-'.$skin.' '.$skin.' modern-style s_modern '.$el_class.'">';
    $output .= '<form class="mk-contact-form" method="post" novalidate="novalidate" enctype="multipart/form-data">';
    $output .= '<div class="mk-form-row"><input placeholder="'.__( 'Your Name', 'mk_framework' ).'" type="text" required="required" name="contact_name" class="text-input s_txt-input" value="" tabindex="'.$tabindex_1.'" /></div>';
    if($phone == 'true') {
        $output .= '<div class="mk-form-row"><input placeholder="'.__( 'Your Phone Number', 'mk_framework' ).'" type="text" name="contact_phone" class="text-input s_txt-input" value="" tabindex="'.$tabindex_2.'" /></div>';
    }
    $output .= '<div class="mk-form-row"><input placeholder="'.__( 'Your Email', 'mk_framework' ).'" type="email" required="required" name="contact_email" class="text-input s_txt-input" value="" tabindex="'.$tabindex_3.'" /></div>';
    $output .= '<div class="mk-form-row"><textarea required="required" placeholder="'.__( 'Your message', 'mk_framework' ).'" name="contact_content" class="mk-textarea s_txt-input" tabindex="'.$tabindex_4.'"></textarea></div>';

    // Upload File
    /*if($attachfile == 'true') {
    $output .= '<div class="mk-form-row">
                    <input placeholder="'.__( 'Select File', 'mk_framework' ).'" type="file" name="file" id="file" class="file-form select-input full"/>
                </div>';
    }*/

    // CAPTCHA
    if($captcha == 'true') {
    $output .= '<div class="mk-form-row">
                    <input placeholder="'.__( 'Enter Captcha', 'mk_framework' ).'" type="text" name="captcha" class="captcha-form text-input s_txt-input full" required="required" autocomplete="off" tabindex="'.$tabindex_5.'" />
                        <span class="captcha-image-holder"></span>
                        <a href="#" class="captcha-change-image">'.__( 'Not readable?', 'mk_framework' ).'<br/>'.__( 'Change text.', 'mk_framework' ).'</a>
                </div>';
    }

    $output .= '<div class="mk-form-row">
                    <button tabindex="'.$tabindex_6.'" class="mk-progress-button mk-button contact-form-button mk-button--dimension-outline mk-button--size-medium skin-'.$skin.'" data-style="move-up">
                        <span class="mk-progress-button-content">'.$btn_text.'</span>
                        <span class="mk-progress">
                            <span class="mk-progress-inner"></span>
                        </span>
                        <span class="state-success"><i class="mk-moon-checkmark"></i></span>
                        <span class="state-error"><i class="mk-moon-close"></i></span>
                    </button>
                </div>';
    $output .= '<input type="hidden" value="'.antispambot($email).'" name="contact_to"/>';
    $output .= '</form>';
    $output .= '<div class="clearboth"></div></div>';

} else if($style == 'outline') {
    $output .= $fancy_title;
    $output .= '<div class="mk-contact-form-wrapper s_contact  mk-shortcode contact-'.$skin.' '.$skin.' outline-style s_outline '.$el_class.'">';
    $output .= '<form class="mk-contact-form" method="post" novalidate="novalidate" enctype="multipart/form-data">';
    $output .= '<div class="mk-form-row">';
    $output .= '<input placeholder="'.__( 'Your Name', 'mk_framework' ).'" type="text" required="required" name="contact_name" class="text-input s_txt-input '.(($phone == 'true')? 'two-third' : 'half').'" value="" tabindex="'.$tabindex_1.'" />';
    if($phone == 'true') {
        $output .= '<input placeholder="'.__( 'Your Phone Number', 'mk_framework' ).'" type="text" name="contact_phone" class="text-input s_txt-input two-third" value="" tabindex="'.$tabindex_2.'" />';
    }
    $output .= '<input placeholder="'.__( 'Your Email', 'mk_framework' ).'" type="email" required="required" name="contact_email" class="text-input s_txt-input '.(($phone == 'true')? 'two-third' : 'half').'" value="" tabindex="'.$tabindex_3.'" />';
    $output .= '</div>';
    $output .= '<div class="mk-form-row"><textarea required="required" placeholder="'.__( 'Your message', 'mk_framework' ).'" name="contact_content" class="mk-textarea s_txt-input" tabindex="'.$tabindex_4.'"></textarea></div>';

    // Upload File
    /*if($attachfile == 'true') {
    $output .= '<div class="mk-form-row">
                    <input placeholder="'.__( 'Select File', 'mk_framework' ).'" type="file" name="file" id="file" class="file-form select-input full"/>
                </div>';
    }*/

    // CAPTCHA
    if($captcha == 'true') {
    $output .= '<div class="mk-form-row">
                    <input placeholder="'.__( 'Enter Captcha', 'mk_framework' ).'" type="text" name="captcha" class="captcha-form text-input s_txt-input full" required="required" autocomplete="off" tabindex="'.$tabindex_5.'"/>
                    <div class="captcha-block">
                        <span class="captcha-image-holder"></span>
                        <a href="#" class="captcha-change-image">'.__( 'Not readable?', 'mk_framework' ).'<br/>'.__( 'Change text.', 'mk_framework' ).'</a>
                    </div>
                </div>';
    }

    $output .= '<div class="mk-form-row">
                    <button tabindex="'.$tabindex_6.'" class="mk-progress-button contact-outline-submit outline-btn-'.$skin.' '.$skin.'" data-style="move-up">
                        <span class="mk-progress-button-content">'.$btn_text.'</span>
                        <span class="mk-progress">
                            <span class="mk-progress-inner"></span>
                        </span>
                        <span class="state-success"><i class="mk-moon-checkmark"></i></span>
                        <span class="state-error"><i class="mk-moon-close"></i></span>
                    </button>
                </div>';
    $output .= '<input type="hidden" value="'.antispambot($email).'" name="contact_to"/>';
    $output .= '</form>';
    $output .= '<div class="clearboth"></div></div>';


} else if($style == 'corporate') {
    $output .= $fancy_title;
    $output .= '<div class="mk-contact-form-wrapper s_contact  mk-shortcode '.$style.'-style s_corporate  '.$el_class.'">';
    $output .= '<form id="mk-contact-form-'.$id.'" class="mk-contact-form" method="post" novalidate="novalidate" enctype="multipart/form-data">';
    $output .= '<div class="mk-form-row">';
    $output .= '<div class="mk-form-half s_form-all"><input placeholder="'.__( 'First Name*', 'mk_framework' ).'" type="text" required="required" name="contact_name" class="text-input s_txt-input" value="" tabindex="'.$tabindex_1.'" /></div>';
    $output .= '<div class="mk-form-half s_form-all"><input placeholder="'.__( 'Last Name*', 'mk_framework' ).'" type="text" required="required" name="contact_last_name" class="text-input s_txt-input" value="" tabindex="'.$tabindex_2.'" /></div>';
    $output .= '</div>';
    $output .= '<div class="mk-form-row">';
    $output .= '<div class="'.(($phone == 'true')? 'mk-form-third s_form-all' : 'mk-form-half s_form-all').'"><input placeholder="'.__( 'Email*', 'mk_framework' ).'" type="email" required="required" name="contact_email" class="text-input s_txt-input" value="" tabindex="'.$tabindex_3.'" /></div>';
    $output .= '<div class="'.(($phone == 'true')? 'mk-form-third s_form-all' : 'mk-form-half s_form-all').'"><input placeholder="'.__( 'Website', 'mk_framework' ).'" type="text" name="contact_website" class="text-input s_txt-input" value="" tabindex="'.$tabindex_4.'" /></div>';
    if($phone == 'true') {
        $output .= '<div class="'.(($phone == 'true')? 'mk-form-third s_form-all' : 'mk-form-half s_form-all').'"><input placeholder="'.__( 'Phone Number', 'mk_framework' ).'" type="text" name="contact_phone" class="text-input s_txt-input two-third" value="" tabindex="'.$tabindex_5.'" /></div>';
    }
    $output .= '</div>';
    $output .= '<div class="mk-form-row"><div class="mk-form-full s_form-all"><textarea required="required" placeholder="'.__( 'Message', 'mk_framework' ).'" name="contact_content" class="mk-textarea s_txt-input" tabindex="'.$tabindex_6.'"></textarea></div></div>';

    // Upload File
   /* if($attachfile == 'true') {
    $output .= '<div class="mk-form-row">
                    <input placeholder="'.__( 'Select File', 'mk_framework' ).'" type="file" name="file" id="file" class="file-form select-input full"/>
                </div>';
    }*/

    // CAPTCHA
    if($captcha == 'true') {
    $output .= '<div class="mk-form-row">
                    <div class="mk-form-full s_form-all">
                        <input placeholder="'.__( 'Enter Captcha', 'mk_framework' ).'" type="text" name="captcha" class="captcha-form text-input s_txt-input full" required="required" autocomplete="off" tabindex="'.$tabindex_7.'" />
                            <span class="captcha-image-holder"></span> <br/>
                            <a href="#" class="captcha-change-image">'.__( 'Not readable? Change text.', 'mk_framework' ).'</a>
                    </div>
                </div>';
    }

    $output .= '<div class="mk-form-row">
                    <button tabindex="'.$tabindex_8.'" class="mk-progress-button mk-button mk-button--dimension-flat mk-button--size-medium text-color-light contact-submit contact-form-button _ font-weight-b" data-style="move-up">
                        <span class="mk-progress-button-content">'.$btn_text.'</span>
                        <span class="mk-progress">
                            <span class="mk-progress-inner"></span>
                        </span>
                        <span class="state-success"><i class="mk-moon-checkmark"></i></span>
                        <span class="state-error"><i class="mk-moon-close"></i></span>
                    </button>
                </div>';
    $output .= '<input type="hidden" value="'.antispambot($email).'" name="contact_to"/>';
    $output .= '</form>';
    $output .= '<div class="clearboth"></div></div>';

}else if($style == 'line') {
    $output .= $fancy_title;
    $output .= '<div class="mk-contact-form-wrapper s_contact  mk-shortcode '.$style.'-style  s_line '.$el_class.'">';
    $output .= '<form id="mk-contact-form-'.$id.'" class="mk-contact-form" method="post" novalidate="novalidate" enctype="multipart/form-data">';
    $output .= '<div class="mk-form-row">';
    $output .= '<div class="mk-form-half s_form-all"><input type="text" required="required" name="contact_name" id="contact_name" class="text-input s_txt-input" value="" tabindex="'.$tabindex_1.'" /><label class="ls-text-label" for="contact_name"><span class="ls-text-label--content">'.__( 'First Name*', 'mk_framework' ).'</span></span></label></div>';
    $output .= '<div class="mk-form-half s_form-all"><input type="text" required="required" name="contact_last_name" id="contact_last_name" class="text-input s_txt-input" value="" tabindex="'.$tabindex_2.'" /> <label class="ls-text-label"><span class="ls-text-label--content">'.__( 'Last Name*', 'mk_framework' ).'</span></label></div>';
    $output .= '<div class="vc_clearfix"></div></div>';
    $output .= '<div class="mk-form-row">';
    $output .= '<div class="'.(($phone == 'true')? 'mk-form-third s_form-all' : 'mk-form-half s_form-all').'"><input type="email" required="required" name="contact_email" id="contact_email" class="text-input s_txt-input" value="" tabindex="'.$tabindex_3.'" /><label class="ls-text-label"><span class="ls-text-label--content">'.__( 'Email*', 'mk_framework' ).'</span></label></div>';
    $output .= '<div class="'.(($phone == 'true')? 'mk-form-third s_form-all' : 'mk-form-half s_form-all').'"><input type="text" name="contact_website" id="contact_website" class="text-input s_txt-input" value="" tabindex="'.$tabindex_4.'" /><label class="ls-text-label"><span class="ls-text-label--content">'.__( 'Website', 'mk_framework' ).'</span></label></div>';
    if($phone == 'true') {
        $output .= '<div class="'.(($phone == 'true')? 'mk-form-third s_form-all' : 'mk-form-half s_form-all').'"><input type="text" name="contact_phone" class="text-input s_txt-input two-third" value="" tabindex="'.$tabindex_5.'" /><label class="ls-text-label"><span class="ls-text-label--content">'.__( 'Phone', 'mk_framework' ).'</span></label></div>';
    }
    $output .= '<div class="vc_clearfix"></div></div>';
    $output .= '<div class="mk-form-row"><div class="mk-form-full s_form-all"><textarea required="required" name="contact_content" class="mk-textarea s_txt-input" tabindex="'.$tabindex_6.'"></textarea><label class="ls-text-label full"><span class="ls-text-label--content">'.__( 'Message*', 'mk_framework' ).'</span></label></div></div>';

    // Upload File
   /* if($attachfile == 'true') {
    $output .= '<div class="mk-form-row">
                    <input placeholder="'.__( 'Select File', 'mk_framework' ).'" type="file" name="file" id="file" class="file-form select-input full"/>
                </div>';
    }*/

    // CAPTCHA
    if($captcha == 'true') {
    $output .= '<div class="mk-form-row">
                    <div class="mk-form-full s_form-all">

                        <input type="text" name="captcha" class="captcha-form text-input s_txt-input full" required="required" autocomplete="off" tabindex="'.$tabindex_7.'" />
                        <label class="ls-text-label"><span class="ls-text-label--content">'.__( 'Enter Captcha*', 'mk_framework' ).'</span></label>
                            <span class="captcha-image-holder"></span>
                            <span class="captcha-change-image-box"><a href="#" class="captcha-change-image">'.__( 'Not readable? Change text.', 'mk_framework' ).'</a></span>
                    </div>
                </div>';
    }

    $output .= '<div class="mk-form-row">
                    <button tabindex="'.$tabindex_8.'" class="mk-progress-button contact-submit contact-form-button mk-button--dimension-flat mk-button--size-large skin-'.$line_button_text_color .'" data-style="move-up">
                        <span class="mk-progress-button-content">'.$btn_text.'</span>
                        <span class="mk-progress">
                            <span class="mk-progress-inner"></span>
                        </span>
                        <span class="state-success"><i class="mk-moon-checkmark"></i></span>
                        <span class="state-error"><i class="mk-moon-close"></i></span>
                    </button>
                </div>';
    $output .= '<input type="hidden" value="'.antispambot($email).'" name="contact_to"/>';
    $output .= '</form>';
    $output .= '<div class="clearboth"></div></div>';

}



$rgba = mk_hex2rgba($font_color, 0.6);



$app_styles = '';

if($style == 'classic') {
    
    $app_styles .= '
       .s_contact.s_classic .s_txt-input {
            background: url('.THEME_IMAGES.'/contact-inputs-bg.png) left top repeat-y #ffffff;
        }
    ';

} else if($style == 'corporate') {
    $app_styles .= '
    .s_corporate #mk-contact-form-'.$id.' .text-input,
    .s_corporate #mk-contact-form-'.$id.' .mk-textarea {
        background-color: '.$bg_color.';
        border-color: '.$border_color.';
        color: '.$font_color.';

    }

    .s_corporate #mk-contact-form-'.$id.' ::-webkit-input-placeholder {
       color: '.$rgba.';
    }

    .s_corporate #mk-contact-form-'.$id.' :-moz-placeholder { /* Firefox 18- */
       color: '.$rgba.';
    }

    .s_corporate #mk-contact-form-'.$id.' ::-moz-placeholder {  /* Firefox 19+ */
       color: '.$rgba.';
    }

    .s_corporate #mk-contact-form-'.$id.' :-ms-input-placeholder {
       color: '.$rgba.';
    }

    .s_corporate #mk-contact-form-'.$id.' .contact-submit {
        background-color: '.$button_color.';
        color: '.$button_font_color.';
    }
    .s_corporate #mk-contact-form-'.$id.' .mk-progress-inner {
        background-color: '.$button_font_color.';
        opacity: .4;
    }';
}else if ($style == 'line') {
    $app_styles .= '
    #mk-contact-form-'.$id.' .mk-form-row .text-input,
    #mk-contact-form-'.$id.' .mk-form-row .mk-textarea,
    #mk-contact-form-'.$id.' .mk-form-row .ls-text-label {
        color: '.$line_skin_color.';
    }
    #mk-contact-form-'.$id.' .mk-form-row .ls-text-label::after {
        background-color: '.$line_skin_color.';
    }
    #mk-contact-form-'.$id.' .mk-form-row .contact-submit {
        background-color: '.$line_skin_color.';
        border: 0;
    }
    #mk-contact-form-'.$id.' .mk-form-row a.captcha-change-image {
        color: '.$line_skin_color.';
    }
    .mk-contact-form-wrapper.s_line .mk-form-row .text-input:focus + .ls-text-label .ls-text-label--content,
    .mk-contact-form-wrapper.s_line .mk-form-row .mk-textarea:focus + .ls-text-label .ls-text-label--content {
        color: '.$line_skin_color.';
    }
    ';
    if ($line_button_text_color == 'light' ){
        $app_styles .= '
        .s_line #mk-contact-form-'.$id.' .mk-form-row .contact-submit {
            color: #222 !important;
        }';
    }else if($line_button_text_color == 'dark') {
        $app_styles .= '
        .s_line #mk-contact-form-'.$id.' .mk-form-row .contact-submit {
            color: #fff !important;
        }';
    }
}

echo $output;

Mk_Static_Files::addCSS($app_styles, $id);
