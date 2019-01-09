<?php
/**
 * Product Types List
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
 * Filter Product Type tabs list
 *
 * @since 1.0.0
 *
 * @param array $args The list of the product tabs allowed within a particular product type.
 */
return apply_filters('qibla_product_type_tabs_list', array(
    'listing' => array(
        // Label used for this product type.
        'label'                     => esc_html__('Listing Product', 'qibla-listings'),
        // The name of the class, for standard compatibility.
        'class_name'                => 'QiblaListings\\Woocommerce\\Product\\ProductListing',
        // Allowed Product data tabs.
        'allowed_product_data_tabs' => array(
            'listing-price' => array(
                'label'    => esc_html__('Price', 'qibla-listings'),
                'target'   => 'listing_pricing_product_data',
                'class'    => array('show_if_listing'),
                'template' => 'pricing',
            ),
            'inventory',
        ),
    ),
));
