<?php
/**
 * Additional Info Fields
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

use QiblaFramework\Functions as Fw;
use QiblaFramework\Form\Factories\FieldFactory;

$fieldFactory = new FieldFactory();
// Get the fields Values.
$fieldsValues = array(
    'business_email' => Fw\getPostMeta('_qibla_mb_business_email', '', $post),
    'business_phone' => Fw\getPostMeta('_qibla_mb_business_phone', '', $post),
    'site_url'       => Fw\getPostMeta('_qibla_mb_site_url', '', $post),
);

/**
 * Listing Form Fields
 *
 * @since 1.0.0
 *
 * @param array $args The list of the fields
 */
return apply_filters('qibla_listings_form_additional_info_fields', array(
    /**
     * Featured Allowed
     *
     * @since 1.0.0
     */
    'qibla_listing_form-meta-is_featured:hidden'   => $fieldFactory->base(array(
        'type'        => 'hidden',
        'restriction' => 'allow_featured',
        'name'        => 'qibla_listing_form-meta-is_featured',
        'attrs'       => array(
            'value' => 'on',
        ),
    )),

    /**
     * Business Email
     *
     * @since 1.0.0
     */
    'qibla_listing_form-meta-business_email:email' => $fieldFactory->base(array(
        'type'                => 'email',
        'restriction'         => 'allow_business_email',
        'name'                => 'qibla_listing_form-meta-business_email',
        'label'               => esc_html__('Your Open Day event email', 'qibla-events'),
        'invalid_description' => esc_html__('Please provide a valid email address.', 'qibla-events'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') and $obj->getInvalidDescription();
        },
        'attrs'               => array(
            'placeholder' => esc_html_x('Es. joe.doe@gmail.com', 'placeholder', 'qibla-events'),
            'value'       => $fieldsValues['business_email'],
        ),
    )),

    /**
     * Business Phone
     *
     * @since 1.0.0
     */
    'qibla_listing_form-meta-business_phone:tel'   => $fieldFactory->base(array(
        'type'                => 'tel',
        'restriction'         => 'allow_business_phone',
        'name'                => 'qibla_listing_form-meta-business_phone',
        'label'               => esc_html__('Your Open Day event phone number', 'qibla-events'),
        'invalid_description' => esc_html__('Please enter valid phone number.', 'qibla-events'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') and $obj->getInvalidDescription();
        },
        'attrs'               => array(
            'placeholder' => esc_html_x('+44 111 222 333', 'placeholder', 'qibla-events'),
            'value'       => $fieldsValues['business_phone'],
        ),
    )),

    /**
     * WebSite url
     *
     * @since 1.0.0
     */
    'qibla_listing_form-meta-site_url:url'         => $fieldFactory->base(array(
        'type'                => 'url',
        'restriction'         => 'allow_website_url',
        'name'                => 'qibla_listing_form-meta-site_url',
        'label'               => esc_html__('Open Day website URL', 'qibla-events'),
        'invalid_description' => esc_html__(
            'Please enter a valid url address. Do not exclude http/s',
            'qibla-events'
        ),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') and $obj->getInvalidDescription();
        },
        'attrs'               => array(
            'placeholder' => esc_html_x('https://www.website.com', 'placeholder', 'qibla-events'),
            'value'       => $fieldsValues['site_url'],
        ),
    )),
), $fieldsValues, $post);
