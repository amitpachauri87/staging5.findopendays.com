<?php
/**
 * Scripts
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

// Get the Environment.
$dev = ! ! ('dev' === QB_ENV);

// Get current lang.
$lang = substr(get_bloginfo('language'), 0, 2);

$scripts = array(
    'scripts' => array(
        // Date Picker Scripts.
        'multidatespicker'      => array(
            'multidatespicker',
            \AppMapEvents\Plugin::getPluginDirUrl('/assets/js/vendor/jquery-ui.multidatespicker.js'),
            array('jquery'),
            $dev ? time() : '',
            true,
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),

        // Multi Date Picker Scripts.
        'multidatespicker-type' => array(
            'multidatespicker-type',
            \AppMapEvents\Plugin::getPluginDirUrl('/assets/js/types/multiDatesPicker.js'),
            array('multidatespicker'),
            $dev ? time() : '',
            true,
            function () {
                return true;
            },
            function () {
                return false;
            },
            array(
                'async' => 'async',
            ),
        ),

        // Date Time Picker Scripts.
        'timepicker'            => array(
            'timepicker',
            \AppMapEvents\Plugin::getPluginDirUrl('/assets/js/vendor/jquery.timepicker.js'),
            array('jquery'),
            $dev ? time() : '',
            true,
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),
    ),
    'styles'  => array(
        // Date Picker Style.
        'multidatespicker-style' => array(
            'multidatespicker-style',
            \AppMapEvents\Plugin::getPluginDirUrl('/assets/css/vendor/jquery-ui.multidatespicker.css'),
            array(),
            $dev ? time() : '',
            'screen',
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),

        // Date Time Picker Style.
        'timepicker-style'       => array(
            'timepicker-style',
            \AppMapEvents\Plugin::getPluginDirUrl('/assets/css/vendor/jquery.timepicker.css'),
            array('jquery-ui'),
            $dev ? time() : '',
            'screen',
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),
    ),
);

// DatePicker Language.
if (file_exists(\AppMapEvents\Plugin::getPluginDirPath("/assets/js/datepicker-lang/datepicker-{$lang}.js"))) {

    $scripts['scripts'] = array_merge($scripts['scripts'], array(
        'datepicker-lang' => array(
            'datepicker-lang',
            \AppMapEvents\Plugin::getPluginDirUrl("/assets/js/datepicker-lang/datepicker-{$lang}.js"),
            array('jquery', 'jquery-ui-datepicker'),
            $dev ? time() : '',
            true,
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),
    ));
}

if (is_admin()) {
    $scripts['scripts'] = array_merge($scripts['scripts'], array());

    $scripts['styles'] = array_merge($scripts['styles'], array());

    /**
     * Filter Scripts
     *
     * @since 1.0.0
     *
     * @param array $scripts The array of the scripts
     */
    $scripts = apply_filters('appmap_ev_fw_admin_scripts_list', $scripts);
} else {
    // Front Scripts.
    $scripts['scripts'] = array_merge($scripts['scripts'], array(
        // Add Event Calendar
        'appmap-ev-add-calendar'    => array(
            'appmap-ev-add-calendar',
            \AppMapEvents\Plugin::getPluginDirUrl('/assets/js/vendor/ouical.js'),
            array('jquery'),
            $dev ? time() : '',
            function () {
                return true;
            },
            function () {
                return is_singular('events');
            },
            array(
                'defer' => 'defer',
            ),
        ),
        // Sidebar.
        'appmap-ev-sidebar'         => array(
            'appmap-ev-sidebar',
            \AppMapEvents\Plugin::getPluginDirUrl('/assets/js/sidebar.js'),
            array('underscore', 'jquery'),
            $dev ? time() : '',
            true,
            function () {
                return is_singular('events');
            },
            function () {
                return is_singular('events');
            },
        ),
        // Calendar Filter.
        'appmap-ev-calendar-filter' => array(
            'appmap-ev-calendar-filter',
            \AppMapEvents\Plugin::getPluginDirUrl('/assets/js/front/calendarFilter.js'),
            array('underscore', 'jquery'),
            $dev ? time() : '',
            true,
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),
        // Search dates.
        'appmap-ev-search-dates' => array(
            'appmap-ev-search-dates',
            \AppMapEvents\Plugin::getPluginDirUrl('/assets/js/front/searchDates.js'),
            array('underscore', 'jquery'),
            $dev ? time() : '',
            true,
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),
    ));

    // Front Styles.
    $scripts['styles'] = array_merge($scripts['styles'], array());

    /**
     * Filter Scripts
     *
     * @since 1.0.0
     *
     * @param array $scripts The array of the scripts
     */
    $scripts = apply_filters('appmap_ev_fw_front_scripts_list', $scripts);
}

return $scripts;