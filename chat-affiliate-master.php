<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://erkadam.dev
 * @since             1.0.0
 * @package           Chat_Affiliate_Master
 *
 * @wordpress-plugin
 * Plugin Name:       Chat Affiliate Master
 * Plugin URI:        https://wordpress.org/plugins/search/chat-affiliate-master/
 * Description:       Do Affiliate Marketing by specifying products in your articles.
 * Version:           1.0.0
 * Author:            Münker Erkadam
 * Author URI:        https://erkadam.dev/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       chat-affiliate-master
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CHAT_AFFILIATE_MASTER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-chat-affiliate-master-activator.php
 */
function activate_chat_affiliate_master() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-chat-affiliate-master-activator.php';
	Chat_Affiliate_Master_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-chat-affiliate-master-deactivator.php
 */
function deactivate_chat_affiliate_master() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-chat-affiliate-master-deactivator.php';
	Chat_Affiliate_Master_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_chat_affiliate_master' );
register_deactivation_hook( __FILE__, 'deactivate_chat_affiliate_master' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-chat-affiliate-master.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-chat-affiliate-post-types.php';
require plugin_dir_path( __FILE__ ) . 'admin/class-chat-affiliate-meta-boxes.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-chat-affiliate-shortcodes.php';




/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_chat_affiliate_master() {

	$plugin = new Chat_Affiliate_Master();
	$plugin->run();

}
run_chat_affiliate_master();
