<?php
/**
 * Listings Media Restrictions Fields
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
return apply_filters('qibla_mb_inc_media_restrictions_fields', array(
    /**
     * Restriction allow gallery
     *
     * @since 1.0.0
     */
    'qibla_listings_mb_restriction_allow_gallery:checkbox'           => $fieldFactory->base(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_listings_mb_restriction_allow_gallery',
        'label'       => esc_html__('Allow Gallery', 'qibla-listings'),
        'value'       => Fw\getPostMeta(
            '_qibla_listings_mb_restriction_allow_gallery',
            'on'
        ),
        'description' => esc_html__('To allow or not to set images for gallery.', 'qibla-listings'),
    )),
    /**
     * Restriction max image number for gallery
     *
     * @since 1.0.0
     */
    'qibla_listings_mb_restriction_max_gallery_images_number:number' => $fieldFactory->base(array(
        'type'        => 'number',
        'name'        => 'qibla_listings_mb_restriction_max_gallery_images_number',
        'label'       => esc_html__('Max Gallery Images', 'qibla-listings'),
        'description' => esc_html__(
            'Maximum number of images that can be uploaded to use in gallery. Allow Gallery must enabled.',
            'qibla-listings'
        ),
        'attrs'       => array(
            'value' => intval(Fw\getPostMeta(
                '_qibla_listings_mb_restriction_max_gallery_images_number',
                1
            )),
            'min'   => 1,
        ),
    )),
));
