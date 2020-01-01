<?php

/**
 * Return filtered status for tickets.
 */
function eswp_ticket_statuses() {
	$statuses = apply_filters( 'eswp_titcket_statuses', array(
		'awaiting_response' => __( 'Awaiting Response', 'eswp' ),
		'needs_reply'       => __( 'Needs Reply', 'eswp' ),
		'new'               => __( 'New', 'eswp' ),
		'closed'            => __( 'Closed', 'eswp' )
	) );

	return $statuses;
}

function eswp_date_diff( $date1, $date2 = '' ) {
	$start_date  = new DateTime( $date1 );
	$since_start = $start_date->diff( new DateTime( current_time( 'mysql' ) ) );
	if ( $since_start->i > 60 && $since_start->d < 1 ) {
		return $since_start->h . ' Hrs';
	} elseif ( $since_start->d > 0 ) {
		return $since_start->d . ' Days';
	}

	// Default to Mins
	return $since_start->i . ' Mins';
}

/**
 * Get readable status
 *
 * @param $status
 */
function eswp_get_ticket_status( $status ) {
	$statues = eswp_ticket_statuses();

	if ( ! isset( $statues[ $status ] ) ) {
		return __( 'Invalid Status Type', 'eswp' );
	}

	return $statues[ $status ];
}

/**
 * Simple check at the content level for authorization
 */
add_action( 'eswp_auth_check', 'eswp_auth_check' );
function eswp_auth_check() {
	global $post;

	/*
	 * If the user is not logged in, we need to show the login screen.
	 */
	if ( ! is_user_logged_in() ) {
		// @todo Show Login Form
		return;
	}

	// Check if the user is allowed to view the ticket. This is done by comparing the post author with the current user
	if ( $post->post_author != get_current_user_id() ) {
		print ' < h3 stycle = "color: red;" > Your not allow to view this ticket . Sorry for your luck .</h3 > ';

		return;
	}
}