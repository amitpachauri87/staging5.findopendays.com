<?php
namespace QiblaListings\Front\Woocommerce;

/**
 * FilterProductsQuery
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaListings\Front\Woocommerce
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
 * Class FilterProductsQuery
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Front\Woocommerce
 */
class FilterProductsQuery
{
    /**
     * Remove Booking Products from WooCommerce Archives
     *
     * @since  1.1.0
     * @access public
     *
     * @param \WP_Query $query The query to filter.
     *
     * @return void
     */
    public static function removeListingProductsFilter($query)
    {
        // Set the main condition.
        // Only on frontend and within a product post type or taxonomy related to the product post type.
        $mainCondition = ! is_admin() &&
                         (isset($query->query_vars['post_type']) && 'product' === $query->query_vars['post_type']);

        // Not in admin and only for product post types.
        if ($mainCondition) {
            // Allow for single product.
            $condition = ! ($query->is_singular(array('product')) && $query->is_main_query());
            // Allow in cart and checkout.
            $condition = $condition && (! \QiblaListings\Functions\isCart() && ! \QiblaListings\Functions\isCheckout());

            if ($condition) {
                $query->tax_query->queries[] = array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => array('listing'),
                    'operator' => 'NOT IN',
                );
            }
        }
    }
}
