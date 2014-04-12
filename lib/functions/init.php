<?php

/**
 * Init File
 *
 * This file defines the Child Theme's constants & tells WP not to update.
 */

 /** Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit( 'Cheatin&#8217; uh?' );

add_action( 'genesis_init', 'tibs_constants', 15 );
/**
 * This function defines the Genesis Child theme constants
 *
 * @since 0.2
 */
function tibs_constants() {
	$theme = wp_get_theme();

	// Child theme (Change but do not remove)
  /** @type constant Text Domain. */
		define( 'CHILD_DOMAIN', $theme->get('TextDomain') );

		/** @type constant Child Theme Version. */
		define( 'CHILD_THEME_VERSION', $theme->Version );

	// Define Directory Location Constants
		/** @type constant Child Theme Library/Includes URL Location. */
		define( 'CHILD_LIB_DIR', CHILD_DIR . '/lib' );

		/** @type constant Child Theme JS URL Location. */
		define( 'CHILD_JS_DIR', CHILD_DIR .'/lib/js' );

	// Define URL Location Constants
		/** @type constant Child Theme Library/Includes URL Location. */
		define( 'CHILD_LIB', CHILD_URL . '/lib' );

		/** @type constant Child Theme JS URL Location. */
		define( 'CHILD_JS', CHILD_URL .'/lib/js' );

		/** @type constant Child Theme CSS URL Location. */
		define( 'CHILD_CSS', CHILD_URL .'/css' );
}

add_action( 'genesis_init', 'tibs_init', 15 );
/**
 * This function calls necessary child theme files
 *
 * @since 0.2
 */
function tibs_init() {

	/** Theme Specific Functions */
	include_once( CHILD_LIB_DIR . '/functions/child-theme-functions.php' );

	// Load admin files when necessary
	if ( is_admin() ) {

		/** Admin Functions */
		//include_once( CHILD_LIB_DIR . '/admin/gs-admin-functions.php');

		/** Child Theme Settings Page */
		include_once( CHILD_LIB_DIR . '/functions/child-theme-settings.php');


		/** Get required plugins */
	//require_once( CHILD_LIB_DIR . '/plugins/plugins.php' );

	}

}