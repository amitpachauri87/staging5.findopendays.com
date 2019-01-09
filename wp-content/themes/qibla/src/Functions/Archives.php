<?php
namespace Qibla\Functions;

use Qibla\TemplateEngine\Engine as TEngine;

/**
 * Archive Functions
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
 * Get the term archive description
 *
 * This function override the default WordPress get_the_archive_description able to get raw content.
 *
 * @see   term_description()
 *
 * @since 1.0.0
 *
 * @param \stdClass $queriedObject The object needed to get the correct archive description
 *
 * @return string The term archive description.
 */
function getTheArchiveDescription($queriedObject = null)
{
    global $wp_query;

    $page_for_posts = intval(get_option('page_for_posts'));
    $description    = '';
    $queriedObject  = $queriedObject ?: get_queried_object();

    if (is_tax() || is_tag() || is_category()) {
        $taxonomy    = $queriedObject->taxonomy;
        $term        = $queriedObject->term_id;
        $description = get_term_field('description', $term, $taxonomy, 'raw');

        // Show or not the taxonomy description if the term description is empty.
        if ('no' === apply_filters('qibla_hide_taxonomy_description', 'no')) {
            $description = empty($description) ? get_taxonomy($taxonomy)->description : $description;
        }
    } elseif (is_post_type_archive()) {
        /**
         * Filters the description for a post type archive.
         *
         * @since 1.5.1
         * @since Wp4.9.0
         *
         * @param string        $description   The post type description.
         * @param \WP_Post_Type $queriedObject The post type object.
         */
        $description = apply_filters('get_the_post_type_description', $queriedObject->description, $queriedObject);
    } elseif (is_search()) {
        $description = sprintf(
        // Translators: The %s is the i18n numbers of the posts found.
            esc_html__('We had found %s posts based on your search', 'qibla'),
            '<strong class="' . getScopeClass('', 'postsfound-number') . '">' .
            number_format_i18n($wp_query->found_posts) . '</strong>'
        );
    } elseif (is_home() && get_option('page_for_posts')) {
        $description = strip_shortcodes(get_post($page_for_posts)->post_content);
        $description = wp_strip_all_tags($description);
    }

    if (is_wp_error($description)) {
        $description = '';
    }

    if (! empty($description)) {
        // Apply the default WordPress filters but not 'wpautop'.
        foreach (array('wptexturize', 'convert_chars', 'shortcode_unautop') as $filter) {
            apply_filters($filter, $description);
        }
    }

    /**
     * Filter the archive description.
     *
     * @since WordPress 4.1.0
     * @since 1.0.0
     *
     * @see   get_the_archive_description()
     *
     * @param string $description Archive description to be displayed.
     */
    $description = apply_filters('get_the_archive_description', $description);

    return $description;
}

/**
 * Archive Description
 *
 * @since 1.0.0
 *
 * @return void
 */
function theArchiveDescription()
{
    // Initialize data instance.
    $data = new \stdClass();

    // Set the archive description.
    $data->description = getTheArchiveDescription();

    $engine = new TEngine('archive_description', $data, '/views/archives/description.php');
    $engine->render();
}

/**
 * Add Pagination to archive page
 *
 * @todo 2.0.0 Move into a TemplateClass with archivePaginationTmpl
 *
 * @since 1.0.0
 *
 * @return \stdClass The object data.
 */
function getArchivePagination()
{
    global $wp_query;

    // Data for template.
    $data = new \stdClass();
    // Need an unlikely integer.
    $big = 999999999;

    $data->paginateArgs = array(
        'base'               => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'type'               => 'array',
        'format'             => '?paged=%#%',
        'current'            => max(1, get_query_var('paged')),
        'total'              => $wp_query->max_num_pages,
        'before_page_number' => '<span class="screen-reader-text">' . esc_html__('Page', 'qibla') . '</span>',
        'prev_text'          => sprintf(
            '<i class="fa fa-chevron-left" aria-hidden="true"></i>%s',
            '<span class="screen-reader-text">' . esc_html__('Previous Page', 'qibla') . '</span>'
        ),
        'next_text'          => sprintf(
            '%s<i class="fa fa-chevron-right" aria-hidden="true"></i>',
            '<span class="screen-reader-text">' . esc_html__('Next Page', 'qibla') . '</span>'
        ),
    );

    // Get the pagination markup.
    $data->list = (array)paginate_links($data->paginateArgs);

    return $data;
}

/**
 * Archive Pagination Template
 *
 * @todo 2.0.0 Move into a TemplateClass with getArchivePagination
 *
 * @since 1.0.0
 *
 * @return void
 */
function archivePaginationTmpl()
{
    $data = call_user_func_array('Qibla\\Functions\\getArchivePagination', func_get_args());

    if (! $data->list) {
        return;
    }

    $engine = new TEngine('archive_pagination', $data, '/views/pagination/archivePagination.php');
    $engine->render();
}
