<?php
/**
 * Common Post Type fields
 *
 * This file is not indeed to be used directly as Fields generator for
 * Visual Composer.
 *
 * Instead is a collection of parameters fields used by other included file to define
 * the hierarchy of the fields. See for example post.php and listings.php.
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

    // Featured.
    'featured'       => array(
        'heading'     => esc_html_x('Show only Featured', 'shortcode-vc', 'qibla-framework'),
        'description' => esc_html_x('Flag to show only featured listings.', 'shortcode-vc', 'qibla-framework'),
        'type'        => 'qibla_radio',
        'param_name'  => 'featured',
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

    // Order By.
    'order_by'       => array(
        'heading'     => esc_html_x('Order By', 'shortcode-vc', 'qibla-framework'),
        'description' => esc_html_x(
            'In which order the posts must appear. See https://goo.gl/hvEHcL for more info.',
            'shortcode-vc',
            'qibla-framework'
        ),
        'type'        => 'dropdown',
        'param_name'  => 'orderby',
        'save_always' => true,
        'value'       => array(
            esc_html_x('Date', 'shortcode-vc', 'qibla-framework')   => 'date',
            esc_html_x('ID', 'shortcode-vc', 'qibla-framework')     => 'ID',
            esc_html_x('Author', 'shortcode-vc', 'qibla-framework') => 'author',
            esc_html_x('Title', 'shortcode-vc', 'qibla-framework')  => 'title',
            esc_html_x('Random', 'shortcode-vc', 'qibla-framework') => 'rand',
        ),
    ),

    // Order.
    'order'          => array(
        'heading'     => esc_html_x('Order', 'shortcode-vc', 'qibla-framework'),
        'description' => esc_html_x('Ascending or Descending order.', 'shortcode-vc', 'qibla-framework'),
        'type'        => 'dropdown',
        'param_name'  => 'order',
        'save_always' => true,
        'value'       => array(
            esc_html_x('Descending', 'shortcode-vc', 'qibla-framework') => 'DESC',
            esc_html_x('Ascending', 'shortcode-vc', 'qibla-framework')  => 'ASC',
        ),
    ),
);
