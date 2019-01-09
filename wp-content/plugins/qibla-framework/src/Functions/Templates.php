<?php
/**
 * Map Templates
 *
 * @license GPL 2
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

namespace QiblaFramework\Functions;

use QiblaFramework\TemplateEngine\Engine as TEngine;
use QiblaFramework\ListingsContext\Context;

/**
 * Map Marker
 *
 * @since 1.0.0
 *
 * @return void
 */
function markerTmpl()
{
    $engine = new TEngine('map_template_marker', new \stdClass(), '/views/map/marker.php');
    /**
     * Filter engine template for change template in other post type archive.
     *
     * @since 2.5.1
     */
    $engine = apply_filters('qibla_engine_map_template_marker', $engine);
    $engine->render();
}

/**
 * Map Marker Clusterer
 *
 * @since 1.0.0
 *
 * @return void
 */
function markerClustererTmpl()
{
    $engine = new TEngine('map_template_marker_clusterer', new \stdClass(), '/views/map/markerClusterer.php');
    $engine->render();
}

/**
 * Map Marker InfoBox
 *
 * @since 1.0.0
 *
 * @return void
 */
function infoBoxTmpl()
{
    $engine = new TEngine('map_template_infobox', new \stdClass(), '/views/map/infoWindow.php');

    /**
     * Filter engine template for change template in other post type archive.
     *
     * @since 2.3.0
     */
    $engine = apply_filters('qibla_engine_map_template_infobox', $engine);
    $engine->render();
}

/**
 * Add Listings Map Templates
 *
 * @since 1.0.0
 *
 * @return void
 */
function mapTmpls()
{
    if (! isListingsArchive() && ! Context::isSingleListings() && ! wp_script_is('dlmap-listings', 'enqueued')) {
        return;
    }

    markerTmpl();
    markerClustererTmpl();
    infoBoxTmpl();
}

/**
 * Togglers Templates
 *
 * @since 1.0.0
 *
 * @return void
 */
function togglersTmpls()
{
    if (! isListingsArchive() && ! Context::isSingleListings()) {
        return;
    }

    $engine = new TEngine('toggler_filters', new \stdClass(), '/views/togglers.php');
    $engine->render();
}
