<?php
/**
 * General Functions
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

use QiblaFramework\Front\Settings\Listings;
use QiblaFramework\ListingsContext\Context;
use QiblaFramework\ListingsContext\Types;
use QiblaFramework\TemplateEngine\Engine;

/**
 * Outputs the html selected attribute.
 *
 * Compares the first two arguments and if identical marks as selected.
 * This function is a wrap of WordPress select that work with arrays.
 *
 * @since 1.0.0
 *
 * @param mixed $selected One of the values to compare
 * @param mixed $current  (true) The other value to compare if not just true
 * @param bool  $echo     Whether to echo or just return the string
 *
 * @return string|null The html attribute or empty string. Echo if $echo is true.
 */
function selected($selected, $current, $echo)
{
    if (is_array($selected)) {
        $index = array_search($current, $selected, true);
        if (false === $index) {
            return '';
        }

        $selected = $selected[$index];
    }

    $selected = \selected($selected, $current, false);

    if (! $echo) {
        return $selected;
    }

    // @codingStandardsIgnoreLine
    echo $selected;
}

/**
 * Get Referrer
 *
 * @todo  Check for use wp_get_raw_referer() but pay attention to the _wp_http_referer in forms.
 *
 * @since 1.3.0
 *
 * @return string The referrer if set. Empty string otherwise.
 */
function getReferer()
{
    if (! empty($_SERVER['HTTP_REFERER'])) {
        return wp_unslash($_SERVER['HTTP_REFERER']);
    }

    return '';
}

/**
 * Get Input Data Provider
 *
 * Return the correct data container based on request method.
 *
 * @since 1.5.0
 *
 * @param mixed $method The Input method. GET, POST etc...
 *
 * @return mixed The container of the values
 */
function getInputDataProvider($method)
{
    // Canonicalize the input data provider name.
    $method = strtoupper($method);

    // @codingStandardsIgnoreStart
    switch ($method) {
        case 'GET':
            $data = $_GET;
            break;
        case 'REQUEST':
            $data = $_REQUEST;
            break;
        default:
            $data = $_POST;
            break;
    } // @codingStandardsIgnoreEnd

    return $data;
}

/**
 * Filter Input
 *
 * @since 1.0.0
 *
 * @uses  filter_var() To filter the value.
 *
 * @param array  $data    The haystack of the elements.
 * @param string $key     The key of the element within the haystack to filter.
 * @param int    $filter  The filter to use.
 * @param array  $options The option for the filter var.
 *
 * @return bool|mixed The value filtered on success false if filter fails or key doesn't exists.
 */
function filterInput($data, $key, $filter = FILTER_DEFAULT, $options = array())
{
    return isset($data[$key]) ? filter_var($data[$key], $filter, $options) : false;
}

/**
 * Get Current Screen
 *
 * @since 1.5.1
 *
 * @return \WP_Screen|null The \WP_Screen instance or null if function not exists or not screen is set.
 */
function currentScreen()
{
    return function_exists('get_current_screen') ? get_current_screen() : null;
}

/**
 * Svg Loader Template
 *
 * @since 1.0.0
 *
 * @return void
 */
function svgLoaderTmpl()
{
    $engine = new Engine('svg_loader', new \stdClass(), '/assets/svg/loader.svg');
    $engine->render();
}

/**
 * Send headers for Ajax Requests
 *
 * @since  2.0.0
 *
 * @param int $status The header status.
 *
 * @return void
 */
function setHeaders($status = 200)
{

    @header('Content-Type: text/html; charset=' . get_option('blog_charset'));
    @header('X-Frame-Options: DENY');
    @header('X-Robots-Tag: noindex');

    send_origin_headers();
    send_nosniff_header();

    nocache_headers();

    status_header($status);
}

/**
 * Search Navigation Template
 *
 * @since 1.7.0
 *
 * @return void
 */
