<?php
/**
 * FrontEnd Filters
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

// Scripts and Styles.
$scripts = new ScriptsFacade(include Plugin::getPluginDirPath('/inc/scripts.php'));

/**
 * Filter Front Filters
 *
 * @since 1.0.0
 *
 * @param array $array The list of the filters list
 */
return apply_filters('appmap_ev_filters_front', array(
    'front' => array(
        'action' => array(
            /**
             * Requests
             *
             * - Search @since 1.1.0
             */
            array(
                'filter'   => 'init',
                'callback' => 'AppMapEvents\\Search\\Request\\RequestSearch::handleRequestFilter',
                'priority' => 10,
            ),
            /**
             * Template Redirect
             *
             *  Request Listings Form               @since 1.0.0
             *  Redirect Product to Listing Package @since 1.0.0
             */
            array(
                'filter'   => 'template_redirect',
                'callback' => 'AppMapEvents\\Front\\ListingForm\\RequestForm::handleRequestFilter',
                'priority' => 0,
            ),

            /**
             * Single Listings
             *
             * Add the Edit Post Link                                   @since 1.0.0
             * Disable Jumbotron within the single listing package post @since 1.0.0
             * Remove the breadcrumb from the single listing package    @since 1.0.0
             */
            array(
                'filter'   => 'qibla_fw_is_jumbotron_allowed',
                'callback' => 'AppMapEvents\\Front\\ListingPackage\\SingleListingPackage::disableJumbotronWithinSingularListingPackageFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_template_engine_data_breadcrumb',
                'callback' => 'AppMapEvents\\Front\\ListingPackage\\SingleListingPackage::removeBreadcrumbFromSingleListingPackage',
                'priority' => 20,
            ),

            /**
             * Sidebar
             *
             * Single Package Listing @since 1.0.0
             */
            array(
                'filter'   => array(
                    'qibla_has_sidebar',
                    'qibla_show_sidebar',
                ),
                'callback' => 'AppMapEvents\\Sidebar::removeSidebarFromSinglePackageListingFilter',
                // After the theme and framework.
                'priority' => 40,
            ),

            /**
             * Register/Enqueue/Localize Scripts
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'wp_enqueue_scripts',
                'callback' => array($scripts, 'register'),
                'priority' => 20,
            ),
            array(
                'filter'   => 'init',
                'callback' => function () {
                    AppMapEvents\Scripts\LocalizeScripts::lazyLocalize(
                        '/inc/localizedScriptsList.php',
                        'wp_enqueue_scripts'
                    );
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'wp_enqueue_scripts',
                'callback' => array($scripts, 'enqueuer'),
                'priority' => 30,
            ),

            /**
             * Article Events
             *
             * - Set Icon for events article title. @since 1.0.0
             * - Events Post Thubmnail Template     @since 1.0.0
             * - Events postTitle                   @since 1.0.0
             * - Events subtitle                    @since 1.0.0
             * - Events loopFooter                  @since 1.0.0
             * - Events button                      @since 1.0.0
             */
            array(
                'filter'   => 'qibla_template_engine_data_the_post_title',
                'callback' => function ($data) {
                    if ('events' === get_post_type() && ! is_singular('events')) {
                        // Retrieve the Icon instance.
                        $icon       = new \QiblaFramework\IconsSet\Icon('Lineawesome::la-calendar');
                        $data->icon = $icon->getArrayVersion();
                    }

                    return $data;
                },
                'priority' => 10,
            ),
            array(
                'filter'   => 'qibla_listings_events_loop_header',
                'callback' => 'QiblaFramework\\Template\\Thumbnail::template',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_listings_events_loop_header',
                'callback' => array(new AppMapEvents\Post\Title(), 'postTitleTmpl'),
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_listings_events_loop_entry_content',
                'callback' => 'QiblaFramework\\Template\\Subtitle::template',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_listings_events_loop_entry_content',
                'callback' => 'AppMapEvents\\Functions\\loopFooter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_listings_events_loop_header_after',
                'callback' => 'AppMapEvents\\Front\\CustomFields\\Button::buttonFilter',
                'priority' => 20,
            ),

            /**
             * Wishlist
             *
             * @since 2.0.0
             */
            array(
                'filter'   => 'qibla_before_listings_events_loop_header',
                'callback' => 'QiblaFramework\\Wishlist\\Template::template',
                'priority' => 20,
            ),

            array(
                'filter'   => 'qibla_after_single_listings_content',
                'callback' => 'AppMapEvents\\Front\\CustomFields\\RelatedPosts::relatedPostsFilter',
                'priority' => 50,
            ),

            /**
             * Sidebar Events
             *
             * - Load Events sidebar template @since 1.0.0
             * - Events Card                  @since 1.0.0
             * - Button                       @since 1.0.0
             * - Share and Wish               @since 1.0.0
             * - Social links                 @since 1.0.0
             */
            array(
                'filter'   => array(
                    'qibla_load_template_sidebar',
                    'qibla_load_template_archive_listings_sidebar'
                ),
                'callback' => 'AppMapEvents\\Sidebars\\EventsSidebar::sidebarEventsFilter',
                'priority' => 10,
            ),
            array(
                'filter'   => 'qibla_before_events_sidebar',
                'callback' => 'AppMapEvents\\Front\\CustomFields\\EventsSidebarCard::eventsSidebarCardFilter',
                'priority' => 10,
            ),
            array(
                'filter'   => 'appmap_ev_events_after_time_sidebar_card',
                'callback' => 'AppMapEvents\\Functions\\addEventCalendar',
                'priority' => 10,
            ),
            array(
                'filter'   => 'appmap_ev_events_after_time_sidebar_card',
                'callback' => 'AppMapEvents\\Functions\\addEventLocation',
                'priority' => 20,
            ),
            array(
                'filter'   => 'appmap_ev_events_after_time_sidebar_card',
                'callback' => 'AppMapEvents\\Functions\\addEventSiteAndTel',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_before_events_sidebar',
                'callback' => 'AppMapEvents\\Front\\CustomFields\\Button::buttonFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_before_events_sidebar',
                'callback' => 'QiblaFramework\\Template\\ShareAndWish::template',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_before_events_sidebar',
                'callback' => 'QiblaFramework\\Front\\CustomFields\\ListingsSocials::socialLinksFilter',
                'priority' => 20,
            ),

            /**
             * Single Events
             *
             * - Events Dates  @since 1.0.0
             */
            array(
                'filter'   => 'qibla_single_listings_header',
                'callback' => 'AppMapEvents\\Front\\CustomFields\\EventsDates::eventsDatesFilter',
                // After single events sub title.
                'priority' => 45,
            ),

            /**
             * After Single
             *
             * - Google Map    @since 1.0.0
             */
            array(
                'filter'   => 'qibla_after_single_listings',
                'callback' => 'AppMapEvents\\Front\\CustomFields\\EventsLocation::eventsLocationFilter',
                'priority' => 40,
            ),

            array(
                'filter'   => 'wp_print_footer_scripts',
                'callback' => 'AppMapEvents\\Functions\\calendarScripts',
                'priority' => PHP_INT_MAX,
            ),

            /**
             * Search Events
             *
             */
            array(
                'filter'   => 'qibla_after_jumbotron',
                'callback' => 'AppMapEvents\\Search\\Search::searchByOptionsFilter',
                // Related to the filters within the theme.
                'priority' => 30,
            ),
        ),
        'filter' => array(
            /**
             * Events Query
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'pre_get_posts',
                'callback' => 'AppMapEvents\\Filter\\EventsQuery::filterFilter',
                'priority' => 10,
            ),

            /**
             * Filters
             *
             * - Relationship filter @since 1.0.0
             * - Filter fields       @since 1.0.0
             * - Sidebar events.     @since 1.0.0
             */
            array(
                'filter'   => 'qibla_listings_type_filter_relationship',
                'callback' => 'AppMapEvents\\Filter\\RelationshipFiltersFilter::filterFilter',
                'priority' => 30,
            ),
            array(
                'filter'   => 'qibla_listings_filters_fields',
                'callback' => 'AppMapEvents\\Filter\\FiltersFieldsFilter::filterFilter',
                'priority' => 30,
            ),
            // Singular event sidebar (same sidebar of listings)
            array(
                'filter'   => 'qibla_sidebar_in_singular_post_type',
                'callback' => 'AppMapEvents\\Sidebars\\EventSidebarFilter::filterFilter',
                'priority' => 30,
            ),

            /**
             * Filter Template loop listings file
             *
             * @since 1.0.0
             */
            array(
                'filter'   => array(
                    'qibla_listings_loop_file_template',
                    'qibla_class_loop_template_file_path',
                ),
                'callback' => function ($file) {
                    if ('events' === get_post_type()) {
                        $file = Plugin::getPluginDirPath('/views/loop/events.php');
                    }

                    return $file;
                },
                'priority' => 30,
            ),

            /**
             * Filter Template loop listings no content file
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'qibla_listings_loop_file_nocontent_template',
                'callback' => function ($file) {
                    // @todo I overwrite the file from the plugin to set the correct post type link. once tested, edit the framework file directly
                    $file = Plugin::getPluginDirPath('/views/loop/noContentEvents.php');

                    return $file;
                },
                'priority' => 30,
            ),

            /**
             * Filter Icon title exclude
             *
             * @since 1.0.0
             */
            array(
                'filter'        => 'qibla_exclude_icon_from_title',
                'callback'      => function ($exclude, $postType) {
                    if ('events' === $postType && is_singular('events')) {
                        $exclude = 'yes';
                    }

                    return $exclude;
                },
                'priority'      => 30,
                'accepted_args' => 2,
            ),

            /**
             * Filter post type package.
             *
             * @since 1.0.0
             */
            array(
                'filter'        => 'qibla_listings_post_type_package',
                'callback'      => function ($postTypePackage, $postType) {
                    if ('events' === $postType) {
                        $postTypePackage = 'event_package';
                    }

                    return $postTypePackage;
                },
                'priority'      => 30,
                'accepted_args' => 2,
            ),

            /**
             * Filter Categories Taxonomy
             *
             * @since 1.0.0
             */
            array(
                'filter'        => array(
                    'qibla_taxonomy_get_listings_term_list',
                    'qibla_listings_post_taxonomy_for_icon',
                    'qibla_listings_post_taxonomy_for_color',
                ),
                'callback'      => function ($taxonomy, $post) {
                    if ('events' === $post->post_type) {
                        $taxonomy = 'event_categories';
                    }

                    return $taxonomy;
                },
                'priority'      => 30,
                'accepted_args' => 2,
            ),

            /**
             * Filter edit link label.
             *
             * @since 1.0.0
             */
            array(
                'filter'        => 'qibla_listings_edit_link_label',
                'callback'      => function ($label, $postType) {
                    if ('events' === $postType) {
                        $label = esc_html__('Edit this event', 'qibla-events');
                    }

                    return $label;
                },
                'priority'      => 30,
                'accepted_args' => 2,
            ),

            /**
             * Default Icon
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'qibla_listings_post_default_icon',
                'callback' => 'AppMapEvents\\Front\\ListingsDefaultIconFilter::filterFilter',
                'priority' => 30,
            ),

            /**
             * Single Event Tags
             *
             * @since 1.0.0
             */
            array(
                'filter'   => array(
                    'qibla_amenities_template_taxonomy_get_data',
                    'qibla_amenities_template_taxonomy_icon_label',
                ),
                'callback' => function ($taxonomy) {
                    if (is_singular('events')) {
                        $taxonomy = 'event_tags';
                    }

                    return $taxonomy;
                },
                'priority' => 30,
            ),
            // Tags title.
            array(
                'filter'   => 'qibla_amenities_template_title',
                'callback' => function ($title) {
                    if (is_singular('events')) {
                        $title = esc_html__('Tags', 'qibla-events');
                    }

                    return $title;
                },
                'priority' => 30,
            ),

            /**
             * Info Window
             *
             * - Event Info Window Template   @since 1.0.0
             * - Event Info Window extra data @since 1.0.0
             */
            array(
                'filter'   => 'qibla_engine_map_template_infobox',
                'callback' => 'AppMapEvents\\Functions\\eventInfoBoxTmpl',
                'priority' => 30,
            ),
            array(
                'filter'        => 'qibla_extra_data_in_listings_post',
                'callback'      => function ($data, $post) {

                    if ('events' === $post->post_type) {
                        // Get Date start.
                        $dateStart = \QiblaFramework\Functions\getPostMeta(
                            '_qibla_mb_event_dates_multidatespicker_start',
                            ''
                        );
                        if ($dateStart) {
                            $date            = new \DateTime($dateStart);
                            $data->date = date_i18n('l d F', intval($date->getTimestamp())) ?: '';
                        }
                    }

                    return $data;
                },
                'priority'      => 30,
                'accepted_args' => 2,
            ),
        ),
    ),
));
