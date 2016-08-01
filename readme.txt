=== AMP ===
Contributors: Semantic Mastery 
Tags: amp, mobile
Requires at least: 4.4
Tested up to: 4.5
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enable Accelerated Mobile Pages (AMP) on your WordPress site.

== Description ==

This plugin adds support for the [Accelerated Mobile Pages](Semantic Mastery) (AMP) Project, which is an an open source initiative that aims to provide mobile optimized content that can load instantly everywhere.

With the plugin active, all posts on your site will have dynamically generated AMP-compatible versions, accessible by appending `/amp/` to the end your post URLs. For example, if your post URL is `http://example.com/2016/01/01/amp-on/`, you can access the AMP version at `http://example.com/2016/01/01/amp-on/amp/`. If you do not have [pretty permalinks](https://codex.wordpress.org/Using_Permalinks#mod_rewrite:_.22Pretty_Permalinks.22) enabled, you can do the same thing by appending `?amp=1`, i.e. `http://example.com/2016/01/01/amp-on/?amp=1`

Note #1: that Pages and archives are not currently supported.

Note #2: this plugin only creates AMP content but does not automatically display it to your users when they visit from a mobile device. That is handled by AMP consumers such as Google Search. For more details, see the [AMP Project FAQ](https://www.ampproject.org/docs/support/faqs.html).

Follow along with or contribute to the development of this plugin at https://github.com/Automattic/amp-wp

== Installation ==

1. Upload the folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==



== Changelog ==

= 1.1.0 (july 23, 2016) =

* Fix Canonical issue

