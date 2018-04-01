=== Google Cloud CDN Page Cache ===
Contributors: o10n
Donate link: https://github.com/o10n-x/
Tags: cache, google, cdn, google cloud, page cache, site cache, cloud, seo, international, performance, speed, page speed, pagespeed, fpc, full, gc
Requires at least: 4.0
Requires PHP: 5.4
Tested up to: 4.9.4
Stable tag: 1.0.26
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Low cost and high performance international page cache based on Google Cloud CDN.

== Description ==

This plugin provides a low cost and high performance international page cache solution based on [Google Cloud CDN](https://cloud.google.com/cdn/).

**Warning: This plugin requires manual configuration of a [Google Cloud instance](https://cloud.google.com/wordpress/) (a free VPS with SSH) and a custom nginx server**

The plugin uses a simple concept: use Google's [free micro instance](https://cloud.google.com/compute/pricing#freeusage) for a simple custom Nginx based origin pull server and connect that instance with a Google Cloud CDN front as a international page cache layer. The micro Nginx origin pull server could be used for many domains and subdomains enabling to use the free solution for unlimited sites and pay only for [Google Cloud CDN usage costs](https://cloud.google.com/cdn/pricing).

The plugin provides in basic management functionality such as controlling the CDN cache expiry. Cache invalidation is not yet possible from PHP but once that's made available by Google it will be added. 

We are interested to learn about your experiences and feedback when using this plugin. Please submit your feedback on the [Github community forum](https://github.com/o10n-x/wordpress-google-cdn-page-cache/issues).

== Installation ==

### WordPress plugin installation

1. Upload the `gc-page-cache/` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to the plugin setup page or follow the below instructions.

### Google Cloud CDN Page Cache installation

The instructions are available on ...

== Screenshots ==

1. Google Cloud CDN Page Cache
2. Google Cloud CDN Page Cache Settings
3. Google Cloud CDN Invalidation Form
4. International Google Cloud CDN Performance
5. Google Cloud CDN Network (2017)

== Changelog ==

= 1.0.26 =
* Core update (see changelog.txt)

= 1.0.20 =
* Bugfix: cache headers not set.

= 1.0.19 =
Core update (see changelog.txt)

= 1.0.17 =
* Added: JSON profile editor for all optimization modules.

= 1.0.16 =
Core update (see changelog.txt)

= 1.0.9 =
* Bugfix: settings not saved.

= 1.0.7 =
Core update (see changelog.txt)

= 1.0.5 =
* Documentation links.

= 1.0.4 =
* Bugfix: uninstaller.

= 1.0.3 =
Bugfix: settings link on plugin index.

= 1.0.2 =
Core update (see changelog.txt)

= 1.0 =
* The first version.

== Upgrade Notice ==

None.