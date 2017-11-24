=== MailPoet Checkout Subscription for WooCommerce (Legacy) ===
Contributors:      wysija, sebd86
Donate Link:       https://www.paypal.me/CodeBreaker
Tags:              mailpoet, woo commerce, ecommerce, e-commerce, extension, newsletter, newsletters, subscription, checkout, integration, post-notification, email
Requires at least: 4.7
Tested up to:      4.9
Stable tag:        4.0.1
License:           GPLv3
License URI:       http://www.gnu.org/licenses/gpl-3.0.html

Let your customers subscribe to your newsletters as they checkout with their purchase.

== Description ==

> #### Minimum Requirements
> [MailPoet 2](https://wordpress.org/plugins/wysija-newsletters/)<br />
> [WooCommerce v3.0.0 or higher](https://wordpress.org/plugins/woocommerce/)<br />
> [WordPress v4.7 or higher](https://wordpress.org/download/)

This integration connects your WooCommerce store with MailPoet 2 so your customers can subscribe to your newsletters as they checkout with their purchase.

= Features =

* If you have more than one newsletter list, customers can select which list to subscribe to when making a purchase.
* Double Opt-in.
* Decide if the checkbox/s to subscribe is checked or un-checked.
* Place the subscription fields on the checkout page where you want them to be.
* Custom checkbox label.

= Translation Support =

MailPoet Checkout Subscription for WooCommerce (Legacy) is in need of translations. Is the plugin not translated in your language or do you spot errors with the current translations? Helping out is easy! Click on "[Translate MailPoet Checkout Subscription for WooCommerce (Legacy)](https://translate.wordpress.org/projects/wp-plugins/mailpoet-woocommerce-add-on)" on the side of this page.

= Support =

This plugin will no longer be supported or maintained. I suggest you upgrade <a href="https://sebastiendumont.com/product-category/mailpoet/">MailPoet Checkout Subscription for WooCommerce</a> to receive continued support. <a href="https://wordpress.org/plugins/mailpoet/">MailPoet 3</a> will be required. All previous settings will be carried over.

> #### Additional Features
> - Manage subscription from account dashboard.<br />
> - Subscription notice added to order email if customers subscribed.<br />

[Sign up](http://eepurl.com/c0hQe9) to be notified of release.

**More information**

- Other [WordPress plugins](http://profiles.wordpress.org/sebd86/) by [Sébastien Dumont](https://sebastiendumont.com)
- Contact Sébastien on Twitter: [@sebd86](http://twitter.com/sebd86)

== Installation ==

Installing "MailPoet Checkout Subscription for WooCommerce (Legacy)" can be done either by searching for "MailPoet Checkout Subscription for WooCommerce (Legacy)" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
2. Upload the ZIP file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard.
3. Activate the plugin through the 'Plugins' menu in WordPress.

= Setting up the Plugin =

To setup this plugin, go to "WooCommerce -> Settings" and then the MailPoet tab. The settings are in two sections. 'General | Available Lists'

"General" contains the main settings for the plugin.

"Available Lists" contains a table listing of all your created lists. Select the lists you want your customers to subscribe to by ticking the checkbox next to the list and press 'Save changes'.

That's it, now when your customers tick the subscribe checkbox on the checkout page, they will be subscribed to the newsletter/s you selected when processing an order.

= Upgrading =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Screenshots ==

1. Single checkbox subscribe field on the checkout page.
2. Multi checkbox subscribe fields on the checkout page.
3. General plugin settings.
4. Available Newsletters.

== Frequently Asked Questions ==

= Q1. Can I place the subscription field anywhere? =
A1. Yes you can. Simply select the "Subscription Position" you want and the field will be placed there.

= Q2. I need to double opt-in my customers to send a confirmation email. Do you have this option? =
A2. Yes and it is enabled by default.

= Q3. Can my customers select which lists they wish to subscribe to? =
A3. Yes, simply set "Multi-Subscription" option to "Yes" under the "General" section and then on the "Available Lists" section, select the lists your customers can select from.

= Q4. Will this extension work with MailPoet 3? =
A4. No. If you are using MailPoet 3 then you will need to <a href="https://sebastiendumont.com/product-category/mailpoet/">upgrade MailPoet Checkout Subscription for WooCommerce</a> to the new version.

= Q5. Will my settings be carried over to the updated integration if I upgrade? =
A5. Yes they will.

== Changelog ==

= v4.0.1 - 24/11/2017 =
* Updated name of plugin fully.

= v4.0.0 - 24/11/2017 =
* Refactored to be more secure and stable.
* Renamed main plugin file to match text domain. Must re-activate plugin manually.
* Added two new filters that controls the display of confirmation and thank you notice once customer has subscribed.
* Added two new action hooks for checkout field. See documentation.
* Updated settings page to be compatible with WooCommerce fully again.
* Updated settings JavaScript.
* Requires WooCommerce v3.0.0 and up.
* Requires WordPress v4.7 and up.
* Tested with WooCommerce v3.2.5
* Tested with WordPress v4.9

= v3.0.2 - 10/11/2015 =
* Added another check if logged in user is subscribed for function - on_process_order(). This fixes an issue when a returning customer who is already subscribed places another order.
* Moved subscription check and applied it to both single and multi subscriptions methods. This fixes the ability to order without subscribing and re-order if a previous customer.

= v3.0.1 - 07/11/2015 =
* Corrected incorrect text domain for loading the language files.

= v3.0.0 - 06/11/2015 =
* Compatible - WooCommerce v2.3 and up.
* Added - Customers can now select which newsletter to subscribe to.
* Added - Double Opt-in option to send a confirmation email.
* Added - Option to have the checkbox/s checked or un-checked.
* Added - Option to place the subscription fields on the checkout page were you want.
* Added - If customer has already subscribed, don't show the subscription field/s on the checkout page.
* Fixed - Default checkbox label is no longer left blank.
* Improved - The overall code of this plugin.
* Updated - Much improved localization copy.

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

= v4.0.0 - 24/11/2017 =

Must re-activate plugin manually after updating. Last update for this integration between MailPoet 2 and WooCommerce. See plugin details on how to upgrade for MailPoet 3 support.
