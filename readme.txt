=== MailPoet WooCommerce Add-on ===
Contributors:      wysija, sebd86
Tags:              mailpoet, wysija, woocommerce, extension, add-on, newsletter, newsletters, checkout
Requires at least: 4.0
Tested up to:      4.3.1
Stable tag:        3.0.0
License:           GPLv2 or later
License URI:       http://www.gnu.org/licenses/gpl-2.0.html

Let your customers subscribe to your newsletter as they checkout with their purchase.

== Description ==

> This plugin extension requires [MailPoet](http://wordpress.org/plugins/wysija-newsletters/) and [WooCommerce](http://wordpress.org/plugins/woocommerce/).

This small extension adds a checkbox on the checkout page allowing your customers to subscribe to your newsletter as they make their purchase.

= Translate =

If you would like to translate the plugin, you can do so via [Transifex](https://www.transifex.com/projects/p/mailpoet-woocommerce-add-on/).

= Support =

Support for MailPoet and it's extensions is provided at [support.mailpoet.com](support.mailpoet.com)

== Installation ==

= Minimum Requirements =

* [MailPoet](http://wordpress.org/plugins/wysija-newsletters/)
* [WooCommerce](http://wordpress.org/plugins/woocommerce/)

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't even need to leave your web browser. To do an automatic install of "MailPoet WooCommerce Add-On", log in to your WordPress admin panel, navigate to the Plugins menu and click Add New.

In the search field type "MailPoet WooCommerce Add-On" and click Search Plugins. Once you've found the plugin extension you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking Install Now. After clicking that link you will be asked if you're sure you want to install the plugin. Click yes and WordPress will automatically complete the installation.

= Manual installation =

The manual installation method involves downloading my plugin and uploading it to your webserver via your favourite FTP application.

1. Download the plugin file to your computer and unzip it
2. Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installation's wp-content/plugins/ directory.
3. Activate the plugin from the Plugins menu within the WordPress admin.

= Setting up the Plugin =

Simply config this plugin under WooCommerce settings in the MailPoet tab. The settings are in two sections. 'General | Lists'

Under 'General' simply enable the checkbox field to show on the checkout page.

You can also change the label of the checkbox. Default 'Yes, add me to your mailing list'

Next is 'Lists'. Each list you created in MailPoet that you have enabled to send to is listed here. Simply tick the checkbox next to the list your customers will be subscribed to and press 'Save Changes'.

That's it, now when your customers tick the subscribe button on the checkout page, they will be subscribed to the newsletters you selected when processing an order.

= Upgrading =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Screenshots ==

1. Subscribe checkbox field on checkout page.
2. WooCommerce MailPoet Checkout Settings.
3. WooCommerce MailPoet Newsletters Enabled.

== Frequently Asked Questions ==

= Q1. Can I place the checkbox field any where else? =
A1. We haven't designed the extension to give the shop manager that option at this time. We will add this option in the future. Currently the checkbox field is set to show after the billing and shipping fields near the submit button.

= Q2. Will this extension work with MailPoet 3.0? =
A2. Once MailPoet 3.0 is available we will be working on re-writing this extension and all the others to make sure they are compaitable with each other again.

== Changelog ==

= v3.0.0 - 02/11/2015 =
* Updated the plugin

= v2.0.2 - 14/02/2015 =
* Fixed fatal error conflict with WooCommerce v2.3.3

= v2.0.1 - 25/03/2014 =
* Added - Arabic language.
* Corrected - If function 'mailpoet_lists' is already defined, then don't load again.
* Removed - Translation of the brand name 'MailPoet' only.
* Updated - POT file.
* Updated - Plugin Update Information filter

= v2.0.0 - 11/03/2014 =
* Updated - Code structure.
* Updated - POT file.
* Updated - Read me file.
* Now Supports - WooCommerce v2.0 and up, v2.1 and up.

= v1.0.3 - 03/03/2014 =
* Added - Greek, Portuguese and Russian languages.

= v1.0.2 - 07/01/2014 =
* Corrected - Default source language from en_GB to en_US, now both UK and US language is available.
* Renamed - 'lang' folder to 'languages'.
* Added - mailpoet-woocommerce-addon.pot file.
* Updated - Read me file.

= v1.0.1 - 04/01/2014 =
* Added - Turkish language.
* Updated - ReadMe file.

= v1.0.0 - 20/12/2013 =
* Initial Version.

== Upgrade Notice ==

= v2.0.2 - 14/02/2015 =
* Fixed fatal error conflict with WooCommerce v2.3.3
