<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://erkadam.dev
 * @since      1.0.0
 *
 * @package    Chat_Affiliate_Master
 * @subpackage Chat_Affiliate_Master/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Chat_Affiliate_Master
 * @subpackage Chat_Affiliate_Master/includes
 * @author     Münker Erkadam <info@erkada.dev>
 */
class Chat_Affiliate_Master_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'chat-affiliate-master',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
