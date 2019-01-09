<?php
/**
 * Visual Composer Listings Fields Map
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

use QiblaFramework\Functions as F;

$types = new \QiblaFramework\ListingsContext\Types();

return array(
    'name'        => esc_html_x('Qibla Events', 'shortcode-vc', 'qibla-events'),
    'base'        => $this->tag,
    'category'    => esc_html_x('Qibla', 'shortcode-vc', 'qibla-events'),
    'description' => esc_html_x('Events Posts', 'shortcode-vc', 'qibla-events'),
    'icon'        => \QiblaFramework\Plugin::getPluginDirUrl('/assets/imgs/vc-sc-icon.png'),
    'params'      => array_merge(
        array(
            // Events Categories.
            'event_categories'         => array(
                'heading'     => esc_html_x('Event Categories', 'shortcode-vc', 'qibla-events'),
                'description' => esc_html_x('Category to show.', 'shortcode-vc', 'qibla-events'),
                'type'        => 'dropdown',
                'param_name'  => 'event_categories',
                'save_always' => true,
                'std'         => '',
                'value'       => array_merge(array(
                    esc_attr__('All', 'qibla-events') => '',
                ), array_flip(F\getTermsList(array(
                    'taxonomy'   => 'event_categories',
                    'hide_empty' => false,
                )))),
            ),

            // Events Locations.
            'event_locations'          => array(
                'heading'     => esc_html_x('Event Locations', 'shortcode-vc', 'qibla-events'),
                'description' => esc_html_x('Location to show.', 'shortcode-vc', 'qibla-events'),
                'type'        => 'dropdown',
                'param_name'  => 'event_locations',
                'save_always' => true,
                'std'         => '',
                'value'       => array_merge(array(
                    esc_attr__('All', 'qibla-events') => '',
                ), array_flip(F\getTermsList(array(
                    'taxonomy'   => 'event_locations',
                    'hide_empty' => false,
                )))),
            ),

            // Events Tags.
            'event_tags'               => array(
                'heading'     => esc_html_x('Event Tags', 'shortcode-vc', 'qibla-events'),
                'description' => esc_html_x('Tags to show.', 'shortcode-vc', 'qibla-events'),
                'type'        => 'dropdown',
                'param_name'  => 'event_tags',
                'save_always' => true,
                'std'         => '',
                'value'       => array_merge(array(
                    esc_attr__('All', 'qibla-events') => '',
                ), array_flip(F\getTermsList(array(
                    'taxonomy'   => 'event_tags',
                    'hide_empty' => false,
                )))),
            ),

            // Events Dates.
            'event_dates'              => array(
                'heading'     => esc_html_x('Event Dates', 'shortcode-vc', 'qibla-events'),
                'description' => esc_html_x('format: yyyy-mm-dd (enter a maximum of two comma-separated dates)',
                    'shortcode-vc', 'qibla-events'),
                'type'        => 'textfield',
                'param_name'  => 'event_dates',
                'save_always' => true,
                'std'         => '',
                'value'       => '',
            ),

            // Events Number
            'posts_per_page'           => array(
                'heading'     => esc_html_x('Events Number', 'shortcode-vc', 'qibla-events'),
                'description' => esc_html_x('Type the number of posts to show.', 'shortcode-vc', 'qibla-events'),
                'type'        => 'textfield',
                'param_name'  => 'posts_per_page',
                'value'       => 10,
            ),

            // Show Title.
            'show_title'               => array(
                'heading'     => esc_html_x('Show post title', 'shortcode-vc', 'qibla-events'),
                'description' => esc_html_x('To show or not the post title.', 'shortcode-vc', 'qibla-events'),
                'type'        => 'qibla_radio',
                'param_name'  => 'show_title',
                'save_always' => true,
                'value'       => array(
                    esc_html_x('Yes', 'shortcode-vc', 'qibla-events') => 'yes',
                    esc_html_x('No', 'shortcode-vc', 'qibla-events')  => 'no',
                ),
            ),

            // Show Subtitle.
            'show_subtitle'            => array(
                'heading'     => esc_html_x('Show Sub-title', 'shortcode-vc', 'qibla-events'),
                'description' => esc_html_x('To show or not the subtitle.', 'shortcode-vc', 'qibla-events'),
                'type'        => 'qibla_radio',
                'param_name'  => 'show_subtitle',
                'save_always' => true,
                'value'       => array(
                    esc_html_x('Yes', 'shortcode-vc', 'qibla-events') => 'yes',
                    esc_html_x('No', 'shortcode-vc', 'qibla-events')  => 'no',
                ),
            ),

            // Featured.
            'featured'                 => array(
                'heading'     => esc_html_x('Show only Featured', 'shortcode-vc', 'qibla-events'),
                'description' => esc_html_x('Flag to show only featured listings.', 'shortcode-vc', 'qibla-events'),
                'type'        => 'qibla_radio',
                'param_name'  => 'featured',
                'save_always' => true,
                'std'         => 'no',
                'value'       => array(
                    esc_html_x('Yes', 'shortcode-vc', 'qibla-events') => 'yes',
                    esc_html_x('No', 'shortcode-vc', 'qibla-events')  => 'no',
                ),
            ),

            // Show Thumbnail.
            'show_thumbnail'           => array(
                'heading'     => esc_html_x('Show Featured Image', 'shortcode-vc', 'qibla-events'),
                'description' => esc_html_x('To show or not the featured image.', 'shortcode-vc', 'qibla-events'),
                'type'        => 'qibla_radio',
                'param_name'  => 'show_thumbnail',
                'save_always' => true,
                'value'       => array(
                    esc_html_x('Yes', 'shortcode-vc', 'qibla-events') => 'yes',
                    esc_html_x('No', 'shortcode-vc', 'qibla-events')  => 'no',
                ),
            ),

            // Show Address.
            'show_address'             => array(
                'heading'     => esc_html_x('Show Address', 'shortcode-vc', 'qibla-events'),
                'description' => esc_html_x('To show or not the location address.', 'shortcode-vc', 'qibla-events'),
                'type'        => 'qibla_radio',
                'param_name'  => 'show_address',
                'save_always' => true,
                'value'       => array(
                    esc_html_x('Yes', 'shortcode-vc', 'qibla-events') => 'yes',
                    esc_html_x('No', 'shortcode-vc', 'qibla-events')  => 'no',
                ),
            ),

            // Columns (Grid Class).
            'grid_class'               => array(
                'heading'     => esc_html_x('Columns', 'shortcode-vc', 'qibla-events'),
                'description' => esc_html_x('Number of columns per row.', 'shortcode-vc', 'qibla-events'),
                'type'        => 'dropdown',
                'param_name'  => 'grid_class',
                'save_always' => true,
                'std'         => 'col--md-6 col--lg-4',
                'value'       => array(
                    esc_html_x('Two', 'shortcode-vc', 'qibla-events')   => 'col--md-6',
                    esc_html_x('Three', 'shortcode-vc', 'qibla-events') => 'col--md-6 col--lg-4',
                    esc_html_x('Four', 'shortcode-vc', 'qibla-events')  => 'col--md-6 col--lg-3',
                ),
            ),

            // Order By.
            'order_by'                 => array(
                'heading'     => esc_html_x('Order By', 'shortcode-vc', 'qibla-events'),
                'description' => esc_html_x(
                    'In which order the posts must appear. See https://goo.gl/hvEHcL for more info.',
                    'shortcode-vc',
                    'qibla-events'
                ),
                'type'        => 'dropdown',
                'param_name'  => 'orderby',
                'save_always' => true,
                'value'       => array(
                    esc_html_x('Date', 'shortcode-vc', 'qibla-events')       => 'date',
                    esc_html_x('Event Date', 'shortcode-vc', 'qibla-events') => 'event_value',
                    esc_html_x('ID', 'shortcode-vc', 'qibla-events')         => 'ID',
                    esc_html_x('Author', 'shortcode-vc', 'qibla-events')     => 'author',
                    esc_html_x('Title', 'shortcode-vc', 'qibla-events')      => 'title',
                    esc_html_x('Random', 'shortcode-vc', 'qibla-events')     => 'rand',
                ),
            ),

            // Order.
            'order'                    => array(
                'heading'     => esc_html_x('Order', 'shortcode-vc', 'qibla-events'),
                'description' => esc_html_x('Ascending or Descending order.', 'shortcode-vc', 'qibla-events'),
                'type'        => 'dropdown',
                'param_name'  => 'order',
                'save_always' => true,
                'value'       => array(
                    esc_html_x('Descending', 'shortcode-vc', 'qibla-events') => 'DESC',
                    esc_html_x('Ascending', 'shortcode-vc', 'qibla-events')  => 'ASC',
                ),
            ),

            // Layout.
            'layout'                   => array(
                'heading'     => esc_html_x('Layout', 'shortcode-vc', 'qibla-events'),
                'type'        => 'qibla_radio',
                'group'       => esc_html_x('Layout', 'shortcode-vc-group', 'qibla-events'),
                'description' => esc_html_x(
                    'Set the layout, container width or boxed (default: container width)',
                    'shortcode-vc',
                    'qibla-events'
                ),
                'param_name'  => 'layout',
                'save_always' => true,
                'value'       => array(
                    esc_html_x('Container Width', 'shortcode-vc', 'qibla-events') => 'container-width',
                    esc_html_x('Boxed', 'shortcode-vc', 'qibla-events')           => 'boxed',
                ),
            ),

            // Background Color.
            'section-background-color' => array(
                'heading'     => esc_html_x('Background Color', 'shortcode-vc', 'qibla-events'),
                'type'        => 'colorpicker',
                'param_name'  => 'section-background-color',
                'group'       => esc_html_x('Layout', 'shortcode-vc-group', 'qibla-events'),
                'description' => esc_html_x(
                    'Select the color for the background of the section.',
                    'shortcode-vc',
                    'qibla-events'
                ),
            ),

            // Padding Top.
            'section-padding-top'      => array(
                'heading'     => esc_html_x('Padding Top', 'shortcode-vc', 'qibla-events'),
                'group'       => esc_html_x('Layout', 'shortcode-vc-group', 'qibla-events'),
                'description' => esc_html_x(
                    'Padding top for section es: (22px, 2em, 3rem)',
                    'shortcode-vc',
                    'qibla-events'
                ),
                'type'        => 'textfield',
                'param_name'  => 'section-padding-top',
                'value'       => '',
            ),

            // Padding Top.
            'section-padding-bottom'   => array(
                'heading'     => esc_html_x('Padding Bottom', 'shortcode-vc', 'qibla-events'),
                'group'       => esc_html_x('Layout', 'shortcode-vc-group', 'qibla-events'),
                'description' => esc_html_x(
                    'Padding bottom for section es: (22px, 2em, 3rem)',
                    'shortcode-vc',
                    'qibla-events'
                ),
                'type'        => 'textfield',
                'param_name'  => 'section-padding-bottom',
                'value'       => '',
            ),
        )
    ),
);
