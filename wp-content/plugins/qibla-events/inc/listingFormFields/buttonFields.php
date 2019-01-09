<?php
/**
 * buttonFields
 *
 * @since      1.0.0
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

use QiblaFramework\Functions as Fw;
use QiblaFramework\Form\Factories\FieldFactory;

$fieldFactory = new FieldFactory();
$fieldsValues = array(
    'url'    => Fw\getPostMeta('_qibla_mb_button_url', '', $post),
    'text'   => Fw\getPostMeta('_qibla_mb_button_text', '', $post),
    'target' => Fw\getPostMeta('_qibla_mb_target_link', '', $post),
);

/**
 * Listing Form Button Fields
 *
 * @since 1.0.0
 *
 * @param array $args The list of the fields
 */
return apply_filters('qibla_listings_form_button_fields', array(
    /**
     * Button url
     *
     * @since 1.0.0
     */
    'qibla_listing_form-meta-button_url:url'     => $fieldFactory->base(array(
        'type'                => 'url',
        'name'                => 'qibla_listing_form-meta-button_url',
        'label'               => esc_html__('Button url', 'qibla-events'),
        'container_class'     => array('dl-field', 'dl-field--url', 'dl-field--in-column'),
        'invalid_description' => esc_html__('Please enter valid url.', 'qibla-events'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
        'attrs'               => array(
            'placeholder' => esc_html_x('http://www.mywebsite.com/opendays', 'placeholder', 'qibla-events'),
            'value'       => $fieldsValues['url'],
        ),
    )),

    /**
     * Button text
     *
     * @since 1.0.0
     */
    'qibla_listing_form-meta-button_text:text'   => $fieldFactory->base(array(
        'type'                => 'text',
        'name'                => 'qibla_listing_form-meta-button_text',
        'label'               => esc_html__('Button text', 'qibla-events'),
        'container_class'     => array('dl-field', 'dl-field--text', 'dl-field--in-column'),
        'invalid_description' => esc_html__('Please enter label for button.', 'qibla-events'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
        'attrs'               => array(
            'placeholder' => esc_html_x('3 Dec-More Info', 'placeholder', 'qibla-events'),
            'value'       => $fieldsValues['text'],
        ),
    )),

    /**
     * Button Target
     *
     * @since 1.2.1
     */
    'qibla_listing_form-meta-target_link:checkbox' => $fieldFactory->base(array(
        'type'            => 'checkbox',
        'style'           => 'toggler',
        'name'            => 'qibla_listing_form-meta-target_link',
        'label'           => esc_html__('Button target', 'qibla-events'),
        'description'     => esc_html__('Make this as internal link (default: external)', 'qibla-events'),
        'container_class' => array('dl-field', 'dl-field--target', 'dl-field--clear-in-column'),
        'value'           => $fieldsValues['target'],
    )),

), $fieldsValues, $post);