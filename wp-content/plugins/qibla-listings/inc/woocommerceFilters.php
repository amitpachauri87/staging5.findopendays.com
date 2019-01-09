<?php
/**
 * WooCommerce Filters
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
return apply_filters('qibla_listings_wc_filters', array(
    'inc' => array(
        'action' => array(
            /**
             * Orders
             *
             * - Update status for the listing post on order marked as completed @since 1.0.0
             */
            array(
                'filter'        => 'woocommerce_order_status_changed',
                'callback'      => 'QiblaListings\\Crud\\ListingUpdateStatus::publishOnOrderCompletedFilter',
                'priority'      => 20,
                'accepted_args' => 4,
            ),
        ),
        'filter' => array(),
    ),
));
