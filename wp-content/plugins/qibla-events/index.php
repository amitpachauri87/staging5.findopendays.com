<?php
/**
 * WordPress Events Plugin
 *
 * @link    http://www.southemes.com
 * @package Qibla Events
 * @version 1.2.1
 *
 * @wordpress-plugin
 * Plugin Name: Qibla Events
 * Plugin URI: http://www.southemes.com
 * Description: A WordPress Events Plugin
 * Version: 1.2.1
 * Author: App&Map <luca@appandmap.com>
 * Author URI: http://appandmap.com/en/
 * License: GPL2
 * Text Domain: qibla-events
 * WC requires at least: 3.2.0
 * WC tested up to: 3.4.4
 *
 * Copyright (C) 2018 App&Map <luca@appandmap.com>
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

// Base requirements.
require_once untrailingslashit(plugin_dir_path(__FILE__)) . '/src/Plugin.php';
require_once AppMapEvents\Plugin::getPluginDirPath('/requires.php');
require_once AppMapEvents\Plugin::getPluginDirPath('/src/Autoloader.php');

// Setup Auto-loader.
$loaderMap = include AppMapEvents\Plugin::getPluginDirPath('/inc/autoloaderMapping.php');
$loader    = new \AppMapEvents\Autoloader();

$loader->addNamespaces($loaderMap);
$loader->register();

// Register the activation hook.
register_activation_hook(__FILE__, array('AppMapEvents\\Activate', 'activate'));
register_deactivation_hook(__FILE__, array('AppMapEvents\\Deactivate', 'deactivate'));

add_action('plugins_loaded', function () {
    // Check for the dependency.
    if (did_action('qibla_fw_did_init') || \AppMapEvents\Functions\checkDependencies()) :
        // Filter global types of listings.
        add_filter('qibla_fw_global_listings_types', function ($types){
            $types = array_merge($types, array('events'));
            return $types;
        });
        // Retrieve and build the filters based on context.
        // First common filters, than admin or front-end filters.
        // Filters include actions and filters.
        $filters = array();
        $filters = array_merge($filters, include \AppMapEvents\Plugin::getPluginDirPath('/inc/filters.php'));

        // Add filters based on context.
        if (is_admin()) {
            $filters = array_merge($filters, include \AppMapEvents\Plugin::getPluginDirPath('/inc/filtersAdmin.php'));
        } else {
            $filters = array_merge($filters, include \AppMapEvents\Plugin::getPluginDirPath('/inc/filtersFront.php'));
        }

        // Check if is an ajax request.
        // If so, include even the filters for the ajax actions.
        if (\QiblaFramework\Functions\isAjaxRequest()) {
            $filters = array_merge($filters, include \AppMapEvents\Plugin::getPluginDirPath('/inc/filtersAjax.php'));
        }

        // Get the instance for the Init.
        $init = new QiblaFramework\Init(new QiblaFramework\Loader(), $filters);
        $init->init();

        // Then load the plugin text-domain.
        load_plugin_textdomain('qibla-events', false, '/qibla-events/languages/');

    else :
        // Framework not active, lets disable the plugin.
        \AppMapEvents\Functions\disablePlugin();
    endif;

    /**
     * Did Init
     *
     * @since 1.5.0
     */
    do_action('appmap_ev_fw_did_init');
}, 20);
