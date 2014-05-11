<?php

/**
* 08 Genesis Menus
* Delete any menu systems that you do not wish to use.
*/

 add_theme_support(
	'genesis-menus',
	array(
		'primary'   => __( 'Primary Navigation Menu', 'tibs' ),
		'mobile'    => __( 'Mobile Navigation Menu', 'tibs' ),
	)
);

// Add markup to primary for bootstrap
add_filter( 'genesis_do_nav', 'tibs_bootstrapped_nav', 10, 3 );
function tibs_bootstrapped_nav($nav_output, $nav, $args) {

	$nav_output = sprintf(
		'<nav role="navigation" class="navbar-fixed-top %2$s"><div class="container"><div class="row">%4$s%3$s%5$s</div></div></nav>',
		'nav-primary',
		'nav-primary genesis-nav-menu menu menu-primary',
		$nav,
		genesis_structural_wrap( 'nav', 'open', 0 ),
		genesis_structural_wrap( 'nav', 'close', 0 )
	);
 
	return $nav_output;
 
}

remove_action( 'genesis_after_header', 'genesis_do_nav');
add_action( 'genesis_before_header', 'genesis_do_nav');
/**
 * Add mobile navigation menu to the top.
 *
 * @since 1.0.0
 */
function tibs_navigation( $location, $args ) {
	if ( ! has_nav_menu( $location ) )
		return;

	$defaults = array(
		'theme_location' => $location,
		'container'       => 'nav',
		'container_id'   => $location . '-nav',
		'container_class' => $location . '-nav',
		'menu_class'     => 'genesis-nav-menu menu menu-' . $location,
		'echo'           => false,
	);

	$args = wp_parse_args( $args, $defaults );
	$nav = wp_nav_menu( $args );

	$nav_output = sprintf(
		'<nav id="%1$s" class="%2$s">%4$s%3$s%5$s</nav>',
		$location . '-nav',
		'genesis-nav-menu menu menu-' . $location,
		$nav,
		genesis_structural_wrap( 'nav', 'open', 0 ),
		genesis_structural_wrap( 'nav', 'close', 0 )
	);

	return $nav_output;
}

//Add Mobile Menu
function tibs_mobile_navigation() {

	$mobile_menu_args = array(
		'echo' => true,
	);

	tibs_navigation( 'mobile', $mobile_menu_args );
}


// Add Mobile Navigation
	add_action( 'genesis_before', 'tibs_mobile_navigation', 5 );




// Dynamic footer widget classes
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
add_action( 'genesis_before_footer', 'tibs_footer_widget_areas' );
/**
 * Echos the markup necessary to facilitate the footer widget areas.
 *
 * Checks for a numerical parameter given when adding theme support - if none is
 * found, then the function returns early.
 *
 * Adds column classes based on number of footer widgets registered.
 *
 * @uses tibs_column_class() Gets column class name for footer widgets 2-6.
 *
 * @since 1.1.0
 *
 * @return null Returns early if number of widget areas could not be determined,
 * or nothing is added to the first widget area
 */
function tibs_footer_widget_areas() {

	$footer_widgets = get_theme_support( 'genesis-footer-widgets' );

	if ( ! $footer_widgets || ! isset( $footer_widgets[0] ) || ! is_numeric( $footer_widgets[0] ) )
		return;

	$footer_widgets = (int) $footer_widgets[0];

	/**
	 * Check to see if first widget area has widgets. If not,
	 * do nothing. No need to check all footer widget areas.
	 */
	if ( ! is_active_sidebar( 'footer-1' ) )
		return;

	$output  = '';
	$counter = 1;

	while ( $counter <= $footer_widgets ) {
		/** Darn you, WordPress! Gotta output buffer. */
		ob_start();
		dynamic_sidebar( 'footer-' . $counter );
		$widgets = ob_get_clean();

		/** Dynamically create column classes. */
		$class = 1 == (int) $counter ? 'first ' : '';
		$class .= tibs_column_class( $footer_widgets );

		$output .= sprintf( '<div class="footer-widgets-%1$d widget-area %2$s">%3$s</div>', $counter, $class, $widgets );

		$counter++;
	}

	echo apply_filters( 'genesis_footer_widget_areas', sprintf( '<div id="footer-widgets" class="footer-widgets tibs-footer-widgets-%4$s"><div class="container"><div class="row">%2$s%1$s%3$s</div></div></div>', $output, genesis_structural_wrap	( 'footer-widgets', 'open', 0 ), genesis_structural_wrap( 'footer-widgets', 'close', 0 ), $footer_widgets ) );

}

