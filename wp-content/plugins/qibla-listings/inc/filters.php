<?php

use QiblaListings\PostType;

/**
 * Common Filters
 *
 * @since     1.0.0
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2017, Guido Scialfa
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (c) 2017 Guido Scialfa <dev@guidoscialfa.com>
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
 * Filter Hooks
 *
 * @since 1.0.0
 *
 * @param array $args The arguments for the loader.
 */
return apply_filters('qibla_listings_filters', array(
    'inc' => array(
        'action' => array(
            /**
             * EndPoints
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'init',
                'callback' => array(
                    new QiblaFramework\EndPoint\Register(array(
                        new \QiblaListings\Listing\ManagerPosts\MyListingsEndPoint(),
                    )),
                    'register',
                ),
                'priority' => 1,
            ),

            /*
             * Register Post Type
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'init',
                'callback' => array(
                    new PostType\Register(array(
                        new PostType\ListingPackage(),
                    )),
                    'register',
                ),
                'priority' => 0,
            ),

            /**
             * Register Post Status
             *
             * - Soft Delete Listing @since 1.0.0
             */
            array(
                'filter'   => 'init',
                'callback' => array(
                    new \QiblaFramework\PostStatus\Register(array(
                        new \QiblaListings\PostStatus\Expired(),
                        new \QiblaListings\PostStatus\SoftDeleted(),
                    )),
                    'register',
                ),
                // After the post types has been registered.
                // See above.
                'priority' => 2,
            ),

            /**
             * Register Shortcodes
             *
             * Listing Package @since 1.0.0
             */
            array(
                'filter'   => 'after_setup_theme',
                'callback' => array(
                    new QiblaFramework\Shortcode\Register(array(
                        new \QiblaListings\Shortcode\ListingPackage(),
                    )),
                    'register',
                ),
                'priority' => 20,
            ),

            /**
             * Send Email on Listing Expiration
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'qibla_listings_expire_by_date',
                'callback' => 'QiblaListings\\Listing\\Expire\\EmailOnExpiration::handleFilter',
                'priority' => 20,
            ),
        ),
        'filter' => array(
            /**
             * Update Listing Posts Status
             *
             * @since 1.0.0
             */
            array(
                'filter'        => 'posts_results',
                'callback'      => 'QiblaListings\\Crud\\ListingUpdateStatus::expireDuringPostResultsFilter',
                'priority'      => 0,
                'accepted_args' => 2,
            ),
        ),
    ),
));
