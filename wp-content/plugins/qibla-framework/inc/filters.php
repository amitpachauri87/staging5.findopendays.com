<?php
/**
 * Common Filters
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

use QiblaFramework\PostType;
use QiblaFramework\Taxonomy;
use QiblaFramework\Widget;
use QiblaFramework\Parallax;
use QiblaFramework\Shortcode;

/**
 * Filter Inc Filters
 *
 * @since 1.0.0
 *
 * @param array $array The list of the filters list
 */
return apply_filters('qibla_filters_inc', array(
    'inc' => array(
        'action' => array(

            /**
             * Logon logo
             *
             * @since 2.4.0
             */
            array(
                'filter'   => 'login_enqueue_scripts',
                'callback' => 'QiblaFramework\\Functions\\loginLogo',
                'priority' => 30,
            ),
            array(
                'filter'   => 'login_headerurl',
                'callback' => 'QiblaFramework\\Functions\\loginLogoUrl',
                'priority' => 30,
            ),
            array(
                'filter'   => 'login_headertitle',
                'callback' => 'QiblaFramework\\Functions\\loginLogoUrlTitle',
                'priority' => 30,
            ),

            /**
             * Add menu in admin bar
             *
             * @since 2.1.0
             */
            array(
                'filter'   => 'admin_bar_menu',
                'callback' => function ($adminBar) {
                    if (! current_user_can('administrator')) {
                        return $adminBar;
                    }
                    $qiblaPage       = new \QiblaFramework\Admin\Page\Qibla();
                    $usefulLinksPage = new \QiblaFramework\Admin\Page\UsefulLinks();
                    $qiblaPage->adminToolbar($adminBar);
                    $usefulLinksPage->adminToolbar($adminBar);
                },
                'priority' => 40,
            ),

            /**
             * Register EndPoints
             *
             * @since 2.0.0
             */
            array(
                'filter'   => 'init',
                'callback' => array(
                    new QiblaFramework\EndPoint\Register(array(
                        new \QiblaFramework\Woocommerce\WishlistEndPoint(),
                    )),
                    'register',
                ),
                'priority' => 1,
            ),

            /**
             * Widgets
             *
             * - Register          @since 1.0.0
             * - Register Sidebars @since 1.5.1
             */
            array(
                'filter'   => 'widgets_init',
                'callback' => array(
                    new Widget\Register(array(
                        new Widget\ContactForm7(),
                    )),
                    'register',
                ),
                'priority' => 20,
            ),
            array(
                'filter'   => 'widgets_init',
                'callback' => array(
                    new \QiblaFramework\Sidebars\Register(
                        include QiblaFramework\Plugin::getPluginDirPath('/inc/sidebarsList.php')
                    ),
                    'register',
                ),
                // After the one in theme.
                'priority' => 30,
            ),

            /**
             * After Setup Theme
             *
             * - Add Image Sizes      @since 1.0.0
             * - Register Shortcodes  @since 1.0.0
             */
            array(
                'filter'   => 'after_setup_theme',
                'callback' => 'QiblaFramework\\Functions\\addImageSizes',
                'priority' => 30,
            ),
            array(
                'filter'   => 'after_setup_theme',
                'callback' => array(
                    new Shortcode\Register(array(
                        new Shortcode\Section(),
                        new Shortcode\Post(),
                        new Shortcode\Listings(),
                        new Shortcode\Terms(),
                        new Shortcode\TermsLocationsFake(),
                        new Shortcode\Button(),
                        new Shortcode\Alert(),
                        new Shortcode\Search(),
                        new Shortcode\Testimonial(),
                        new Shortcode\Maps(),
                        new Shortcode\RecentlyViewedListings(),
                    )),
                    'register',
                ),
                'priority' => 40,
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
                        new PostType\Listings(),
                        new PostType\Testimonial(),
                    )),
                    'register',
                ),
                'priority' => 0,
            ),

            /*
             * Register Taxonomy
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'init',
                'callback' => array(
                    new Taxonomy\Register(array(
                        new Taxonomy\Locations(),
                        new Taxonomy\Amenities(),
                        new Taxonomy\ListingCategories(),
                        new Taxonomy\ListingsAddress(),
                    )),
                    'register',
                ),
                'priority' => 0,
            ),

            /**
             * Rating Average
             *
             * - delete and restore Average @since 1.2.0
             */
            array(
                'filter'   => 'wp_update_comment_count',
                'callback' => function ($postID) {
                    $average = new \QiblaFramework\Review\AverageCrud(get_post($postID));
                    $average->resetAverage();
                },
                'priority' => 20,
            ),

            /**
             * Svg Loader
             *
             * @todo Move all templats within the same file. One hook one file. Stop.
             *
             * - Front @since 1.0.0
             * - Admin @since 1.7.0
             */
            array(
                'filter'   => array(
                    'wp_footer',
                    'admin_footer',
                ),
                'callback' => 'QiblaFramework\\Functions\\svgLoaderTmpl',
                'priority' => 40,
            ),

            /**
             * User Login
             *
             * Clean the user roles and capabilities after introduced the `manage_listings` role.
             *
             * @since 2.0.0
             */
            array(
                'filter'        => 'wp_login',
                'callback'      => function ($userLogin, $user) {
                    \QiblaFramework\Update\UpdateManageListingsUserCapability::resetListingsAuthorRoles($user);
                    \QiblaFramework\Update\UpdateManageListingsUserCapability::resetAdministratorListingsRoles($user);
                },
                'priority'      => 0,
                'accepted_args' => 2,
            ),

            /**
             * Update User Roles
             *
             * @since 2.0.0
             */
            array(
                'filter'        => 'upgrader_process_complete',
                'callback'      => function ($instance, $args) {
                    if (! empty($args['plugin']) &&
                        in_array('qibla-framework/index.php', (array)$args['plugin'], true) &&
                        'plugin' === $args['type'] &&
                        'update' === $args['action']
                    ) {
                        \QiblaFramework\Activate::activate();
                    }
                },
                'priority'      => 20,
                'accepted_args' => 2,
            ),
        ),
        'filter' => array(
            array(
                'filter'        => 'qibla_fw_scope_attribute',
                'callback'      => array(new Parallax\ClassScopeModifier(), 'setUseParallax'),
                'priority'      => 20,
                'accepted_args' => 6,
            ),
            /**
             * - Login Message      @since 2.2.1
             * - Modal Login Action @since 2.2.1
             */
            array(
                'filter'   => 'login_message',
                'callback' => array(new \QiblaFramework\User\User(), 'loginMessageFilter'),
                'priority' => 10,
            ),
            array(
                'filter'   => 'wp_footer',
                'callback' => array(new \QiblaFramework\User\User(), 'modalLoginAction'),
                'priority' => 20,
            ),
        ),
    ),
));