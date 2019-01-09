<?php
/**
 * Qibla Importer
 *
 * @link    http://www.southemes.com
 * @package QiblaImporter
 * @version 1.2.7
 *
 * @wordpress-plugin
 * Plugin Name: Qibla Importer
 * Plugin URI: http://southemes.com/demos/qibla
 * Description: Demo Content Importer for Qibla Theme.
 * Version: 1.2.7
 * Author: App&Map <luca@appandmap.com>
 * Author URI: http://appandmap.com/en/
 * License: GPL2
 * Text Domain: qibla-importer
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

defined('WPINC') || die;

if (! defined('QB_ENV')) {
    define('QB_ENV', 'prod');
}

// Register the activation hook.
register_activation_hook(__FILE__, array('\\QiblaImporter\\Activate', 'activate'));
register_deactivation_hook(__FILE__, array('\\QiblaImporter\\Deactivate', 'deactivate'));

// Base Requirements.
require_once untrailingslashit(plugin_dir_path(__FILE__)) . '/src/Plugin.php';
require_once QiblaImporter\Plugin::getPluginDirPath('/requires.php');
require_once QiblaImporter\Plugin::getPluginDirPath('/src/AutoLoader.php');

// SetUp Auto-loader.
$loaderMap = include \QiblaImporter\Plugin::getPluginDirPath('/inc/autoloaderMapping.php');
$loader    = new \QiblaImporter\AutoLoader();

$loader->addNamespaces($loaderMap[0]);
$loader->register();

add_action('plugins_loaded', function () {
    // Retrieve and build the filters based on context.
    // First common filters, than admin or front-end filters.
    // Filters include actions and filters.
    $filters = array();
    $filters = array_merge($filters, include QiblaImporter\Plugin::getPluginDirPath('/inc/filters.php'));

    // Add filters based on context.
    if (is_admin()) {
        $filters = array_merge($filters, include QiblaImporter\Plugin::getPluginDirPath('/inc/filtersAdmin.php'));
    } else {
        $filters = array_merge($filters, include QiblaImporter\Plugin::getPluginDirPath('/inc/filtersFront.php'));
    }

    $init = new QiblaImporter\Init(new QiblaImporter\Loader(), $filters);

    $init->init();
}, 30);
