<?php
/**
 * Scripts
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

use QiblaListings\Plugin;

// Get the Environment.
$dev = ! ! ('dev' === QB_ENV);

$scripts = array(
    'scripts' => array(),
    'styles'  => array(),
);

if (! is_admin()) {
    // Front Scripts.
    $scripts['scripts'] = array_merge($scripts['scripts'], array(
        // Listings Submit Handler.
        'listing-submit-handler' => array(
            'listing-submit-handler',
            Plugin::getPluginDirUrl('/assets/js/submit-listings-handler.js'),
            array('dl-form'),
            $dev ? time() : '1.0.0',
            true,
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),
        // Manager Post Actions.
        'manager-post-actions'   => array(
            'manager-post-actions',
            Plugin::getPluginDirUrl('/assets/js/manager-post-actions.js'),
            array('underscore', 'jquery', 'dl-utils'),
            $dev ? time() : '1.0.0',
            true,
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),
    ));

    /**
     * Filter Scripts
     *
     * @since 1.0.0
     *
     * @param array $scripts The array of the scripts
     */
    $scripts = apply_filters('qibla_listings_front_scripts_list', $scripts);
}

return $scripts;