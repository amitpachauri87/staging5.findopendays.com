<?php
/**
 * Common Filters
 *
 * @since      1.0.0
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

use AppMapEvents\PostType;
use AppMapEvents\Taxonomy;
use AppMapEvents\Shortcode;

/**
 * Filter Inc Filters
 *
 * @since 1.0.0
 *
 * @param array $array The list of the filters list
 */
return apply_filters('appmap_ev_filters_inc', array(
    'inc' => array(
        'action' => array(
            /**
             * Remove Filters
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'wp_loaded',
                'callback' => array(new \AppMapEvents\DetachFilters(), 'detach'),
                'priority' => 0,
            ),

            /**
             * Widgets
             *
             * - Register Sidebars @since 1.5.1
             */
            array(
                'filter'   => 'widgets_init',
                'callback' => array(
                    new \QiblaFramework\Sidebars\Register(
                        include AppMapEvents\Plugin::getPluginDirPath('/inc/sidebarsList.php')
                    ),
                    'register',
                ),
                // After the one in theme.
                'priority' => 30,
            ),

            array(
                'filter'   => 'after_setup_theme',
                'callback' => array(
                    new \QiblaFramework\Shortcode\Register(array(
                        new Shortcode\Events(),
                        new Shortcode\EventMaps(),
                        new Shortcode\Terms(),
                        new Shortcode\TermsLocations(),
                        new Shortcode\EventSearch(),
                        new Shortcode\ListingPackage(),
                    )),
                    'register',
                ),
                'priority' => 40,
            ),

            /**
             * Register Post Type
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'init',
                'callback' => array(
                    new \QiblaFramework\PostType\Register(array(
                        new PostType\Events(),
                        new PostType\ListingPackage(),
                    )),
                    'register',
                ),
                'priority' => 0,
            ),

            /**
             * Register Taxonomy
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'init',
                'callback' => array(
                    new \QiblaFramework\Taxonomy\Register(array(
                        new Taxonomy\Locations(),
                        new Taxonomy\Tags(),
                        new Taxonomy\EventCategories(),
                        new Taxonomy\EventDates(),
                    )),
                    'register',
                ),
                'priority' => 0,
            ),

            /**
             * Send Email on Listing Expiration
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'qibla_listings_expire_by_date',
                'callback' => 'AppMapEvents\\Listing\\Expire\\EmailOnExpiration::handleFilter',
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
                'callback'      => 'AppMapEvents\\Crud\\EventUpdateStatus::expireDuringPostResultsFilter',
                'priority'      => 0,
                'accepted_args' => 2,
            ),
            /**
             * Filter Listings Types
             *
             * - Include types @since 1.0.0
             */
            array(
                'filter'   => array(
                    'qibla_listings_types_list',
                ),
                'callback' => 'AppMapEvents\\PostType\\ListingsTypesListFilter::filterFilter',
                'priority' => 20,
            ),
        ),
    ),
));