=== Smart Layers by AddThis ===
Contributors: abramsm, jgrodel, bradaddthis.com, addthis_paul, addthis_matt, ribin_addthis, addthis_elsa, addthisleland
Tags: AddThis, bookmark, bookmarking, email sharing, mobile sharing, mobile sharing buttons, plugin, share, share buttons, share buttons plugin, sharing, sharing buttons, sharing sidebar, social buttons, social tools, widget, follow buttons, follow buttons plugin, shortcode, facebook, twitter, pinterest, linkedin, instagram, content recommendations, recommended content, related content, related posts
Requires at least: 3.0
Tested up to: 4.9
Stable tag: 3.1.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Increase your website traffic, social media followers, and content engagement with Smart Layers by AddThis.



== Description ==

AddThis Smart Layers helps you get more of your content shared, grow your following on social media, and increase your visitors’ time on site. Smart Layers includes share buttons, follow buttons, and content recommendation tools.

Here are some tips for maximizing content engagement with our Smart Layers plugin:

* Add share buttons and follow buttons to make it easier for your visitors to share your content and follow you on social media
* Increase on-site engagement with your visitors by showing them related and trending content
* Easily turn tools on/off and customize their appearance by choosing from four different themes: light, gray, dark, and transparent

You can also <a href="https://www.addthis.com/register">register with AddThis</a> to access even more share, follow, and recommended content tools, and get analytics that show how they are performing. After you register, these analytics are accessible by logging into your AddThis.com account and visiting your AddThis dashboard. Analytics include your top shared content, referring social networks, and more.

Check out our other plugins!

We also have plugins specifically available for:

* <a href="http://wordpress.org/extend/plugins/addthis/">Share Buttons</a>
* <a href="http://wordpress.org/extend/plugins/addthis-follow/">Follow Buttons</a>
* <a href="http://wordpress.org/extend/plugins/addthis-related-posts/">Related Posts</a>
* <a href="http://wordpress.org/extend/plugins/addthis-all">Website Tools - Our All-In-One WordPress plugin</a>

<em>For our Website Tools plugin, tools are configured in your AddThis account on AddThis.com.</em>

<a href="http://www.addthis.com/academy/">AddThis Academy</a> | <a href="http://www.addthis.com/privacy">Privacy Policy</a>



== Installation ==

For an automatic installation through WordPress:

1. Go to the 'Add New' plugins screen in your WordPress admin area
1. Search for 'AddThis'
1. Click 'Install Now' and activate the plugin

For a manual installation via FTP:

1. Upload the addthis folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' screen in your WordPress admin area

To upload the plugin through WordPress, instead of FTP:

1. Upload the downloaded zip file on the 'Add New' plugins screen (see the 'Upload' tab) in your WordPress admin area and activate.



== Frequently Asked Questions ==

= What services does AddThis support? =

AddThis supports over 200 social sharing services, and over 60 follow services.

= Is AddThis free? =

Many of our tools are free, but Pro users get the benefit of exclusive widgets, priority support and deeper analytics.

= Do I need to create an account? =

No, you do not need to create an account in order to control a limited number of AddThis tools from within WordPress. In order to use more AddThis tools, more options and see your site's analytics you will need to create an account with AddThis. It requires an email address and name, but that's it.

= Is JavaScript required? =

All AddThis website tools require JavaScript. JavaScript must be enabled. We load the actual interface via JavaScript at run-time, which allows us to upgrade the core functionality of the menu itself automatically everywhere whenever a new social sharing services comes out.

= Why use AddThis? =
1. Ease of use. AddThis is easy to install, customize and localize. We've worked hard to make a suite of simple and beautiful website tools on the internet.
1. Performance. The AddThis menu code is tiny and fast. We constantly optimize its behavior and design to make sharing a snap.
1. Peace of mind. AddThis gathers the best services on the internet so you don't have to, and backs them up with industrial strength analytics, code caching, active tech support and a thriving developer community.
1. Flexibility. AddThis can be customized via an API, and served securely via SSL. Share just about anything, anywhere ­­your way.
1. Global reach. AddThis sends content to 200+ sharing services 60+ languages, to over 2 billion unique users in countries all over the world.

= What PHP version is required? =

This plugin requires PHP 5.2.4 or greater and is tested on the following versions of PHP:

* 5.2.4
* 5.2.17
* 5.3.29
* 5.4.45
* 5.5.38
* 5.6.31
* 7.0.22
* 7.1.8

= Who else uses AddThis? =
Over 15,000,000 sites have installed AddThis. With over 2 billion unique users, AddThis is helping share content all over the world, in more than sixty languages.

= Are there filters? =

Yes! There are lots of filters in this plugin.

Filters allow developers to hook into this plugin's functionality in upgrade-safe ways to define very specific behavior by writing their own PHP code snippets.

Developer <a href="https://plugins.svn.wordpress.org/addthis-all/trunk/documentation.filters.md">documentation</a> on our filters is available. This documentation lists all the filters for our plugins.

= Are there widgets? =

