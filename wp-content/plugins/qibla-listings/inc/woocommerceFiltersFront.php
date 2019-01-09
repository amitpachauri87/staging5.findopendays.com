<?php
/**
 * WooCommerce Filters Front
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    GNU General Public License, version 2
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
 * @since 1.0.0
 *
 * @param array $args The arguments for the loader.
 */
return apply_filters('qibla_listings_wc_front_filters', array(
    'front' => array(
        'action' => array(
            /**
             * Hook the add to cart for Listings Product Type
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'woocommerce_listing_add_to_cart',
                'callback' => 'woocommerce_simple_add_to_cart',
                // Same of the WooCommerce priority.
                'priority' => 30,
            ),
            /**
             * Orders
             *
             * - Store the listings id's to the new order @since 1.0.0
             */
            array(
                'filter'   => 'woocommerce_new_order',
                'callback' => 'QiblaListings\\Woocommerce\\ListingExtraDataOrder::addListingToOrderFilter',
                'priority' => 20,
            ),
        ),
        'filter' => array(
            /**
             * Remove Listing Product Type from WooCommerce loop.
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'parse_tax_query',
                'callback' => 'QiblaListings\\Front\\Woocommerce\\FilterProductsQuery::removeListingProductsFilter',
                // After the one defined in theme.
                'priority' => 1,
            ),
        ),
    ),
));
