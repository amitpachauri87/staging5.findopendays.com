<?php
/**
 * Search Visual Composer Map
 *
 * @since      1.1.0
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

return array(
    'name'        => esc_html_x('Qibla Events Search', 'shortcode-vc', 'qibla-events'),
    'base'        => $this->tag,
    'class'       => esc_html_x('Search Shortcode', 'shortcode-vc', 'qibla-events'),
    'category'    => esc_html_x('Qibla', 'shortcode-vc', 'qibla-events'),
    'description' => esc_html_x('The Qibla Search engine as shortcode.', 'shortcode-vc', 'qibla-events'),
    'icon'        => \QiblaFramework\Plugin::getPluginDirUrl('/assets/imgs/vc-sc-icon.png'),
    'params'      => array(
        // Search Type.
        array(
            'heading'     => esc_html_x('Search Type', 'shortcode-vc', 'qibla-events'),
            'type'        => 'dropdown',
            'param_name'  => 'search_type',
            'save_always' => true,
            'description' => esc_html_x('Select the type of the search to show.', 'shortcode-sc', 'qibla-events'),
            'value'       => array(
                esc_html_x('Simple Search', 'shortcode-vc', 'qibla-events')                  => 'simple',
                esc_html_x('Dates and Categories', 'shortcode-vc', 'qibla-events')           => 'dates',
                esc_html_x('Search and Geocode', 'shortcode-vc', 'qibla-events')             => 'geocoded',
                esc_html_x('Search, Geocode and Categories', 'shortcode-vc', 'qibla-events') => 'combo',
            ),
        ),
    ),
);
