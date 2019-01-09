<?php

namespace Qibla\Functions;

use Qibla\TemplateEngine\Engine as TEngine;

/**
 * Comments Functions
 *
 * @todo    2.0.0 Refactor to a class if possibile.
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
 * Add Comments to Single Posts
 *
 * @since 1.0.0
 */
function comments()
{
    /**
     * Disable Comments Template
     *
     * @todo  Future. In a future version of WordPress the compact template will be removed, so, have a look in
     * wp-include/comment-template.php and see if something changed.
     *
     * @since 1.0.0
     *
     * @param string $disable 'yes' to disable comments template, 'no' otherwise
     */
    $disable = apply_filters('qibla_disable_comments', 'no');

    'no' === $disable && comments_template();
}

/**
 * Comments Section Title
 *
 * @since 1.0.0
 *
 * @return \stdClass The instance of the data
 */
function getCommentsSectionTitleData()
{
    // Data for view.
    $data = new \stdClass();
    // Comments Number.
    $cnumber = number_format_i18n(get_comments_number());
    // Get the Title.
    $title = get_the_title();

    // The comment Title.
    if ($title) {
        $data->commentTitle = sprintf(
            esc_html(_n('One response to %2$s', '%1$s responses to %2$s', $cnumber, 'qibla')),
            $cnumber,
            '<span class="' . getScopeClass('comments', 'title__article-title') . '">' . $title . '</span>'
        );
    } else {
        $data->commentTitle = sprintf(
            esc_html(_n('One response', '%1$s responses', $cnumber, 'qibla')),
            $cnumber
        );
    }

    return $data;
}

/**
 * Comments Section Title Template
 *
 * @since 1.0.0
 *
 * @return void
 */
function commentsSectionTitleTmpl()
{
    $data = call_user_func_array('Qibla\\Functions\\getCommentsSectionTitleData', func_get_args());

    $engine = new TEngine('comments_section_title', $data, 'views/comments/sectionTitle.php');
    $engine->render();
}

/**
 * Get Paginate Comments Links
 *
 * @since 1.0.0
 *
 * @return \stdClass The data object
 */
function getpaginateCommentsLinks()
{
    // Initialize Data Instance.
    $data = new \stdClass();

    $data->paginateArgs = array(
        'echo'               => 0,
        'type'               => 'array',
        'before_page_number' => '<span class="screen-reader-text">' . esc_html__('Page', 'qibla') . '</span>',
        'prev_text'          => sprintf(
            '<i class="fa fa-chevron-left" aria-hidden="true"></i>%s',
            '<span class="screen-reader-text">' . esc_html__('Previous Comments', 'qibla') . '</span>'
        ),
        'next_text'          => sprintf(
            '%s<i class="fa fa-chevron-right" aria-hidden="true"></i>',
            '<span class="screen-reader-text">' . esc_html__('Next Comments', 'qibla') . '</span>'
        ),
    );

    $data->list = (array)paginate_comments_links($data->paginateArgs);

    return $data;
}

/**
 * Paginate Comments Links
 *
 * @since 1.0.0
 *
 * @return void
 */
function paginateCommentsLinksTmpl()
{
    // Don't show the pagination if there is only one page of comments.
    if (1 >= get_comment_pages_count()) {
        return;
    }

    $data = call_user_func_array('Qibla\\Functions\\getpaginateCommentsLinks', func_get_args());

    if (! $data->list) {
        return;
    }

    $engine = new TEngine('archive_pagination', $data, '/views/pagination/archivePagination.php');
    $engine->render();
}

/**
 * Disable Comments On Page
 *
 * Disable comments on specific page.
 *
 * @since 1.0.0
 *
 * @param string $disabled If the comments must be disabled in a particular page.
 *
 * @return string The filtered parameter
 */
function disableCommentsOnPage($disabled)
{
    if (is_page_template('templates/homepage.php') ||
        is_page_template('templates/homepage-fullwidth.php') ||
        is_page_template('templates/events-search.php')
    ) {
        $disabled = 'yes';
    }

    return $disabled;
}

/**
 * Filter Logged In As
 *
 * Remove the link to the edit profile admin page.
 *
 * @since 1.6.0
 *
 * @param array $defaults The default arguments list for the comments form.
 *
 * @return mixed
 */
function filterLoggedInAs($defaults)
{
    $user          = wp_get_current_user();
    $user_identity = $user->exists() ? $user->display_name : '';

    $defaults['logged_in_as'] = '<p class="logged-in-as">' . sprintf(
        // Translators: 1: user name, 2: logout URL.
            __('Logged in as <strong>%1$s</strong>. <a href="%2$s">Log out?</a>', 'qibla'),
            $user_identity,
            wp_logout_url(apply_filters('the_permalink', get_permalink(get_the_ID())))
        ) . '</p>';

    return $defaults;
}
