<?php
/**
 * On the Cornershop dev server, send mail via SMTP through localhost.
 */

function cshp_phpmailer_init( PHPMailer $phpmailer ) {

	$phpmailer->Host = 'localhost';
	$phpmailer->SMTPAuth = false;
	$phpmailer->IsSMTP();
}

if ( false !== stripos( $_SERVER['HTTP_HOST'], '.cshp.co' ) ) {
	add_action( 'phpmailer_init', 'cshp_phpmailer_init' );
}
