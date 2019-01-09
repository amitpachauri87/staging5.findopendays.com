<?php
/**
 * Taxonomy Terms Fields
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
// Retrieve the terms associated.
$postLocationsTerm = get_the_terms($post, 'locations');
$postCategoryTerm  = get_the_terms($post, 'listing_categories');
$postAmenityTerm   = get_the_terms($post, 'amenities');
// May be WP_Error or false.
$postLocationsTerm = is_wp_error($postLocationsTerm) ? array() : (array)$postLocationsTerm;
$postCategoryTerm  = is_wp_error($postCategoryTerm) ? array() : (array)$postCategoryTerm;
$postAmenityTerm   = is_wp_error($postAmenityTerm) ? array() : (array)$postAmenityTerm;

/**
 * Listing Form Fields
 *
 * @since 1.0.0
 *
 * @param array $args The list of the fields
 */
return apply_filters('qibla_listings_form_taxonomy_terms_fields', array(
    /**
     * Locations
     *
     * @since 1.0.0
     */
    'qibla_listing_form-tax-locations:select'          => $fieldFactory->base(array(
        'type'                => 'select',
        'name'                => 'qibla_listing_form-tax-locations',
        'label'               => esc_html__('Location', 'qibla-listings'),
        'container_class'     => array('dl-field', 'dl-field--select', 'dl-field--in-column'),
        'exclude_none'        => true,
        'select2'             => true,
        'select2_theme'       => 'qibla',
        'options'             => Fw\getTermsList(array('taxonomy' => 'locations', 'hide_empty' => false)),
        'value'               => isset($postLocationsTerm[0]->slug) ? $postLocationsTerm[0]->slug : '',
        'attrs'               => array(
            'required' => 'required',
        ),
        'invalid_description' => esc_html__('Please select a location.', 'qibla-listings'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
    )),

    /**
     * Category
     *
     * @since 1.0.0
     */
    'qibla_listing_form-tax-listing_categories:select' => $fieldFactory->base(array(
        'type'                => 'select',
        'name'                => 'qibla_listing_form-tax-listing_categories',
        'label'               => esc_html__('Categories', 'qibla-listings'),
        'container_class'     => array('dl-field', 'dl-field--select', 'dl-field--in-column'),
        'exclude_none'        => true,
        'select2'             => true,
        'select2_theme'       => 'qibla',
        'value'               => isset($postCategoryTerm[0]->slug) ? $postCategoryTerm[0]->slug : '',
        'options'             => Fw\getTermsList(array(
            'taxonomy'   => 'listing_categories',
            'hide_empty' => false,
        )),
        'attrs'               => array(
            'required' => 'required',
        ),
        'invalid_description' => esc_html__('Please select a category.', 'qibla-listings'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
    )),

    /**
     * Amenities
     *
     * @since 1.0.0
     */
    'qibla_listing_form-tax-amenities:multicheck'      => $fieldFactory->base(array(
        'type'                => 'multi_check',
        'name'                => 'qibla_listing_form-tax-amenities',
        'label'               => esc_html__('Amenities', 'qibla-listings'),
        'container_class'     => array('dl-field', 'dl-field--multicheck', 'dl-field--clear-in-column'),
        'exclude_all'         => true,
        'value'               => wp_list_pluck($postAmenityTerm, 'slug'),
        'options'             => Fw\getTermsList(array(
            'taxonomy'   => 'amenities',
            'hide_empty' => false,
        )),
        'invalid_description' => esc_html__('Please select at least an amenity.', 'qibla-listings'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
    )),
), array(), $post);
