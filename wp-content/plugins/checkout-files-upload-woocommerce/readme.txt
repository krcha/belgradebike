=== Checkout Files Upload for WooCommerce ===
Contributors: algoritmika,anbinder
Tags: woocommerce,checkout files upload
Requires at least: 4.4
Tested up to: 4.7
Stable tag: 1.2.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Let your customers upload files on (or after) WooCommerce checkout.

== Description ==

Checkout Files Upload for WooCommerce plugin lets your customers upload files on (or after) WooCommerce checkout.

= Features =

Set exact field **position** on the checkout page:

* Before checkout form.
* After checkout form.
* Do not add on checkout.

Set if file upload **is required**.

If you need files to be uploaded after order is created, you can optionally add field to:

* **Thank You** (i.e. **Order Received**) page.
* **My Account** page.

Add custom **label** to the field.

Set **accepted file types**.

Set custom Upload and Remove **buttons labels**.

Set **custom messages**:

* "Wrong file type".
* "File is required".
* "File was successfully uploaded".
* "No file selected".
* "File was successfully removed".

Optionally set field to show up only if in cart there are selected:

* **Products**.
* **Product categories**.
* **Product tags**.

Add uploaded files to admin and customers **emails**.

**Customize** the frontend files upload form.

= Feedback =
* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* Drop us a line at [www.algoritmika.com](http://www.algoritmika.com).

= More =
* Visit the [Checkout Files Upload for WooCommerce plugin page](https://wpcodefactory.com/item/checkout-files-upload-woocommerce-plugin/).

== Installation ==

1. Upload the entire 'checkout-files-upload-woocommerce' folder to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Start by visiting plugin settings at WooCommerce > Settings > Checkout Files Upload.

== Changelog ==

= 1.2.0 - 10/05/2017 =
* Fix - `Call to undefined function is_shop_manager()` error fixed.
* Dev - WooCommerce v3.x.x compatibility - Order ID - using function instead of accessing property directly.
* Dev - `load_plugin_textdomain` moved to constructor from `init` hook.
* Tweak - Plugin link changed from `http://coder.fm` to `https://wpcodefactory.com`.

= 1.1.1 - 07/12/2016 =
* Dev - `alg_current_filter_priority()` modified for compatibility with WordPress since v4.7.
* Dev - Language (POT) file updated.
* Tweak - Checking for Pro modified.

= 1.1.0 - 28/11/2016 =
* Dev - "Form Template Options" settings section added.
* Dev - Language (POT) file added.
* Tweak - "Emails Options" settings moved to separate section.

= 1.0.0 - 05/09/2016 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
