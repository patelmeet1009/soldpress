=== SoldPress ===
Contributors: Sanskript
Donate link: http://sanskript.com/
Tags: crea, rets, idx
Requires at least: 3.0.1
Tested up to: 3.5.1
Stable tag: 0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

SoldPress is a WordPress plugin to enable CREA’s members to easily disseminate MLS® listing content on WordPress Sites.

== Description ==

SoldPress is a WordPress plugin to enable CREA’s members to easily disseminate MLS® listing content on WordPress Sites.

SoldPress is a designed to connect directly to CREA's RETS API without the need to integrate with Third Party Vendors.

It is highly *recommend* that you use a Cache plugin to minimize API calls.

= How to Use =

SoldPress uses Shortcode syntax to intergrate listing content on your WordPress site.

`[soldpress-listing listingkey="11937198" template="template.html"]`

**Listing Key**

There are two way to specify a listing key.

*Option A : Specifiy Listing Key in the Shortcode.

	`[soldpress-listing listingkey="11937198" template="template.html"]`

*Oprion B : Specify Listing Key in QueryString

	`?ListingKey=11937198`

= Contact Us =

* Support (http://support.sanskript.com)
* Facebook (http://facebook.com/sanskript)
* Twitter (http://twitter.com/sanskript)

== Installation ==

1. Install SoldPress either via the WordPress.org plugin directory, or by uploading the files to your server.
1. Click the Settings -> Soldpress. You need to add your authentication credentials.

* Username 		: Your Username
* Password		: Your Password
* Url			: http://sample.data.crea.ca/Login.svc/Login
* Template Location 	: wp-content/plugins/soldpress/template/

1. Click the "Test Connection" to verify the Connection.

== Frequently Asked Questions ==

= Where do I get authentication credentials? =

An email containing user name and password is sent to the email address submitted as Technical Contact when data feeds are registered in http://tools.realtorlink.ca.  The owner of the data feed can view credentials when editing the feed on the Data Feeds page..

== Screenshots ==

Sample - Basic Template File.

`/assets/template.png`

Sample - No Template File

`/assets/notemplate.png`

== Changelog ==

= 0.9 RC =
* Release Candiate.

== Upgrade Notice ==

= 0.9 RC =
* Release Candiate
