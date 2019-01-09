<?php
/**
 * Filter Products Query
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    Qibla\Woocommerce
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

namespace QiblaFramework\Woocommerce;

use QiblaFramework\Functions as F;
use QiblaFramework\ListingsContext\Types;

/**
 * Class FilterProductsQuery
 *
 * @since   1.1.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package Qibla\Woocommerce
 */
class FilterProductsQuery
{
    /**
     * Remove Booking Products from WooCommerce Archives
     *
     * @since  1.1.0
     *
     * @param \WP_Query $query The query to filter.
     *
     * @return void
     */
    public static function removeBookingProductsFilter($query)
    {
        $types            = new Types();
        $allowedPostTypes = array('product') + $types->types();

        // Set the main condition.
        // Only on frontend and within a product post type or taxonomy related to the product post type.
        $mainCondition = ! is_admin() &&
                         (isset($query->query_vars['post_type']) && 'product' === $query->query_vars['post_type']) ||
                         F\isWooCommerceActive();

        // Not in admin and only for product post types.
        if ($mainCondition) {
            // Allow for single product and in single listings.
            $condition = ! ($query->is_singular($allowedPostTypes) && $query->is_main_query());
            // Allow in cart and checkout.
            $condition = $condition && (! F\isCart() && ! F\isCheckout());

            if ($condition) {
                $query->tax_query->queries[] = array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => array('booking'),
                    'operator' => 'NOT IN',
                );
            }
        }
    }
}
