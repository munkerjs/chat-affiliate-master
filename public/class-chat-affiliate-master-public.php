<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://erkadam.dev
 * @since      1.0.0
 *
 * @package    Chat_Affiliate_Master
 * @subpackage Chat_Affiliate_Master/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Chat_Affiliate_Master
 * @subpackage Chat_Affiliate_Master/public
 * @author     Münker Erkadam <info@erkada.dev>
 */
class Chat_Affiliate_Master_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Chat_Affiliate_Master_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Chat_Affiliate_Master_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/chat-affiliate-master-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Chat_Affiliate_Master_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Chat_Affiliate_Master_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/chat-affiliate-master-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/frontend.js', array( 'jquery' ), $this->version, false );

	}

	public function enqueue_frontend_scripts() {
        wp_enqueue_script('chat-affiliate-frontend', plugin_dir_url(__FILE__) . 'js/frontend.js', array('jquery'), null, true);
        wp_localize_script('chat-affiliate-frontend', 'ajaxurl', admin_url('admin-ajax.php'));
    }

}
