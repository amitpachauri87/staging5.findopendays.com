<?php
/**
 * Archive Functions
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @package   QiblaFramework\Functions
 * @copyright Copyright (c) 2017, Guido Scialfa
 * @license   GNU General Public License, version 2
 *
 * Copyright (C) 2017 Guido Scialfa <dev@guidoscialfa.com>
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

namespace QiblaFramework\Functions;

use QiblaFramework\ListingsContext\Types;
use QiblaFramework\TemplateEngine\Engine as TEngine;
use QiblaFramework\Front\Settings;

/**
 * Found Posts
 *
 * @since  1.0.0
 *
 * @return \stdClass $data The data object.
 */
function getFoundPosts()
{
    $mainQuery = getWpQuery();

    // Initialize Data.
    $data = new \stdClass();

    // Set the found posts.
    $data->number = intval($mainQuery->found_posts);

    // Default label.
    $data->label = esc_html__('results', 'qibla-framework');
    if (0 < $mainQuery->found_posts) {
        $data->label = isset($mainQuery->query['post_type']) && ! is_array($mainQuery->query['post_type']) ?
            esc_html(ucfirst($mainQuery->query['post_type'])) :
            esc_html(ucfirst($mainQuery->posts[0]->post_type));
    }

    $postsPerPage = intval($mainQuery->get('posts_per_page'));
    $paged        = intval($mainQuery->get('paged')) ?: 1;

    // The Number Of value is the number of post that we are showing into the current page.
    $data->numberOf = 0;

    if (-1 === $postsPerPage) {
        $data->numberOf = $data->number;
    } elseif (is_paged() || $data->number > $postsPerPage) {
        $data->numberOf = $postsPerPage * $paged;
        // Last page.
        if (intval($mainQuery->max_num_pages) === $paged) {
            $data->numberOf = $data->number;
        }
    } elseif ($data->number) {
        $data->numberOf = $data->number;
    }

    // Set the numbers separator.
    $data->numSeparator = esc_html_x('of', 'found-posts-separator', 'qibla-framework');

    // The current page label.
    // Based on queried Object.
    $currLabel = '';

    $currObj = get_queried_object();
    if ($currObj instanceof \WP_Term) {
        $currLabel = is_array($currObj) ? $currObj[0]->name : $currObj->name;
    }

    // All depend by the ajax url for filtering.
    // @codingStandardsIgnoreLine
    $inputValue = filterInput($_POST, 'qibla_listing_categories_filter', FILTER_SANITIZE_STRING) ?: false;
    if (! $currLabel && $inputValue) {
        $currLabel = ' ' . esc_html(ucfirst($inputValue));
    }

    $data->currObjLabel = $currLabel ? '<i>&#183;</i>' . $currLabel : '';

    return $data;
}

/**
 * Get Archive Description
 *
 * @since 2.1.0
 *
 * @return \stdClass $data The data object.
 */
function getArchiveDescription()
{
    $currObj = get_queried_object();
    // Initialize Data.
    $data = new \stdClass();
    // Initialized description.
    $data->description = '';

    if (isset($currObj->taxonomy)) {
        $taxonomy          = $currObj->taxonomy;
        $term              = $currObj->term_id;
        $data->description = get_term_field('description', $term, $taxonomy, 'raw');
    } else {
        $data->description = $currObj->description;
    }

    return $data;
}

/**
 * Found Posts View
 *
 * @since 1.0.0
 *
 * @return void
 */
function foundPostsTmpl()
{
    $engine = new TEngine('listings_found_posts', getFoundPosts(), 'views/foundPosts.php');
    $engine->render();
}

/**
 * Toolbar
 *
 * @since  1.0.0
 *
 * @return void
 */
function listingsToolbarTmpl()
{
    $engine = new TEngine('listings_toolbar', new \stdClass(), '/views/archive/listingsToolbar.php');
    $engine->render();
}

/**
 * Post Thumbnail Size
 *
 * @since  1.0.0
 *
 * @hooked to post_thumbnail_size
 *
 * @param string|array $size The post thumbnail size. Image size or array of width and height values
 *                           (in that order).
 *
 * @return string
 */
function postThumbnailSize($size)
{
    $types = new Types();

    // Get the post type and if not set return.
    if (! is_singular() && $types->isListingsType(get_post_type())) {
        /**
         * Filter size for post thumbnail loop.
         *
         * @since 2.4.0
         */
        $size = apply_filters('qibla_post_thumbnail_size', 'qibla-post-thumbnail-loop');
    }

    return $size;
}

/**
 * Get the archive Page
 *
 * Archive page are the ones like page for posts and shop.
 * All of the pages that works like archives.
 *
 * @since 1.0.0
 * @uses  get_post() To retrieve the post
 *
 * @return mixed Whatever the get_post return
 */
function getArchivePage()
{
    $post = null;

    if (is_home() && get_option('page_for_posts')) {
        $post = intval(get_option('page_for_posts'));
    } elseif (isWooCommerceActive() && isShop()) {
        $post = intval(get_option('woocommerce_shop_page_id'));
    }

    return get_post($post);
}

/**
 * Remove Footer From Archive listings if showing map
 *
 * If showing map setting is set to true, hide the footer on listings archives pages.
 * This include even the taxonomies.
 *
 * @since 1.7.0
 *
 * @param \stdClass $data The data object to pass to the view.
 *
 * @return null|\stdClass Null if footer should not be showed. The \stdClass instance otherwise.
 */
function removeFooterFromArchiveListingsIfMap(\stdClass $data)
{
    if (isListingsArchive() && Settings\Listings::showMapOnArchive()) {
        $data = null;
    }

    return $data;
}
