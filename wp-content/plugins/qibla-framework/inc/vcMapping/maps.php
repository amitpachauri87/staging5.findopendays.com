<?php
/**
 * Visual Composer GoogleMap Fields Map
 *
 * @author    Alfio Piccione <alfio.piccione@gmail.com>
 * @copyright Copyright (c) 2018, Alfio Piccione
 * @license   GNU General Public License, version 2
 *
 * Copyright (C) 2018 Alfio Piccione <alfio.piccione@gmail.com>
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

$types = new \QiblaFramework\ListingsContext\Types();

return array(
    'name'        => esc_html_x('Map', 'shortcode-vc', 'qibla-framework'),
    'base'        => $this->tag,
    'category'    => esc_html_x('Qibla', 'shortcode-vc', 'qibla-framework'),
    'description' => esc_html_x('Google Map.', 'shortcode-vc', 'qibla-framework'),
    'icon'        => \QiblaFramework\Plugin::getPluginDirUrl('/assets/imgs/vc-sc-icon.png'),
    'params'      => array(
        // Taxonomy.
        array(
            'type'        => 'hidden',
            'param_name'  => 'post_type',
            'save_always' => true,
            'value'       => 'listings',
        ),
        // Listings Categories.
        'categories' => array(
            'heading'     => esc_html_x('Listing Categories', 'shortcode-vc', 'qibla-framework'),
            'description' => esc_html_x('Category to show.', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'dropdown',
            'param_name'  => 'categories',
            'save_always' => true,
            'std'         => '',
            'value'       => array_merge(array(
                esc_attr__('All', 'qibla-framework') => '',
            ), array_flip(F\getTermsList(array(
                'taxonomy'   => 'listing_categories',
                'hide_empty' => false,
            )))),
        ),

        // Listings Locations.
        'locations'          => array(
            'heading'     => esc_html_x('Listing Locations', 'shortcode-vc', 'qibla-framework'),
            'description' => esc_html_x('Location to show.', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'dropdown',
            'param_name'  => 'locations',
            'save_always' => true,
            'std'         => '',
            'value'       => array_merge(array(
                esc_attr__('All', 'qibla-framework') => '',
            ), array_flip(F\getTermsList(array(
                'taxonomy'   => 'locations',
                'hide_empty' => false,
            )))),
        ),

        // Height.
        'height'             => array(
            'heading'     => esc_html_x('Map height', 'shortcode-vc', 'qibla-framework'),
            'description' => esc_html_x('Set the map height (default: 100vh).', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'textfield',
            'param_name'  => 'height',
            'save_always' => true,
            'value'       => '',
        ),

        // Width.
        'width'              => array(
            'heading'     => esc_html_x('Map width', 'shortcode-vc', 'qibla-framework'),
            'description' => esc_html_x('Set the map width (default: 100%).', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'textfield',
            'param_name'  => 'width',
            'save_always' => true,
            'value'       => '',
        ),

        // Layout.
        'layout' => array(
            'heading'     => esc_html_x('Layout', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_radio',
            'group'       => esc_html_x('Layout', 'shortcode-vc-group', 'qibla-framework'),
            'description' => esc_html_x(
                'Set the layout, container width or boxed (default: container width)',
                'shortcode-vc',
                'qibla-framework'
            ),
            'param_name'  => 'layout',
            'save_always' => true,
            'value'       => array(
                esc_html_x('Container Width', 'shortcode-vc', 'qibla-framework') => 'container-width',
                esc_html_x('Boxed', 'shortcode-vc', 'qibla-framework')           => 'boxed',
            ),
        ),

        // Background Color.
        'section-background-color' => array(
            'heading'     => esc_html_x('Background Color', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'colorpicker',
            'param_name'  => 'section-background-color',
            'group'       => esc_html_x('Layout', 'shortcode-vc-group', 'qibla-framework'),
            'description' => esc_html_x(
                'Select the color for the background of the section.',
                'shortcode-vc',
                'qibla-framework'
            ),
        ),

        // Padding Top.
        'section-padding-top' => array(
            'heading'     => esc_html_x('Padding Top', 'shortcode-vc', 'qibla-framework'),
            'group'       => esc_html_x('Layout', 'shortcode-vc-group', 'qibla-framework'),
            'description' => esc_html_x(
                'Padding top for section es: (22px, 2em, 3rem)',
                'shortcode-vc',
                'qibla-framework'
            ),
            'type'        => 'textfield',
            'param_name'  => 'section-padding-top',
            'value'       => '',
        ),

        // Padding Top.
        'section-padding-bottom' => array(
            'heading'     => esc_html_x('Padding Bottom', 'shortcode-vc', 'qibla-framework'),
            'group'       => esc_html_x('Layout', 'shortcode-vc-group', 'qibla-framework'),
            'description' => esc_html_x(
                'Padding bottom for section es: (22px, 2em, 3rem)',
                'shortcode-vc',
                'qibla-framework'
            ),
            'type'        => 'textfield',
            'param_name'  => 'section-padding-bottom',
            'value'       => '',
        ),
    ),
);
