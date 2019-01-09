<?php
/**
 * eventsFields
 *
 * @since      2.4.0
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
 * Filter Search Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
return apply_filters('qibla_opt_inc_search_fields', array(
        /**
         * Show Map
         *
         * @since 2.5.1
         */
        'qibla_opt-events-archive_show_map:checkbox'   => $fieldFactory->table(array(
            'type'        => 'checkbox',
            'style'       => 'toggler',
            'name'        => 'qibla_opt-events-archive_show_map',
            'value'       => F\getThemeOption('events', 'archive_show_map', true),
            'label'       => esc_html_x('Show Map on Archive', 'settings', 'qibla-framework'),
            'description' => esc_html_x(
                'Active to show or not the map within the events archive.',
                'settings',
                'qibla-framework'
            ),
        )),

        /**
         * Search Type
         *
         * @since 2.4.0
         */
        'qibla_opt-events-search-type:select' => $fieldFactory->table(array(
            'type'         => 'select',
            'name'         => 'qibla_opt-events-search-type',
            'label'        => esc_html_x('Search Type', 'settings', 'qibla-framework'),
            'description'  => esc_html_x('Select the type of the search.', 'settings', 'qibla-framework'),
            'select2'      => true,
            'exclude_none' => true,
            'value'        => F\getThemeOption('events', 'search-type', true),
            'options'      => array(
                'simple'   => esc_html_x('Simple Search', 'settings', 'qibla-framework'),
                'dates'    => esc_html_x('Dates and Categories', 'settings', 'qibla-framework'),
                'geocoded' => esc_html_x('Search and Geocode', 'ssettings', 'qibla-framework'),
                'combo'    => esc_html_x('Search, Geocode and Categories', 'settings', 'qibla-framework'),
            ),
            'attrs'        => array(
                'class' => 'dlselect2--wide',
            ),
        )),
    )
);