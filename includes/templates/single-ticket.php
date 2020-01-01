<?php global $post; ?>
<style>
    #eswp-ticket-wrapper {
        box-sizing: border-box;
    }

    #eswp-ticket-content-container {
        padding: 0.5em 0.5em 0.5em 1.5em;
        box-sizing: border-box;
        color: #000;
        font-size: 15px;
        border-left: 3px solid #f1f1f1;
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
        margin: 0;
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
    <hr class="eswp-hr"/>
    <div id="eswp-ticket-content-container">
		<?php echo $post->post_content; ?>
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
            border-bottom: 1px solid #f1f1f1;
            padding-bottom: 2em;
        }

        .eswp-reply-date {
            position: absolute;
            right: 1em;
            top: 0.4em;
            color: #50575d;
        }

        .eswp-reply-author {
            display: block;
            font-size: 0.9rem;
            color: #50575d;
        }

        .eswp-reply-author img {
            position: relative;
            top: 12px;
        }

        .eswp-reply-content {
            padding-left: 1.6em;
            margin-top: 1.5em;
            border-left: solid 1px orange;
        }

        #eswp-ticket-reply-editor {
            background: #f1f1f1;
            padding: 1.5em;
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
            font-size: 0.8rem;
            color: #555;
            margin-top: 1em;
        }
    </style>

    <hr/>
    <h3 style="background: #000; color: #FFF; padding: 10px 15px;">Support Ticket Replies</h3>
    <div id="eswp_ticket_replies">
		<?php
		$replies = get_comments( array( 'post_id' => $post->ID ) );
		foreach ( $replies as $reply ):
			$email_hash = md5( $reply->comment_author_email );
			?>
            <div class="eswp-single-reply-wrapper <?php echo $reply->comment_type; ?>">
                <small class="eswp-reply-date"><?php print date( 'M jS, Y g:i:s A', strtotime( $reply->comment_date ) ); ?></small>
                <div class="eswp-reply-author">
                    <img src="https://www.gravatar.com/avatar/<?php echo $email_hash; ?>?s=30" width="30"
                         style="border-radius: 50%;"/>
                    <small><?php echo $reply->comment_author; ?> (Support)</small>
                </div>
                <div class="eswp-reply-content">
					<?php print nl2br( $reply->comment_content ); ?>
                </div>
            </div>

		<?php endforeach; ?>
    </div>

    <form method="post">
        <div id="eswp-ticket-reply-editor">
            <h3 class="eswp-ticket-title">Reply to this Support Ticket</h3>
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
