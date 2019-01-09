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
    'name'        => esc_html_x('Qibla Button', 'shortcode-vc', 'qibla-framework'),
    'base'        => $this->tag,
    'category'    => esc_html_x('Qibla', 'shortcode-vc', 'qibla-framework'),
    'description' => esc_html_x('Custom Button', 'shortcode-vc', 'qibla-framework'),
    'icon'        => \QiblaFramework\Plugin::getPluginDirUrl('/assets/imgs/vc-sc-icon.png'),
    'params'      => array(
        // Label.
        array(
            'heading'     => esc_html_x('Label', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'textfield',
            'param_name'  => 'label',
            'save_always' => true,
            'description' => esc_html_x('The label of the button', 'shortcode-vc', 'qibla-framework'),
        ),

        // Url.
        array(
            'heading'     => esc_html_x('Url', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'textfield',
            'param_name'  => 'url',
            'save_always' => true,
        ),

        // Style.
        array(
            'heading'     => esc_html_x('Style', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'dropdown',
            'param_name'  => 'style',
            'save_always' => true,
            'value'       => array(
                esc_html_x('Tiny', 'shortcode-vc', 'qibla-framework')   => 'tiny',
                esc_html_x('Small', 'shortcode-vc', 'qibla-framework')  => 'small',
                esc_html_x('Medium', 'shortcode-vc', 'qibla-framework') => 'medium',
                esc_html_x('Wide', 'shortcode-vc', 'qibla-framework')   => 'wide',
                esc_html_x('Gray', 'shortcode-vc', 'qibla-framework')   => 'gray',
                esc_html_x('White', 'shortcode-vc', 'qibla-framework')  => 'white',
                esc_html_x('Ghost', 'shortcode-vc', 'qibla-framework')  => 'ghost',
            ),
        ),

        // Icon Before.
        array(
            'heading'    => esc_html_x('Icon Before Label', 'shortcode-vc', 'qibla-framework'),
            'type'       => 'qibla_icon',
            'param_name' => 'icon_before',
            'value'      => '',
        ),

        // Icon After.
        array(
            'heading'    => esc_html_x('Icon After Label', 'shortcode-vc', 'qibla-framework'),
            'type'       => 'qibla_icon',
            'param_name' => 'icon_after',
            'value'      => '',
        ),
    ),
);