function searchNavigationTmpl()
{
    $engine = new Engine('search_navigation_tmpl', new \stdClass(), '/views/template/searchNavigation.php');
    $engine->render();
}

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
    $query   = \QiblaFramework\Functions\getWpQuery();
    $context = new Context($query, new Types());

    // Add class to identify that the plugin is active.
    $classes[] = 'dlfw';

    // Additional class for:.
    if (isListingsArchive()) {
        $classes[] = 'dl-is-listings-archive';
        $classes[] = 'dl-is-listings-archive--' . (Listings::showMapOnArchive() ? 'with-map' : 'no-map');
    }

    // Add extra class to know if the user is logged in or not.
    $classes[] = is_user_logged_in() ? 'dl-user-login' : 'dl-user-logout';

    // Is Singular Listings?
    if (Context::isSingleListings()) {
        $classes[] = 'dl-is-singular-listings';
    }

    // @codingStandardsIgnoreLine
    $action = filterInput($_POST, 'post_type', FILTER_SANITIZE_STRING);

    // Use hero map?
    $heroMap   = getPostMeta('_qibla_mb_heromap_active');
    $classes[] = isset($heroMap) && 'on' === $heroMap ? 'dl-hero-map' : '';

    // Remove unneeded classes if search is made for listings context.
    if (is_search() and $context->context() and $action) {
        $classes = array_filter($classes, function ($class) {
            return ! in_array($class, array('search', 'search-no-results', 'search-results'), true);
        });
    }

    return $classes;
}

/**
 * Admin Body Classes
 *
 * @since 2.0.0
 *
 * @param string $classes The attribute value for class.
 *
 * @return string The filtered classes value
 */
function adminBodyClass($classes)
{
    $currentScreen = currentScreen();
    $types         = new Types();

    // Assign a generic reusable value for when we are showing the edit list table.
    if (isset($currentScreen->post_type) and $types->isListingsType($currentScreen->post_type)) {
        $classes = rtrim($classes) . ' dl-is-listings-type-table-list';
    }

    return $classes;
}

/**
 * Get Scope Class
 *
 * @since 1.0.0
 *
 * @param string       $block    The custom block scope.
 * @param string       $element  The element within the scope.
 * @param string|array $modifier The block modifier key.
 * @param string       $attr     The attribute name for which build the scope.
 *
 * @return string $upxscope The scope class
 */
function getScopeClass($block = '', $element = '', $modifier = '', $attr = 'class')
{
    // The Scope prefix.
    $upxscope = $scope = 'dl';

    if ($block) :
        $upxscope .= $block;
    else :
        if (is_author()) {
            $upxscope .= 'author-archive';
        } elseif (is_attachment()) {
            $upxscope .= 'attachment';
        } elseif (is_archive() || is_search() || is_home()) {
            $upxscope .= 'archive';
        } elseif (is_page()) {
            $upxscope .= 'page';
        } elseif (is_singular()) {
            $postType = 'post';

            /**
             * Filter the post type scope
             *
             * @since 1.0.0
             *
             * @param string $postType The current post type
             * @param string $upxscope The current scope block
             */
            $upxscope .= apply_filters('qibla_fw_get_singular_scope_post_type', $postType, $upxscope);
        } elseif (is_404()) {
            $upxscope .= '404';
        }//end if
    endif;

    if ($modifier) {
        $tmpScope = $upxscope;
        $upxscope = '';

        foreach ((array)$modifier as $mod) {
            if ($mod) {
                $upxscope .= ' ' . $tmpScope . "--{$mod}";
            }
        }

        $upxscope = trim($tmpScope . ' ' . $upxscope);

        unset($tmpScope);
    } elseif ($element) {
        $upxscope .= "__{$element}";
    }

    /**
     * Scope Filter
     *
     * Filter the scope string before it is returned.
     *
     * @since 1.0.0
     *
     * @param string $upxscope The scope prefix. Default 'upx'.
     * @param string $element  The current element of the scope.
     * @param string $block    The custom block scope. Default empty.
     * @param string $scope    The default scope prefix. Default 'upx'.
     * @param string $attr     The attribute for which the value has been build.
     * @param string $modifier The modifier value.
     */
    $upxscope = apply_filters('qibla_fw_scope_attribute', $upxscope, $element, $block, $scope, $attr, $modifier);

    return $upxscope;
}

/**
 * Scope Class
 *
 * @since 1.0.0
 *
 * @uses  getScopeClass()
 *
 * @param string $block    The custom block scope.
 * @param string $element  The element within the scope.
 * @param string $modifier The block modifier key.
 *
 * @return void
 */
function scopeClass($block = '', $element = '', $modifier = '')
{
    echo 'class="' . sanitizeHtmlClass(getScopeClass($block, $element, $modifier)) . '"';
}

/**
 * Scope ID
 *
 * @since 1.5.0
 *
 * @uses  getScopeClass()
 *
 * @param string $block The custom block scope.
 *
 * @return void
 */
function scopeID($block = '')
{
    echo 'id="' . getScopeClass($block, '', '', 'id') . '"';
}

/**
 * Set Viewed Post Cookie
 *
 * @since  2.1.0
 */
