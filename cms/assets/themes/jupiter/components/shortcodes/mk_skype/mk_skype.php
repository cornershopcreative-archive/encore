<?php 
$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );
?>

<a href="skype:<?php echo $number; ?>?call" class="mk-skype-call <?php echo $el_class; ?>"><i class="mk-social-skype"></i><?php echo $display_number; ?></a>
