<?php
/**
 * Search Visual Composer Map
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

// Retrieve the navigation elements.
$menu = wp_get_nav_menus();
if ($menu) {
    $menu = wp_list_pluck($menu, 'slug', 'name');
}

return array(
    'name'        => esc_html_x('Qibla Search', 'shortcode-vc', 'qibla-framework'),
    'base'        => $this->tag,
    'class'       => esc_html_x('Search Shortcode', 'shortcode-vc', 'qibla-framework'),
    'category'    => esc_html_x('Qibla', 'shortcode-vc', 'qibla-framework'),
    'description' => esc_html_x('The Qibla Search engine as shortcode.', 'shortcode-vc', 'qibla-framework'),
    'icon'        => \QiblaFramework\Plugin::getPluginDirUrl('/assets/imgs/vc-sc-icon.png'),
    'params'      => array(
        // Search Type.
        array(
            'heading'     => esc_html_x('Search Type', 'shortcode-vc', 'qibla-framework'),
            'type'        => 'dropdown',
            'param_name'  => 'search_type',
            'save_always' => true,
            'description' => esc_html_x('Select the type of the search to show.', 'shortcode-sc', 'qibla-framework'),
            'value'       => array(
                esc_html_x('Search and Geocode', 'shortcode-vc', 'qibla-framework')             => 'geocoded',
                esc_html_x('Simple Search', 'shortcode-vc', 'qibla-framework')                  => 'simple',
                esc_html_x('Search, Geocode and Categories', 'shortcode-vc', 'qibla-framework') => 'combo',
            ),
        ),
    ),
);
