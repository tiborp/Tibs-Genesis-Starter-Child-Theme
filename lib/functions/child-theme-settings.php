<?php
/**
 * Child Theme Settings
 *
 * Registers all of this child theme's specific Theme Settings, accessible from
 * Genesis > Child Theme Settings.
 *
 * @package     Tibs Sample Child
 * @since       1.0.0
 * @link        https://github.com/billerickson/BE-Genesis-Child
 * @author      Bill Erickson <bill@billerickson.net>
 * @copyright   Copyright (c) 2011, Bill Erickson
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        https://github.com/billerickson/BE-Genesis-Child
 */

/**
 * Registers a new admin page, providing content and corresponding menu item
 * for the Child Theme Settings page.
 *
 * @since 1.0.0
 *
 * @package BE_Genesis_Child
 * @subpackage Child_Theme_Settings
 */
class Child_Theme_Settings extends Genesis_Admin_Boxes {

	/**
	 * Create an admin menu item and settings page.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		// Specify a unique page ID.
		$page_id = 'child';

		// Set it as a child to genesis, and define the menu and page titles
		$menu_ops = array(
			'submenu' => array(
				'parent_slug' => 'genesis',
				'page_title'  => __( 'Genesis - Child Theme Settings', 'tibs' ),
				'menu_title'  => __( 'Child Theme Settings', 'tibs' )
			)
		);

		// Set up page options. These are optional, so only uncomment if you want to change the defaults
		$page_ops = array(
			// 'screen_icon'       => 'options-general',
			'save_button_text'  => __( 'Save Settings', 'tibs' ),
			'reset_button_text' => __( 'Reset Settings', 'tibs' ),
			'save_notice_text'  => __( 'Settings saved.', 'tibs' ),
			'reset_notice_text' => __( 'Settings reset.', 'tibs' )
		);

		// Give it a unique settings field.
		// You'll access them from genesis_get_option( 'option_name', 'child-settings' );
		$settings_field = 'child-settings';

		// Set the default values
		$default_settings = array(
			'footer-left'   => '&copy; ' . date( 'Y' ),
			'footer-right' => 'Website door <a href="http://i-tibor.nl">Tibor Paulsch</a>',
		);

		// Create the Admin Page
		$this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );

		// Initialize the Sanitization Filter
		add_action( 'genesis_settings_sanitizer_init', array( $this, 'sanitization_filters' ) );

	}

	/**
	 * Set up Sanitization Filters
	 *
	 * @since 1.0.0
	 *
	 * See /lib/classes/sanitization.php for all available filters.
	 */
	function sanitization_filters() {

		genesis_add_option_filter( 'safe_html', $this->settings_field,
			array(
				'footer-left',
				'footer-right',
			) );
	}

	/**
	 * Set up Help Tab
	 *
	 * @since 1.0.0
	 *
	 * Genesis automatically looks for a help() function, and if provided uses it for the help tabs
	 * @link http://wpdevel.wordpress.com/2011/12/06/help-and-screen-api-changes-in-3-3/
	 */
	function help() {
		$screen = get_current_screen();

		$screen->add_help_tab( array(
				'id'      => 'footer-help',
				'title'   => __( 'Footer text', 'tibs' ),
				'content' => '<p>' .__( 'Change the footer text according to your wishes here.', 'tibs' ) .'</p>'
			) );
	}

	/**
	 * Register metaboxes on Child Theme Settings page
	 *
	 * @since 1.0.0
	 */
	function metaboxes() {

		add_meta_box( 'footer_metabox', __( 'Footer text', 'tibs' ), array( $this, 'footer_metabox' ), $this->pagehook, 'main', 'high' );

	}

	/**
	 * Footer Metabox
	 *
	 * @since 1.0.0
	 */
	function footer_metabox() {

		echo '<p><strong>'. __( 'Footer Left:', 'tibs' ) .'</strong></p>';
		wp_editor( $this->get_field_value( 'footer-left' ), $this->get_field_id( 'footer-left' ), array( 'textarea_rows' => 5 ) );

		echo '<p><strong>'. __( 'Footer Right:', 'tibs' ) .'</strong></p>';
		wp_editor( $this->get_field_value( 'footer-right' ), $this->get_field_id( 'footer-right' ), array( 'textarea_rows' => 5 ) );
	}


}

/**
 * Add the Theme Settings Page
 *
 * @since 1.0.0
 */
function be_add_child_theme_settings() {
	global $_child_theme_settings;
	$_child_theme_settings = new Child_Theme_Settings;
}
add_action( 'genesis_admin_menu', 'be_add_child_theme_settings' );
