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

/**
 * Add navigation menu to the top.
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

	echo apply_filters( 'genesis_footer_widget_areas', sprintf( '<div id="footer-widgets" class="footer-widgets tibs-footer-widgets-%4$s">%2$s%1$s%3$s</div>', $output, genesis_structural_wrap( 'footer-widgets', 'open', 0 ), genesis_structural_wrap( 'footer-widgets', 'close', 0 ), $footer_widgets ) );

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
			return 'one-half';
		case 3:
			return 'one-third';
		case 4:
			return 'one-fourth';
		case 5:
			return 'one-fifth';
		case 6:
			return 'one-sixth';
		default:
			return '';
	}
}
