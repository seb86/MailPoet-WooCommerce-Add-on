<?php
/*
 * Plugin Name:       MailPoet WooCommerce Add-on
 * Plugin URI:        https://wordpress.org/plugins/mailpoet-woocommerce-add-on
 * Description:       Let your customers subscribe to your newsletter as they checkout with their purchase.
 * Version:           3.0.2
 * Author:            Sébastien Dumont
 * Author URI:        http://www.sebastiendumont.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mailpoet-woocommerce-add-on
 * Domain Path:       languages
 * Network:           false
 *
 * MailPoet WooCommerce Add-on is distributed under the terms of the
 * GNU General Public License as published by the Free Software Foundation,
 * either version 2 of the License, or any later version.
 *
 * MailPoet WooCommerce Add-on is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with MailPoet WooCommerce Add-on.
 * If not, see <http://www.gnu.org/licenses/>.
 *
 * @package MailPoet_WooCommerce_Add_on
 * @author  Sébastien Dumont
 */
if(! defined('ABSPATH')) exit; // Exit if accessed directly

if(! class_exists('MailPoet_WooCommerce_Add_On')){

/**
 * Main MailPoet WooCommerce Add-on Class
 *
 * @since 1.0.0
 */
final class MailPoet_WooCommerce_Add_On {

	/**
	 * The single instance of the class
	 *
	 * @since  2.0.0
	 * @access private
	 * @var    object
	 */
	private static $_instance = null;

	/**
	 * Main MailPoet WooCommerce Add-on Instance
	 *
	 * Ensures only one instance of MailPoet WooCommerce Add-on is loaded or can be loaded.
	 *
	 * @access public static
	 * @see    MailPoet_WooCommerce_Add_on()
	 * @return MailPoet WooCommerce Add-on instance
	 */
	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new MailPoet_WooCommerce_Add_On();
			self::$_instance->setup_constants();
			self::$_instance->load_plugin_textdomain();
			self::$_instance->includes();
		}
		return self::$_instance;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since  3.0.0
	 * @access public
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong(__FUNCTION__, __('Cheatin’ huh?', 'mailpoet-woocommerce-add-on'), MAILPOET_WOOCOMMERCE_VERSION);
	} // END __clone()

	/**
	 * Disable unserializing of the class
	 *
	 * @since  3.0.0
	 * @access public
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong(__FUNCTION__, __('Cheatin’ huh?', 'mailpoet-woocommerce-add-on'), MAILPOET_WOOCOMMERCE_VERSION);
	} // END __wakeup()


	/**
	 * Constructor
	 *
	 * @since  3.0.0
	 * @access public
	 */
	public function __construct(){
		// Auto-load classes on demand
		if(function_exists("__autoload"))
			spl_autoload_register("__autoload");

		spl_autoload_register(array($this, 'autoload'));
	}

	/**
	 * Auto-load "MailPoet WooCommerce Add-on" classes on demand to reduce memory consumption.
	 *
	 * @since  3.0.0
	 * @access public
	 * @param  mixed $class
	 * @return void
	 */
	public function autoload($class) {
		$path  = null;
		$file  = strtolower('class-'.str_replace('_', '-', $class)).'.php';

		if(strpos($class, 'mailpoet_woocommerce') === 0){
			$path = MAILPOET_WOOCOMMERCE_FILE_PATH.'/includes/admin/';
		}

		if($path !== null && is_readable($path.$file)){
			include_once($path.$file);
			return true;
		}
	} // END autoload()

	/**
	 * Setup Constants
	 *
	 * @since  3.0.0
	 * @access private
	 */
	private function setup_constants(){
		$this->define('MAILPOET_WOOCOMMERCE_VERSION', '3.0.2');
		$this->define('MAILPOET_WOOCOMMERCE_FILE', __FILE__);
		$this->define('MAILPOET_WOOCOMMERCE_SLUG', 'mailpoet-woocommerce-add-on');

		$this->define('MAILPOET_WOOCOMMERCE_URL_PATH', untrailingslashit(plugins_url('/', __FILE__)));
		$this->define('MAILPOET_WOOCOMMERCE_FILE_PATH', untrailingslashit(plugin_dir_path(__FILE__)));

		$this->define('MAILPOET_WOOCOMMERCE_WP_VERSION_REQUIRED', '4.0');
		$this->define('MAILPOET_WOOCOMMERCE_WC_VERSION_REQUIRED', '2.3');
	} // END setup_constants()

	/**
	 * Define constant if not already set.
	 *
	 * @param  string $name
	 * @param  string|bool $value
	 * @access private
	 * @since  3.0.0
	 */
	private function define($name, $value){
		if( ! defined($name)) {
			define($name, $value);
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @access public
	 * @return void
	 */
	public function includes() {
		include_once('includes/mailpoet-woocommerce-core-functions.php'); // Contains core functions for the front/back end.
		include_once('includes/mailpoet-woocommerce-hooks.php'); // Hooks used at the frontend.

		if ( is_admin() ) {
			include_once('includes/admin/class-mailpoet-woocommerce-install.php'); // Install plugin
			include_once('includes/admin/class-mailpoet-woocommerce-admin.php'); // Admin section
		}
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any
	 * following ones if the same translation is present.
	 *
	 * @since  1.0.0
	 * @version 3.0.1
	 * @access public
	 * @filter mailpoet_woocommerce_add_on_languages_directory
	 * @filter plugin_locale
	 * @return void
	 */
	public function load_plugin_textdomain() {
		// Set filter for plugin's languages directory
		$lang_dir = dirname(plugin_basename(MAILPOET_WOOCOMMERCE_FILE)).'/languages/';
		$lang_dir = apply_filters('mailpoet_woocommerce_add_on_languages_directory', $lang_dir);

		// Traditional WordPress plugin locale filter
		$locale = apply_filters('plugin_locale',  get_locale(), 'mailpoet-woocommerce-add-on');
		$mofile = sprintf('%1$s-%2$s.mo', 'mailpoet-woocommerce-add-on', $locale);

		// Setup paths to current locale file
		$mofile_local  = $lang_dir.$mofile;
		$mofile_global = WP_LANG_DIR.'/mailpoet-woocommerce-add-on/'.$mofile;

		if ( file_exists($mofile_global) ) {
			// Look in global /wp-content/languages/mailpoet-woocommerce-add-on/ folder
			load_textdomain('mailpoet-woocommerce-add-on', $mofile_global);
		} else if(file_exists($mofile_local)){
			// Look in local /wp-content/plugins/mailpoet-woocommerce-add-on/languages/ folder
			load_textdomain('mailpoet-woocommerce-add-on', $mofile_local);
		} else {
			// Load the default language files
			load_plugin_textdomain('mailpoet-woocommerce-add-on', false, $lang_dir);
		}
	} // END load_plugin_textdomain()

} // END class

} // END if class exists

// Run the Plugin
return MailPoet_WooCommerce_Add_On::instance();
