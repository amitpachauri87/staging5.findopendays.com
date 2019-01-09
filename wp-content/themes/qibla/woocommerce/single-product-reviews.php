<?php
use Qibla\Functions as F;
use Qibla\Functions\Woocommerce as Wf;

/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       3.2.0
 */

global $product;

// @todo This doesn't make sense. If comments are not opens, just show them and not the form.
if (! comments_open()) {
    return;
}
?>

<section <?php F\scopeID('comments') ?> <?php F\scopeClass('comments') ?>>

    <?php
    /**
     * Before Reviews
     *
     * @since 1.0.0
     */
    do_action('qibla_before_reviews');

    if (have_comments()) :
        /**
         * Before Reviews List
         *
         * @since 1.0.0
         */
        do_action('qibla_before_reviews_list'); ?>

        <ol <?php F\scopeClass('comments', 'list') ?>>
            <?php wp_list_comments(apply_filters('woocommerce_product_review_list_args', array(
                'callback' => 'woocommerce_comments',
            ))); ?>
        </ol>

        <?php
        /**
         * After Reviews List
         *
         * @since 1.0.0
         */
        do_action('qibla_after_reviews_list');

    endif;
    if (comments_open() && ! have_comments()) : ?>
        <p <?php F\scopeClass('comments', 'nocomments') ?>>
            <?php esc_html_e('There are no reviews yet.', 'qibla'); ?>
        </p>
    <?php elseif (! comments_open()) : ?>
        <p <?php F\scopeClass('comments', 'nocomments') ?>>
            <?php esc_html_e('Reviews are closed.', 'qibla'); ?>
        </p>
    <?php endif;

    if (Wf\isUserAllowedToReviews()) {
        if (comments_open()) {
            Wf\reviewsForm();
        }
    } else { ?>
        <p class="woocommerce-verification-required">
            <?php _e('Only logged in customers who have purchased this product may leave a review.', 'qibla'); ?>
        </p>
    <?php
    }

    /**
     * After Reviews
     *
     * @since 1.0.0
     */
    do_action('qibla_after_reviews'); ?>
</section>
