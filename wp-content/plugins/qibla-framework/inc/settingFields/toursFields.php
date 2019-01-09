<?php
/**
 * toursFields.php
 *
 * @since      2.5.1
 * @package    ${NAMESPACE}
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

use QiblaFramework\Functions as F;
use QiblaFramework\Form\Factories\FieldFactory;

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

/**
 * Filter Tours Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
return apply_filters('qibla_opt_inc_tours_fields', array(
        /**
         * Show Map
         *
         * @since 2.5.1
         */
        'qibla_opt-tours-archive_show_map:checkbox'   => $fieldFactory->table(array(
            'type'        => 'checkbox',
            'style'       => 'toggler',
            'name'        => 'qibla_opt-tours-archive_show_map',
            'value'       => F\getThemeOption('tours', 'archive_show_map', true),
            'label'       => esc_html_x('Show Map on Archive', 'settings', 'qibla-framework'),
            'description' => esc_html_x(
                'Active to show or not the map within the tours archive.',
                'settings',
                'qibla-framework'
            ),
        )),

        /**
         * Travel Mode
         *
         * @since 2.4.0
         */
        'qibla_opt-tours-travel-mode:select' => $fieldFactory->table(array(
            'type'         => 'select',
            'name'         => 'qibla_opt-tours-travel-mode',
            'label'        => esc_html_x('Travel Mode', 'settings', 'qibla-framework'),
            'description'  => esc_html_x('Select the default travel mode.', 'settings', 'qibla-framework'),
            'select2'      => true,
            'exclude_none' => true,
            'value'        => F\getThemeOption('tours', 'travel-mode', true),
            'options'      => array(
                'DRIVING'   => esc_html_x('Driving', 'settings', 'qibla-framework'),
                'WALKING'   => esc_html_x('Walking', 'settings', 'qibla-framework'),
                'BICYCLING' => esc_html_x('Bicycling', 'settings', 'qibla-framework'),
                'TRANSIT'   => esc_html_x('Transit', 'settings', 'qibla-framework'),
            ),
            'attrs'        => array(
                'class' => 'dlselect2--wide',
            ),
        )),

        /**
         * Search Type
         *
         * @since 2.4.0
         */
        'qibla_opt-tours-search-type:select' => $fieldFactory->table(array(
            'type'         => 'select',
            'name'         => 'qibla_opt-tours-search-type',
            'label'        => esc_html_x('Search Type', 'settings', 'qibla-framework'),
            'description'  => esc_html_x('Select the type of the search.', 'settings', 'qibla-framework'),
            'select2'      => true,
            'exclude_none' => true,
            'value'        => F\getThemeOption('tours', 'search-type', true),
            'options'      => array(
                'simple'   => esc_html_x('Simple Search', 'settings', 'qibla-framework'),
                'geocoded' => esc_html_x('Search and Geocode', 'settings', 'qibla-framework'),
                'combo'    => esc_html_x('Search, Geocode and Categories', 'settings', 'qibla-framework'),
            ),
            'attrs'        => array(
                'class' => 'dlselect2--wide',
            ),
        )),
    )
);