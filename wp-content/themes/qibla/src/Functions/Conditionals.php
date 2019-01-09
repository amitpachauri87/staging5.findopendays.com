<?php

namespace Qibla\Functions;

/**
 * Conditionals Functions
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
 * Is Date Archive
 *
 * @since 1.1.0
 *
 * @return bool If the current archive is one of teh date, year, month, day, time.
 */
function isDateArchive()
{
    // It is blog if the queried object is null and one of the "time" conditional return true.
    return (null === get_queried_object() && (is_date() || is_year() || is_month() || is_day() || is_time()));
}

/**
 * Is Blog
 *
 * @since 1.0.0
 *
 * @return bool True if one of the category, home, tag page is displaying, false otherwise
 */
function isBlog()
{
    return is_home() || is_category() || is_tag() || is_author() ||
           // It is blog if the queried object is null and one of the "time" conditional return true.
           (null === get_queried_object() && (is_date() || is_year() || is_month() || is_day() || is_time()));
}

/**
 * Is WooCommerce active
 *
 * @since 1.0.0
 *
 * @return bool If the function WC exists and the theme support WooCommerce.
 */
function isWooCommerceActive()
{
    return function_exists('WC') && current_theme_supports('woocommerce');
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
 * Is Jumbo-tron Allowed
 *
 * If the current page is allowed to process jumbo-tron data.
 *
 * @since  1.1.0
 *
 * @return bool True if allowed, false otherwise.
 */
function isJumbotronAllowed()
{
    $allowed = false;

    if (isWooCommerce() || isCart() || isCheckout() || isAccountPage()) {
        $allowed = false;
    } elseif ((is_singular() && ! isProduct()) || isBlog()) {
        $allowed = true;
    }

    return apply_filters('qibla_is_jumbotron_allowed', $allowed);
}

/**
 * Has Header Video
 *
 * @todo  4.9 - Remove the conditional.
 *
 * @since 1.2.0
 *
 * @return bool True if the video is set and is eligible.
 */
function isHeaderVideoEligible()
{
    // Allowed on 4.7 only.
    if (! function_exists('has_header_video')) {
        return false;
    }

    return (is_header_video_active() && (has_header_video() || is_customize_preview()));
}

/**
 * Check Dependencies
 *
 * @since 1.0.0
 *
 * @return bool True if check pass, false otherwise
 */
function checkDependencies()
{
    if (! function_exists('is_plugin_active')) {
        require_once untrailingslashit(ABSPATH) . '/wp-admin/includes/plugin.php';
    }

    return is_plugin_active('qibla-framework/index.php');
}
