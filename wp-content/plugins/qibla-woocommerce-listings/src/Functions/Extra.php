<?php

namespace QiblaWcListings\Functions;

use QiblaFramework\Functions as Fw;
use QiblaFramework\ListingsContext\Context;

/**
 * Extra Functions
 *
 * @package QiblaWcListings\Front\Functions
 * @author  guido scialfa <dev@guidoscialfa.com>
 *
 * @license GPL 2
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

defined('WPINC') || die;

/**
 * Add body classes
 *
 * Extend the WordPress body class by adding more classes about the device.
 *
 * @since 1.0.0
 *
 * @param  array $classes The body classes.
 *
 * @return array $classes The body classes filtered
 */
function bodyClass($classes)
{
    if (Context::isSingleListings() && 'none' !== Fw\getPostMeta('_qibla_mb_wc_listings_products', 'none')) {
        $classes[] = 'woocommerce';
    }

    return array_unique($classes);
}
