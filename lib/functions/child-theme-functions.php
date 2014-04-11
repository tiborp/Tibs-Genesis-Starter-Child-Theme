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
