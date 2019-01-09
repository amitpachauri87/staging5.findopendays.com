<?php
/**
 * Listings Package Visual Composer Map
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2017, Guido Scialfa
 * @license   GNU General Public License, version 2
 *
 * Copyright (C) 2017 Guido Scialfa <dev@guidoscialfa.com>
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

return array(
    'name'        => esc_html_x('Qibla Listings Packages Table', 'shortcode-vc', 'qibla-listings'),
    'base'        => $this->tag,
    'category'    => esc_html_x('Qibla', 'shortcode-vc', 'qibla-listings'),
    'description' => esc_html_x('Listings Packages Table', 'shortcode-vc', 'qibla-listings'),
    'icon'        => \QiblaFramework\Plugin::getPluginDirUrl('/assets/imgs/vc-sc-icon.png'),
    'save_always' => true,
    'params'      => array(
        array(
            'heading'    => esc_html_x('Select Packages', 'shortcode-vc', 'qibla-listings'),
            'type'       => 'qibla_select2',
            'param_name' => 'posts',
            'std'        => '',
            'value'      => \QiblaFramework\Functions\getPostList('listing_package', array('post_title', 'post_name')),
            'attrs'      => array(
                'multiple' => 'multiple',
            ),
        ),
        // Layout.
        array(
            'heading'     => esc_html_x('Layout', 'shortcode-vc', 'qibla-listings'),
            'type'        => 'qibla_radio',
            'group'       => esc_html_x('Layout', 'shortcode-vc-group', 'qibla-listings'),
            'description' => esc_html_x(
                'Set the layout, container width or boxed (default: container width)',
                'shortcode-vc',
                'qibla-listings'
            ),
            'param_name'  => 'layout',
            'save_always' => true,
            'value'       => array(
                esc_html_x('Container Width', 'shortcode-vc', 'qibla-listings') => 'container-width',
                esc_html_x('Boxed', 'shortcode-vc', 'qibla-listings')           => 'boxed',
            ),
        ),

        // Background Color.
        'section-background-color' => array(
            'heading'     => esc_html_x('Background Color', 'shortcode-vc', 'qibla-listings'),
            'type'        => 'colorpicker',
            'param_name'  => 'section-background-color',
            'group'       => esc_html_x('Layout', 'shortcode-vc-group', 'qibla-listings'),
            'description' => esc_html_x(
                'Select the color for the background of the section.',
                'shortcode-vc',
                'qibla-listings'
            ),
        ),

        // Padding Top.
        'section-padding-top' => array(
            'heading'     => esc_html_x('Padding Top', 'shortcode-vc', 'qibla-listings'),
            'group'       => esc_html_x('Layout', 'shortcode-vc-group', 'qibla-listings'),
            'description' => esc_html_x(
                'Padding top for section es: (22px, 2em, 3rem)',
                'shortcode-vc',
                'qibla-listings'
            ),
            'type'        => 'textfield',
            'param_name'  => 'section-padding-top',
            'value'       => '',
        ),

        // Padding Top.
        'section-padding-bottom' => array(
            'heading'     => esc_html_x('Padding Bottom', 'shortcode-vc', 'qibla-listings'),
            'group'       => esc_html_x('Layout', 'shortcode-vc-group', 'qibla-listings'),
            'description' => esc_html_x(
                'Padding bottom for section es: (22px, 2em, 3rem)',
                'shortcode-vc',
                'qibla-listings'
            ),
            'type'        => 'textfield',
            'param_name'  => 'section-padding-bottom',
            'value'       => '',
        ),
    ),
);
