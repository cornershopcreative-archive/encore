=== Kint Debugger ===
Contributors: misternifty, chriswallace
Author: Brian Fegter
Author URI: http://coderrr.com/
Plugin URI: http://coderrr.com/kint-debugger-for-wordpress/
Tags: debug, debugger, print_r, var_dump, backtrace, debug_backtrace, krumo, php, tool, trace, developer, debug-bar
Requires at least: 2.5
Tested up to: 4.5.2
Stable tag: 1.0.0

Kint Debugger makes debugging and dumping variables a more pleasant experience. Kint Debugger integrates seamlessly with the Debug Bar plugin.

== Description ==

Kint Debugger is a simple WordPress wrapper for [Kint](https://github.com/raveren/kint), a debugging tool to output information about variables and traces. Debugging is presented in a styled, collapsible format that is easy on the eyes. Kint Debugger plays nice with the Debug Bar plugin by creating its own panel to display your debug results.

**No more adding PRE tags before print_r or var_dump!**

For those who love Krumo, **Kint is Krumo++**.


Please see [Kint Debugger](https://github.com/raveren/kint) for more information about dumping variables.


We've also baked in a few functions that are WordPress specific:

* `dump_wp_query()`
* `dump_wp()`
* `dump_post()`

When you use the ever awesome Debug Bar plugin, Kint Debugger keeps your theme looking beautiful by placing all debug output into a Debug Bar sub-panel. If you are not using Debug Bar, your debug output will be displayed inline.

== Installation ==

Upload the Kint Debugger plugin to your `wp-content/plugins/` directory and activate.

== Frequently Asked Questions ==

= I have called the debug functions, but I can't find the output! =
* If you have the Debug Bar plugin installed, all of your debug results will be displayed under the "Kint Debugger" sub panel.

If you have a feature request or question, please use the [Kint Debugger support forum](http://wordpress.org/tags/kint-debugger).

If you have a question about Kint specifically, please visit the [Kint repo](https://github.com/raveren/kint).

== Screenshots ==

1. Kint debugging outputs are easy on the eyes.
2. Kint integrates with WP Debug Bar

== Changelog ==

= 1.0 =
* Upgraded to Kint Debugger v1.0.0
* Isolated Kint to vendor folder
= 0.2 =
* Added Debug Bar support
= 0.1 =
* Added Kint 3.2 and created dump_wp_query(), dump_wp(), and dump_post() functions

If you have a feature request or question, please use the <a href='http://wordpress.org/tags/kint-debugger'>Kint Debugger support forum</a>.
