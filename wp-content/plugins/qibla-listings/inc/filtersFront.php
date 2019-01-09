<?php
/**
 * FrontEnd Filters
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

use QiblaListings\Plugin;
use Unprefix\Scripts;

// Scripts and Styles.
$scripts = new Scripts\ScriptsFacade(include Plugin::getPluginDirPath('/inc/scripts.php'));

/**
 * Front Filter Hooks
 *
 * @since 1.0.0
 *
 * @param array $args The arguments for the loader.
 */
return apply_filters('qibla_listings_front_filters', array(
    'front' => array(
        'action' => array(
            /**
             * Template Redirect
             *
             *  Request Listings Form               @since 1.0.0
             *  Redirect Product to Listing Package @since 1.0.0
             */
            array(
                'filter'   => 'template_redirect',
                'callback' => 'QiblaListings\\Front\\ListingForm\\RequestForm::handleRequestFilter',
                'priority' => 0,
            ),
            array(
                'filter'   => 'template_redirect',
                'callback' => 'QiblaListings\\Front\\Woocommerce\\RedirectListingProductToPackage::redirect',
                'priority' => 20,
            ),

            /**
             * Enqueue Scripts
             *
             * - Enqueue Scripts @since 1.0.0
             * - Enqueue Styles  @since 1.0.0
             */
            array(
                'filter'   => 'wp_enqueue_scripts',
                'callback' => array($scripts, 'register'),
                'priority' => 30,
            ),
            array(
                'filter'   => 'wp_enqueue_scripts',
                'callback' => array($scripts, 'enqueuer'),
                'priority' => 40,
            ),

            /**
             * Single Listings
             *
             * Add the Edit Post Link                                   @since 1.0.0
             * Disable Jumbotron within the single listing package post @since 1.0.0
             * Remove the breadcrumb from the single listing package    @since 1.0.0
             */
            array(
                'filter'   => 'qibla_before_single_listing_loop_entry_content',
                'callback' => 'QiblaListings\\Front\\Listings\\EditListingLinkTemplate::templateFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_fw_is_jumbotron_allowed',
                'callback' => 'QiblaListings\\Front\\ListingPackage\\SingleListingPackage::disableJumbotronWithinSingularListingPackageFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_template_engine_data_breadcrumb',
                'callback' => 'QiblaListings\\Front\\ListingPackage\\SingleListingPackage::removeBreadcrumbFromSingleListingPackage',
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
                'callback' => 'QiblaListings\\Sidebar::removeSidebarFromSinglePackageListingFilter',
                // After the theme and framework.
                'priority' => 40,
            ),
        ),
    ),
));
