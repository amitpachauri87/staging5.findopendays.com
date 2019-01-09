<?php
use QiblaFramework\Functions as F;
use QiblaFramework\Form\Factories\FieldFactory;

/**
 * Settings Google Map Fields
 *
 * @author  guido scialfa <dev@guidoscialfa.com>
 *
 * @license GPL 2
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

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

/**
 * Filter Map Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
return apply_filters('qibla_opt_inc_map_fields', array(
    /**
     * Google Map Api Key
     *
     * @todo  Add link to create an api key.
     *
     * @since 1.0.0
     */
    'qibla_opt-google_map-apikey:text' => $fieldFactory->table(array(
        'type'        => 'text',
        'name'        => 'qibla_opt-google_map-apikey',
        'label'       => esc_html_x('Api Key', 'settings', 'qibla-framework'),
        'description' => sprintf(
        /* Translators: The %s is the link to the google developer site. */
            esc_html_x(
                'Add the google map api key to able to show the maps within your site. More info about how to %s.',
                'settings',
                'qibla-framework'
            ),
            '<a href="//developers.google.com/maps/documentation/javascript/get-api-key">' .
            esc_html_x('get the google api key', 'settings', 'qibla-framework') .
            '</a>'
        ),
        'attrs'       => array(
            'value'    => F\getThemeOption('google_map', 'apikey'),
            'required' => 'required',
        ),
    )),

    /**
     * Google Map Default
     *
     * @since 2.5.0
     */
    'qibla_opt-google_map-location:map' => $fieldFactory->table(array(
        'type'        => 'map',
        'name'        => 'qibla_opt-google_map-location',
        'attrs'       => array(
            'value' => F\getThemeOption(
                'google_map',
                'location'
            ) ?: '40.7127837,-74.00594130000002:New York, NY, USA',
            'data-append-map-to' => 'dl-field',
            'class'              => array('widefat'),
        ),
        'label'       => esc_html__('Default Address', 'qibla-framework'),
        'description' => esc_html__(
            'Type the address of the location. Coordinate if no js.',
            'qibla-framework'
        ),
        'display'     => array($this, 'displayField'),
    )),

    /**
     * Google Map Zoom
     *
     * @since 1.0.0
     */
    'qibla_opt-google_map-zoom:number' => $fieldFactory->table(array(
        'type'        => 'number',
        'name'        => 'qibla_opt-google_map-zoom',
        'label'       => esc_html_x('Zoom Level', 'settings', 'qibla-framework'),
        'description' => esc_html_x(
            'Choose the level of the zoom for the google map. From 0 to 18',
            'settings',
            'qibla-framework'
        ),
        'attrs'       => array(
            'value' => absint(F\getThemeOption('google_map', 'zoom', true)),
            'min'   => 0,
            'max'   => 18,
        ),
    )),
));
