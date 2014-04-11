<?php

/**
 * Theme Setup
 *
 * This setup function attaches all of the site-wide functions
 * to the correct hooks and filters. All the functions themselves
 * are defined below this setup function.
 *
 * @author Tibor Paulsch
 *
 */

 /** Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit( 'Cheatin&#8217; uh?' );

add_action( 'genesis_init', 'tibs_constants', 15 );
/**
 * This function defines the Genesis Child theme constants
 *
 * Data Constants: CHILD_SETTINGS_FIELD, CHILD_DOMAIN, CHILD_THEME_VERSION
 * CHILD_THEME_NAME, CHILD_THEME_URL, CHILD_DEVELOPER, CHILD_DEVELOPER_URL
 * Directories: CHILD_LIB_DIR, CHILD_IMAGES_DIR, CHILD_ADMIN_DIR, CHILD_JS_DIR, CHILD_CSS_DIR
 * URLs: CHILD_LIB, CHILD_IMAGES, CHILD_ADMIN, CHILD_JS, CHILD_CSS
 *
 * @since 1.1.0
 */
function tibs_constants() {
	$theme = wp_get_theme();

	// Child theme (Change but do not remove)
  /** @type constant Text Domain. */
		define( 'CHILD_DOMAIN', $theme->get('TextDomain') );

		/** @type constant Child Theme Version. */
		define( 'CHILD_THEME_VERSION', $theme->Version );

		/** @type constant Child Theme Name, used in footer. */
		define( 'CHILD_THEME_NAME', $theme->Name );

		/** @type constant Child Theme URL, used in footer. */
		define( 'CHILD_THEME_URL', $theme->get('ThemeURI') );

	// Developer Information, see lib/admin/admin-functions.php
		/** @type constant Child Theme Developer, used in footer. */
		define( 'CHILD_DEVELOPER', $theme->Author );

		/** @type constant Child Theme Developer URL, used in footer. */
		define( 'CHILD_DEVELOPER_URL', $theme->{'Author URI'}  );

	// Define Directory Location Constants
		/** @type constant Child Theme Library/Includes URL Location. */
		define( 'CHILD_LIB_DIR',    CHILD_DIR . '/lib' );

		/** @type constant Child Theme JS URL Location. */
		define( 'CHILD_JS_DIR',     CHILD_DIR .'/lib/js' );

	// Define URL Location Constants
		/** @type constant Child Theme Library/Includes URL Location. */
		define( 'CHILD_LIB',    CHILD_URL . '/lib' );
}



add_action( 'genesis_setup', 'tibs_theme_setup', 15 );
function tibs_theme_setup() {

	/* ---- BACKEND  ---- */

	// Remove Dashboard Meta Boxes
	add_action( 'wp_dashboard_setup', 'tibs_remove_dashboard_widgets' );

	// Remove Genesis Theme Settings Metaboxes
	add_action( 'genesis_theme_settings_metaboxes', 'tibs_remove_genesis_metaboxes' );

	// Remove default link for images
	add_action( 'admin_init', 'tibs_imagelink_setup', 10 );

	// Add custom image size
	add_image_size( 'wide-horizontal', 700, 300, true );

	// Add custom image sizes to the image editor
	add_filter( 'image_size_names_choose', 'tibs_display_custom_image_sizes' );

	// Show Kitchen Sink in WYSIWYG Editor
	add_filter( 'tiny_mce_before_init', 'tibs_unhide_kitchensink' );

	// Unregister Unused Page Layouts
	genesis_unregister_layout( 'content-sidebar-sidebar' );
	genesis_unregister_layout( 'sidebar-sidebar-content' );
	genesis_unregister_layout( 'sidebar-content-sidebar' );

	// Sidebars
	unregister_sidebar( 'sidebar-alt' );

	// Unregister Genesis widgets
	add_action( 'widgets_init', 'tibs_unregister_genesis_widgets', 20 );

	// Setup Theme Settings & load Custom functions
	require_once( CHILD_DIR . '/lib/functions/child-theme-settings.php' );
  require_once( CHILD_DIR . '/lib/functions/child-theme-functions.php' );


	// Don't update theme
	add_filter( 'http_request_args', 'tibs_dont_update_theme', 5, 2 );


	/* ---- FRONT END ---- */


	// Clean up Head
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );

	// Add viewport meta tag for mobile browsers
	add_theme_support( 'genesis-responsive-viewport' );

  // remove the default stylesheet
  remove_action( 'genesis_meta', 'genesis_load_stylesheet' );

  // add the new sass generated stylesheet
  add_action( 'genesis_meta', 'tibs_sass_styles' );

	// Enqueue Custom Scipts
	add_action( 'wp_enqueue_scripts', 'tibs_load_custom_scripts' );

	// Add support for custom background
	add_theme_support( 'custom-background' );

  // Add support for structural wraps
	add_theme_support( 'genesis-structural-wraps', array(
		'header',
		'nav',
		'subnav',
		'inner',
		'footer-widgets',
		'footer'
	) );

	// Add support for 3-column footer widgets
	add_theme_support( 'genesis-footer-widgets', 3 );

	// Add HTML5 markup structure
	add_theme_support( 'html5' );

	// Replace default footer with custom footer
	remove_action( 'genesis_footer', 'genesis_do_footer' );
	add_action( 'genesis_footer', 'tibs_custom_footer_text' );

}

