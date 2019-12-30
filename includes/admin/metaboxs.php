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
    <hr/>
    <style>
        #eswp-ticket-content-container {
            background: #FFFFFF;
            color: #555;
            box-sizing: border-box;
            padding: 1em 1em;
            font-size: 15px;
        }

        #eswp-ticket-content-container .content {
            font-size: 15px;
        }
    </style>
    <h1>Support Ticket</h1>
    <p>Created: <strong><?php print date( "F jS, Y g:i:s A", strtotime( $post->post_date ) ); ?></strong></p>
    <div id="eswp-ticket-content-container">
        <p class="content">
			<?php echo $post->post_content; ?>
        </p>
    </div>
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
    <div class="eswp_main_ticket_content">
        <center>Last Replied: 2 Hours Ago</center>
        <p>
			<?php print nl2br($post->post_content); ?>
        </p>
    </div>

    <hr/>
    <div id="eswp_main_ticket_reply_editor">
		<?php wp_editor(
			'',
			'custom_editor',
			array( "media_buttons" => true )
		); ?>
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
                Status: <span style="background: #44bd32; padding: 5px 10px; color: #fff; border-radius: 3px;">OPEN</span>
            </div>

            <div class="misc-pub-section">
	            <span id="timestamp">
                    Ticket Age: <strong>3 Weeks</strong>
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