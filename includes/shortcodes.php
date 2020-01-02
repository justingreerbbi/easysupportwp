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
    <div id="simple-fep-postbox" class="">
		<?php do_action( 'simple-fep-notice' ); ?>
        <div class="simple-fep-inputarea">
			<?php if ( is_user_logged_in() ) : ?>
                <form id="fep-new-post" method="post"
                      action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                    <p>
                        <label>Title *</label><input type="text" id="fep-post-title" name="post-title"/>
                    </p>
                    <p>
                        <label>Content *</label>
                        <textarea class="fep-content" name="posttext" id="fep-post-text"
                                  tabindex="1" rows="4" cols="60"></textarea>
                    </p>
                    <p>
                        <label>Tags</label>
                        <input id="fep-tags" name="tags" type="text" tabindex="2" autocomplete="off"
                               value="<?php esc_attr_e( 'Add tags', 'simple-fep' ); ?>"/>
                    </p>
                    <input type="hidden" name="action" value="eswp_frontend_ticket_submission">
                    <input id="submit" type="submit" tabindex="3" value="<?php esc_attr_e( 'Post', 'simple-fep' ); ?>"/>
                    <input type="hidden" name="empty-description" id="empty-description" value="1"/>
                </form>
			<?php endif; ?>
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
