<?php
/**
 * Listings Additional Info Restrictions Fields
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
return apply_filters('qibla_mb_inc_additional_info_restrictions_fields', array(
    /**
     * Restriction allow featured
     *
     * @since 1.0.0
     */
    'qibla_listings_mb_restriction_allow_featured:checkbox'       => $fieldFactory->base(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_listings_mb_restriction_allow_featured',
        'label'       => esc_html__('Allow featured', 'qibla-listings'),
        'value'       => Fw\getPostMeta(
            '_qibla_listings_mb_restriction_allow_featured',
            'on'
        ),
        'description' => esc_html__(
            'If set every listing created with this package will be set as featured.',
            'qibla-listings'
        ),
    )),

    /**
     * Restriction Open Hours
     *
     * @since 1.0.0
     */
    'qibla_listings_mb_restriction_allow_open_hours:checkbox'     => $fieldFactory->base(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_listings_mb_restriction_allow_open_hours',
        'label'       => esc_html__('Allow set open hours', 'qibla-listings'),
        'value'       => Fw\getPostMeta(
            '_qibla_listings_mb_restriction_allow_open_hours',
            'on'
        ),
        'description' => esc_html__('To allow or not to set the open hours for listing.', 'qibla-listings'),
    )),

    /**
     * Restriction Allow Email Address
     *
     * @since 1.0.0
     */
    'qibla_listings_mb_restriction_allow_business_email:checkbox' => $fieldFactory->base(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_listings_mb_restriction_allow_business_email',
        'label'       => esc_html__('Allow business email address', 'qibla-listings'),
        'value'       => Fw\getPostMeta(
            '_qibla_listings_mb_restriction_allow_business_email',
            'on'
        ),
        'description' => esc_html__(
            'To allow or not to set the web site url for listing. This will allow users to contact the owner via contact form.',
            'qibla-listings'
        ),
    )),

    /**
     * Restriction Allow Business Phone
     *
     * @since 1.0.0
     */
    'qibla_listings_mb_restriction_allow_business_phone:checkbox' => $fieldFactory->base(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_listings_mb_restriction_allow_business_phone',
        'label'       => esc_html__('Allow business phone number', 'qibla-listings'),
        'value'       => Fw\getPostMeta(
            '_qibla_listings_mb_restriction_allow_business_phone',
            'on'
        ),
        'description' => esc_html__('To allow or not to set the web site url for listing.', 'qibla-listings'),
    )),

    /**
     * Restriction Allow Web site url
     *
     * @since 1.0.0
     */
    'qibla_listings_mb_restriction_allow_website_url:checkbox'    => $fieldFactory->base(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_listings_mb_restriction_allow_website_url',
        'label'       => esc_html__('Allow web site url', 'qibla-listings'),
        'value'       => Fw\getPostMeta(
            '_qibla_listings_mb_restriction_allow_website_url',
            'on'
        ),
        'description' => esc_html__('To allow or not to set the web site url for listing.', 'qibla-listings'),
    )),
));
