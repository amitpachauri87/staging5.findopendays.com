<?php
/**
 * Shortcode Button Visual Composer Map
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
    'name'        => esc_html_x('Qibla Section', 'shortcode-vc', 'qibla-framework'),
    'base'        => 'dl_section',
    'category'    => esc_html_x('Qibla', 'shortcode-vc', 'qibla-framework'),
    'description' => esc_html_x('Custom Button', 'shortcode-vc', 'qibla-framework'),
    'icon'        => \QiblaFramework\Plugin::getPluginDirUrl('/assets/imgs/vc-sc-icon.png'),
    'params'      => array(

        // The title.
        array(
            'heading'     => esc_html_x('Title', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'textfield',
            'param_name'  => 'title',
            'description' => esc_html_x('The title to show within the section.', 'shortcode-vc', 'qibla-framework'),
        ),

        // Subtitle.
        array(
            'heading'     => esc_html_x('Sub title', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'textfield',
            'param_name'  => 'subtitle',
            'description' => esc_html_x('The tagline of the section', 'shortcode-vc', 'qibla-framework'),
        ),

        // Content.
        array(
            'heading'     => esc_html_x('Content', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'textarea_html',
            'param_name'  => 'content',
            'description' => esc_html_x(
                'Content of the section. You can use extra shortcodes.',
                'shortcode-vc',
                'qibla-framework'
            ),
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

        // Size.
        array(
            'heading'     => esc_html_x('Size', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_radio',
            'description' => esc_html_x('How big the section must be.', 'shortcode-vc', 'qibla-framework'),
            'param_name'  => 'size',
            'save_always' => true,
            'value'       => array(
                esc_html_x('Normal', 'shortcode-vc', 'qibla-framework') => 'normal',
                esc_html_x('Big', 'shortcode-vc', 'qibla-framework')    => 'big',
            ),
        ),

        /* -----------------------------------------------------------------
           Link 1
        ----------------------------------------------------------------- */

        // Link.
        array(
            'heading'     => esc_html_x('Link Label', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'textfield',
            'param_name'  => 'link',
            'group'       => esc_html_x('Links', 'shortcode-vc-group', 'qibla-framework'),
            'description' => esc_html_x(
                'The label link for the cta. Leave blank to now add button link',
                'shortcode-vc',
                'qibla-framework'
            ),
        ),

        // Link Url.
        array(
            'heading'     => esc_html_x('Link Url', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'textfield',
            'param_name'  => 'link_url',
            'group'       => esc_html_x('Links', 'shortcode-vc-group', 'qibla-framework'),
            'description' => esc_html_x('The link url for the first label link', 'shortcode-vc', 'qibla-framework'),
            'dependency'  => array(
                'element'   => 'link',
                'not_empty' => true,
            ),
        ),

        // Link Icon.
        array(
            'heading'     => esc_html_x('Link Icon', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_icon',
            'param_name'  => 'link_icon',
            'group'       => esc_html_x('Links', 'shortcode-vc-group', 'qibla-framework'),
            'description' => esc_html_x('The icon to use in relation with the link', 'shortcode-vc', 'qibla-framework'),
            'dependency'  => array(
                'element'   => 'link',
                'not_empty' => true,
            ),
        ),

        // Link Icon Position.
        array(
            'heading'     => esc_html_x('Link Icon Position', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_radio',
            'param_name'  => 'link_icon_position',
            'group'       => esc_html_x('Links', 'shortcode-vc-group', 'qibla-framework'),
            'description' => esc_html_x(
                'The position of the icon relative to the link label',
                'shortcode-vc',
                'qibla-framework'
            ),
            'value'       => array(
                esc_html_x('After Label', 'shortcode-vc', 'qibla-framework')  => 'after',
                esc_html_x('Before Label', 'shortcode-vc', 'qibla-framework') => 'before',
            ),
            'save_always' => true,
            'dependency'  => array(
                'element'   => 'link',
                'not_empty' => true,
            ),
        ),

        /* -----------------------------------------------------------------
           Link 2
        ----------------------------------------------------------------- */

        // Link 2.
        array(
            'heading'     => esc_html_x('Link2 Label', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'textfield',
            'param_name'  => 'link_2',
            'group'       => esc_html_x('Links', 'shortcode-vc-group', 'qibla-framework'),
            'description' => esc_html_x(
                'The label link for the cta. Leave blank to now add button link',
                'shortcode-vc',
                'qibla-framework'
            ),
        ),

        // Link Url.
        array(
            'heading'     => esc_html_x('Link2 Url', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'textfield',
            'param_name'  => 'link_2_url',
            'group'       => esc_html_x('Links', 'shortcode-vc-group', 'qibla-framework'),
            'description' => esc_html_x('The link url for the first label link', 'shortcode-vc', 'qibla-framework'),
            'dependency'  => array(
                'element'   => 'link_2',
                'not_empty' => true,
            ),
        ),

        // Link Icon.
        array(
            'heading'     => esc_html_x('Link2 Icon', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_icon',
            'param_name'  => 'link_2_icon',
            'group'       => esc_html_x('Links', 'shortcode-vc-group', 'qibla-framework'),
            'description' => esc_html_x('The icon to use in relation with the link', 'shortcode-vc', 'qibla-framework'),
            'dependency'  => array(
                'element'   => 'link_2',
                'not_empty' => true,
            ),
        ),

        // Link Icon Position.
        array(
            'heading'     => esc_html_x('Link2 Icon Position', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_radio',
            'param_name'  => 'link_2_icon_position',
            'group'       => esc_html_x('Links', 'shortcode-vc-group', 'qibla-framework'),
            'description' => esc_html_x(
                'The position of the icon relative to the link label',
                'shortcode-vc',
                'qibla-framework'
            ),
            'value'       => array(
                esc_html_x('After Label', 'shortcode-vc', 'qibla-framework')  => 'after',
                esc_html_x('Before Label', 'shortcode-vc', 'qibla-framework') => 'before',
            ),
            'save_always' => true,
            'dependency'  => array(
                'element'   => 'link_2',
                'not_empty' => true,
            ),
        ),

        // Links Style.
        array(
            'heading'     => esc_html_x('Style of the links.', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'qibla_radio',
            'param_name'  => 'links_2_style',
            'group'       => esc_html_x('Links', 'shortcode-vc-group', 'qibla-framework'),
            'description' => esc_html_x('The style of the links buttons.', 'shortcode-vc', 'qibla-framework'),
            'value'       => array(
                esc_html_x('Ghost', 'shortcode-vc', 'qibla-framework')  => 'ghost',
                esc_html_x('Filled', 'shortcode-vc', 'qibla-framework') => 'fill',
            ),
            'save_always' => true,
            'dependency'  => array(
                'element'   => 'link',
                'not_empty' => true,
            ),
        ),
    ),
);
