<?php
/**
 * Listing Package Related Fields
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

use QiblaFramework\Form\Factories\FieldFactory;
use QiblaFramework\Functions as Fw;

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

/**
 * Filter Package Products List
 *
 * @since 1.0.0
 *
 * @param array $array The list of the fields.
 */
return apply_filters('qibla_mb_inc_listings_package_related', array(
    /**
     * Related Package
     *
     * @since 1.0.0
     */
    'qibla_mb_listing_package_related:select' => $fieldFactory->base(array(
        'type'        => 'select',
        'name'        => 'qibla_mb_listing_package_related',
        'value'       => Fw\getPostMeta('_qibla_mb_listing_package_related'),
        'label'       => esc_html__('Package Post', 'qibla-listings'),
        'select2'     => true,
        'description' => esc_html__('Select what package must be related with this listing.', 'qibla-listings'),
        'options'     => array_merge(
            array('none' => esc_html__('None', 'qibla-framework')),
            Fw\getPostList('listing_package')
        ),
        'display'     => array($this, 'displayField'),
        'attrs'       => array(
            'class' => array('dlselect2--wide'),
        ),
    )),
));
