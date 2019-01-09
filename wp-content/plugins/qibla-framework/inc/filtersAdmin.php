<?php
/**
 * Admin Filters
 *
 * @since     1.0.0
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2016, Guido Scialfa
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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

use Unprefix\Scripts\ScriptsFacade;
use Unprefix\Scripts\LocalizeScripts;
use QiblaFramework\Plugin;
use QiblaFramework\Requirements;
use QiblaFramework\Admin;
use QiblaFramework\Autocomplete;

// Build the requirements.
$req = new Requirements(include Plugin::getPluginDirPath('/inc/requirements.php'));
// Scripts and Styles.
$scripts          = new ScriptsFacade(include Plugin::getPluginDirPath('/inc/scripts.php'));

/**
 * Filter Admin Filters
 *
 * @since 1.0.0
 *
 * @param array $array The list of the filters list
 */
return apply_filters('qibla_filters_admin', array(
    'admin' => array(
        'action' => array(
            /**
             * Autocomplete Handler
             *
             * @since 1.3.0
             */
            array(
                'filter'   => array(
                    'save_post_listings',
                    'after_delete_post',
                    'delete_listing_categories',
                    'edit_listing_categories',
                    'update_option_rewrite_rules',
                ),
                'callback' => array(
                    new Autocomplete\CacheHandler(
                        new Autocomplete\CacheTransient(),
                        'listings',
                        array(
                            'save_post_listings',
                            'after_delete_post',
                            'delete_listing_categories',
                            'edit_listing_categories',
                            'update_option_rewrite_rules',
                        ),
                        array(
                            'taxonomies' => 'listing_categories',
                        ),
                        'listing_categories'
                    ),
                    'updateCachedDataOnPostInsert',
                ),
                'priority' => 20,
            ),

            /**
             * Notices
             *
             * Requirements Notices  @since 1.0.0
             * Listings Meta Updater @since 1.7.0
             */
            array(
                'filter'   => 'admin_notices',
                'callback' => array(
                    new Admin\Notice\NoticeList(
                        '<strong>' . esc_html__('Qibla Framework Warning', 'qibla-framework') . '</strong>',
                        $req->check(),
                        'error'
                    ),
                    'notice',
                ),
                'priority' => 20,
            ),
            // @todo Remove since 1.9.0 but keep the task.
            array(
                'filter'   => 'admin_notices',
                'callback' => 'QiblaFramework\\Notice\\NoticeUpdateMetaListings::noticeFilter',
                'priority' => 10,
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
                    LocalizeScripts::lazyLocalize('/inc/localizedScriptsList.php', 'admin_enqueue_scripts');
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'admin_enqueue_scripts',
                'callback' => array($scripts, 'enqueuer'),
                'priority' => 30,
            ),

            /**
             * Settings
             *
             * - Permalink Settings @since 1.5.0
             * - Theme Option       @since 1.0.0
             */
            array(
                'filter'   => 'admin_init',
                'callback' => 'QiblaFramework\\Admin\\PermalinkSettings::permalinkSettingsFilter',
                // Set to 0 so we can update the permalinks before taxonomies and post type are registered.
                'priority' => 0,
            ),
            array(
                'filter'   => 'wp_loaded',
                'callback' => array(
                    'QiblaFramework\\Admin\\Settings\\Controller',
                    'initializeControllerFilterCallback',
                ),
                'priority' => 20,
            ),

            /**
             * Menu Pages
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'admin_menu',
                'callback' => array(
                    new Admin\Page\Register(array(
                        new Admin\Page\Qibla(),
                        new Admin\Page\Settings(),
                        new Admin\Page\UsefulLinks(),
                    )),
                    'register',
                ),
                'priority' => 20,
            ),

            /**
             * Walkers
             *
             * - Main Menu Edit Walker @since 1.0.0
             */
            array(
                'filter'        => 'wp_edit_nav_menu_walker',
                'callback'      => 'QiblaFramework\\Admin\\Walker\\MenuEditMainWalker::editMenuMainFilter',
                'priority'      => 20,
                'accepted_args' => 2,
            ),
            array(
                'filter'        => 'wp_update_nav_menu_item',
                'callback'      => 'QiblaFramework\\Admin\\Walker\\MenuEditMainWalker::storeExtraMenuFields',
                'priority'      => 20,
                'accepted_args' => 3,
            ),

            /**
             * Meta-boxes
             *
             * Add Standard Meta-boxes @since 1.0.0
             * Add Comments Meta-boxes @since 1.2.0
             * Store Meta-boxes        @since 1.0.0
             * Store Comments Meta     @since 1.2.0
             */
            array(
                'filter'   => 'add_meta_boxes',
                'callback' => function () {
                    $register = new  Admin\Metabox\Register(array(
                        new Admin\Metabox\Page(),
                        new Admin\Metabox\Testimonial(),
                        new Admin\Metabox\Listings(),
                        new Admin\Metabox\Sidebar(),
                    ));
                    $register->register();
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'add_meta_boxes_comment',
                'callback' => function ($comment) {
                    $types = new \QiblaFramework\ListingsContext\Types();
                    $type  = $types->isListingsType(get_post_type(get_comment($commentID)->comment_post_ID));

                    if ($type) {
                        $register = new Admin\Metabox\Register(array(
                            new Admin\Metabox\Review($comment),
                        ));
                        $register->register();
                    }
                },
                'priority' => 20,
            ),
            array(
                'filter'        => 'save_post',
                'callback'      => function ($postID, $post, $update) {
                    Admin\Metabox\Store::storeMetaFilter(array(
                        new Admin\Metabox\Page(),
                        new Admin\Metabox\Testimonial(),
                        new Admin\Metabox\Listings(),
                        new Admin\Metabox\Sidebar(),
                    ), $postID, $post, $update);
                },
                'priority'      => 20,
                'accepted_args' => 3,
            ),
            array(
                'filter'        => 'edit_comment',
                'callback'      => function ($commentID, $data) {
                    $types = new \QiblaFramework\ListingsContext\Types();
                    $type  = $types->isListingsType(get_post_type(get_comment($commentID)->comment_post_ID));

                    if ($type) {
                        Admin\Metabox\StoreComments::storeMetaFilter(array(
                            new Admin\Metabox\Review(get_comment($commentID)),
                        ), $commentID, $data);
                    }
                },
                'priority'      => 20,
                'accepted_args' => 2,
            ),

            /**
             * Add Taxonomy Custom Fields
             *
             * - Term Meta Color   @since 2.0.0
             */
            array(
                'filter'   => array(
                    'listing_categories_add_form_fields',
                    'listing_categories_edit_form_fields',
                ),
                'callback' => function () {
                    $register = new Admin\Termbox\Register(array(
                        new Admin\Termbox\Color(),
                    ));
                    $register->register();
                },
                'priority' => 10,
            ),

            /**
             * Add Taxonomy Custom Fields
             *
             * - Category           @since 1.0.0
             * - Post Tags          @since 1.0.0
             * - Locations          @since 1.0.0
             * - Listing Categories @since 1.0.0
             * - Amenities          @since 1.0.0
             * - Product Categories @since 1.0.0
             * - Product Tags       @since 1.0.0
             */
            array(
                'filter'   => array(
                    'category_add_form_fields',
                    'post_tag_add_form_fields',
                    'locations_add_form_fields',
                    'listing_categories_add_form_fields',
                    'amenities_add_form_fields',
                    'product_cat_add_form_fields',
                    'product_tag_add_form_fields',

                    'category_edit_form_fields',
                    'post_tag_edit_form_fields',
                    'locations_edit_form_fields',
                    'listing_categories_edit_form_fields',
                    'amenities_edit_form_fields',
                    'product_cat_edit_form_fields',
                    'product_tag_edit_form_fields',
                ),
                'callback' => function () {
                    $register = new Admin\Termbox\Register(array(
                        new Admin\Termbox\Header(),
                        new Admin\Termbox\Thumbnail(),
                        new Admin\Termbox\Jumbotron(),
                        new Admin\Termbox\JumbotronSlider(),
                        new Admin\Termbox\Icon(),
                        new Admin\Termbox\Sidebar(),
                        new Admin\Termbox\TaxonomyRelation(),
                    ));
                    $register->register();
                },
                'priority' => 20,
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
                    Admin\Termbox\Store::storeMetaFilter(array(
                        new Admin\Termbox\Header(),
                        new Admin\Termbox\Color(),
                        new Admin\Termbox\Thumbnail(),
                        new Admin\Termbox\Jumbotron(),
                        new Admin\Termbox\JumbotronSlider(),
                        new Admin\Termbox\Icon(),
                        new Admin\Termbox\Sidebar(),
                        new Admin\Termbox\TaxonomyRelation(),
                    ), $term_id, $tt_id, $taxonomy);
                },
                'priority'      => 20,
                'accepted_args' => 3,
            ),

            /**
             * Set parent term
             *
             * Flag the parent term when a child term is saved.
             *
             * @since 1.0.0
             */
            array(
                'filter'        => 'set_object_terms',
                'callback'      => 'QiblaFramework\\Functions\\setParentTerm',
                'priority'      => 20,
                'accepted_args' => 6,
            ),

            /**
             * Register Visual Composer Custom Types
             *
             * @since 1.6.0
             */
            array(
                'filter'   => 'admin_init',
                'callback' => array(
                    new QiblaFramework\VisualComposer\TypeRegister(array(
                        new \QiblaFramework\VisualComposer\Type\IconList(),
                        new \QiblaFramework\VisualComposer\Type\Radio(),
                        new \QiblaFramework\VisualComposer\Type\Select2(),
                    )),
                    'register',
                ),
                'priority' => 20,
            ),

            /**
             * Additional Field-sets
             *
             * Additional field-sets for Listings Meta-box
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'qibla_metabox_set_fieldsets_listing_option',
                'callback' => array(
                    '\\QiblaFramework\\Admin\\Metabox\\CustomContentFieldset',
                    'hookFieldsets',
                ),
                'priority' => 20,
            ),

            /**
             * Update Post in qibla_meta_locations_no_updated if data is correct
             *
             * @todo  1.9.0 To Remove
             *
             * @since 1.7.1
             */
            array(
                'filter'        => array(
                    'updated_postmeta',
                    'added_post_meta',
                ),
                'callback'      => 'QiblaFramework\\Update\\UpdateLocationOptionOldMeta::updateFilter',
                'priority'      => 30,
                'accepted_args' => 4,
            ),
        ),
        'filter' => array(
            /**
             * Admin Body Classes
             *
             * @since 2.0.0
             */
            array(
                'filter'   => 'admin_body_class',
                'callback' => 'QiblaFramework\\Functions\\adminBodyClass',
                'priority' => 20,
            ),
        ),
    ),
));