/**
 * Gets the column class for 2-6 footer widgets.
 *
 * @since 1.1.0
 *
 * @return string Column class name.
 */
function tibs_column_class( $i ) {
	switch ( $i ) {
		case 1:
			return '';
		case 2:
			return 'col-md-6';
		case 3:
			return 'col-md-4';
		case 4:
			return 'col-md-3';
		default:
			return '';
	}
}

/* Modify the Bootstrap Classes being applied
 * based on the Genesis template chosen
 */

// remove unused layouts
add_action('genesis_setup', 'bsg_remove_unused_layout_options', 15);
function bsg_remove_unused_layout_options() {
    genesis_unregister_layout( 'content-sidebar-sidebar' );
    genesis_unregister_layout( 'sidebar-sidebar-content' );
    genesis_unregister_layout( 'sidebar-content-sidebar' );
}

// modify bootstrap classes based on genesis_site_layout
add_filter('bsg-classes-to-add', 'bsg_modify_classes_based_on_template', 10, 3);
function bsg_layout_options_modify_classes_to_add( $classes_to_add ) {

    $layout = genesis_site_layout();

    // content-sidebar          // default

    // full-width-content       // supported
    if ( 'full-width-content' === $layout ) {
        $classes_to_add['content'] = 'col-md-12';
    }

    // sidebar-content          // not yet supported 
    // - same markup as content-sidebar with css modifications rather than markup

    // content-sidebar-sidebar  // not yet supported

    // sidebar-sidebar-content  // not yet supported

    // sidebar-content-sidebar  // not yet supported

    return $classes_to_add;
};

function bsg_modify_classes_based_on_template( $classes_to_add, $context, $attr ) {
    $classes_to_add = bsg_layout_options_modify_classes_to_add( $classes_to_add );

    return $classes_to_add;
}

// Priority 15 ensures it runs after Genesis itself has setup.
add_action( 'genesis_setup', 'bsg_bootstrap_markup_setup', 15 );

function bsg_bootstrap_markup_setup() {

    // add bootstrap classes
    add_filter( 'genesis_attr_site-header',         'bsg_add_markup_class', 10, 2 );
    //add_filter( 'genesis_attr_nav-primary',         'bsg_add_markup_class', 10, 2 );
    add_filter( 'genesis_attr_site-inner',          'bsg_add_markup_class', 10, 2 );
    add_filter( 'genesis_attr_content-sidebar-wrap','bsg_add_markup_class', 10, 2 );
    add_filter( 'genesis_attr_content',             'bsg_add_markup_class', 10, 2 );
    add_filter( 'genesis_attr_sidebar-primary',     'bsg_add_markup_class', 10, 2 );
    add_filter( 'genesis_attr_archive-pagination',  'bsg_add_markup_class', 10, 2 );
    add_filter( 'genesis_attr_site-footer',         'bsg_add_markup_class', 10, 2 );

} // bsg_bootstrap_markup_setup()

function bsg_add_markup_class( $attr, $context ) {
    // default classes to add
    $classes_to_add = apply_filters ('bsg-classes-to-add', 
        // default bootstrap markup values
        array(
            'site-header'       		=> 'fluid-container',
            //'nav-primary'       		=> 'row',
            'site-inner'        		=> 'container',
            'site-footer'       		=> 'row',
            'content-sidebar-wrap'      => 'row',
            'content'           		=> 'col-md-8',
            'sidebar-primary'   		=> 'col-md-4',
            'archive-pagination'		=> 'clearfix',
        ),
        $context,
        $attr
    );

    // lookup class from $classes_to_add
    $class = isset( $classes_to_add[ $context ] ) ? $classes_to_add[ $context ] : '';

    // apply any filters to modify the class
    $class = apply_filters( 'bsg-add-class', $class, $context, $attr );

    // append the class(es) string (e.g. 'span9 custom-class1 custom-class2')
    $attr['class'] .= ' ' . sanitize_html_class( $class );

    return $attr;
}
