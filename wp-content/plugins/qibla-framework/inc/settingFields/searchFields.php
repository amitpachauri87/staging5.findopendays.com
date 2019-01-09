<?php

use QiblaFramework\Functions as F;
use QiblaFramework\IconsSet;
use QiblaFramework\Form\Factories\FieldFactory;

/**
 * Settings Search Fields
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
 * Filter Search Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
return apply_filters('qibla_opt_inc_search_fields', array(
    /**
     * Search Type
     *
     * @since 2.0.0
     */
    'qibla_opt-search-type:select'               => $fieldFactory->table(array(
        'type'         => 'select',
        'name'         => 'qibla_opt-search-type',
        'label'        => esc_html_x('Search Type', 'settings', 'qibla-framework'),
        'description'  => esc_html_x('Select the type of the search.', 'settings', 'qibla-framework'),
        'select2'      => true,
        'exclude_none' => true,
        'value'        => F\getThemeOption('search', 'type', true),
        'options'      => array(
            'geocoded' => esc_html_x('Search and Geocode', 'settings', 'qibla-framework'),
            'simple'   => esc_html_x('Simple Search', 'settings', 'qibla-framework'),
            'combo'    => esc_html_x('Search, Geocode and Categories', 'settings', 'qibla-framework'),
        ),
        'attrs'        => array(
            'class' => 'dlselect2--wide',
        ),
    )),

    /**
     * Placeholder
     *
     * @since 1.0.0
     */
    'qibla_opt-search-placeholder:text'          => $fieldFactory->table(array(
        'type'        => 'text',
        'name'        => 'qibla_opt-search-placeholder',
        'label'       => esc_html_x('Search Placeholder', 'settings', 'qibla-framework'),
        'description' => esc_html_x('The placeholder for the search input text.', 'settings', 'qibla-framework'),
        'attrs'       => array(
            'value' => F\getThemeOption('search', 'placeholder', true),
        ),
    )),

    /**
     * Geocoded Placeholder
     *
     * @since 1.0.0
     */
    'qibla_opt-search-geocoded_placeholder:text' => $fieldFactory->table(array(
        'type'        => 'text',
        'name'        => 'qibla_opt-search-geocoded_placeholder',
        'label'       => esc_html_x('Geocode Placeholder', 'settings', 'qibla-framework'),
        'description' => esc_html_x('The placeholder for the search geocoded field.', 'settings', 'qibla-framework'),
        'attrs'       => array(
            'value' => F\getThemeOption('search', 'geocoded_placeholder', true),
        ),
    )),

    /**
     * Submit Label
     *
     * @since 1.0.0
     */
    'qibla_opt-search-submit_label:text'         => $fieldFactory->table(array(
        'type'        => 'text',
        'name'        => 'qibla_opt-search-submit_label',
        'label'       => esc_html_x('Input Submit Label', 'settings', 'qibla-framework'),
        'description' => esc_html_x('The label for the submit button.', 'settings', 'qibla-framework'),
        'attrs'       => array(
            'value'    => F\getThemeOption('search', 'submit_label', true),
            'required' => 'required',
        ),
    )),

    /**
     * Submit Icon
     *
     * @since 1.0.0
     */
    'qibla_opt-search-submit_icon:text'          => $fieldFactory->table(array(
        'type'        => 'icon-list',
        'name'        => 'qibla_opt-search-submit_icon',
        'label'       => esc_html_x('Input Submit Icon', 'settings', 'qibla-framework'),
        'description' => esc_html_x('The icon for the submit button.', 'settings', 'qibla-framework'),
        'value'       => F\getThemeOption('search', 'submit_icon', true),
        'options'     => array(
            new IconsSet\Lineawesome(),
            new IconsSet\Fontawesomeregular(),
            new IconsSet\Fontawesomesolid(),
            new IconsSet\Foundation(),
            new IconsSet\Material(),
            new IconsSet\Mapicons(),
            new IconsSet\Glyphs(),
        ),
    )),
));
