<?php

function eswp_autorespond_addon( $post_id, $post_after, $post_before ) {
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( isset( $post_before->post_status ) && 'auto-draft' == $post_before->post_status ) {
		return;
	}

	if ( 'ticket' == $_POST['post_type'] ) {
		$comment = 'Hello there. Thank you for submitting. An agent should be with you shortly.';
		$time    = current_time( 'mysql' );
		$data    = array(
			'comment_post_ID'      => $post_id,
			'comment_author'       => 'admin',
			'comment_author_email' => 'admin@admin.com',
			'comment_author_url'   => 'http://www.xyz.com',
			'comment_content'      => $comment,
			'user_id'              => 0,
			'comment_date'         => $time,
			'comment_approved'     => 1,
			'comment_type'         => 'custom-comment-class'
		);
		wp_insert_comment( $data );
	}
}

//add_action( 'post_updated', 'eswp_autorespond_addon', 10, 3 );
