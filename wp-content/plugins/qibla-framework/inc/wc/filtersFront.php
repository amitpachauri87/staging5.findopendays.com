<?php
/**
 * WooCommerce Filter Front
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
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

/**
 * WooCommerce Front Filter Hooks
 *
 * @since 2.0.0
 *
 * @param array $args The arguments for the loader.
 */
return apply_filters('qibla_fw_wc_front_filters', array(
    'front' => array(
        'action' => array(
            /**
             * Sidebar
             *
             * - WooCommerce Sidebar @since 2.1.0
             */
            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaFramework\\Sidebars\\WooCommerceSidebar::actionAction',
                'priority' => 20,
            ),
        ),
        'filter' => array(
            /**
             * Sidebar
             *
             * - WooCommerce Sidebar @since 2.1.0
             */
            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaFramework\\Sidebars\\WooCommerceSidebar::filterFilter',
                'priority' => 20,
            ),
            /**
             * Additional items for the WooCommerce account menu
             *
             * @since 2.0.0
             */
            array(
                'filter'   => 'woocommerce_account_menu_items',
                'callback' => 'QiblaFramework\\Woocommerce\\MyAccountNavigation::navigationItemsFilter',
                'priority' => 20,
            ),
            /**
             * Remove Booking Products
             *
             * Remove the booking product from the query if framework is active.
             *
             * @since 2.0.0
             */
            array(
                'filter'   => 'parse_tax_query',
                'callback' => 'QiblaFramework\\Woocommerce\\FilterProductsQuery::removeBookingProductsFilter',
                'priority' => 0,
            ),
            /**
             * Remove archive description in shop page
             *
             * @since 2.1.0
             */
            array(
                'filter'   => 'get_the_post_type_description',
                'callback' => function ($description) {
                    if (is_post_type_archive() && \QiblaFramework\Functions\isShop()) {
                        return '';
                    }

                    return $description;
                },
                'priority' => 10,
            ),
        ),
    ),
));
