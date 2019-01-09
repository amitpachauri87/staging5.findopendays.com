<?php
/**
 * RedirectListingProductToPackage
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

namespace QiblaListings\Front\Woocommerce;

use QiblaFramework\Functions as Fw;
use QiblaListings\Package\PackageProductRelation;

/**
 * Class RedirectListingProductToPackage
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Front\Woocommerce
 */
class RedirectListingProductToPackage
{
    /**
     * Redirect Product to Package
     *
     * The method make a safe redirect to the singular package post if the product is related with one package.
     * Otherwise the query will be set to a 404.
     *
     * @since  1.0.0
     * @access public static
     *
     * @return void
     */
    public static function redirect()
    {
        global $wp_query;
        // Get the queried object.
        $obj = wc_get_product(get_queried_object());

        // Make a check to be sure we are into the correct context.
        if ($obj instanceof \WC_Product &&
            'listing' === $obj->get_type() &&
            $wp_query->is_main_query()
        ) {
            // Get the package product.
            $package = Fw\getPostMeta(PackageProductRelation::PROD_META_KEY, false, $obj->get_id());

            // Package found? Redirect.
            if ($package) {
                // Redirect from product to package page. 301 Moved Permanently.
                wp_safe_redirect(esc_url_raw(get_permalink($package)), 301);
                die;
                // 404 otherwise.
            } else {
                $wp_query->set_404();
                status_header(404);
                get_template_part(404);
                die;
            }
        }
    }
}
