<?php
/**
 * Qibla Listings
 *
 * Allow users to create custom listings for a fee. WooCommerce support.
 *
 * @package QiblaListings
 * @version 2.4.1
 *
 * @wordpress-plugin
 * Plugin Name: Qibla Listings
 * Plugin URI: https://www.southemes.com
 * Description: Allow users to create custom listings for a fee. WooCommerce support.
 * Version: 2.4.1
 * Author: App&Map <luca@appandmap.com>
 * Author URI: http://appandmap.com/en/
 * License: GPL2
 * WC requires at least: 3.2.0
 * WC tested up to: 3.4.4
 *
 * Copyright (C) 2017 App&Map <luca@appandmap.com>
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

if (! defined('QB_ENV')) {
    /**
     * The Current Environment
     *
     * @since 1.0.0
     */
    define('QB_ENV', 'prod');
}

// Base plugin requirements.
require_once untrailingslashit(plugin_dir_path(__FILE__)) . '/src/Plugin.php';
require_once QiblaListings\Plugin::getPluginDirPath('/requires.php');
require_once QiblaListings\Plugin::getPluginDirPath('/src/Autoloader.php');

// Setup Auto-loader.
$loaderMap = include QiblaListings\Plugin::getPluginDirPath('/inc/autoloaderMapping.php');
$loader    = new \QiblaListings\Autoloader();

$loader->addNamespaces($loaderMap);
$loader->register();

// Register the activation hook.
register_activation_hook(__FILE__, array('\\QiblaListings\\Activate', 'activate'));
register_deactivation_hook(__FILE__, array('\\QiblaListings\\Deactivate', 'deactivate'));

add_action('plugins_loaded', function () {
    // Check for the dependency.
    if (did_action('qibla_fw_did_init') && function_exists('WC')) :
        // Init
        //
        // Retrieve and build the filters based on context.
        // First common filters, than admin or front-end filters.
        // Filters include actions and filters.
        $filters = array();
        $filters = array_merge($filters, include QiblaListings\Plugin::getPluginDirPath('/inc/filters.php'));

        // Add filters based on context.
        if (is_admin()) {
            $filters = array_merge(
                $filters,
                include QiblaListings\Plugin::getPluginDirPath('/inc/filtersAdmin.php')
            );
        } else {
            $filters = array_merge(
                $filters,
                include QiblaListings\Plugin::getPluginDirPath('/inc/filtersFront.php')
            );

            // Check if is an ajax request.
            // If so, include even the filters for the ajax actions.
            if (\QiblaListings\Functions\isAjaxRequest()) {
                $filters = array_merge(
                    $filters,
                    include QiblaListings\Plugin::getPluginDirPath('/inc/filtersAjax.php')
                );
            }
        }

        // Get the instance for the Init.
        $init = new QiblaFramework\Init(new QiblaFramework\Loader(), $filters);
        $init->init();

        add_action('woocommerce_init', function () {
            // Set the Adapter filters in order to hook the custom product types
            // to the WooCommerce logic.
            $adapter = new \QiblaListings\Woocommerce\ProductTypeAdapter();
            $adapter->setHooks();

            // Set the filters.
            $filters = include QiblaListings\Plugin::getPluginDirPath('/inc/woocommerceFilters.php');
            // In front.
            if (! is_admin()) {
                $filters = array_merge(
                    $filters,
                    include QiblaListings\Plugin::getPluginDirPath('/inc/woocommerceFiltersFront.php')
                );
            }

            $wcInit = new QiblaFramework\Woocommerce\Init(new QiblaFramework\Loader(), $filters);
            $wcInit->init();
        });

        // Then load the plugin text-domain.
        load_plugin_textdomain('qibla-listings', false, '/qibla-listings/languages/');
    else :
        // Framework not active, lets disable the plugin.
        \QiblaListings\Functions\disablePlugin();
    endif;

    /**
     * Listings Did Init
     *
     * @since 2.0.0
     */
    do_action('qibla_listings_did_init');
}, 30);
