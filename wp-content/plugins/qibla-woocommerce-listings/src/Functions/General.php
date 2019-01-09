<?php
/**
 * General Functions
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

namespace QiblaWcListings\Functions;

/**
 * Check Dependencies
 *
 * @since 1.1.2
 *
 * @return bool True if check pass, false otherwise
 */
function checkDependencies()
{
    if (! function_exists('is_plugin_active')) {
        require_once untrailingslashit(ABSPATH) . '/wp-admin/includes/plugin.php';
    }

    return is_plugin_active('qibla-framework/index.php') && function_exists('WC');
}

/**
 * Disable Plugin
 *
 * This function disable the plugin because of his dependency.
 *
 * @since 1.1.2
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
                        'Qibla WooCommerce Listing has been deactivated. The plugin required the Qibla Framework and WooCommerce that are currently not active or installed.',
                        'qibla-woocommerce-listing'
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
        deactivate_plugins('qibla-woocommerce-listings/index.php');
    endif;
}