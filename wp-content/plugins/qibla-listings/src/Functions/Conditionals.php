<?php
namespace QiblaListings\Functions;

/**
 * Conditionals Functions
 *
 * @todo    Deprecate the entire file in favor of the function from the class.
 *
 * @see     https://core.trac.wordpress.org/ticket/27583
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
 * Is WooCommerce active
 *
 * @since 1.0.0
 *
 * @return bool If the function WC exists and the theme support WooCommerce.
 */
function isWooCommerceActive()
{
    return function_exists('WC');
}

/**
 * Is WpMl active
 *
 * @since 2.1.0
 *
 * @return bool If the class SitePress exists and the theme support WpMl.
 */
function isWpMlActive()
{
    return class_exists('SitePress');
}

/**
 * Is WooCommerce
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isWooCommerce()
{
    return isWooCommerceActive() && is_woocommerce();
}

/**
 * Is Shop
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isShop()
{
    return isWooCommerceActive() && is_shop();
}

/**
 * Is Product Taxonomy
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isProductTaxonomy()
{
    return isWooCommerceActive() && is_product_taxonomy();
}

/**
 * Is Product Category
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isProductCategory()
{
    return isWooCommerceActive() && is_product_category();
}

/**
 * Is Product Tag
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isProductTag()
{
    return isWooCommerceActive() && is_product_tag();
}

/**
 * Is Product
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isProduct()
{
    return isWooCommerceActive() && is_product();
}

/**
 * Is Cart
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isCart()
{
    return isWooCommerceActive() && is_cart();
}

/**
 * Is Checkout
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isCheckout()
{
    return isWooCommerceActive() && is_checkout();
}

/**
 * Is WooCommerce Archive
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isWooCommerceArchive()
{
    return isShop() || isProductTaxonomy();
}

/**
 * Is Account Page
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isAccountPage()
{
    return isWooCommerceActive() && is_account_page();
}

/**
 * Is Ajax Request
 *
 * Define that this is a request made via ajax.
 * It's not the traditional WordPress ajax request, infact the DOING_AJAX is not set.
 * It's a convention that the argument passed with the dlajax_action query are made by an ajax request.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isAjaxRequest()
{
    // @codingStandardsIgnoreLine
    $action = filterInput($_POST, 'dlajax_action', FILTER_SANITIZE_STRING);

    return (bool)$action;
}
