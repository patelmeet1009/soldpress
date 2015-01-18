=== SoldPress ===
Contributors: Sanskript
Donate link: http://sanskript.com/
Tags: crea, rets, idx, ddf,Data Distribution Facility,real estate,mls,realtor
Requires at least: 3.0.1
Tested up to: 4.1.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Get Live MLS® data directly with this WordPress Plugin..

== Description ==

[SoldPress](http://www.sanskript.com/products/soldpress) is a WordPress plugin to enable members of The Canadian Real Estate Assoiciation to easily intergate MLS® listing content on their WordPress Sites.  

SoldPress is designed to connect directly to CREA's Data Distribution Facility API without the need to integrate with Third Party Vendors. 

You may register for a data feed at http://tools.realtorlink.ca. An email containing user name and password is sent to the email address submitted as Technical Contact.

Your site is required to comply with CREA [Data Distribution Policy and Rules](http://www.realtorlink.ca/portal/server.pt/document/3400226/data_distribution_facility_policy_and_rules_updated_april_30%2C_2012).

[SoldPress Premium](http://soldpress.sanskript.com) is avaliable to Agents/Brokers who want a complete package that ensures thier listing content is displayed accurately and up to date, and uses CREA's trademarks correctly. SoldPress Premium provides full compliance with Data Distribution Policy and Rules.

**Copyright**

MLS®, REALTOR®, and the associated logos are trademarks of The Canadian Real Estate Association

== Documentation ==

SoldPress uses Shortcode syntax to intergrate listing content on your WordPress site.

`[soldpress-listing listingkey="11937198" template="template.html"]`


**Listing Key**

There are two methods to specify a Listing Key.

* Option A : Specifiy Listing Key in the Shortcode.

	`[soldpress-listing listingkey="11937198" template="template.html"]`

* Option B : Specify Listing Key in QueryString

	`?ListingKey=11937198`

*The QueryString ListingKey paramerter superseeds the Shortcode.*


**Templates**

SoldPress uses a basic template enginge to render html output. A SoldPress template is a text file that contains variables, which get replaced with values when the template is evaluated. The template engine is based on heredoc syntax.

The general syntax is `${$rets['ListPrice']}`. 

A comprehensive list of variable names and descriptions can be found at in the [Data Distribution Facility Documentation](http://crea.ca/data-distribution-facility-documentation).


The default template location is wp-content/plugins/soldpress/template/

= Contact Us =

* Support (http://support.sanskript.com)
* Web Site (http://www.sanskript.com)

= CREA =

* [Data Distribution Facility Documentation] (http://crea.ca/data-distribution-facility-documentation)

== Installation ==

1. Install SoldPress either via the WordPress.org plugin directory, or by uploading the files to your server.
1. Click the Settings -> Soldpress. You need to add your authentication credentials.
1. Click the "Test Connection" to verify the Connection.

**You are required to register for a data feed a http://tools.realtorlink.ca. An email containing user name and password is sent to the email address submitted as Technical Contact.**

== Frequently Asked Questions ==

= Where do I get authentication credentials? =

An email containing user name and password is sent to the email address submitted as Technical Contact when data feeds are registered in http://tools.realtorlink.ca.  The owner of the data feed can view credentials when editing the feed on the Data Feeds page.

== Screenshots ==

1. Sample - Basic Template File.
2. Sample - No Template File


== Changelog ==

= 1.1.0 =
Fixed Issue with encoding file

= 1.0.0 =
Simplified Login
Added Link to SoldPress Premium

= 0.9.2 RC =
Fixed issue with encoding on files. Using UTF-8 without BOM.

= 0.9.1 RC =
Added support for French.
Fixed Issue with other pluigin referance to phRETS.

= 0.9 RC =
* Release Candiate.

== Upgrade Notice ==

= 0.9 RC =
* Release Candiate
