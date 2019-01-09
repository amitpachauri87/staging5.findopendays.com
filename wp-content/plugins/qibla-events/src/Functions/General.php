<?php
/**
 * General Functions
 *
 * @since      1.0.0
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

namespace AppMapEvents\Functions;

/**
 * Get Referrer
 *
 * @todo  Remove in favor of the function by the framework
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
 * Filter Input
 *
 * @todo  Remove in favor of the function by the framework
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
 * Strip Content various tags such as img, script and style
 *
 * @since 1.0.0
 *
 * @param $content
 *
 * @return string
 */
function stripContent($content)
{
    // Strip shortcode.
    $content = rtrim(strip_shortcodes($content), "\n\t\r");
    // Strip images.
    $content = preg_replace('/<img[^>]+\>/i', '', $content);
    // Strip div.
    $content = preg_replace("/<div>(.*?)<\/div>/", "$1", $content);
    // Remove empty links after remove the images. Some images are wrapped around an anchor.
    $content = preg_replace('/<a[^>]+\><\/a>/i', '', $content);
    // Strip scripts.
    $content = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $content);
    // Current content html.
    $description_html = shortcode_unautop(wpautop(wptexturize(apply_filters('the_content', $content))));

    return $description_html;
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

    return is_plugin_active('qibla-framework/index.php') && is_plugin_active('qibla-listings/index.php');
}

/**
 * Disable Plugin
 *
 * This function disable the plugin because of his dependency.
 *
 * @since 1.0.0
 *
 * @return void
 */
function disablePlugin()
{
    if (! checkDependencies()) :
        add_action('admin_notices', function () {
            ?>
            <div class="error">
                <p>
                    <?php esc_html_e(
                        'Qibla Events has been deactivated. The plugin require: Qibla Framework and Qibla Listings, that are not currently active or installed.',
                        'qibla-events'
                    ); ?>
                </p>
            </div>
            <?php

            // Don't show the activated notice.
            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
        });

        // Deactivate the plugin.
        deactivate_plugins('qibla-events/index.php');
    endif;
}
