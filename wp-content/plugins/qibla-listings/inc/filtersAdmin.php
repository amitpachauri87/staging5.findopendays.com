<?php

use QiblaFramework\Admin as QFAdmin;
use QiblaListings\Plugin;
use QiblaListings\Requirements;
use QiblaListings\Admin;

/**
 * Admin Filters
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

// Build the requirements.
$req = new Requirements(include Plugin::getPluginDirPath('/inc/requirements.php'));

/**
 * Ex.
 * 'context' => [
 *      'type' => [
 *          [
 *              'filter'             => 'filter_name',
 *              'callback'           => theCallback,
 *              'priority'           => Number,
 *              'accepted_arguments' => Number
 *          ],
 *      ],
 * ],
 */

/**
 * Admin Filter Hooks
 *
 * @since 1.0.0
 *
 * @param array $args The arguments for the loader.
 */
return apply_filters('qibla_listings_admin_filters', array(
    'admin' => array(
        'action' => array(
            /**
             * Admin Notices
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'admin_notices',
                'callback' => array(
                    new Admin\Notices\NoticeList(
                        '<strong>' . esc_html__('Qibla Listings Warnings', 'qibla-listings') . '</strong>',
                        $req->check(),
                        'error'
                    ),
                    'notice',
                ),
                'priority' => 20,
            ),

            /**
             * Menu Pages
             *
             * Listing Package Post Listings @since 1.0.0
             */
            array(
                'filter'   => 'admin_menu',
                'callback' => array(
                    new QFAdmin\Page\Register(array(
                        new Admin\Page\ListingPackage(),
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
                    $register = new QFAdmin\Metabox\Register(array(
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
                    QFAdmin\Metabox\Store::storeMetaFilter(array(
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
                'callback'      => 'QiblaListings\\Package\\PackageProductRelation::updateRelatedProductMetaWithPackageIDFilter',
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
                'callback' => 'QiblaListings\\Admin\\Metabox\\ListingAdditionalFields::listingAdditionalFieldsFilter',
                'priority' => 20,
            ),
        ),
        'filter' => array(
            /**
             * Extra Theme Option Settings
             *
             * Expired email listing message @since 1.0.0
             */
            array(
                'filter'   => 'qibla_opt_inc_listings_fields',
                'callback' => 'QiblaListings\\Admin\\Setting\\InsertExtraListingThemeOptionFields::appendFieldsFilter',
                'priority' => 20,
            ),

            /**
             * Admin Authors Dropdown list
             *
             * @since 1.0.1
             */
            array(
                'filter'        => 'wp_dropdown_users_args',
                'callback'      => 'QiblaListings\\Admin\\Metabox\\AuthorOverrideList::authorOverrideListingsListFilter',
                'priority'      => 20,
                'accepted_args' => 2,
            ),
        ),
    ),
));
