<?php
/**
 * Plugin Name: Easy Support WP
 * Plugin URI: https://easysupportwp.com
 * Description: A Free & Lightweight Support Ticket System for WordPress.
 * Version: 1.0.0
 * Date: December 30, 2019
 * Author: justingreerbbi
 * Author URI: https://dash10.digital
 * Text Domain: eswp
 * Domain Path: /languages
 * License: GPL2
 * Tags: helpdesk, help desk, ticket system, support ticket, support
 */

define( 'ESWP_PLUGIN_DIR', dirname( __FILE__ ) );

function eswp_setup_post_types() {
	$args = array(
		'public'    => true,
		'label'     => __( 'Tickets', 'eswp' ),
		'menu_icon' => 'dashicons-welcome-write-blog',
		'supports'  => array( 'title' ),
		//'show_in_rest' => true,
	);
	register_post_type( 'ticket', $args );
}

add_action( 'init', 'eswp_setup_post_types' );

function hide_permalink() {
	return '';
}
add_filter( 'get_sample_permalink_html', 'hide_permalink' );


/**
 *
 * FILE INCLUDES
 *
 */
require_once( ESWP_PLUGIN_DIR . '/includes/shortcodes.php' );

// Only if is admin to limit bloat
if ( is_admin() ) {
	require_once( ESWP_PLUGIN_DIR . '/includes/admin/metaboxs.php' );
	require_once( ESWP_PLUGIN_DIR . '/includes/admin/autoresponse.php' ); // Add-on $5
}