/*-------------- BACK END FUNCTIONS ----------------*/

/**
 * Remove Dashboard Meta Boxes
 */
function tibs_remove_dashboard_widgets() {
	global $wp_meta_boxes;
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts'] );
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );
}

/**
 * Remove Genesis Theme Settings Metaboxes
 *
 * @since 0.1
 * @param string $_genesis_theme_settings_pagehook
 */
function tibs_remove_genesis_metaboxes( $_genesis_theme_settings_pagehook ) {
	remove_meta_box( 'genesis-theme-settings-feeds',      $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-header',     $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-nav',        $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-breadcrumb', $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-comments',   $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-posts',      $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-blogpage',   $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-scripts',    $_genesis_theme_settings_pagehook, 'main' );
}

/**
 * Remove default link for images in image editor
 */
function tibs_imagelink_setup() {
	$image_set = get_option( 'image_default_link_type' );
	if ( $image_set !== 'none' ) {
		update_option( 'image_default_link_type', 'none' );
	}
}

/**
 * Add custom image sizes to image editor
 */

function tibs_display_custom_image_sizes( $sizes ) {
	global $_wp_additional_image_sizes;
	if ( empty( $_wp_additional_image_sizes ) )
		return $sizes;

	foreach ( $_wp_additional_image_sizes as $id => $data ) {
		if ( !isset( $sizes[$id] ) )
			$sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
	}

	return $sizes;
}

/**
 * Show Kitchen Sink in WYSIWYG Editor
 */
function tibs_unhide_kitchensink( $args ) {
	$args['wordpress_adv_hidden'] = false;
	return $args;
}

/**
 * Remove Genesis widgets
 */
function tibs_unregister_genesis_widgets() {
	unregister_widget( 'Genesis_eNews_Updates' );
	unregister_widget( 'Genesis_Featured_Page' );
	unregister_widget( 'Genesis_Featured_Post' );
	unregister_widget( 'Genesis_Latest_Tweets_Widget' );
	unregister_widget( 'Genesis_Menu_Pages_Widget' );
	unregister_widget( 'Genesis_User_Profile_Widget' );
	unregister_widget( 'Genesis_Widget_Menu_Categories' );
}

/**
 * Don't Update Theme
 *
 * @since 0.1
 *
 * If there is a theme in the repo with the same name,
 * this prevents WP from prompting an update.
 *
 * @author Mark Jaquith
 * @link http://markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
 *
 * @param array   $r,   request arguments
 * @param string  $url, request url
 * @return array request arguments
 */
function tibs_dont_update_theme( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/themes/update-check' ) )
		return $r; // Not a theme update request. Bail immediately.
	$themes = unserialize( $r['body']['themes'] );
	unset( $themes[ get_option( 'template' ) ] );
	unset( $themes[ get_option( 'stylesheet' ) ] );
	$r['body']['themes'] = serialize( $themes );
	return $r;
}

/*--------- FRONT END FUNCTIONS ----------------*/

/**
 * Enqueue Scripts
 */
function tibs_load_custom_scripts() {
  wp_enqueue_script( 'responsive-menu', CHILD_LIB . '/js/responsive-menu.js', array( 'jquery' ), CHILD_THEME_VERSION );
}


/**
 * Enqueue Sass stylesheet
 */
function tibs_sass_styles() {
  echo '<link rel="stylesheet" type="text/css" href="'. CHILD_URL . '/css/style.css">';
}

/**
 * Footer
 *
 */
function tibs_custom_footer_text() {
echo '<div class="one-half first footer-left">' . wpautop( genesis_get_option( 'footer-left', 'child-settings' ) ) . '</div>';
	echo '<div class="one-half footer-right">' . wpautop( genesis_get_option( 'footer-right', 'child-settings' ) ) . '</div>';
}

/*--------- TRANSLATION ----------------*/

/**
 * Load text domain
 *
 * @link http://codex.wordpress.org/Function_Reference/load_child_theme_textdomain
 *
 */
add_action( 'after_setup_theme', 'tibs_load_textdomain' );
function tibs_load_textdomain() {
    load_child_theme_textdomain( 'tibs', get_stylesheet_directory() . '/lib/languages' );
}
