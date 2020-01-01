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

/**
 * @todo Move this to an admin file somewhere
 */
function hide_permalink() {
	return '';
}

add_filter( 'get_sample_permalink_html', 'hide_permalink' );

/**
 * Replace Content on Single Tickets
 * @todo Add hooks to look for theme files in theme as well to override templates
 */
add_filter( 'the_content', 'emd_content' );
function emd_content( $content ) {
	if ( is_singular( array( 'ticket' ) ) ) {

		do_action( 'eswp_auth_check' );

		require_once( ESWP_PLUGIN_DIR . '/includes/templates/single-ticket.php' );
	} else {
		return $content;
	}
}

/**
 * @param $single_template
 *
 * @return string
 * @todo We NEED to figure out hwo to assign a themes templatets to the single ticket items.
 */
function single_page_template( $single_template ) {
	global $post;

	if ( $post->post_type == 'ticket' ) {

		/**
		 * @todo Add this login into the settings to select the page template to use for single tickets.
		 * $templates = wp_get_theme()->get_post_templates();
		 * print_r($templates);
		 */
		//$single_template = '/Applications/MAMP/htdocs/WP-Nightly/wp-content/themes/twentyten/onecolumn-page.php';
	}

	return $single_template;
}

add_filter( 'single_template', 'single_page_template' );

/**
 * Pre auth check for singular posts
 *
 * @todo redirect user to default wp or check is there is a custom login page in the settings.
 * @todo Be sure to document that the redirect URL will be sent as well to handle the redirect back to the ticket.
 */
add_filter( 'template_redirect', 'eswp_pre_auth_check_before_template' );
function eswp_pre_auth_check_before_template( $template ) {
	if ( is_singular( array( 'ticket' ) ) ) {
		global $post;

		do_action( 'eswp_pre_auth_check_before_template' );

		// @todo Check for custom redirect location
		if ( ! is_user_logged_in() ) {
			wp_redirect( wp_login_url( $_SERVER['REQUEST_URI'] ) );
			exit;
		}

		// Security Check.
		if ( $post->post_author != get_current_user_id() ) {
			wp_die( '"There is no right or wrong, just fun and boring." <br/> <br/> <strong>BTW.... You\'re not allowed here.</strong>' );
		}

		// Handle Callback from reply
		if ( ! empty( $_POST['eswp_reply'] ) ) {
			$agent = wp_get_current_user();
			$time  = current_time( 'mysql' );

			$data = array(
				'comment_post_ID'      => $post->ID,
				'comment_author'       => $agent->display_name,
				'comment_author_email' => $agent->user_email,
				'comment_author_url'   => '',
				'comment_content'      => $_POST['eswp_reply'],
				'user_id'              => 0,
				'comment_date'         => $time,
				'comment_approved'     => 1,
				'comment_type'         => 'user-reply'
			);
			wp_insert_comment( $data );

			/*
			 * Set the ticket status to "Needs Reply" from agent
			 */
			update_post_meta( $post->ID, 'ticket_status', 'needs_reply' );
		}
	}

	return $template;
}


/**
 *
 * FILE INCLUDES
 *
 */
require_once( ESWP_PLUGIN_DIR . '/includes/shortcodes.php' );
require_once( ESWP_PLUGIN_DIR . '/includes/functions.php' );

// Only if is admin to limit bloat
if ( is_admin() ) {
	require_once( ESWP_PLUGIN_DIR . '/includes/admin/metaboxs.php' );
	require_once( ESWP_PLUGIN_DIR . '/includes/admin/autoresponse.php' ); // Add-on $5
}