=== Posts by Type Access ===
Contributors: GregRoss
Tags: admin, posts, categories
Requires at least: 3.1.0
Tested up to: 4.2
Author URI: http://toolstack.com
Plugin URI: http://toolstack.com/PostsByTypeAccess
Stable tag: 2.3
License: GPLv2

Adds a link to drafts, posted, scheduled items and categories under the posts, pages, and other custom post type sections in the admin menu.

== Description ==

This plugin adds menu items to the admin menu in WordPress to allow one click access to the drafts, posted, scheduled items and categories under posts, pages and other post types.

Included is a administration menu to allow for options to be set, including which types of posts are added to the menu and how the number of posts in each category are displayed in the menu.

This code is released under the GPL v2, see license.txt for details.

Special thanks to the translators:

* Spanish - Andrew Kurtis (www.webhostinghub.com)

== Installation ==

1. Extract the archive file into your plugins directory in the posts-by-type-access folder.
2. Activate the plugin in the Plugin options.
3. Customize the settings from the Options panel, if desired.

== Frequently Asked Questions ==

= Why is the category count incorrect in version 2.0? =

WordPress only counts published articles as being 'in' a category so any drafts you have will not be reflected in the category count.

Version 2.1 replaced the built in WordPress function with a custom SQL query that properly reflects the category count for each post type and status of the posts.

== Screenshots ==

1. A screenshot of the admin menu with the posts menu expanded.
2. A screenshot of the options menu.

== Changelog ==
= 2.3 =
* Release date: May 7, 2015
* Added: i18n support.
* Added: Spanish translation, thanks Andrew Kurtis.
* Fixed: Cleaned up various WP_DEBUG warnings.

= 2.2 =
* Release date: December 12, 2014
* Added: Option to hide categories that have no posts in them.

= 2.1 =
* Release date:  December 12, 2014
* Updated: Replaced built in WordPress Category article counts with custom SQL code that provides correct counts.

= 2.0 =
* Release date: September 10, 2014
* Added: Support for categories.
* Updated: Screen shots.

= 1.2 =
* Release date: February 17, 2014
* Added: Square bracket option for post count.
* Updated: Minor visual tweaks to the options page.
* Updated: Screen shots.
* Updated: Replaced deprecated use of numeric access type.

= 1.1 =
* Release date: April 16, 2012
* Minor update to include a notice that settings have been saved.
* Updated version requirements to WordPress 3.1 after testing.

= 1.0 =
* Release date: January 6, 2012
* Initial release.

== Upgrade Notice ==

= 2.1 =
None.

