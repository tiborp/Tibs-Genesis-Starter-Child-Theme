<?php

// Initialize Child Theme ** DON'T REMOVE **
require_once( get_stylesheet_directory() . '/lib/functions/init.php');

add_action( 'genesis_setup', 'tibs_theme_setup', 15 );
/**
 * Theme Setup
 *
 * This setup function attaches all of the site-wide functions
 * to the correct hooks and filters.
 *
 * @author Tibor Paulsch
 *
 */
function tibs_theme_setup() {

	// Add viewport meta tag for mobile browsers
	add_theme_support( 'genesis-responsive-viewport' );

	add_action( 'wp_enqueue_scripts', 'tibs_load_dashicons' );
	function tibs_load_dashicons() {
    	wp_enqueue_style( 'dashicons' );
	}

	// remove the default stylesheet which is just there for WP to recognize the theme
	remove_action( 'genesis_meta', 'genesis_load_stylesheet' );

	// Add support for Dashicons
	add_action( 'wp_enqueue_scripts', 'tibs_load_dashicons' );
	function tibs_load_dashicons() {
    	wp_enqueue_style( 'dashicons' );
	}

	// add the new sass generated stylesheet
	add_action( 'genesis_meta', 'tibs_load_child_stylesheet' );
	function tibs_load_child_stylesheet() {
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

	/** Define Genesis Options */
	add_filter('genesis_options', 'define_genesis_setting_custom', 10, 2);
	function define_genesis_setting_custom($options, $setting) {
	    if($setting == GENESIS_SETTINGS_FIELD) {
	        $options['show_info'] = 0; // Display theme info in document source
	        $options['update'] = 1; // Enable Automatic Updates
	        $options['update_email'] = 0; // Notify when updates are available
	        $options['update_email_address'] = ''; // Update email address
	        $options['feed_uri'] = ''; // Custom reed URI
	        $options['redirect_feed'] = 0; // Redirect reed
	        $options['comments_feed_uri'] = ''; // Custom comments feed URI
	        $options['redirect_comments_feed'] = 0; // Redirect feed
	        $options['site_layout'] = 'content-sidebar'; // Default layout
	        $options['blog_title'] = 'text'; // Blog title/logo - 'text' or 'image'
	        $options['nav'] = 1; // Include primary navigation
	        $options['nav_superfish'] = 1; // Enable fancy dropdowns
	        $options['nav_extras_enable'] = 0; // Enable extras
	        $options['nav_extras'] = ''; // Extras - 'date', 'rss', 'search', 'twitter'
	        $options['nav_extras_twitter_id'] = ''; // Twitter ID
	        $options['nav_extras_twitter_text'] = 'Follow me on Twitter'; // Twitter link text
	        $options['subnav'] = 0; // Include secondary navigation
	        $options['subnav_superfish'] = 1; // Enable fancy dropdowns
	        $options['breadcrumb_home'] = 0; // Enable breadcrumbs on Front Page
	        $options['breadcrumb_single'] = 0; // Enable breadcrumbs on Posts
	        $options['breadcrumb_page'] = 0; // Enable breadcrumbs on Pages
	        $options['breadcrumb_archive'] = 0; // Enable breadcrumbs on Archives
	        $options['breadcrumb_404'] = 0; // Enable breadcrumbs on 404 Page
	        $options['breadcrumb_attachment'] = 0; // Enable breadcrumbs on Attachment Pages
	        $options['comments_posts'] = 0; // Enable comments on Posts
	        $options['comments_pages'] = 0; // Enable comments on Pages
	        $options['trackbacks_posts'] = 0; // Enable trackbacks on Posts
	        $options['trackbacks_pages'] = 0; // Enable trackbacks on Pages
	        $options['content_archive'] = 'full'; // Content archives display - 'full', 'excerpts'
	        $options['content_archive_limit'] = '240'; // Limit content to n characters
	        $options['content_archive_thumbnail'] = 1; // Include featured image
	        $options['posts_nav'] = 'numeric'; // Post navigation - 'older-newer', 'prev-next', 'numeric'
	        $options['blog_cat'] = '0'; // Blog page displays which category
	        $options['blog_cat_exclude'] = ''; // Blog page excludes which category 
	        $options['blog_cat_num'] = 5; // Number of posts to show
	        $options['header_scripts'] = ''; // Header scripts
	        $options['footer_scripts'] = ''; // Footer scripts
	        }
	    return $options;
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

	// Remove default link for images in image editor
	add_action( 'admin_init', 'tibs_imagelink_setup', 10 );
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

	// Remove Genesis Widgets
	add_action( 'widgets_init', 'tibs_unregister_genesis_widgets', 20 );
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

	// Add support for *-column footer widgets (max 6)
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
