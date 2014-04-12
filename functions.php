<?php

// Initialize Child Theme ** DON'T REMOVE **
require_once( get_stylesheet_directory() . '/lib/functions/init.php');

add_action( 'genesis_setup', 'tibs_theme_setup', 15 );
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
function tibs_theme_setup() {

	// Add viewport meta tag for mobile browsers
	add_theme_support( 'genesis-responsive-viewport' );

	// remove the default stylesheet
	remove_action( 'genesis_meta', 'genesis_load_stylesheet' );

	// add the new sass generated stylesheet
	add_action( 'genesis_meta', 'tibs_sass_styles' );
	function tibs_sass_styles() {
		wp_enqueue_style( 'child-theme', CHILD_CSS . '/style.css' );
	}

	// Clean up Head
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );

	add_action( 'wp_dashboard_setup', 'tibs_remove_dashboard_widgets' );
	/**
	* Remove Dashboard WP Meta Boxes
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


	add_action( 'genesis_theme_settings_metaboxes', 'tibs_remove_genesis_metaboxes' );
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

	add_action( 'admin_init', 'tibs_imagelink_setup', 10 );
	/**
	 * Remove default link for images in image editor
	 */
	function tibs_imagelink_setup() {
		$image_set = get_option( 'image_default_link_type' );
		if ( $image_set !== 'none' ) {
			update_option( 'image_default_link_type', 'none' );
		}
	}

	// Add custom image sizes to image editor
	add_filter( 'image_size_names_choose', 'tibs_display_custom_image_sizes' );
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

	// Add custom image size
	add_image_size( 'wide-horizontal', 700, 300, true );



	// Show Kitchen Sink in WYSIWYG Editor
	add_filter( 'tiny_mce_before_init', 'tibs_unhide_kitchensink' );
	function tibs_unhide_kitchensink( $args ) {
		$args['wordpress_adv_hidden'] = false;
		return $args;
	}

	// Unregister Unused Page Layouts
	genesis_unregister_layout( 'content-sidebar-sidebar' );
	genesis_unregister_layout( 'sidebar-sidebar-content' );
	genesis_unregister_layout( 'sidebar-content-sidebar' );

	// Sidebars
	unregister_sidebar( 'sidebar-alt' );

	// Unregister Genesis widgets
	function tibs_unregister_genesis_widgets() {
		unregister_widget( 'Genesis_eNews_Updates' );
		unregister_widget( 'Genesis_Featured_Page' );
		unregister_widget( 'Genesis_Featured_Post' );
		unregister_widget( 'Genesis_Latest_Tweets_Widget' );
		unregister_widget( 'Genesis_Menu_Pages_Widget' );
		unregister_widget( 'Genesis_User_Profile_Widget' );
		unregister_widget( 'Genesis_Widget_Menu_Categories' );
	}


	// Enqueue Custom Scipts
	add_action( 'wp_enqueue_scripts', 'tibs_load_custom_scripts' );
	function tibs_load_custom_scripts() {
	  wp_enqueue_script( 'responsive-menu', CHILD_JS. '/responsive-menu.js', array( 'jquery' ), CHILD_THEME_VERSION );
	}

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

	// Add support for *-column footer widgets, up until six
	add_theme_support( 'genesis-footer-widgets', 4 );

	// Add HTML5 markup structure
	add_theme_support( 'html5' );

	// Replace default footer with custom footer
	remove_action( 'genesis_footer', 'genesis_do_footer' );
	add_action( 'genesis_footer', 'tibs_custom_footer_text' );
	function tibs_custom_footer_text() {
		echo '<div class="one-half first footer-left">' . wpautop( genesis_get_option( 'footer-left', 'child-settings' ) ) . '</div>';
		echo '<div class="one-half footer-right">' . wpautop( genesis_get_option( 'footer-right', 'child-settings' ) ) . '</div>';
	}

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
