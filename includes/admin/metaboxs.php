<?php
/***********************************
 *
 * CLEANUP
 *
 ***********************************/
add_action( 'admin_menu', function () {
	remove_meta_box( 'submitdiv', 'ticket', 'side' );
} );

/***********************************
 *
 * ORIGINAL TICKET CONTENT
 *
 ***********************************/
add_action( 'edit_form_after_title', 'easy_support_original_ticket_content' );
function easy_support_original_ticket_content() {
	global $post;
	?>
    <style>
        #eswp-ticket-content-container {
            background: #ffffff;
            background: rgba(255, 255, 255, 0.9);
            color: #555;
            box-sizing: border-box;
            padding: 1em 1em;
            font-size: 15px;
            border-radius: 2px;
            border: 1px solid #ccd0d4;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        }

        #eswp-ticket-content-container .content {
            font-size: 15px;
        }
    </style>
    <h1>Support Ticket</h1>
    <p>Created: <strong><?php print date( "F jS, Y g:i:s A", strtotime( $post->post_date ) ); ?></strong></p>
    <div id="eswp-ticket-content-container">
        -
        <p class="content">
			<?php echo $post->post_content; ?>
        </p>
        ---
    </div>
    <br/>
    <hr/>
	<?php
}

/***********************************
 *
 * REPLY META BOX
 *
 ***********************************/
add_action( 'add_meta_boxes', 'easy_support_ticket_meta_box' );
function easy_support_ticket_meta_box() {
	add_meta_box(
		'eswp_replies_metabox',
		__( 'Replies', 'eswp' ),
		'eswp_ticket_replies_content',
		'ticket',
		'normal',
		'high',
		null
	);
}

function eswp_ticket_replies_content( $post ) {
	?>
    <style>
        table.eswp-reply-table {
            margin-bottom: 1em;
        }

        #eswp_ticket_replies {
            margin: 1em 0;
        }

        .eswp-column-author {
            width: 12% !important;
        }

        table.eswp-reply-table td {
            padding: 15px;
        }

        table.eswp-reply-table td.comment {
            padding: 10px;
            font-size: 15px;
        }

        .ticket-meta {
            background: #f1f1f1;
            color: #50575d;
            text-align: center;
            padding: 2em;
            width: 100%;
            border-radius: 2px;
            box-sizing: border-box;
            text-transform: uppercase;
        }
    </style>

    <!-- Possibly Remove This -->
    <!--<div class="ticket-meta ticket-notice center">
        <strong>Last Replied:</strong> 2 Hours Ago
    </div> -->

    <div id="eswp_ticket_replies">
		<?php
		$replies = get_comments( array( 'post_id' => $post->ID ) );
		foreach ( $replies as $reply ):
			$email_hash = md5( $reply->comment_author_email );
			?>
            <table class="widefat fixed striped comments wp-list-table comments-box eswp-reply-table <?php echo $reply->comment_type; ?>">
                <tbody id="the-comment-list" data-wp-lists="list:comment">
                <tr id="comment-304" class="custom-comment-class even thread-even depth-1">
                    <td class="author column-author eswp-column-author">
                        <img src="https://www.gravatar.com/avatar/<?php echo $email_hash; ?>?s=75" width="75"
                             style="border-radius: 50%;"/>
                        <strong style="display: inline-block;"><?php echo $reply->comment_author; ?></strong>
                    </td>
                    <td class="comment column-comment column-primary">
                        <small><?php print date( 'M jS, Y g:i:s A', strtotime( $reply->comment_date ) ); ?></small>
                        <hr/>
						<?php print nl2br( $reply->comment_content ); ?>
                    </td>
                </tr>
                </tbody>
            </table>
		<?php endforeach; ?>
    </div>

    <hr/>

    <h1>Reply to Ticket</h1>
    <p>Use the form below to reply to the ticket</p>
    <br/>
    <div id="eswp_main_ticket_reply_editor">
		<?php wp_editor(
			'',
			'ticket_reply',
			array( "media_buttons" => true )
		); ?>
    </div>

    <div id="major-publishing-actions">
        <div id="delete-action">
            <a class="submitdelete deletion" href="#">
                Re-open
            </a>
        </div>

        <div id="publishing-action">
            <span class="spinner"></span>
            <input name="original_publish" type="hidden" id="original_publish" value="Updating">
            <input type="submit" name="publish" id="publish" class="button button-primary button-large"
                   value="Update Ticket" accesskey="u">
        </div>
        <div class="clear"></div>
    </div>
	<?php
}

