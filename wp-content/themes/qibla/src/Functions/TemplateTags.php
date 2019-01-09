<?php
namespace Qibla\Functions;

use Qibla\Logo;
use Qibla\TemplateEngine\Engine as TEngine;

/**
 * Template Part Functions
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

    if ($block) {
        $upxscope .= $block;
    } else {
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
            $upxscope .= apply_filters('qibla_get_singular_scope_post_type', $postType, $upxscope);
        } elseif (is_404()) {
            $upxscope .= '404';
        }
    }

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
     */
    $upxscope = apply_filters('qibla_scope_attribute', $upxscope, $element, $block, $scope, $attr);

    // Sanitize the class name.
    $upxscope = array_map('sanitize_html_class', explode(' ', $upxscope));

    return implode(' ', $upxscope);
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
    echo 'class="' . esc_attr(sanitizeHtmlClass(getScopeClass($block, $element, $modifier))) . '"';
}

/**
 * Scope ID
 *
 * @since 1.0.0
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
 * Loop Post Context
 *
 * Retrieve the correct context for template part in archive templates.
 *
 * Since the theme use two different template parts for archive and single,
 * to able to overwrite the loop-{post_format}.php with the loop-{post_type}.php we need to check if the
 * current post type is 'post' or not.
 *
 * The function must be called within the loop and only on archive templates.
 *
 * @since 1.1.0
 *
 * @return string The post type name if the current post is different than 'post'. The post format otherwise.
 */
function getLoopContext()
{
    if (! in_the_loop()) {
        _doing_it_wrong(__FUNCTION__, 'must be called within the loop', '1.1.0');

        return false;
    }

    $postType = get_post_type();

    if ('post' !== $postType) {
        return $postType;
    }

    return get_post_format();
}

/**
 * Show Site Logo and Motto
 *
 * @deprecated since 1.6.0
 *
 * @since      1.0.0
 */
function siteLogo()
{
    $logo = new Logo();

    $logo->tmpl($logo->getData());
}

/**
 * Get the Breadcrumb Data
 *
 * @todo  Move getBreadcrumbData and breacrumbTmpl within a class that implements TemplateInterface.
 *
 * @since 1.0.0
 *
 * @return \stdClass The data instance
 */
function getBreadcrumbData()
{
    // Initialize Data.
    $data = new \stdClass();

    $data->breadcrumbMarkup = '';

    /**
     * Show Yoast Breadcrumb in Front page
     *
     * Breadcrumb Trail can hide breadcrumb in front-page but not on posts pages if show_on_front is set to page.
     * So, to be coherent we will change the behavior of yoast breadcrumb.
     *
     * @todo  Test again the plugins
     *
     * @since 1.0.0
     *
     * @param string 'yes' To hide the breadcrumb, 'no' otherwise. Default 'yes'.
     */
    $hideYoastBreadcrumbOnFrontpage = apply_filters('qibla_hide_yoast_breadcrumb_on_frontpage', 'yes');

    if (function_exists('breadcrumb_trail')) {
        $data->breadcrumbMarkup = breadcrumb_trail(array(
            'echo'          => false,
            'show_on_front' => false,
        ));
    } elseif (function_exists('yoast_breadcrumb')) {
        if (is_front_page() && 'yes' === $hideYoastBreadcrumbOnFrontpage) {
            return $data;
        }

        // Remove the item separator from the breadcrumb.
        // Use the one provided by css.
        add_filter('wpseo_breadcrumb_separator', function () {
            return '';
        });

        $data->breadcrumbMarkup = yoast_breadcrumb('', '', false);
    }

    return $data;
}

/**
 * Breadcrumb template
 *
 * @since 1.0.0
 *
 * @return void
 */
function breacrumbTmpl()
{
    $data = call_user_func_array('Qibla\\Functions\\getBreadcrumbData', func_get_args());

    if (! $data->breadcrumbMarkup) {
        return;
    }

    $engine = new TEngine('breadcrumb', $data, '/views/breadcrumb.php');
    $engine->render();
}
