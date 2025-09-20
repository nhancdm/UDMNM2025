<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @package Nhan
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php
    // You can start editing here -- including this comment!
    if (have_comments()) :
    ?>
        <h2 class="comments-title">
            <?php
            $nhan_comment_count = get_comments_number();
            if ('1' === $nhan_comment_count) {
                printf(
                    /* translators: 1: title. */
                    esc_html__('One thought on &ldquo;%1$s&rdquo;', 'nhan'),
                    '<span>' . get_the_title() . '</span>'
                );
            } else {
                printf( // WPCS: XSS OK.
                    /* translators: 1: comment count number, 2: title. */
                    esc_html(_nx('%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $nhan_comment_count, 'comments title', 'nhan')),
                    number_format_i18n($nhan_comment_count),
                    '<span>' . get_the_title() . '</span>'
                );
            }
            ?>
        </h2><!-- .comments-title -->

        <?php the_comments_navigation(); ?>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'      => 'ol',
                'short_ping' => true,
                'callback'   => 'nhan_comment_callback',
            ));
            ?>
        </ol><!-- .comment-list -->

        <?php
        the_comments_navigation();

        // If comments are closed and there are comments, let's leave a little note, shall we?
        if (!comments_open()) :
        ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'nhan'); ?></p>
        <?php
        endif;

    endif; // Check for have_comments().

    comment_form();
    ?>

</div><!-- #comments -->

<style>
/* Comments Area Styles */
.comments-area {
    margin-top: 3rem;
    padding: 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.comments-title {
    font-size: 1.8rem;
    margin-bottom: 2rem;
    color: #2c3e50;
    border-bottom: 2px solid #667eea;
    padding-bottom: 0.5rem;
}

/* Comment Navigation */
.comment-navigation {
    margin: 2rem 0;
}

.comment-navigation .nav-links {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.comment-navigation a {
    padding: 0.5rem 1rem;
    background: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.comment-navigation a:hover {
    background: #5a6fd8;
}

/* Comment List */
.comment-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.comment-list .comment {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.comment-list .children {
    list-style: none;
    margin-top: 1rem;
    margin-left: 2rem;
}

.comment-list .children .comment {
    background: #ffffff;
    border-left-color: #bdc3c7;
}

/* Comment Meta */
.comment-meta {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    gap: 1rem;
}

.comment-author {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.comment-author .avatar {
    border-radius: 50%;
}

.comment-author .fn {
    font-weight: 600;
    color: #2c3e50;
    text-decoration: none;
}

.comment-author .fn:hover {
    color: #667eea;
}

.comment-metadata {
    font-size: 0.9rem;
    color: #666;
}

.comment-metadata a {
    color: inherit;
    text-decoration: none;
}

.comment-metadata a:hover {
    color: #667eea;
}

.comment-edit-link {
    margin-left: 1rem;
    font-size: 0.8rem;
}

.comment-edit-link a {
    color: #667eea;
    text-decoration: none;
}

/* Comment Content */
.comment-content {
    line-height: 1.6;
    margin-bottom: 1rem;
}

.comment-content p {
    margin-bottom: 1rem;
}

.comment-content p:last-child {
    margin-bottom: 0;
}

/* Comment Reply */
.reply {
    text-align: right;
}

.reply a {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 0.9rem;
    transition: background-color 0.3s ease;
}

.reply a:hover {
    background: #5a6fd8;
}

/* Comment awaiting moderation */
.comment-awaiting-moderation {
    background: #fff3cd;
    color: #856404;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    margin-bottom: 1rem;
    border: 1px solid #ffeaa7;
}

/* No comments message */
.no-comments {
    text-align: center;
    color: #666;
    font-style: italic;
    padding: 2rem;
    background: #f8f9fa;
    border-radius: 8px;
    margin: 2rem 0;
}

/* Comment Form */
.comment-respond {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid #eee;
}

.comment-reply-title {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    color: #2c3e50;
}

.comment-form {
    display: grid;
    gap: 1rem;
}

.comment-form-comment {
    grid-column: 1 / -1;
}

.comment-form-author,
.comment-form-email,
.comment-form-url {
    display: flex;
    flex-direction: column;
}

.comment-form label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.comment-form input[type="text"],
.comment-form input[type="email"],
.comment-form input[type="url"],
.comment-form textarea {
    padding: 0.75rem;
    border: 2px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    font-family: inherit;
    transition: border-color 0.3s ease;
}

.comment-form input[type="text"]:focus,
.comment-form input[type="email"]:focus,
.comment-form input[type="url"]:focus,
.comment-form textarea:focus {
    outline: none;
    border-color: #667eea;
}

.comment-form textarea {
    min-height: 120px;
    resize: vertical;
}

.comment-form-cookies-consent {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    grid-column: 1 / -1;
}

.comment-form-cookies-consent input[type="checkbox"] {
    margin: 0;
}

.form-submit {
    grid-column: 1 / -1;
}

.form-submit input[type="submit"] {
    background: #667eea;
    color: white;
    padding: 0.75rem 2rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

.form-submit input[type="submit"]:hover {
    background: #5a6fd8;
}

/* Responsive */
@media (max-width: 768px) {
    .comments-area {
        padding: 1.5rem;
    }
    
    .comment-list .children {
        margin-left: 1rem;
    }
    
    .comment-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .comment-form {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .comments-area {
        padding: 1rem;
    }
    
    .comment-list .comment {
        padding: 1rem;
    }
    
    .comment-list .children {
        margin-left: 0.5rem;
    }
}
</style>

<?php
/**
 * Custom comment callback function
 */
if (!function_exists('nhan_comment_callback')) {
    function nhan_comment_callback($comment, $args, $depth) {
        if ('div' === $args['style']) {
            $tag       = 'div';
            $add_below = 'comment';
        } else {
            $tag       = 'li';
            $add_below = 'div-comment';
        }
        ?>
        <<?php echo $tag; ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
        <?php if ('div' != $args['style']) : ?>
            <div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
        <?php endif; ?>
        
        <div class="comment-meta">
            <div class="comment-author vcard">
                <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size']); ?>
                <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>', 'nhan'), get_comment_author_link()); ?>
            </div>
            
            <div class="comment-metadata">
                <a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>">
                    <?php
                    printf(__('%1$s at %2$s', 'nhan'), get_comment_date(), get_comment_time());
                    ?>
                </a>
                <?php edit_comment_link(__('(Edit)', 'nhan'), '  ', ''); ?>
            </div>
        </div>

        <?php if ($comment->comment_approved == '0') : ?>
            <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'nhan'); ?></em>
            <br />
        <?php endif; ?>

        <div class="comment-content">
            <?php comment_text(); ?>
        </div>

        <div class="reply">
            <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
        </div>
        
        <?php if ('div' != $args['style']) : ?>
            </div>
        <?php endif; ?>
        <?php
    }
}
?>