/***********************************
 *
 * TICKET INFO META BOX
 *
 ***********************************/
add_action( 'add_meta_boxes', 'easy_support_ticket_ticket_info_meta_box' );
function easy_support_ticket_ticket_info_meta_box() {
	add_meta_box(
		'eswp_ticet_info',
		__( 'Ticket Info', 'eswp' ),
		'easy_support_ticket_ticket_info_meta_box_content',
		'ticket',
		'side',
		'high',
		null
	);
}

function easy_support_ticket_ticket_info_meta_box_content( $post ) {
	?>
    <div class="eswp_main_ticket_info_container submitbox">

        <!-- DEFAULT WP STYLING -->
        <div id="misc-publishing-actions">
            <div class="misc-pub-section">
                Status: <span
                        style="background: #f1f1f1; padding: 5px 10px; border-radius: 3px;"><?php echo eswp_get_ticket_status( get_post_meta( $post->ID, 'ticket_status', true ) ); ?></span>
            </div>

            <div class="misc-pub-section">
	            <span id="timestamp">
                    Ticket Age: <strong><?php print eswp_date_diff(date( "F jS, Y g:i:s A", strtotime( $post->post_date ) ) ); ?></strong>
                </span>
            </div>
            <div class="misc-pub-section">
	            <span id="timestamp">
                    <strong><?php print date( "F jS, Y g:i:s A", strtotime( $post->post_date ) ); ?></strong>
                </span>
            </div>
        </div>

        <div id="major-publishing-actions">
            <div id="delete-action">
                <a class="submitdelete deletion" href="#">
                    Re-open
                </a>
            </div>

            <div id="publishing-action">
                <span class="spinner"></span>
                <input name="original_publish" type="hidden" id="original_publish" value="Updating">
                <input type="submit" name="publish" id="publish" class="button button-primary button-large"
                       value="Update Ticket" accesskey="u">
            </div>
            <div class="clear"></div>
        </div>
    </div>
	<?php
}

/***********************************
 *
 * AGENT TICKET UPDATE
 *
 ***********************************/
add_action( 'save_post', 'eswp_ticket_save_post_update' );
function eswp_ticket_save_post_update( $post_id ) {

	// verify if this is an auto save routine.
	// If it is our form has not been submitted, so we dont want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check permissions
	if ( ( isset ( $_POST['post_type'] ) ) && ( 'ticket' == $_POST['post_type'] ) ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	// OK, we're authenticated: we need to find and save the data
	if ( ! empty ( $_POST['ticket_reply'] ) ) {
		$agent = wp_get_current_user();

		// Run before action hook here

		// Add the reply comment
		$comment = 'Hello there. Thank you for submitting. An agent should be with you shortly.';
		$time    = current_time( 'mysql' );
		$data    = array(
			'comment_post_ID'      => $post_id,
			'comment_author'       => $agent->display_name,
			'comment_author_email' => $agent->user_email,
			'comment_author_url'   => '',
			'comment_content'      => $_POST['ticket_reply'],
			'user_id'              => 0,
			'comment_date'         => $time,
			'comment_approved'     => 1,
			'comment_type'         => 'agent-reply'
		);
		wp_insert_comment( $data );

		/*
		 * Set the ticket status to awaiting response from the customer.
		 */
		update_post_meta( $post_id, 'ticket_status', 'awaiting_response' );
	}
}