function setViewedCookie()
{
    // Single Listings?
    if (! Context::isSingleListings()) {
        return;
    }

    // Remove adjacent posts hook, for prevent executed twice.
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

    // Get Types.
    $types = new Types();

    // Check if post type is in types.
    if (in_array(get_post_type(), $types->types(), true)) :
        $postID = get_the_ID();

        // Check if isset cookie.
        if (! isset($_COOKIE['qibla_listings_recently_viewed'])) {
            // If there's no cookie set, set up a new array.
            $cookieArray = array($postID);
        } else {
            $cookieArray = unserialize($_COOKIE['qibla_listings_recently_viewed']);

            if (! is_array($cookieArray)) {
                $cookieArray = array($postID);
            }
        }

        // Add in cookie array current post id.
        if (in_array($postID, $cookieArray, true)) {
            $key = array_search($postID, $cookieArray, true);
            array_splice($cookieArray, $key, 1);
        }

        array_unshift($cookieArray, $postID);

        // Set cookie.
        setcookie(
            'qibla_listings_recently_viewed',
            serialize($cookieArray),
            time() + (DAY_IN_SECONDS * 31),
            '/'
        );
    endif;
}

/**
 *  Open Graph Meta Image.
 *
 * @since 2.5.0
 */
function insertOGImageTag()
{
    $post = get_post();

    if (! $post) {
        return;
    }

    if (! Context::isSingleListings()) {
        return;
    }

    /**
     * Filter open graph.
     * If NO disable, YES is active.
     *
     * @since 2.5.0
     */
    if ('no' === apply_filters('qibla_allow_open_graph_tag', 'yes')) {
        return;
    }

    if (! has_post_thumbnail($post->ID)) :
        $heroID = \QiblaFramework\Functions\getPostMeta('_qibla_mb_jumbotron_background_image', null, $post->ID);
        if ($heroID) :
            $hero = wp_get_attachment_image_src($heroID, 'large', false);
            // Check Seo Yoast.
            if (! defined('WPSEO_VERSION')) {
                // Facebook.
                echo '<!-- Qibla Facebook and Twitter Social share meta tag. -->' . "\n";
                echo '<meta property="og:url" content="' . esc_url(get_permalink($post->ID)) . '"/>' . "\n";
                echo '<meta property="og:type" content="article"/>' . "\n";
                echo '<meta property="og:title" content="' . esc_html(get_the_title($post->ID)) . '"/>' . "\n";
                echo '<meta property="og:description" content="' .
                     esc_html(wp_trim_words(sanitize_text_field($post->post_content), 35, '...')) . '"/>' . "\n";
                echo '<meta property="og:image" content="' . esc_url($hero[0]) . '"/>' . "\n";
                // Twitter.
                echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
                echo '<meta name="twitter:title" content="' . esc_html(get_the_title($post->ID)) . '"/>' . "\n";
                echo '<meta name="twitter:description" content="' .
                     esc_html(wp_trim_words(sanitize_text_field($post->post_content), 35, '...')) . '"/>' . "\n";
                echo '<meta name="twitter:image" content="' . esc_url($hero[0]) . '"/>' . "\n";
                echo '<!-- Qibla Facebook and Twitter Social share meta tag. -->' . "\n";
            }
        endif;
    else :
        $thumbnailSrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
        // Check Seo Yoast.
        if (! defined('WPSEO_VERSION')) {
            // Facebook.
            echo '<!-- Qibla Facebook and Twitter Social share meta tag. -->' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink($post->ID)) . '"/>' . "\n";
            echo '<meta property="og:type" content="article"/>' . "\n";
            echo '<meta property="og:title" content="' . esc_html(get_the_title($post->ID)) . '"/>' . "\n";
            echo '<meta property="og:description" content="' .
                 esc_html(wp_trim_words(sanitize_text_field($post->post_content), 35, '...')) . '"/>' . "\n";
            echo '<meta property="og:image" content="' . esc_url($thumbnailSrc[0]) . '"/>' . "\n";
            // Twitter.
            echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
            echo '<meta name="twitter:title" content="' . esc_html(get_the_title($post->ID)) . '"/>' . "\n";
            echo '<meta name="twitter:description" content="' .
                 esc_html(wp_trim_words(sanitize_text_field($post->post_content), 35, '...')) . '"/>' . "\n";
            echo '<meta name="twitter:image" content="' . esc_url($thumbnailSrc[0]) . '"/>' . "\n";
            echo '<!-- Qibla Facebook and Twitter Social share meta tag. -->' . "\n";
        }
    endif;
}
