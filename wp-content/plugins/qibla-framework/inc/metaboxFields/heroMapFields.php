<?php
/**
 * heroMapsFields.php
 *
 * @since      2.4.0
 * @package    ${NAMESPACE}
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

use \QiblaFramework\Functions as F;
use \QiblaFramework\Form\Factories\FieldFactory;

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

$types    = new \QiblaFramework\ListingsContext\Types();
$postType = array();

foreach ($types->types() as $type) {
    $postType[$type] = $type;
}

$locations = array(
    'listing' => F\getTermsList(array(
        'taxonomy'   => 'locations',
        'hide_empty' => false,
    )),
);

if (taxonomy_exists('event_locations')) {
    $locations['event'] = F\getTermsList(array(
        'taxonomy'   => 'event_locations',
        'hide_empty' => false,
    ));
}

$categories = array(
    'listing' => F\getTermsList(array(
        'taxonomy'   => 'listing_categories',
        'hide_empty' => false,
    )),
);

if (taxonomy_exists('event_categories')) {
    $categories['event'] = F\getTermsList(array(
        'taxonomy'   => 'event_categories',
        'hide_empty' => false,
    ));
}

return apply_filters('qibla_mb_inc_heromap_fields', array(
    /**
     * Active Hero Map
     *
     * @since 2.4.0
     */
    'qibla_mb_heromap_active:checkbox'          => $fieldFactory->base(array(
        'type'        => 'checkbox',
        'name'        => 'qibla_mb_heromap_active',
        'value'       => F\getPostMeta('_qibla_mb_heromap_active', 'off'),
        'label'       => esc_html__('Active Hero Map', 'qibla-framework'),
        'description' => esc_html__('If you want to active the Hero Map but keep data', 'qibla-framework'),
        'display'     => array($this, 'displayField'),
    )),

    /**
     * Active Hero Map Search
     *
     * @since 2.4.0
     */
    'qibla_mb_heromap_search_disable:checkbox'          => $fieldFactory->base(array(
        'type'        => 'checkbox',
        'name'        => 'qibla_mb_heromap_search_disable',
        'value'       => F\getPostMeta('_qibla_mb_heromap_search_disable', 'off'),
        'label'       => esc_html__('Disable Search bar', 'qibla-framework'),
        'description' => esc_html__('If you want to disable the search in Hero Map', 'qibla-framework'),
        'display'     => array($this, 'displayField'),
    )),

    /**
     * Hero Map post type
     *
     * @since 2.4.0
     */
    'qibla_mb_heromap_filter_post_type:select'  => $fieldFactory->base(array(
        'type'        => 'select',
        'select2'     => true,
        'name'        => 'qibla_mb_heromap_filter_post_type',
        'value'       => F\getPostMeta('_qibla_mb_heromap_filter_post_type', 'none'),
        'options'     => $postType,
        'label'       => esc_html__('Choose a post type', 'qibla-framework'),
        'description' => esc_html__(
            'Select a post type (default: listings)',
            'qibla-framework'
        ),
        'display'     => array($this, 'displayField'),
        'attrs'       => array(
            'class' => array('widefat', 'dlselect2--wide'),
        ),
    )),

    /**
     * Hero Map Filter for Location
     *
     * @since 2.4.0
     */
    'qibla_mb_heromap_filter_locations:select'  => $fieldFactory->base(array(
        'type'        => 'select',
        'select2'     => true,
        'name'        => 'qibla_mb_heromap_filter_locations',
        'value'       => F\getPostMeta('_qibla_mb_heromap_filter_locations', 'none'),
        'options'     => ! empty($locations) ? $locations : array(),
        'label'       => esc_html__('Choose a location', 'qibla-framework'),
        'description' => esc_html__(
            'Select a location if you want to filter the map pins (filter by location)',
            'qibla-framework'
        ),
        'display'     => array($this, 'displayField'),
        'attrs'       => array(
            'class' => array('widefat', 'dlselect2--wide'),
        ),
    )),

    /**
     * Hero Map Filter for Category
     *
     * @since 2.4.0
     */
    'qibla_mb_heromap_filter_categories:select' => $fieldFactory->base(array(
        'type'        => 'select',
        'select2'     => true,
        'name'        => 'qibla_mb_heromap_filter_categories',
        'value'       => F\getPostMeta('_qibla_mb_heromap_filter_categories', 'none'),
        'options'     => ! empty($categories) ? $categories : array(),
        'label'       => esc_html__('Choose a category', 'qibla-framework'),
        'description' => esc_html__(
            'Select a category if you want to filter the map pins (filter by category)',
            'qibla-framework'
        ),
        'display'     => array($this, 'displayField'),
        'attrs'       => array(
            'class' => array('widefat', 'dlselect2--wide'),
        ),
    )),

    /**
     * Hero Map Height
     *
     * @since 2.4.0
     */
    'qibla_mb_heromap_min_height:number'        => $fieldFactory->base(array(
        'type'        => 'number',
        'name'        => 'qibla_mb_heromap_min_height',
        'attrs'       => array(
            'value' => F\getPostMeta('_qibla_mb_heromap_min_height'),
        ),
        'label'       => esc_html__('Min Height', 'qibla-framework'),
        'description' => esc_html__(
            'Set the minimum height for the hero map. Set 0 or less to not set. Unit in px. (default: 600px)',
            'qibla-framework'
        ),
        'display'     => array($this, 'displayField'),
    )),
));