<?php
/**
 * This file contains the Autocomplete class
 *
 * @category API
 * @package   Woo-autocomplete/Api
 * @author    conceptualapps
 * @link     http://www.conceptualapps.com
 * @license  http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * The Wooautocomplete class contains all methods for Wooautocomplete api
 *
 * The Wooautocomplete class contains all necessary methods
 * to autofill the shipping and billing address. The important
 * methods include those which overrides default fields,
 * loads javascript and unsetting checkout fields
 *
 * @category   API
 * @package    Woo-autocomplete/Api
 * @author     conceptualapps
 * @version    1.0
 * @link       http://www.conceptualapps.com
 */
class Wooautocomplete {
	/**
	 * Google Api key
	 *
	 * @var string
	 * @access private
	 */
	private $api_key;
	/**
	 * Path of plugin javascript file
	 *
	 * @var string
	 * @access private
	 */
	private $plugin_path;
	/**
	 * Path of google places api
	 *
	 * @var string
	 * @access private
	 */
	private $map_api_path;
	/**
	 * Calls the methods sequentially to autocomplete address
	 */
	public function __construct() {
		$this->api_key = get_option( 'autocompeteapi', '' );
		$this->plugin_path = plugin_dir_url( __FILE__ ) . '../assets/js/conceptualappsautofill.js';
		$this->map_api_path = 'https://maps.googleapis.com/maps/api/js?key=' . $this->api_key . '&libraries=places&callback=initAutocomplete';
		add_action( 'admin_init' , array( $this, 'woo_ac_register_fields' ) );
		add_action( 'init', array( $this, 'woo_ac_register_scripts' ) );
		add_action( 'wp_footer', array( $this, 'woo_ac_print_scripts' ) );
		add_filter( 'woocommerce_checkout_fields' , array( $this, 'partial_unsetting_checkout_fields' ) );
		add_filter( 'woocommerce_default_address_fields' , array( $this, 'woo_ac_override_default_address_fields' ) );
		add_filter( 'woocommerce_shipping_fields' , array( $this, 'woo_ac_override_default_address_fields1' ) );
	}
	/**
	 * Register field for autocomplete in general section
	 *
	 * Adds a new field in general section for filling
	 * autocomplete api key
	 */
	public function woo_ac_register_fields() {
		register_setting( 'general', 'autocompeteapi', 'esc_attr' );
		add_settings_field(
			'extra_blog_desc_id',
			'<label for="autocompeteapi">' . __( 'auto complete api' , 'extra_blog_description' ) . '</label>',
			array( $this, 'woo_ac_fields_html' ),
			'general'
		);
	}
	/**
	 * Creates html for autocompleteapi text box
	 */
	public function woo_ac_fields_html() {
		echo '<input type="text" id="autocompeteapi" name="autocompeteapi" value="' . esc_attr( $this->api_key ) . '" />';
	}
	/**
	 * Register javascript for auto filing and external javascript from google places api
	 */
	public function woo_ac_register_scripts() {
		wp_register_script( 'woo_ac_address_autofill_helper', $this->plugin_path, '', '', true );
		wp_register_script( 'woo_ac_google_address_autofill', $this->map_api_path, '', '', true );
	}
	/**
	 * Print javascript for auto filing and external javascript from google places api
	 */
	public function woo_ac_print_scripts() {
		wp_print_scripts( 'woo_ac_address_autofill_helper' );
		wp_print_scripts( 'woo_ac_google_address_autofill' );
	}
	/**
	 * Register javascript for auto filing and external javascript from google places api
	 *
	 * @param array $fields here we pass the biling field names for unsetting.
	 * @return array
	 */
	public function partial_unsetting_checkout_fields( $fields ) {
		 unset( $fields['billing']['billing_state'] );
		 unset( $fields['billing']['billing_postcode'] );
		 unset( $fields['shipping']['shipping_state'] );
		 unset( $fields['shipping']['shipping_postcode'] );
		 return $fields;
	}
	/**
	 * Override default billing address fields
	 *
	 * @param array $address_fields here we pass the billing field names for overriding.
	 * @return array
	 */
	public function woo_ac_override_default_address_fields( $address_fields ) {
		$address_fields['billing_state']['type'] = 'text';
		$address_fields['billing_state']['required'] = true;
		$address_fields['billing_state']['class'] = array( 'form-row-wide' );
		$address_fields['billing_state']['label'] = __( 'State', 'my_theme_slug' );
		$address_fields['billing_state']['placeholder'] = __( 'Enter your State', 'my_theme_slug' );
		$address_fields['billing_postcode']['type'] = 'text';
		$address_fields['billing_postcode']['class'] = array( 'form-row-wide' );
		$address_fields['billing_postcode']['required'] = true;
		$address_fields['billing_postcode']['label'] = __( 'Postcode', 'my_theme_slug' );
		$address_fields['billing_postcode']['placeholder'] = __( 'Enter your postcode', 'my_theme_slug' );
		return $address_fields;
	}
	/**
	 * Override default shipping address fields
	 *
	 * @param array $address_fields here we pass the shipping field names for overriding.
	 * @return array
	 */
	public function woo_ac_override_default_address_fields1( $address_fields ) {
		$address_fields['shipping_state']['type'] = 'text';
		$address_fields['shipping_state']['required'] = true;
		$address_fields['shipping_state']['class'] = array( 'form-row-wide' );
		$address_fields['shipping_state']['label'] = __( 'State', 'my_theme_slug' );
		$address_fields['shipping_state']['placeholder'] = __( 'Enter your State', 'my_theme_slug' );
		$address_fields['shipping_postcode']['type'] = 'text';
		$address_fields['shipping_postcode']['class'] = array( 'form-row-wide' );
		$address_fields['shipping_postcode']['required'] = true;
		$address_fields['shipping_postcode']['label'] = __( 'Postcode', 'my_theme_slug' );
		$address_fields['shipping_postcode']['placeholder'] = __( 'Enter your postcode', 'my_theme_slug' );
		return $address_fields;
	}
}