Yes! There are widgets available for all AddThis inline tools (the ones that don't float on the page).

If you register with an AddThis Pro account, you'll also see widgets for our Pro tools.

Developer <a href="https://plugins.svn.wordpress.org/addthis-all/trunk/documentation.widgets.md">documentation</a> on our widgets is also available. This documentation lists all the widgets for our plugins.

= Are there shortcodes? =

Yes! There are lots of shortcodes in this plugin. There are shortcodes are available for all AddThis inline tools (the ones that don't float on the page).

If you register with an AddThis Pro account, the shortcodes for our Pro tools will work for you, too.

See our <a href="https://plugins.svn.wordpress.org/addthis-all/trunk/documentation.shortcodes.md">documentation</a> on our shortcodes. This documentation lists all the shortcodes for our plugins.



== Changelog ==

= 3.1.2 =
* Updated error messaging that is no longer relevant regarding related post tools
* Tested compatibility with Wordpress 4.9

= 3.1.1 =
* Fix for PHP notice from AddThisPlugin.php on line 610
* Changing the permission capability used for determining when users can edit AddThis settings from activate_plugins to manage_options. This will allow most admins on multi-site instances to edit settings. <a href="https://codex.wordpress.org/Roles_and_Capabilities">More information on WordPress roles and capabilities.</a>

= 3.1.0 =
* Fix for PHP error from AddThisSharingButtonsMobileToolbarTool.php line 66
* Fix for PHP error from AddThisSharingButtonsFeature.php line 200
* Fix for PHP notice from AddThisFeature.php line 652
* Removing line breaks from HTML added to public pages
* Not using addthis.layers() json on page when user is using their AddThis account as this creates buggy behavior
* Disabling the wp_trim_excerpt by default as it's the most likely to cause theme issues
* Adding error message if browser can't talk to addthis.com and communication with AddThis APIs are required for funtionality.
* Compatibility updates for version 6.1.0 of <a href="https://wordpress.org/plugins/addthis/">Share Buttons by AddThis</a>.
* Adding requested AddThisWidgetByDomClass functionality that will allow users adding a widget via PHP to customze the URL, title, description and image used for that share. Please see the <a href="https://plugins.svn.wordpress.org/addthis-all/trunk/documentation.widgets.md">widget documentation</a> for more infromation.

= 3.0.1 =
* Fixing shortcode bug.
* Eliminating PHP Notice on AddThisPlugin.php line 1433
* Compatibility updates for version 6.0.0 of <a href="https://wordpress.org/plugins/addthis/">Share Buttons by AddThis</a>. This plugin is no longer compatible with version before 6.0.0 of <a href="https://wordpress.org/plugins/addthis/">Share Buttons by AddThis</a>.

= 3.0.0 =
* Adding meta box to allow site editors to disable automatically added tools when editing posts and pages. Compatible with the <a href="https://wordpress.org/support/plugin/addthis">Share Button by AddThis</a> meta box. If disabled in one, auto adding of tools will be disabled in both.
* Redesigned the plugin's widgets to work with AddThis.com's support of multiple definitions of the same tool type. The class for the new widget is AddThisWidgetByDomClass. Widgets created through WordPress's UI will automatically be migrated to use the new class. However, any hard coded use of the old widget classes will need to be updated before upgrading. Deleted widget classes: AddThisSharingButtonsSquareWidgets, AddThisSharingButtonsOriginalWidget, AddThisSharingButtonsCustomWidget, AddThisSharingButtonsJumboWidget, AddThisSharingButtonsResponsiveWidget, AddThisFollowButtonsHorizontalWidget, AddThisFollowButtonsVerticalWidget, AddThisFollowButtonsCustomWidget, AddThisRecommendedContentHorizontalWidget, AddThisRecommendedContentVerticalWidget. Developer <a href="https://plugins.svn.wordpress.org/addthis-all/trunk/documentation.widgets.md">documentation</a> on the new widget is available.
* Fix for PHP Warning on AddThisFollowButtonsToolParent.php line 127
* Doing profile ID validation, sharing and follow service list retreival directly in the browser rather than proxying through a WordPress backend AJAX call. This will make this plugin work for "Ignore the tool configurations in this profile" mode in environments where the WordPress server can't talk to AddThis.com.

= 2.0.0 =
* Plugin rewritten from scratch.
* Adding widgets and shortcodes.
* Includes the Follow Header tool as before, and adding Vertical Follow Buttons, Horizontal Follow Buttons and (Pro only) Custom Follow Buttons.
* Adding many new follow services
* Includes the Sharing Sidebar tool as before, and adding AddThis' Mobile Sharing Sidebar, Sharing Buttons and Classic Sharing Buttons.
* Includes the Recommended Content Footer tool and What's Next tool as before, and for registered users adding Horizontal Recommended Content, Vertical Recommended Content, What's Next Mobile, and (Pro only) Recommended Content Drawer, Recommended Content Toaster, Jumbo Recommended Content Footer.
* Optionally, allows users to set up their AddThis account and AddThis site profile from inside WordPress.
* Optionally, walks existing AddThis users through logging into their AddThis account and picking a site profile to register their plugin without leaving WordPress. Once registered, AddThis is able to start collecting Analystics on your visitors social use of your site. No more copying in Profile IDs! (Analytics are only available at <a href="https://addthis.com">addthis.com</a>.)
* After registering the plugin with AddThis, users will see more customization options for most tools. After registering, Pro users can edit settings for Pro Follow Button and Recommended Content tools from within WordPress. This also enables widgets and shortcodes for Pro tools.
* Shares most Advanced Options with the <a href="https://wordpress.org/support/plugin/addthis">Share Buttons by AddThis</a> plugin v 5.0+ and all Advanced Settings with <a href="https://wordpress.org/support/plugin/addthis-follow">Follow Buttons by AddThis</a>, <a href="http://wordpress.org/extend/plugins/addthis-related-posts/">Related Posts by AddThis</a> and <a href="https://wordpress.org/support/plugin/addthis-all">Website Tools by AddThis</a> (where installed).
* If you're using the <a href="https://wordpress.org/support/plugin/addthis-follow">Follow Buttons by AddThis</a> plugin as well, please ensure you have it updated to version 2.0.0 or newer. This plugin is not compatible with older version of the <a href="https://wordpress.org/support/plugin/addthis-follow">Follow Buttons</a> plugin (1.3.1 and older). The order in which you upgrade the two plugins does not matter.

= 1.0.12 =
* Updating license for dependency

= 1.0.11 =
* Adding AddThis EULA

= 1.0.10 =
* Minor Bug fix

= 1.0.9 =
* Resolved conflict with WP-Supercache plugin
* Fix for servers without CURL support

= 1.0.8 =
* Resolved conflict with another plugin

= 1.0.7 =
* Minor update

= 1.0.6 =
* Minor update

= 1.0.5 =
* Better compatibility with other WordPress plugins from AddThis

= 1.0.4 =
* Better compatibility for servers with Magic Qoutes and ASP style short tags.

= 1.0.3 =
* Bug fix.

= 1.0.2 =
* Security fix.

= 1.0.1 =
* Bug fixes.



== Upgrade Notice ==

= 3.1.1 =
Fix for PHP notice from AddThisPlugin.php on line 610. Changing the permission capability used for determining when users can edit AddThis settings from activate_plugins to manage_options. This will allow most admins on multi-site instances to edit settings. <a href="https://codex.wordpress.org/Roles_and_Capabilities">More information on WordPress roles and capabilities.</a>

= 3.1.0 =
Fixs for PHP errors, whitespace issues, changes in default and upgraded settings. Adding requested AddThisWidgetByDomClass functionality that will allow users adding a widget via PHP to customze the URL, title, description and image used for that share.

= 3.0.1 =
Fixing shortcode bug. Eliminating PHP Notice on AddThisPlugin.php line 1433. Compatibility updates for version 6.0.0 of <a href="https://wordpress.org/plugins/addthis/">Share Buttons by AddThis</a>. This plugin is no longer compatible with version before 6.0.0 of <a href="https://wordpress.org/plugins/addthis/">Share Buttons by AddThis</a>.

= 3.0.0 =
Fix for PHP Warning on AddThisFollowButtonsToolParent.php line 127. Doing profile ID validation, sharing and follow service list retreival directly in the browser rather than proxying through a WordPress backend AJAX call. This will make this plugin work for "Ignore the tool configurations in this profile" mode in environments where the WordPress server can't talk to AddThis.com. Redesigned the plugin's widgets (including renaming classes) to work with AddThis.com's support of multiple definitions of the same tool type (see changelog for details). Adding meta box support.

= 2.0.0 =
More follow button tools! More follow services! More share button tools! Widgets! Shortcodes! Easier set up for linking your WordPress site with your AddThis account (optional). Linking with an account will unlock even more follow button and recommended content tools, and more options. Not compatible with 1.3.1 or earlier versions of <a href="https://wordpress.org/support/plugin/addthis-follow">Follow Buttons by AddThis.</a> Completely compatible with <a href="https://wordpress.org/support/plugin/addthis-follow">Follow Buttons by AddThis.</a> versions 2.0.0+ and all versions of <a href="https://wordpress.org/support/plugin/addthis-all">Website Tools by AddThis</a> and <a href="http://wordpress.org/extend/plugins/addthis-related-posts/">Related Posts by AddThis</a>. Mostly compatible with <a href="https://wordpress.org/support/plugin/addthis">Share Buttons by AddThis</a> 5.0+.

= 1.0.12 =
Updating license for dependency

= 1.0.11 =
EULA

= 1.0.10 =
Minor bug fix

= 1.0.9 =
Resolved conflict with WP-Supercache plugin and support for servers without CURL

= 1.0.8 =
Minor bug fix

= 1.0.7 =
Minor update

= 1.0.6 =
Minor update

= 1.0.5 =
Better compatibility with other WordPress plugins from AddThis

= 1.0.4 =
Better compatibility for servers with Magic Qoutes / ASP Style short tags enabled.

= 1.0.3 =
Bug fix.

= 1.0.2 =
Security fix.

= 1.0.1 =
Minor bug fixes.
