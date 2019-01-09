<?php
/**
 * The template to display the reviewers meta data (name, verified owner, review date)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

global $comment;
$verified = wc_review_is_from_verified_owner($comment->comment_ID);

if ('0' === $comment->comment_approved) { ?>

    <footer class="meta">
        <em class="woocommerce-review__awaiting-approval">
            <?php esc_html_e('Your comment is awaiting approval', 'qibla'); ?>
        </em>
    </footer>

<?php } else { ?>

    <footer class="comment-metadata">

        <?php woocommerce_review_display_rating() ?>

        <a href="<?php echo esc_url(get_comment_link($comment)); ?>">
            <time itemprop="datePublished" datetime="<?php echo get_comment_date('c'); ?>">
                <?php echo get_comment_date(wc_date_format()); ?>
            </time>
        </a>
    </footer>

<?php }
