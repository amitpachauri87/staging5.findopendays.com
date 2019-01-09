<?php

/**
 * Alert Visual Composer Mapper
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
    'name'        => esc_html_x('Qibla Alert', 'shortcode-vc', 'qibla-framework'),
    'base'        => $this->tag,
    'class'       => esc_html_x('Alert Shortcode', 'shortcode-vc', 'qibla-framework'),
    'category'    => esc_html_x('Qibla', 'shortcode-vc', 'qibla-framework'),
    'description' => esc_html_x('Alert message box', 'shortcode-vc', 'qibla-framework'),
    'icon'        => \QiblaFramework\Plugin::getPluginDirUrl('/assets/imgs/vc-sc-icon.png'),
    'params'      => array(
        // Alert Style Type.
        array(
            'heading'     => esc_html_x('Alert Style', 'shortcode-vc', 'qibla-framework'),
            'description' => esc_html_x('Select the type of the alert to show.', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'dropdown',
            'param_name'  => 'type',
            'save_always' => true,
            'value'       => array(
                esc_html_x('Success', 'shortcode-vc', 'qibla-framework') => 'success',
                esc_html_x('Info', 'shortcode-vc', 'qibla-framework')    => 'info',
                esc_html_x('Warning', 'shortcode-vc', 'qibla-framework') => 'warning',
                esc_html_x('Error', 'shortcode-vc', 'qibla-framework')   => 'error',
            ),
        ),

        // Message Content.
        array(
            'heading'     => esc_html_x('Content', 'shortcode-vc', 'qibla-framework'),
            'description' => esc_html_x('The alert content', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'textarea',
            'param_name'  => 'content',
        ),
    ),
);
