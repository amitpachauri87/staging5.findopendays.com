<?php
/**
 * WordPress Plugin Framework
 *
 * @link    http://www.southemes.com
 * @package QiblaFramework
 * @version 2.5.1
 *
 * @wordpress-plugin
 * Plugin Name: Qibla Framework
 * Plugin URI: http://www.southemes.com
 * Description: A Framework for WordPress Qibla Theme
 * Version: 2.5.1
 * Author: App&Map <luca@appandmap.com>
 * Author URI: http://appandmap.com/en/
 * License: GPL2
 * Text Domain: qibla-framework
 * WC requires at least: 3.2.0
 * WC tested up to: 3.4.4
 *
 * Copyright (C) 2016 App&Map <luca@appandmap.com>
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
    define('QB_ENV', 'prod');
}

if (! defined('UPX_ENV')) {
    define('UPX_ENV', 'prod');
}

// Base requirements.
require_once untrailingslashit(plugin_dir_path(__FILE__)) . '/src/Plugin.php';
require_once QiblaFramework\Plugin::getPluginDirPath('/requires.php');
require_once QiblaFramework\Plugin::getPluginDirPath('/src/Autoloader.php');

// Setup Auto-loader.
$loaderMap = include QiblaFramework\Plugin::getPluginDirPath('/inc/autoloaderMapping.php');
$loader    = new \QiblaFramework\Autoloader();

$loader->addNamespaces($loaderMap);
$loader->register();

// Register the activation hook.
register_activation_hook(__FILE__, array('QiblaFramework\\Activate', 'activate'));
register_deactivation_hook(__FILE__, array('QiblaFramework\\Deactivate', 'deactivate'));

add_action('plugins_loaded', function () {
    // Retrieve and build the filters based on context.
    // First common filters, than admin or front-end filters.
    // Filters include actions and filters.
    $filters = array();
    $filters = array_merge($filters, include QiblaFramework\Plugin::getPluginDirPath('/inc/filters.php'));

    // Add filters based on context.
    if (is_admin()) {
        $filters = array_merge($filters, include QiblaFramework\Plugin::getPluginDirPath('/inc/filtersAdmin.php'));
    } else {
        $filters = array_merge($filters, include QiblaFramework\Plugin::getPluginDirPath('/inc/filtersFront.php'));
    }

    // Check if is an ajax request.
    // If so, include even the filters for the ajax actions.
    if (QiblaFramework\Functions\isAjaxRequest()) {
        $filters = array_merge($filters, include QiblaFramework\Plugin::getPluginDirPath('/inc/filtersAjax.php'));
    }

    // Let's start the game.
    $init = new QiblaFramework\Init(new QiblaFramework\Loader(), $filters);
    $init->init();

    add_action('woocommerce_init', function () {

        $filters = array();

        // In front.
        if (! is_admin()) {
            $filters = array_merge(
                $filters,
                include QiblaFramework\Plugin::getPluginDirPath('/inc/wc/filtersFront.php')
            );
        }

        $wcInit = new QiblaFramework\Woocommerce\Init(new QiblaFramework\Loader(), $filters);
        $wcInit->init();
    });

    // Then load the plugin text-domain.
    load_plugin_textdomain('qibla-framework', false, '/qibla-framework/languages/');

    /**
     * Did Init
     *
     * @since 1.5.0
     */
    do_action('qibla_fw_did_init');
}, 20);
