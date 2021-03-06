## v4.0.1 - 24th November 2017
* Updated name of plugin fully.

## v4.0.0 - 24th November 2017
* Refactored to be more secure and stable.
* Renamed main plugin file to match text domain. Must re-activate plugin manually.
* Added two new filters that controls the display of confirmation and thank you notice once customer has subscribed. [See documentation](https://github.com/seb86/MailPoet-WooCommerce-Add-on/wiki).
* Added two new action hooks for checkout field.  [See documentation](https://github.com/seb86/MailPoet-WooCommerce-Add-on/wiki)
* Updated settings page to be compatible with WooCommerce fully again.
* Updated settings JavaScript.
* Requires WooCommerce v3.0.0 and up.
* Requires WordPress v4.7 and up.
* Tested with WooCommerce v3.2.5
* Tested with WordPress v4.9

## v3.0.2 - 10th November 2015
* Added another check if logged in user is subscribed for function - on_process_order(). This fixes an issue when a returning customer who is already subscribed places another order.
* Moved subscription check and applied it to both single and multi subscriptions methods. This fixes the ability to order without subscribing and re-order if a previous customer.

## v3.0.1 - 07th November 2015
* Corrected incorrect text domain for loading the language files.

## v3.0.0 - 06th November 2015
* Compatible - WooCommerce v2.3 and up.
* Added - Customers can now select which newsletter to subscribe to.
* Added - Double Opt-in option to send a confirmation email.
* Added - Option to have the checkbox/s checked or un-checked.
* Added - Option to place the subscription fields on the checkout page were you want.
* Added - If customer has already subscribed, don't show the subscription field/s on the checkout page.
* Fixed - Default checkbox label is no longer left blank.
* Improved - The overall code of this plugin.
* Updated - Much improved localization copy.

## v2.0.2 - 14th Feburary 2015
* Fixed fatal error conflict with WooCommerce v2.3.3

## v2.0.1 - 25th March 2014
* Added - Arabic language.
* Corrected - If function 'mailpoet_lists' is already defined, then don't load again.
* Removed - Translation of the brand name 'MailPoet' only.
* Updated - POT file.
* Updated - Plugin Update Information filter

## v2.0.0 - 11th March 2014
* Updated - Code structure.
* Updated - POT file.
* Updated - Read me file.
* Now Supports - WooCommerce v2.0 and up, v2.1 and up.

## v1.0.3 - 03th March 2014
* Added - Greek, Portuguese and Russian languages.

## v1.0.2 - 07th January 2014
* Corrected - Default source language from en_GB to en_US, now both UK and US language is available.
* Renamed - 'lang' folder to 'languages'.
* Added - mailpoet-woocommerce-addon.pot file.
* Updated - Read me file.

## v1.0.1 - 04th January 2014
* Added - Turkish language.
* Updated - ReadMe file.

## v1.0.0 - 20th December 2013
* Initial Version.
