<?php
namespace Qibla\Functions\Woocommerce;

use Qibla\Woocommerce\CartCounterTemplate;

/**
 * WooCommerce Cart Functions
 *
 * @license GNU General Public License, version 2
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
 * Cart Counter
 *
 * @todo       Remove in 2.0.0
 *
 * @since      1.1.0
 *
 * @deprecated since 1.5.1 Use the CartCounterTemplate class instead.
 *
 * @return void
 */
function cartCounter()
{
    $cartCounterTemplate = new CartCounterTemplate();
    $cartCounterTemplate->tmpl(
        $cartCounterTemplate->getData()
    );
}

/**
 * Cart Fragment Updater
 *
 * @since 1.1.0
 *
 * @param array $fragments The cart fragments list.
 *
 * @return array The filtered list
 */
function CartFragmentUpdater($fragments)
{
    ob_start();
    $cartCounterTemplate = new CartCounterTemplate();
    $cartCounterTemplate->tmpl(
        $cartCounterTemplate->getData()
    );

    $fragments['.dlcart-counter'] = ob_get_clean();

    return $fragments;
}
