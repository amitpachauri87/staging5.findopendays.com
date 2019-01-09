<?php
/**
 * Visual Composer Terms Shortcode Map
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

// Get the terms.
$terms = \QiblaFramework\Functions\getTermsList(array(
    'taxonomy'   => 'listing_categories',
    'hide_empty' => false,
), 'name', 'slug');

return array(
    'name'        => esc_html_x('Qibla Categories Grid', 'shortcode-vc', 'qibla-framework'),
    'base'        => $this->tag,
    'category'    => esc_html_x('Qibla', 'shortcode-vc', 'qibla-framework'),
    'description' => esc_html_x('Listing Categories', 'shortcode-vc', 'qibla-framework'),
    'icon'        => \QiblaFramework\Plugin::getPluginDirUrl('/assets/imgs/vc-sc-icon.png'),
    'params'      => array(
        // Taxonomy.
        array(
            'type'        => 'hidden',
            'param_name'  => 'taxonomy',
            'save_always' => true,
            'value'       => 'listing_categories',
        ),

        // Order By.
        array(
            'heading'     => esc_html_x('Order By', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'dropdown',
            'param_name'  => 'orderby',
            'save_always' => true,
            'description' => esc_html_x('How to order the terms.', 'shortcode-sc', 'qibla-framework'),
            'value'       => array(
                esc_html_x('Name', 'shortcode-vc', 'qibla-framework')        => 'name',
                esc_html_x('Slug', 'shortcode-vc', 'qibla-framework')        => 'slug',
                esc_html_x('Term Group', 'shortcode-vc', 'qibla-framework')  => 'term_group',
                esc_html_x('Term ID', 'shortcode-vc', 'qibla-framework')     => 'term_id',
                esc_html_x('Description', 'shortcode-vc', 'qibla-framework') => 'description',
            ),
        ),

        // Slug.
        array(
            'heading'     => esc_html_x('Terms', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'checkbox',
            'param_name'  => 'slug',
            'save_always' => true,
            'value'       => $terms,
        ),

        // Grid.
        array(
            'heading'     => esc_html_x('Items Grid', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_radio',
            'param_name'  => 'grid',
            'save_always' => true,
            'value'       => array(
                '2,2'   => '2,2',
                '2,1,1' => '2,1,1',
                '1,2,1' => '1,2,1',
                '1,1,2' => '1,1,2',
            ),
        ),

        // Layout.
        array(
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
