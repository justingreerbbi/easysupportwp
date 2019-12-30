<?php
function easy_support_frontend_submission_form( $content = null ) {
	global $post;
	ob_start();
	?>
    <style>
        #fep-new-post label {
            display: inline-block;
            width: 15%;
        }

        #fep-new-post input {
            width: 60%;
        }

        #fep-new-post input[type="submit"] {
            margin-left: 15%;
            width: 30%;
            padding: 7px;
        }

        #fep-new-post textarea {
            display: inline-block;
            width: 80%;
            vertical-align: top;
        }
    </style>
    <div id="simple-fep-postbox" class="<?php if ( is_user_logged_in() ) {
		echo 'closed';
	} else echo 'loggedout' ?>">
		<?php do_action( 'simple-fep-notice' ); ?>
        <div class="simple-fep-inputarea">
			<?php if ( is_user_logged_in() ) { ?>
                <form id="fep-new-post" name="new_post" method="post" action="<?php the_permalink(); ?>">
                    <p><label>Title *</label><input type="text" id="fep-post-title" name="post-title"/></p>
                    <p><label>Content *</label><textarea class="fep-content" name="posttext" id="fep-post-text"
                                                         tabindex="1" rows="4" cols="60"></textarea></p>
                    <p><label>Tags</label><input id="fep-tags" name="tags" type="text" tabindex="2" autocomplete="off"
                                                 value="<?php esc_attr_e( 'Add tags', 'simple-fep' ); ?>"
                                                 onfocus="this.value=(this.value=='<?php echo esc_js( __( 'Add tags', 'simple-fep' ) ); ?>') ? '' : this.value;"
                                                 onblur="this.value=(this.value=='') ? '<?php echo esc_js( __( 'Add tags', 'simple-fep' ) ); ?>' : this.value;"/>
                    </p>
                    <input id="submit" type="submit" tabindex="3" value="<?php esc_attr_e( 'Post', 'simple-fep' ); ?>"/>
                    <input type="hidden" name="action" value="post"/>
                    <input type="hidden" name="empty-description" id="empty-description" value="1"/>
					<?php wp_nonce_field( 'new-post' ); ?>
                </form>
			<?php } else { ?>
                <h4>Please Log-in To Post</h4>
			<?php } ?>
        </div>

    </div>
	<?php
	// Output the content.
	$output = ob_get_contents();
	ob_end_clean();

	// Return only if we're inside a page. This won't list anything on a post or archive page.
	if ( is_page() ) {
		return $output;
	}
}

// Add the shortcode to WordPress.
add_shortcode( 'easy-support', 'easy_support_frontend_submission_form' );


function simple_fep_errors() {
	?>
    <style>
        .simple-fep-error {
            border: 1px solid #CC0000;
            border-radius: 5px;
            background-color: #FFEBE8;
            margin: 0 0 16px 0px;
            padding: 12px;
        }
    </style>
	<?php
	global $error_array;
	foreach ( $error_array as $error ) {
		echo '<p class="simple-fep-error">' . $error . '</p>';
	}
}

function simple_fep_notices() {
	?>
    <style>
        .simple-fep-notice {
            border: 1px solid #E6DB55;
            border-radius: 5px;
            background-color: #FFFBCC;
            margin: 0 0 16px 0px;
            padding: 12px;
        }
    </style>
	<?php

	global $notice_array;
	foreach ( $notice_array as $notice ) {
		echo '<p class="simple-fep-notice">' . $notice . '</p>';
	}
}

function simple_fep_add_post() {

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && $_POST['action'] == 'post' ) {

		if ( ! is_user_logged_in() ) {
			return;
		}

		global $current_user;

		$user_id      = $current_user->ID;
		$post_title   = $_POST['post-title'];
		$post_content = nl2br($_POST['posttext']);
		$tags         = $_POST['tags'];

		global $error_array;
		$error_array = array();

		if ( empty( $post_title ) ) {
			$error_array[] = 'Please add a title.';
		}
		if ( empty( $post_content ) ) {
			$error_array[] = 'Please add some content.';
		}

		if ( count( $error_array ) == 0 ) {
			$post_id = wp_insert_post( array(
				'post_author'  => $user_id,
				'post_title'   => $post_title,
				'post_type'    => 'ticket',
				'post_content' => $post_content,
				'tags_input'   => $tags,
				'post_status'  => 'publish'
			) );

			global $notice_array;
			$notice_array   = array();
			$notice_array[] = "Thank you for posting. Your post is now live. ";
			add_action( 'simple-fep-notice', 'simple_fep_notices' );
		} else {
			add_action( 'simple-fep-notice', 'simple_fep_errors' );
		}
	}
}

add_action( 'init', 'simple_fep_add_post' );
