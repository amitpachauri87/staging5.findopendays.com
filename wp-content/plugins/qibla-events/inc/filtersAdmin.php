<?php
/**
 * Admin Filters
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

use AppMapEvents\Scripts\ScriptsFacade;
use AppMapEvents\Plugin;
use AppMapEvents\Requirements;
use AppMapEvents\Admin;
use QiblaFramework\Autocomplete;

// Build the requirements.
$req = new Requirements(include Plugin::getPluginDirPath('/inc/requirements.php'));
// Scripts and Styles.
$scripts = new ScriptsFacade(include Plugin::getPluginDirPath('/inc/scripts.php'));

/**
 * Filter Admin Filters
 *
 * @since 1.0.0
 *
 * @param array $array The list of the filters list
 */
return apply_filters('appmap_ev_filters_admin', array(
    'admin' => array(
        'action' => array(
            /**
             * Autocomplete Handler
             *
             * @since 1.3.0
             */
            array(
                'filter'   => array(
                    'save_post_events',
                    'after_delete_post',
                    'delete_event_categories',
                    'edit_event_categories',
                    'update_option_rewrite_rules',
                ),
                'callback' => array(
                    new Autocomplete\CacheHandler(
                        new Autocomplete\CacheTransient(),
                        'events',
                        array(
                            'save_post_events',
                            'after_delete_post',
                            'delete_event_categories',
                            'edit_event_categories',
                            'update_option_rewrite_rules',
                        ),
                        array(
                            'taxonomies' => 'event_categories',
                        ),
                        'event_categories'
                    ),
                    'updateCachedDataOnPostInsert',
                ),
                'priority' => 20,
            ),
            /**
             * Menu Pages
             *
             * Event Package Post Listings @since 1.0.0
             */
            array(
                'filter'   => 'admin_menu',
                'callback' => array(
                    new QiblaFramework\Admin\Page\Register(array(
                        new Admin\Page\EventPackage(),
                    )),
                    'register',
                ),
                'priority' => 20,
            ),

            /**
             * Meta-Boxes
             *
             * Add Meta-boxes                                     @since 1.0.0
             * Store Meta-boxes                                   @since 1.0.0
             * Set the Page Options to the Listing Package Screen @since 1.0.0
             * Relation between Packages and Product              @since 1.0.0
             */
            array(
                'filter'   => 'add_meta_boxes',
                'callback' => function () {
                    $register = new QiblaFramework\Admin\Metabox\Register(array(
                        new Admin\Metabox\ListingRestrictions(),
                        new Admin\Metabox\PackageOptions(),
                        new Admin\Metabox\ListingPackageRelated(),
                    ));
                    $register->register();
                },
                'priority' => 30,
            ),
            array(
                'filter'        => 'save_post',
                'callback'      => function ($postID, $post, $update) {
                    QiblaFramework\Admin\Metabox\Store::storeMetaFilter(array(
                        new Admin\Metabox\ListingRestrictions(),
                        new Admin\Metabox\PackageOptions(),
                        new Admin\Metabox\ListingPackageRelated(),
                    ), $postID, $post, $update);
                },
                'priority'      => 30,
                'accepted_args' => 3,
            ),
            array(
                'filter'        => 'qibla_fw_metabox_after_store_meta',
                'callback'      => 'AppMapEvents\\Package\\PackageProductRelation::updateRelatedProductMetaWithPackageIDFilter',
                'priority'      => PHP_INT_MAX,
                'accepted_args' => 6,
            ),

            /**
             * Framework Override Metabox Fields
             *
             * Additional Listings Fields @since 1.0.1
             */
            array(
                'filter'   => 'qibla_mb_inc_listings_additional_fields',
                'callback' => 'AppMapEvents\\Admin\\Metabox\\ListingAdditionalFields::listingAdditionalFieldsFilter',
                'priority' => 20,
            ),

            /**
             * Register/Enqueue/Localize Scripts
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'admin_enqueue_scripts',
                'callback' => array($scripts, 'register'),
                'priority' => 20,
            ),
            array(
                'filter'   => 'init',
                'callback' => function () {
                    AppMapEvents\Scripts\LocalizeScripts::lazyLocalize(
                        '/inc/localizedScriptsList.php',
                        'admin_enqueue_scripts'
                    );
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'admin_enqueue_scripts',
                'callback' => array($scripts, 'enqueuer'),
                'priority' => 30,
            ),

            /**
             * Add Taxonomy Custom Fields
             *
             * - Events Categories @since 1.0.0
             * - Events Locations  @since 1.0.0
             * - Events Tags  @since 1.0.0
             */
            array(
                'filter'   => array(
                    'event_categories_add_form_fields',
                    'event_categories_edit_form_fields',
                ),
                'callback' => function () {
                    $register = new QiblaFramework\Admin\Termbox\Register(array(
                        new \QiblaFramework\Admin\Termbox\Header(),
                        new \QiblaFramework\Admin\Termbox\Color(),
                        new \QiblaFramework\Admin\Termbox\Thumbnail(),
                        new \QiblaFramework\Admin\Termbox\Icon(),
                        new \QiblaFramework\Admin\Termbox\Sidebar(),
                    ));
                    $register->register();
                },
                'priority' => 30,
            ),
            array(
                'filter'   => array(
                    'event_locations_add_form_fields',
                    'event_locations_edit_form_fields',
                ),
                'callback' => function () {
                    $register = new QiblaFramework\Admin\Termbox\Register(array(
                        new \QiblaFramework\Admin\Termbox\Thumbnail(),
                    ));
                    $register->register();
                },
                'priority' => 30,
            ),
            array(
                'filter'   => array(
                    'event_tags_add_form_fields',
                    'event_tags_edit_form_fields',
                ),
                'callback' => function () {
                    $register = new QiblaFramework\Admin\Termbox\Register(array(
                        new \QiblaFramework\Admin\Termbox\Icon(),
                        new \QiblaFramework\Admin\Termbox\TaxonomyRelation(),
                    ));
                    $register->register();
                },
                'priority' => 30,
            ),

            /**
             * Term Boxes
             *
             * - Store Term-boxes   @since 1.0.0
             * - Created Term store behavior @since 2.0.0
             */
            array(
                'filter'        => array(
                    'created_term',
                    'edit_term',
                ),
                'callback'      => function ($term_id, $tt_id, $taxonomy) {
                    \QiblaFramework\Admin\Termbox\Store::storeMetaFilter(array(
                        new \QiblaFramework\Admin\Termbox\Header(),
                        new \QiblaFramework\Admin\Termbox\Color(),
                        new \QiblaFramework\Admin\Termbox\Thumbnail(),
                        new \QiblaFramework\Admin\Termbox\Icon(),
                        new \QiblaFramework\Admin\Termbox\Sidebar(),
                        new \QiblaFramework\Admin\Termbox\TaxonomyRelation(),
                    ), $term_id, $tt_id, $taxonomy);
                },
                'priority'      => 30,
                'accepted_args' => 3,
            ),

            /**
             * Event Date Meta box
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'add_meta_boxes',
                'callback' => function () {
                    $register = new \QiblaFramework\Admin\Metabox\Register(array(
                        new Admin\Metabox\EventDates(),
                    ));
                    $register->register();
                },
                'priority' => 30,
            ),
            /**
             * Store Start/End Event Dates
             *
             * @since 1.0.0
             */
            array(
                'filter'        => 'qibla_fw_metabox_after_store_meta',
                'callback'      => 'AppMapEvents\\Admin\\Metabox\\EventDates::filterEventsStoreFilters',
                'priority'      => 20,
                'accepted_args' => 5,
            ),
            array(
                'filter'        => 'save_post',
                'callback'      => function ($postID, $post, $update) {
                    \QiblaFramework\Admin\Metabox\Store::storeMetaFilter(array(
                        new Admin\Metabox\EventDates(),
                    ), $postID, $post, $update);
                },
                'priority'      => 30,
                'accepted_args' => 3,
            ),
        ),
        'filter' => array(
            /**
             * Filter Listings Package screen
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'qibla_listing_package_options_screen',
                'callback' => function ($types) {
                    $screen = get_current_screen();
                    if ('events' === $screen->post_type && in_array('events', $types, true)) {
                        return array();
                    }

                    return $types;
                },
                'priority' => 30,
            ),
            /**
             * Notices
             *
             * Requirements Notices  @since 1.0.0
             */
            array(
                'filter'   => 'admin_notices',
                'callback' => array(
                    new \QiblaFramework\Admin\Notice\NoticeList(
                        '<strong>' . esc_html__('Qibla Events Warning', 'qibla-events') . '</strong>',
                        $req->check(),
                        'error'
                    ),
                    'notice',
                ),
                'priority' => 20,
            ),

            /**
             * Filter Permalink Settings
             *
             * @since 1.0.0
             */
            array(
                'filter'        => 'qibla_fw_permalinks_settings_fields',
                'callback'      => 'AppMapEvents\\Admin\\Settings\\PermalinkSettingsFieldsFilter::filterFilter',
                'priority'      => 30,
                'accepted_args' => 3,
            ),

            /**
             * Filter Screen For TermBox
             *
             * @since 1.0.0
             */
            array(
                'filter'   => array(
                    'qibla_fw_termbox_color_screen',
                    'qibla_fw_termbox_thumbnail_screen',
                    'qibla_fw_termbox_icon_screen',
                ),
                'callback' => function ($screen) {
                    $screen = array_merge($screen, array(
                        'event_categories',
                    ));

                    return $screen;
                },
                'priority' => 30,
            ),
            array(
                'filter'   => array(
                    'qibla_fw_termbox_thumbnail_screen',
                ),
                'callback' => function ($screen) {
                    $currentScreen = function_exists('get_current_screen') ? get_current_screen() : false;
                    if ($currentScreen && 'event_locations' === $currentScreen->taxonomy) {
                        $screen = array_merge($screen, array(
                            'event_locations',
                        ));
                    }

                    return $screen;
                },
                'priority' => 30,
            ),
            array(
                'filter'   => array(
                    'qibla_fw_termbox_icon_screen',
                ),
                'callback' => function ($screen) {
                    $screen = array_merge($screen, array(
                        'event_tags',
                    ));

                    return $screen;
                },
                'priority' => 30,
            ),
            array(
                'filter'   => array(
                    'qibla_fw_termbox_tax_relation_screen',
                ),
                'callback' => function ($screen) {
                    $screen = array_merge($screen, array(
                        'event_tags',
                    ));

                    return $screen;
                },
                'priority' => 30,
            ),
            /**
             * Filter Taxonomy Relation For TermBox
             *
             * @since 1.0.0
             */
            array(
                'filter'   => array(
                    'qibla_taxonomy_relation_term_list',
                ),
                'callback' => function ($taxonomy) {
                    $screen = function_exists('get_current_screen') ? get_current_screen() : false;
                    if ($screen && 'events' === $screen->post_type) {
                        $taxonomy = 'event_categories';
                    }

                    return $taxonomy;
                },
                'priority' => 30,
            ),

            /**
             * Filter Product listing related
             *
             * @since 1.0.0
             */
            array(
                'filter'        => 'qibla_wc_listings_listing_related_product',
                'callback'      => function ($bool, $types) {
                    if (in_array('events', $types, true) && 'events' === get_post_type()) {
                        $bool = 'no';
                    }

                    return $bool;
                },
                'priority'      => 30,
                'accepted_args' => 2,
            ),

            /**
             * Meta-box filter
             *
             * @since 1.0.0
             */
            array(
                'filter'        => 'qibla_mb_inc_listings_additional_fields',
                'callback'      => 'AppMapEvents\\Admin\\Metabox\\MetaboxFilter::filterFilter',
                'priority'      => 20,
                'accepted_args' => 2,
            ),
        ),
    ),
));
