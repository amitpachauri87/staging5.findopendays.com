<?php
/**
 * Testimonial Shortcode Visual Composer Map
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
    'name'        => esc_html_x('Qibla Testimonials', 'shortcode-vc', 'qibla-framework'),
    'base'        => $this->tag,
    'category'    => esc_html_x('Qibla', 'shortcode-vc', 'qibla-framework'),
    'description' => esc_html_x('Testimonials', 'shortcode-vc', 'qibla-framework'),
    'icon'        => \QiblaFramework\Plugin::getPluginDirUrl('/assets/imgs/vc-sc-icon.png'),
    'params'      => array(
        // Title.
        array(
            'heading'     => esc_html_x('Title', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'textfield',
            'param_name'  => 'title',
            'description' => esc_html_x('The title to use within the section', 'shortcode-vc', 'qibla-framework'),
        ),

        // Background Color.
        array(
            'heading'     => esc_html_x('Background Color', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'colorpicker',
            'param_name'  => 'background-color',
            'group'       => esc_html_x('Background', 'shortcode-vc-group', 'qibla-framework'),
            'description' => esc_html_x(
                'Select the color for the background of the section.',
                'shortcode-vc',
                'qibla-framework'
            ),
        ),

        // Background Image.
        array(
            'heading'     => esc_html_x('Background Image', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'attach_image',
            'param_name'  => 'background-image',
            'group'       => esc_html_x('Background', 'shortcode-vc-group', 'qibla-framework'),
            'description' => esc_html_x(
                'Select the image for the background of the section.',
                'shortcode-vc',
                'qibla-framework'
            ),
        ),

        // Slider.
        array(
            'heading'     => esc_html_x('Activate Slider', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_radio',
            'param_name'  => 'slider',
            'save_always' => true,
            'description' => esc_html_x('Slide the card for the testimonials.', 'shortcode-vc', 'qibla-framework'),
            'value'       => array(
                esc_html_x('Yes', 'shortcode-vc', 'qibla-framework') => 'yes',
                esc_html_x('No', 'shortcode-vc', 'qibla-framework')  => 'no',
            ),
        ),

        // Show Rating.
        array(
            'heading'     => esc_html_x('Show Rating', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_radio',
            'param_name'  => 'rating',
            'save_always' => true,
            'description' => esc_html_x('Show or not the star rating.', 'shortcode-vc', 'qibla-framework'),
            'value'       => array(
                esc_html_x('Yes', 'shortcode-vc', 'qibla-framework') => 'yes',
                esc_html_x('No', 'shortcode-vc', 'qibla-framework')  => 'no',
            ),
        ),

        // Show Rating.
        array(
            'heading'     => esc_html_x('Show Avatar', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_radio',
            'param_name'  => 'avatar',
            'save_always' => true,
            'description' => esc_html_x('Show or not testimonial\'s avatar.', 'shortcode-vc', 'qibla-framework'),
            'value'       => array(
                esc_html_x('Yes', 'shortcode-vc', 'qibla-framework') => 'yes',
                esc_html_x('No', 'shortcode-vc', 'qibla-framework')  => 'no',
            ),
        ),
    ),
);
