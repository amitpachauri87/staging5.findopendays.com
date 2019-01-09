<?php
use QiblaImporter\Plugin;
use QiblaImporter\Functions as F;
use QiblaImporter\Admin\Functions as Af;

/**
 * Scripts
 *
 * @todo  Rename the handle for the types scripts. Can be used in front and backend.
 *
 * @since 1.0.0
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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

// Get the Environment.
$dev = ! ! ('dev' === QB_ENV);

$scripts = array(
    'scripts'   => array(),
    'styles'    => array(),
    'localized' => array(
        array(
            'handle' => is_admin() ? 'admin' : 'front',
            'name'   => 'qbimplocalized',
            array(
                'env' => defined('QB_ENV') ? QB_ENV : 'prod',
            ),
        ),
    ),
);

if (is_admin()) {
    // Push scripts.
    $scripts['scripts'] = array_merge($scripts['scripts'], array(
        // Select2.
        array(
            'importer',
            Plugin::getPluginDirUrl('/assets/js/importer.js'),
            array('underscore', 'jquery'),
            $dev ? time() : false,
            true,
            function () {
                return true;
            },
            function () {
                return Af\isImporterPage();
            },
        ),
    ));

    // Push Styles.
    $scripts['styles'] = array_merge($scripts['styles'], array(
        // Admin Style.
        array(
            'qbimp-admin-importer',
            Plugin::getPluginDirUrl('/assets/css/admin.css'),
            array(),
            $dev ? time() : false,
            'screen',
        ),
    ));

    /**
     * Filter Scripts
     *
     * @since 1.0.0
     *
     * @param array $scripts The array of the scripts
     */
    $scripts = apply_filters('qibla_importer_admin_scripts_list', $scripts);
}

return $scripts;