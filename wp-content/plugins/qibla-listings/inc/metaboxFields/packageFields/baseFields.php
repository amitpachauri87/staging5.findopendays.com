<?php
use \QiblaFramework\Functions as Fw;
use \QiblaFramework\Form\Factories\FieldFactory;

/**
 * Package Fields
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

/**
 * Filter Listing Package Meta
 *
 * @since 1.0.0
 *
 * @param array $array The list of the fields.
 */
return apply_filters('qibla_mb_inc_listings_package', array(
    /**
     * Short description
     *
     * @since 1.0.0
     */
    'qibla_mb_listing_package_short_description:text'   => $fieldFactory->base(array(
        'type'        => 'text',
        'name'        => 'qibla_mb_listing_package_short_description',
        'label'       => esc_html__('Set Extra information about the package', 'qibla-listings'),
        'description' => esc_html__(
            'A short description that will be showed within the price table.',
            'qibla-listings'
        ),
        'attrs'       => array(
            'class' => array('widefat'),
            'value' => Fw\getPostMeta('_qibla_mb_listing_package_short_description'),
        ),
    )),

    /**
     * Highlight
     *
     * @since 1.0.0
     */
    'qibla_mb_listing_package_highlight:checkbox'       => $fieldFactory->base(array(
        'type'           => 'checkbox',
        'name'           => 'qibla_mb_listing_package_highlight',
        'value'          => Fw\getPostMeta('_qibla_mb_listing_package_highlight', 'no'),
        'label'          => esc_html__('Package Highlight', 'qibla-listings'),
        'description'    => esc_html__(
            'If you want to highlight this package like a featured package.',
            'qibla-listings'
        ),
        'style'          => 'toggler',
        'label_position' => 'after',
    )),

    /**
     * Additional Info
     *
     * @since 1.0.0
     */
    'qibla_mb_listing_package_additional_info:textarea' => $fieldFactory->base(array(
        'type'        => 'textarea',
        'name'        => 'qibla_mb_listing_package_additional_info',
        'value'       => Fw\getPostMeta('_qibla_mb_listing_package_additional_info'),
        'label'       => esc_html__('Set Extra information about the package', 'qibla-listings'),
        'description' => esc_html__(
            'Set one info per line like: icon-check|Allowed for 30 days. Get icons name from https://icons8.com/line-awesome by removing the la- prefixes.',
            'qibla-listings'
        ),
        'attrs'       => array(
            'rows'        => 8,
            'placeholder' => esc_html__("icon-check|Allowed for 30 days\nicon-check|Allow Gallery"),
        ),
    )),
));
