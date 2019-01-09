<?php
/**
 * heromap
 *
 * @since      2.4.0
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

if ($data && 'on' === $data->active) :
    $height     = isset($data->height) && null !== $data->height ? $data->height : '600';
    $postType   = isset($data->postType) && 'none' !== $data->postType ? $data->postType : 'listings';
    $categories = isset($data->categories) && 'none' !== $data->categories ? $data->categories : '';
    $locations  = isset($data->locations) && 'none' !== $data->locations ? $data->locations : '';

    echo '<div class="dlheromap">';

    echo do_shortcode(
        '[dl_maps 
        post_type="' . $postType . '" 
        listing_categories="' . $categories . '" 
        locations="' . $locations . '" 
        height="' . $height . 'px"]'
    );

    if (! $data->searchDisable || 'off' === $data->searchDisable) {
        echo '<div class="dlheromap__search">';

        switch ($postType) {
            case 'listings':
                $searchType = \QiblaFramework\Functions\getThemeOption('search', 'type', true);
                echo do_shortcode('[dl_search search_type="' . $searchType . '"]');
                break;
            case 'events':
                $searchType = \QiblaFramework\Functions\getThemeOption('events', 'search-type', true);
                echo do_shortcode('[dl_ev_search search_type="' . $searchType . '"]');
                break;
            default:
                break;
        }

        echo '</div></div>';
    }

endif;
