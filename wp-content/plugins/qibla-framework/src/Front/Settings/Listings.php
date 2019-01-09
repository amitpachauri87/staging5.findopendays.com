<?php

namespace QiblaFramework\Front\Settings;

use QiblaFramework\Functions as F;
use QiblaFramework\ListingsContext\Context;
use QiblaFramework\ListingsContext\Types;

/**
 * Class Front-end Settings Listings
 *
 * @since      1.0.0
 * @package    QiblaFramework\Front\Settings
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa
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
 * Class Listings
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class Listings
{
    /**
     * Show Map On Archive
     *
     * @since 1.7.0
     *
     * @return bool True to show the map, false otherwise
     */
    public static function showMapOnArchive()
    {
        $obj     = get_queried_object();
        $context = new Context(F\getWpQuery(), new Types());

        // Default type.
        $type = 'listings';

        if ($obj instanceof \WP_Post_Type) {
            $type = $obj->name;
        } elseif ($obj instanceof \WP_Term) {
            $type = $context->listingTypeFromTax($obj->taxonomy);
        }

        switch ($type) {
            default:
            case 'listings':
                // By default it's 'on'. See inc/defaultOptions.php.
                $option = F\getThemeOption('listings', 'archive_show_map', true);
                break;
            case 'events':
                // By default it's 'on'. See inc/defaultOptions.php.
                $option = F\getThemeOption('events', 'archive_show_map', true);
                break;
            case 'tours':
                // By default it's 'on'. See inc/defaultOptions.php.
                $option = F\getThemeOption('tours', 'archive_show_map', true);
                break;
        }

        return F\stringToBool($option);
    }

    /**
     * Posts Per Page
     *
     * @since  1.0.0
     *
     * @param \WP_Query $query The query to edit
     *
     * @return void
     */
    public static function postsPerPage($query)
    {
        // Avoid edit other queries.
        if (! F\isListingsMainQuery($query)) {
            return;
        }

        $setting = F\getThemeOption('listings', 'posts_per_page', true);

        if (! is_numeric($setting)) {
            $setting = 10;
        }

        $query->set('posts_per_page', intval($setting));
    }

    /**
     * Order By Featured
     *
     * @since  1.0.0
     *
     * @param array     $posts A list of \WP_Post instances found by the query.
     * @param \WP_Query $query The current query to set.
     *
     * @return array The reorder posts
     */
    public static function orderByFeatured($posts, \WP_Query $query)
    {
        // Avoid edit other queries.
        if (! F\isListingsMainQuery($query)) {
            return $posts;
        }

        $setting = F\getThemeOption('listings', 'order_by_featured', true);
        if ('on' !== $setting) {
            return $posts;
        }

        // Retrieve the posts that have the post meta to on.
        // This posts must be push at the top of the stack.
        $featured = array();
        foreach ($posts as $index => $post) {
            if ('on' === get_post_meta($post->ID, '_qibla_mb_is_featured', true)) {
                $featured[$index] = $post;
            }
        }

        foreach ($featured as $index => $post) {
            // Remove the featured posts from the default posts stack.
            unset($posts[$index]);
        }

        // Reorder.
        $posts = array_merge($featured, $posts);

        // Return posts.
        return $posts;
    }

    /**
     * Force Disable Page Reviews
     *
     * @since  1.2.0
     *
     * @param string $disable The current value of the disable status
     *
     * @return string The filtered status, 'yes' to disable, 'no' to keep.
     */
    public static function forceDisableReviews($disable)
    {
        if (! Context::isSingleListings()) {
            return $disable;
        }

        // Retrieve the option.
        $option = F\getThemeOption('listings', 'disable_reviews', true);
        // 'on' => 'yes', off => 'no'
        $disable = 'on' === $option ? 'yes' : 'no';

        return $disable;
    }
}
