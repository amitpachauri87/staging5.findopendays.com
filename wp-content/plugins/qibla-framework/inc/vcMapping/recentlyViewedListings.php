<?php
/**
 * Visual Composer Last Viewed Listings Fields Map
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

$types = new \QiblaFramework\ListingsContext\Types();

return array(
    'name'        => esc_html_x('Recently Viewed Listings', 'shortcode-vc', 'qibla-framework'),
    'base'        => $this->tag,
    'category'    => esc_html_x('Qibla', 'shortcode-vc', 'qibla-framework'),
    'description' => esc_html_x('Listings Posts recently viewed', 'shortcode-vc', 'qibla-framework'),
    'icon'        => \QiblaFramework\Plugin::getPluginDirUrl('/assets/imgs/vc-sc-icon.png'),
    'params'      => array(
        // Posts Per Page.
        'posts_per_page' => array(
            'heading'     => esc_html_x('Posts Per Page', 'shortcode-vc', 'qibla-framework'),
            'description' => esc_html_x('Type the number of posts to show.', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'textfield',
            'param_name'  => 'posts_per_page',
            'value'       => 10,
        ),

        // Show Title.
        'show_title'     => array(
            'heading'     => esc_html_x('Show post title', 'shortcode-vc', 'qibla-framework'),
            'description' => esc_html_x('To show or not the post title.', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_radio',
            'param_name'  => 'show_title',
            'save_always' => true,
            'value'       => array(
                esc_html_x('Yes', 'shortcode-vc', 'qibla-framework') => 'yes',
                esc_html_x('No', 'shortcode-vc', 'qibla-framework')  => 'no',
            ),
        ),

        // Show Subtitle.
        'show_subtitle'  => array(
            'heading'     => esc_html_x('Show Sub-title', 'shortcode-vc', 'qibla-framework'),
            'description' => esc_html_x('To show or not the subtitle.', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_radio',
            'param_name'  => 'show_subtitle',
            'save_always' => true,
            'value'       => array(
                esc_html_x('Yes', 'shortcode-vc', 'qibla-framework') => 'yes',
                esc_html_x('No', 'shortcode-vc', 'qibla-framework')  => 'no',
            ),
        ),

        // Show Thumbnail.
        'show_thumbnail' => array(
            'heading'     => esc_html_x('Show Featured Image', 'shortcode-vc', 'qibla-framework'),
            'description' => esc_html_x('To show or not the featured image.', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_radio',
            'param_name'  => 'show_thumbnail',
            'save_always' => true,
            'value'       => array(
                esc_html_x('Yes', 'shortcode-vc', 'qibla-framework') => 'yes',
                esc_html_x('No', 'shortcode-vc', 'qibla-framework')  => 'no',
            ),
        ),

        // Show Address.
        'show_address'   => array(
            'heading'     => esc_html_x('Show Address', 'shortcode-vc', 'qibla-framework'),
            'description' => esc_html_x('To show or not the location address.', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_radio',
            'param_name'  => 'show_address',
            'save_always' => true,
            'value'       => array(
                esc_html_x('Yes', 'shortcode-vc', 'qibla-framework') => 'yes',
                esc_html_x('No', 'shortcode-vc', 'qibla-framework')  => 'no',
            ),
        ),

        // Show Excerpt.
        'show_excerpt'   => array(
            'heading'     => esc_html_x('Show Excerpt', 'shortcode-vc', 'qibla-framework'),
            'description' => esc_html_x('To show or not the post excerpt', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_radio',
            'param_name'  => 'show_excerpt',
            'save_always' => true,
            'value'       => array(
                esc_html_x('Yes', 'shortcode-vc', 'qibla-framework') => 'yes',
                esc_html_x('No', 'shortcode-vc', 'qibla-framework')  => 'no',
            ),
        ),

        // Show Meta.
        'show_meta'      => array(
            'heading'     => esc_html_x('Show Meta', 'shortcode-vc', 'qibla-framework'),
            'description' => esc_html_x('To show or not the meta information.', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_radio',
            'param_name'  => 'show_meta',
            'save_always' => true,
            'value'       => array(
                esc_html_x('Yes', 'shortcode-vc', 'qibla-framework') => 'yes',
                esc_html_x('No', 'shortcode-vc', 'qibla-framework')  => 'no',
            ),
        ),

        // Columns (Grid Class).
        'grid_class'     => array(
            'heading'     => esc_html_x('Columns', 'shortcode-vc', 'qibla-framework'),
            'description' => esc_html_x('Number of columns per row.', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'dropdown',
            'param_name'  => 'grid_class',
            'save_always' => true,
            'std'         => 'col--md-6 col--lg-4',
            'value'       => array(
                esc_html_x('Two', 'shortcode-vc', 'qibla-framework')   => 'col--md-6',
                esc_html_x('Three', 'shortcode-vc', 'qibla-framework') => 'col--md-6 col--lg-4',
                esc_html_x('Four', 'shortcode-vc', 'qibla-framework')  => 'col--md-6 col--lg-3',
            ),
        ),
    ),
);
