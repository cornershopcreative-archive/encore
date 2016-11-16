<?php
/**
 * This is a hard-coded custom google analytics implementation
 * done in order to conform to cross-tracking with BSD.
 * See https://tools.bluestatedigital.com/kb/article/how-do-i-integrate-my-google-analytics-with-bsd
 */

function bsd_ga_tracking() {
	$ua = "UA-84927837-1"; ?>

<!-- START Google Analytics -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', '<?php echo $ua; ?>', 'auto', {'allowLinker': true, 'siteSpeedSampleRate': 20});
ga('require', 'linker');
//See https://github.com/bluestatedigital/bsd-google-analytics-integration#configuration for configuration options
var ga_integration_config={};
</script>
<script src="//s.bsd.net/bsdaction/default/page/-/js/analytics/ga_integration-min.js"></script>
<script>
ga('send', 'pageview');
</script>
<!-- END Google Analytics -->

	<?php
	// end GA inclusion
}
add_action( 'wp_footer', 'bsd_ga_tracking', 99 );