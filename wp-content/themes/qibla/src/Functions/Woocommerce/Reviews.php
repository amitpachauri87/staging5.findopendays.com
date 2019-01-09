<?php

namespace Qibla\Functions\Woocommerce;

use Qibla\Exception\InvalidPostException;
use Qibla\TemplateEngine\Engine as TEngine;

/**
 * Reviews Functions
 *
 * @package Qibla\Functions\Woocommerce
 *
 * @license GNU General Public License, version 2
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/**
 * Is User allowed to Reviews
 *
 * @since 1.1.0
 *
 * @throws InvalidPostException In case the product cannot be retrieved
 *
 * @param \WP_Post|\WC_Product|int $post The post from which retrieve the data
 *
 * @return bool True if allowed, false otherwise
 */
function isUserAllowedToReviews($post = null)
{
    if (! $post) {
        $post = get_post();
    }

    // Get the post.
    $post = wc_get_product($post);

    if (! $post) {
        throw new InvalidPostException();
    }

    return get_option('woocommerce_review_rating_verification_required') === 'no' ||
           wc_customer_bought_product('', get_current_user_id(), $post->get_id());
}

/**
 * Get Reviews Title Data
 *
 * @since 1.1.0
 *
 * @return \stdClass The review section data title
 */
function getReviewsTitleData()
{
    // Initialize data.
    $data = new \stdClass();
    // Get the post.
    $post = get_post();
    // Get product.
    $product = wc_get_product($post);

    $data->commentTitle = '';

    if (get_option('woocommerce_enable_review_rating') === 'yes' && ($count = $product->get_review_count())) {
        $data->commentTitle = sprintf(
            _n('%1$s review for %2$s%3$s%4$s', '%1$s reviews for %2$s%3$s%4$s', $count, 'qibla'),
            $count,
            '<span>',
            get_the_title($post),
            '</span>'
        );
    } else {
        $data->commentTitle = esc_html__('Reviews', 'qibla');
    }

    return $data;
}

/**
 * Reviews Title Template
 *
 * @since 1.1.0
 *
 * @return void
 */
function reviewsTitleTmpl()
{
    $data = call_user_func_array('Qibla\\Functions\\Woocommerce\\getReviewsTitleData', func_get_args());

    $engine = new TEngine('reviews_section_title', $data, 'views/comments/sectionTitle.php');
    $engine->render();
}

/**
 * Get Paginate Reviews Links Data
 *
 * @since 1.1.0
 *
 * @return \stdClass The data for pagination
 */
function getPaginateReviewsLinksData()
{
    // Initialize Data.
    $data = new \stdClass();

    $data->paginateArgs = array(
        'echo'               => 0,
        'type'               => 'array',
        'before_page_number' => '<span class="screen-reader-text">' . esc_html__('Page', 'qibla') . '</span>',
        'prev_text'          => sprintf(
            '<i class="fa fa-chevron-left" aria-hidden="true"></i>%s',
            '<span class="screen-reader-text">' . esc_html__('Previous Reviews', 'qibla') . '</span>'
        ),
        'next_text'          => sprintf(
            '%s<i class="fa fa-chevron-right" aria-hidden="true"></i>',
            '<span class="screen-reader-text">' . esc_html__('Next Reviews', 'qibla') . '</span>'
        ),
    );

    /**
     * Filter The Reviews arguments
     *
     * Used to keep compatibility
     *
     * @since 1.0.0
     */
    $data->paginateArgs = apply_filters('woocommerce_comment_pagination_args', $data->paginateArgs);

    $data->list = (array)paginate_comments_links($data->paginateArgs);

    return $data;
}

/**
 * Paginate Reviews Links
 *
 * @since 1.1.0
 *
 * @return void
 */
function paginateReviewsLinksTmpl()
{
    // Don't show the pagination if there is only one page of comments.
    if (1 >= get_comment_pages_count()) {
        return;
    }

    $data = call_user_func_array('Qibla\\Functions\\Woocommerce\\getPaginateReviewsLinksData', func_get_args());

    if (! $data->list) {
        return;
    }

    $engine = new TEngine('reviews_pagination', $data, '/views/pagination/archivePagination.php');
    $engine->render();
}

/**
 * Get Form Data Arguments
 *
 * @since 1.1.0
 *
 * @return \stdClass The data arguments for the form
 */
function reviewsFormData()
{
    // Initialize Data.
    $data = new \stdClass();

    // Get the current commenter.
    $commenter = wp_get_current_commenter();

    $data->commentFormArgs = array(
        'title_reply'         => have_comments() ?
            esc_html__('Add a review', 'qibla') :
            sprintf(esc_html__('Be the first to review &ldquo;%s&rdquo;', 'qibla'), get_the_title()),
        'title_reply_to'      => esc_html__('Leave a Reply to %s', 'qibla'),
        'comment_notes_after' => '',
        'fields'              => array(
            'author' => sprintf(
                '<p class="comment-form-author"><label for="author">%1$s <span class="required">*</span></label> <input id="author" name="author" type="text" value="%2$s" size="30" aria-required="true" required /></p>',
                esc_html__('Name', 'qibla'),
                esc_attr($commenter['comment_author'])
            ),
            'email'  => sprintf(
                '<p class="comment-form-email"><label for="email">%1$s<span class="required">*</span></label> <input id="email" name="email" type="email" value="%2$s" size="30" aria-required="true" required /></p>',
                esc_html__('Email', 'qibla'),
                esc_attr($commenter['comment_author_email'])
            ),
        ),
        'label_submit'        => esc_html__('Submit', 'qibla'),
        'logged_in_as'        => '',
        'comment_field'       => '',
    );

    // Retrieve the link of the account page if set.
    $accountPageUrl = wc_get_page_permalink('myaccount');
    if ($accountPageUrl) {
        $data->commentFormArgs['must_log_in'] = '<p class="must-log-in">' . sprintf(
                esc_html__('You must be %s to post a review.', 'qibla'),
                '<a href="' . esc_url($accountPageUrl) . '">' . esc_html__('logged in', 'qibla') . '</a>'
            ) . '</p>';
    }

    // Retrieve the comments rating Options if set.
    if (get_option('woocommerce_enable_review_rating') === 'yes') {
        $reviewOptions = '<div class="comment-form-rating">';
        $reviewOptions .= '<label for="rating">' . esc_html__('Your Rating', 'qibla') . '</label>';
        $reviewOptions .= '<select name="rating" id="rating" aria-required="true" required>
            <option value="">' . esc_html__('Rate&hellip;', 'qibla') . '</option>
            <option value="5">' . esc_html__('Perfect', 'qibla') . '</option>
            <option value="4">' . esc_html__('Good', 'qibla') . '</option>
            <option value="3">' . esc_html__('Average', 'qibla') . '</option>
            <option value="2">' . esc_html__('Not that bad', 'qibla') . '</option>
            <option value="1">' . esc_html__('Very Poor', 'qibla') . '</option>
        </select></div>';

        $data->commentFormArgs['comment_field'] .= $reviewOptions;
    }

    $data->commentFormArgs['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' .
                                               esc_html__('Your Review', 'qibla') .
                                               ' <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required></textarea></p>';

    /**
     * Filter The arguments
     *
     * @since 1.0.0
     */
    $data->commentFormArgs = apply_filters('woocommerce_product_review_comment_form_args', $data->commentFormArgs);

    return $data;
}

/**
 * The Review Form
 *
 * @since 1.1.0
 *
 * @return void
 */
function reviewsForm()
{
    $data = reviewsFormData();

    comment_form($data->commentFormArgs);
}
