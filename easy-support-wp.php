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

	$labels = array(
		'name'               => __( 'Support Tickets', 'eswp' ),
		'singular_name'      => __( 'Ticket', 'eswp' ),
		'menu_name'          => __( 'Tickets', 'eswp' ),
		'name_admin_bar'     => __( 'Tickets', 'eswp' ),
		'add_new'            => __( 'Create New Ticket', 'textdomain' ),
		'add_new_item'       => __( 'Create New Ticket', 'eswp' ),
		'new_item'           => __( 'New Ticket', 'eswp' ),
		'edit_item'          => __( 'Edit Ticket', 'eswp' ),
		'view_item'          => __( 'View Ticket', 'eswp' ),
		'all_items'          => __( 'All Tickets', 'eswp' ),
		'search_items'       => __( 'Search Tickets', 'eswp' ),
		'parent_item_colon'  => __( 'Parent Tickets:', 'eswp' ),
		'not_found'          => __( 'No Tickets Found', 'eswp' ),
		'not_found_in_trash' => __( 'No Tickets Found in Trash', 'eswp' ),
	);

	$args = array(
		'public'       => true,
		'labels'       => $labels,
		'menu_icon'    => 'dashicons-welcome-write-blog',
		'supports'     => array( 'title' ),
		'show_ui'      => true,
		'show_in_menu' => true,
		'query_var'    => true,
		//'show_in_rest' => true,
	);
	register_post_type( 'ticket', $args );
}

add_action( 'init', 'eswp_setup_post_types' );

function books_register_ref_page() {
	add_submenu_page(
		'edit.php?post_type=ticket',
		__( 'Settings', 'eswp' ),
		__( 'Settings', 'eswp' ),
		'manage_options',
		'eswp-options',
		'eswp_admin_options_page'
	);

	add_submenu_page(
		'edit.php?post_type=ticket',
		__( 'Addons', 'eswp' ),
		__( 'Addons', 'eswp' ),
		'manage_options',
		'eswp-addons',
		'eswp_admin_options_page'
	);
}

add_action( 'admin_menu', 'books_register_ref_page' );

function eswp_admin_options_page() {
	?>
    <div class="wrap">
        <h1 class="wp-heading-inline">
            Easy Support Options
        </h1>
        <a href="#" class="page-title-action">View Documentation</a>
        <hr class="wp-header-end">
        <p class="description">
           Manage Easy Support WP settings.
        </p>
        <form>
            <table class="form-table" role="presentation">
                <!--<tr>
                    <th scope="row">Single Ticket Template</th>
                    <td>
                        <input type="text" name="" class="regular-text"/>
                        <p class="description">
                            Field Description
                        </p>
                    </td>
                </tr>-->
                <tr>
                    <th scope="row">Single Ticket Template</th>
                    <td>
                        <select name="single-template">
                            <option>Default Template</option>
                            <option>Full Width</option>
                        </select>
                        <p class="description">
                            If "Default" is selected, single tickets will use the current themes default template for a
                            single post. Visit <a href="#">Create Ticket Templates</a> if you wish to customize your
                            templates.
                        </p>
                    </td>
                </tr>
            </table>
			<?php submit_button( 'Save Settings' ); ?>
        </form>
    </div>
	<?php
}

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
 * Rewrite Flush on Activation
 */
function eswp_rewrite_flush() {
	flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'eswp_rewrite_flush' );


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