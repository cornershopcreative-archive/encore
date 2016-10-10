=== Media Deduper ===
Contributors: drywallbmb
Tags: media, attachments, admin, upload
Requires at least: 3.6
Tested up to: 4.5
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Save disk space and bring some order to the chaos of your media library by removing and preventing duplicate files.

== Description ==
Media Deduper was built to help you find and eliminate duplicate images and attachments from your WordPress media library. After installing, you'll have a new "Manage Duplicates" option in your Media section.

Before Media Deduper can identify duplicate assets, it first must build an index of all the files in your media library, which can take some time. Once that's done, however, Media Deduper automatically adds new uploads to its index, so you shouldn't have to generate the index again.

Once up and running, Media Deduper provides two key tools:

1. A page listing all of your duplicate media files. The list makes it easy to see and delete duplicate files: delete one and its twin will disappear from the list because it's then no longer a duplicate. Easy! The list is optionally now sortable by file size, so you can focus on deleting the files that will free up the most space.
2. A scan of media files as they're uploaded to prevent a duplicate from being added to your Media Library. Prevents new duplicates from being introduced, automagically!

**New in 1.0**
Media Deduper comes with a "Delete Preserving Featured" option that prevents a post's Featured Image from being deleted, even if that image is found to be a duplicate elsewhere on the site. If a post has a featured image that's a duplicate file, Media Deduper will re-assign that post's image to an already-in-use copy of the image before deleting the duplicate so that the post's appearance is unaffected. At this time, this feature only tracks Featured Images, and not images used in galleries, post bodies, shortcodes, meta fields, or anywhere else.

Note that duplicate identification is based on the data of the files themselves, not any titles, captions or other metadata you may have provided in the WordPress admin.

== Installation ==
1. Upload the `media-deduper` directory to your plugins directory (typically wp-content/plugins)
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit Media > Manage Duplicates to generate the duplicate index and see your duplicated files

== Frequently Asked Questions ==
= How are duplicates computed? =

Media Deduper looks at the original file uploaded to each attachment post and computes a unique hash (using md5) for that file. Those hashes are stored as postmeta information. Once a file's hash is computed it can be compared to other files' hashes to see if their data is an exact match.

= Why does the list of duplicates include all the copies of a duplicated file and not just the extra ones? =

Because there's no way of knowing which of the duplicates is the "real" or "best" one with the preferred metadata, etc.

= Should I just select all duplicates and bulk delete permanently? =

NO! Because the list includes every copy of your duplicates, you'll likely always want to save one version, so bulk deleting all of them would be very, very bad. Don't do that. You've been warned.

= How can I contribute? =

The git repository should be publicly available at https://bitbucket.org/cornershopcreative/plugin_media-deduper. Feel free to fork, edit, make pull requests, etc.


== Changelog ==
= 1.0.3 =
* Fixed a bug with "Are you sure you want to do this?" appearing due to overly-aggressive referrer checking

= 1.0.2 =
* Fixed a bug manifesting when bulk-deleting no items or running HHVM/PHP7

= 1.0.1 =
* Fixed a bug where Media Deduper didn't want to be uninstalled
* Fixed a bug where bulk deletion didn't always work
* Fixed a minor notice-level PHP error

= 1.0.0 =
* Implemented the 'Delete Preserving Featured' option to prevent inadvertently wiping out media assets in use as post thumbnails
* Enhanced indexing to include the file size
* Added a sortable 'filesize' column to the duplicates table that leverages the aforementioned size data
* Refined screen option to control number of media posts shown per page
* Included a new help tab to provide more information regarding the plugin, indexing, and deletion
* Various bugfixes, including one that broke bulk deletion

= 0.9.3 =
* Implementing Screen Options tab to control number of items displayed. This is a precursor to some other UI enhancements (hopefully).

= 0.9.2 =
* Misc cleanup

= 0.9.1 =
* Rewriting SQL query for finding duplicates to be massively more performant. Props to user gizmomol for the rewrite!

= 0.9 =
* Initial public release.
