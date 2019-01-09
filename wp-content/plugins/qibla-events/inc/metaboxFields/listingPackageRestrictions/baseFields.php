<?php
/**
 * Listings Base Restrictions Fields
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    GNU General Public License, version 2
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

use QiblaFramework\Functions as Fw;

// Get the instance of the Field Factory.
$fieldFactory = new \QiblaFramework\Form\Factories\FieldFactory();

/**
 * Filter Restrictions Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the restrictions meta-box fields.
 */
return apply_filters('qibla_mb_inc_base_restrictions_fields', array(
    /**
     * Restriction Duration
     *
     * @since 1.0.0
     */
    'qibla_listings_mb_restriction_duration:number'            => $fieldFactory->base(array(
        'type'        => 'number',
        'name'        => 'qibla_listings_mb_restriction_duration',
        'label'       => esc_html__('Visibility Duration', 'qibla-events'),
        'description' => esc_html__(
            'Visibility is expressed in days since the listing is published. Then the post will be set as Pending. -1 means no expiration.',
            'qibla-events'
        ),
        'attrs'       => array(
            'value' => Fw\getPostMeta('_qibla_listings_mb_restriction_duration', -1),
            'min'   => -1,
            'max'   => 364,
        ),
    )),

    /**
     * Restriction allow subtitle
     *
     * @since 1.0.0
     */
    'qibla_listings_mb_restriction_allow_sub_title:checkbox'           => $fieldFactory->base(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_listings_mb_restriction_allow_sub_title',
        'label'       => esc_html__('Allow subtitle', 'qibla-events'),
        'value'       => Fw\getPostMeta(
            '_qibla_listings_mb_restriction_allow_sub_title',
            'on'
        ),
        'description' => esc_html__('To allow or not to have a subtitle', 'qibla-events'),
    )),
));
