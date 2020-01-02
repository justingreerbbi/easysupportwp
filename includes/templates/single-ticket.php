<?php global $post; ?>
<style>
    #eswp-ticket-wrapper {
        box-sizing: border-box;
        background: #f1f1f1;
        padding: 1em;
    }

    #eswp-ticket-content-container,
    #eswp_ticket_replies {
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

    #eswp-ticket-content-container hr {
        margin-bottom: 0 !important;
    }

    .eswp-ticket-title {
        margin: 0 !important;
        padding: 0 !important;
    }

    .eswp-ticket-meta-data {
        font-size: 12px;
        margin: 15px 0px;
    }

    .eswp-hr {
        margin-bottom: 10px !important;
    }

    .ticket-controls {
        margin: 10px 0px;
        font-size: .8rem;
        background: #fff;
        padding: 10px;
    }
</style>

<div id="eswp-ticket-wrapper">
    <h3 class="eswp-ticket-title">Support Ticket</h3>
    <div class="eswp-ticket-meta-data">
        Created: <strong><?php print date( "F jS, Y g:i:s A", strtotime( $post->post_date ) ); ?></strong>
    </div>
    <div id="eswp-ticket-content-container">
        --<br/>
		<?php echo $post->post_content; ?>
        <br/>
        ----
    </div>
    <div class="ticket-controls">
        <a href="#">Close Ticket</a> -
        <a href="#">Email Ticket Transcript</a>
    </div>

    <style>
        #eswp_ticket_replies {
            margin: 1em 0;
        }

        .eswp-single-reply-wrapper {
            position: relative;
            border: 1px solid #ccc;
            padding-bottom: 2em;
            background: #f1f1f1;
            margin-bottom: 1em;
            padding: 5px;
            box-sizing: border-box;
        }

        .eswp-reply-date {
            position: absolute;
            right: 1em;
            top: 0.7em;
            color: #50575d;
        }

        .eswp-reply-author {
            display: block;
            font-size: 1.8rem;
            color: #50575d;
            position: relative;
            top: 5px;
        }

        .eswp-reply-author img {
            position: relative;
            top: 2px;
        }

        .eswp-reply-content {
            padding: 0 0.3em;
            border-top: 1px solid #555;
            padding-top: 1rem;
            padding-bottom: 14px;
            margin-top: 10px;
            /*background: #FFF;*/
        }

        #eswp-ticket-reply-editor {
            background: #f1f1f1;
            box-sizing: border-box;
        }

        .eswp-textarea {
            width: 100%;
            height: 200px;
            resize: none;
            box-sizing: border-box;
            padding: 1em;
        }

        .eswp-description {
            font-size: 1.3rem;
            color: #555;
            margin-top: 1em;
        }
    </style>

    <hr class="eswp-hr"/>
    <div id="eswp_ticket_replies">
        <h5 class="eswp-ticket-title">Replies</h5>
        <div class="eswp-ticket-meta-data">
            Created: <strong><?php print date( "F jS, Y g:i:s A", strtotime( $post->post_date ) ); ?></strong>
        </div>
		<?php
		$replies = get_comments( array( 'post_id' => $post->ID ) );
		foreach ( $replies as $reply ):
			$email_hash = md5( $reply->comment_author_email );
			?>
            <div class="eswp-single-reply-wrapper <?php echo $reply->comment_type; ?>">
                <small class="eswp-reply-date"><?php print date( 'M jS, Y g:i:s A', strtotime( $reply->comment_date ) ); ?></small>
                <div class="eswp-reply-author">
                    <img src="https://www.gravatar.com/avatar/<?php echo $email_hash; ?>?s=25" width="25"
                         style="border-radius: 50%;"/>
                    <small><?php echo $reply->comment_author; ?> (Support)</small>
                </div>
                <div class="eswp-reply-content">
					<?php print nl2br( $reply->comment_content ); ?>
                </div>
            </div>

		<?php endforeach; ?>
    </div>

    <hr class="eswp-hr"/>
    <br/>
    <form method="post">
        <div id="eswp-ticket-reply-editor">
            <h5 class="eswp-ticket-title">Reply to this Support Ticket</h5>
            <p class="eswp-description">
                HTML and special characters are not allow. If you need to share code snippets, please use a pastebin
                service and share the link.
            </p>
            <textarea class="eswp-textarea" name="eswp_reply"></textarea>
            <input type="checkbox" name="close-ticket"/> Reply & Close <br/>
            <input type="submit" value="Post Reply"/>
        </div>
    </form>
</div>
