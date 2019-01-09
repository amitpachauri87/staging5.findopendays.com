<?php
/**
 * Listings Socials Restrictions Fields
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
return apply_filters('qibla_mb_inc_socials_restrictions_fields', array(
    /**
     * Restriction allow Facebook
     *
     * @since 1.0.0
     */
    'qibla_listings_mb_restriction_allow_social_facebook:checkbox' => $fieldFactory->base(array(
        'type'  => 'checkbox',
        'style' => 'toggler',
        'name'  => 'qibla_listings_mb_restriction_allow_social_facebook',
        'label' => esc_html__('Allow facebook', 'qibla-events'),
        'value' => Fw\getPostMeta(
            '_qibla_listings_mb_restriction_allow_social_facebook',
            'on'
        ),
    )),

    /**
     * Restriction allow Twitter
     *
     * @since 1.0.0
     */
    'qibla_listings_mb_restriction_allow_social_twitter:checkbox' => $fieldFactory->base(array(
        'type'  => 'checkbox',
        'style' => 'toggler',
        'name'  => 'qibla_listings_mb_restriction_allow_social_twitter',
        'label' => esc_html__('Allow twitter', 'qibla-events'),
        'value' => Fw\getPostMeta(
            '_qibla_listings_mb_restriction_allow_social_twitter',
            'on'
        ),
    )),

    /**
     * Restriction allow Instagram
     *
     * @since 1.0.0
     */
    'qibla_listings_mb_restriction_allow_social_instagram:checkbox' => $fieldFactory->base(array(
        'type'  => 'checkbox',
        'style' => 'toggler',
        'name'  => 'qibla_listings_mb_restriction_allow_social_instagram',
        'label' => esc_html__('Allow instagram', 'qibla-events'),
        'value' => Fw\getPostMeta(
            '_qibla_listings_mb_restriction_allow_social_instagram',
            'on'
        ),
    )),

    /**
     * Restriction allow Linkedin
     *
     * @since 1.0.0
     */
    'qibla_listings_mb_restriction_allow_social_linkedin:checkbox' => $fieldFactory->base(array(
        'type'  => 'checkbox',
        'style' => 'toggler',
        'name'  => 'qibla_listings_mb_restriction_allow_social_linkedin',
        'label' => esc_html__('Allow linkedin', 'qibla-events'),
        'value' => Fw\getPostMeta(
            '_qibla_listings_mb_restriction_allow_social_linkedin',
            'on'
        ),
    )),

    /**
     * Restriction allow Trip Advisor
     *
     * @since 1.0.0
     */
    'qibla_listings_mb_restriction_allow_social_tripadvisor:checkbox' => $fieldFactory->base(array(
        'type'  => 'checkbox',
        'style' => 'toggler',
        'name'  => 'qibla_listings_mb_restriction_allow_social_tripadvisor',
        'label' => esc_html__('Allow tripadvisor', 'qibla-events'),
        'value' => Fw\getPostMeta(
            '_qibla_listings_mb_restriction_allow_social_tripadvisor',
            'on'
        ),
    )),
));
