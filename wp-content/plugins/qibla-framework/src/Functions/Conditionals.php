<?php
/**
 * Conditional Functions
 *
 * @package QiblaFramework\Functions
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

namespace QiblaFramework\Functions;

use QiblaFramework\ListingsContext\Context;
use QiblaFramework\ListingsContext\Types;

/**
 * Is JSON
 *
 * Check if a string is a valid json or not.
 *
 * @link  https://codepad.co/snippet/jHa0m4DB
 *
 * @since 1.0.0
 *
 * @param string $data The json string.
 *
 * @return bool True if the string is a json, false otherwise
 */
function isJSON($data)
{
    if (! is_string($data) || ! trim($data)) {
        return false;
    }

    return (
               // Maybe an empty string, array or object.
               $data === '""' ||
               $data === '[]' ||
               $data === '{}' ||
               // Maybe an encoded JSON string.
               $data[0] === '"' ||
               // Maybe a flat array.
               $data[0] === '[' ||
               // Maybe an associative array.
               $data[0] === '{'
           ) && json_decode($data) !== null;
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
 * Importing
 *
 * @since 1.0.0
 *
 * @return bool If the QB_IMPORT constant is defined and is true.
 */
function isImporting()
{
    return defined('QB_IMPORT') && QB_IMPORT;
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

/**
 * Is Edit Term Page
 *
 * @since 2.0.0
 *
 * @return bool True if the current screen is the edit term, false otherwise
 */
function isEditTerm()
{
    return 'term' === currentScreen()->base;
}

/**
 * Is Listings Archive
 *
 * @since 1.0.0
 *
 * @param null $obj The current query object.
 *
 * @return bool True if one of the conditions are meet false otherwise.
 */
function isListingsArchive($obj = null)
{
    $context = new Context(getWpQuery(), new Types());

    // If object is the WP_Term.
    if ($obj && $obj instanceof \WP_Term) {
        // Retrieved taxonomy lists.
        $taxonomies = $context->listingsTaxonomies($obj->taxonomy);
        // Check if taxonomy is in lists.
        $taxonomy = in_array($obj->taxonomy, $taxonomies, true);

        return $taxonomy;
    }

    return $context->isListingsTypeArchive() || $context->isListingsTaxArchive();
}

/**
 * Is Listings Main Query
 *
 * Check whatever the current query object is for the main query regarding the post type listings.
 *
 * @since 1.0.0
 *
 * @param \WP_Query $query The query to check.
 *
 * @return bool True if main listings query, false otherwise
 */
function isListingsMainQuery(\WP_Query $query)
{
    return isListingsArchive($query->get_queried_object()) && $query->is_main_query();
}

/**
 * Is Date Archive
 *
 * @since 1.0.0
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
           // It is blog if the current query is for search and post type is 'post'.
           (is_search() && 'post' === get_post_type()) || isDateArchive();
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
 * @since 1.1.0
 *
 * @return bool
 */
function isAccountPage()
{
    return isWooCommerceActive() && is_account_page();
}

/**
 * Is Archive Page
 *
 * Archive page are the ones like page for posts and shop.
 * All of the pages that works like archives.
 *
 * @since 1.0.0
 * @uses  getArchivePage()
 *
 * @return bool If the page works as an archive.
 */
function isArchivePage()
{
    return getArchivePage() instanceof \WP_Post;
}

/**
 * Is Jumbo-tron Allowed
 *
 * If the current page is allowed to process jumbo-tron data.
 *
 * @since  1.0.0
 * @since  1.1.0 Introduce new Parameter $allowed since the function is now used by filters.
 *
 * @param bool $allowed If the jumbo-tron is allowed within the page. Optional Default to false
 *
 * @return bool True if allowed, false otherwise.
 */
function isJumbotronAllowed($allowed = false)
{
    if ((is_tax() || is_category() || (is_tag()) && isBlog()) || isProductTaxonomy()) :
        // The term meta are not saved like post when a new term is created.
        // In this case we need a default value.
        // Note: off means that jumbo-tron is enable since the meta is _disable action.
        $allowed = 'off' === getTermMeta('_qibla_tb_jumbotron_disable', get_queried_object());
    else :
        if (isArchivePage()) {
            $obj = getArchivePage();
        } else {
            $obj = get_post();
        }

        $allowed = ! (
                // Not for base home.
                (is_home() && ! get_option('page_for_posts')) ||
                // Listings archives doesn't have jumbotron.
                isListingsArchive() ||
                // This page have his own header.
                is_404() ||
                // Single product doesn't have jumbotron.
                is_singular('product') ||
                // Is date Archive
                // Jumbo-tron is not allowed right know in date archive because we have no way to customize it.
                // In this case the theme will provide the jumbotron content.
                isDateArchive() ||
                // Search remove the jumbotron from the page.
                // No options to set the background.
                is_search()
            ) && 'off' === getPostMeta('_qibla_mb_jumbotron_disable', 'off', $obj);
    endif;

    // Remove header from page if jumbo-tron is disable.
    if (intval(get_option('page_on_front')) === get_the_ID()) {
        add_filter('qibla_show_page_header', 'QiblaFramework\\Functions\\removeHeaderPageIfJumbotronIsDisable');
    }

    /**
     * Filter Allowed
     *
     * @since 1.5.0
     *
     * @param bool $allowed If jumbotron is allowed or not.
     */
    $allowed = apply_filters('qibla_fw_is_jumbotron_allowed', $allowed);

    return $allowed;
}

/**
 * Remove Header Page if Jumbo-tron is disable.
 *
 * @since 2.4.0
 *
 * @return string
 */
function removeHeaderPageIfJumbotronIsDisable()
{
    $allowed = call_user_func_array('QiblaFramework\\Functions\\isJumbotronAllowed', func_get_args());
    if (! $allowed) {
        return 'no';
    }
}

/**
 * Has Header Video
 *
 * @since 1.2.0
 *
 * @return bool True if the video is set and is eligible.
 */
function isHeaderVideoEligible()
{
    // Allowed on >= 4.7.
    if (! function_exists('has_header_video')) {
        return false;
    }

    return (is_header_video_active() && (has_header_video() || is_customize_preview()));
}

/**
 * Qibla Woocommerce Listings and WC Booking is active
 *
 * @since 2.3.0
 *
 * @return bool
 */
function isWcBookingListingsActive()
{
    return is_plugin_active('qibla-woocommerce-listings/index.php') &&
           is_plugin_active('woocommerce-bookings/woocommerce-bookings.php.php');
}
