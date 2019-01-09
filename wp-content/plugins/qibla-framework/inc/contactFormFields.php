<?php
/**
 * Email Author Fields
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

// Get Factory.
$fieldFactory = new FieldFactory();

/**
 *
 */
return apply_filters('qibla_fw_contact_form_fields', array(
    /**
     * Name
     *
     * @since 1.5.0
     */
    'qibla_contact_form-name:text'        => $fieldFactory->base(array(
        'type'                => 'text',
        'name'                => 'qibla_contact_form-name',
        'label'               => esc_html_x('Your Name', 'contact_form', 'qibla-framework'),
        'invalid_description' => esc_html_x('Only letters and numbers', 'contact_form', 'qibla-framework'),
        'attrs'               => array(
            'required'    => 'required',
            'placeholder' => esc_html_x('Es. Joe Doe', 'contact_form', 'qibla-framework'),
        ),
    )),

    /**
     * Email
     *
     * @since 1.5.0
     */
    'qibla_contact_form-email:email'      => $fieldFactory->base(array(
        'type'                => 'email',
        'name'                => 'qibla_contact_form-email',
        'label'               => esc_html_x('Your Email', 'contact_form', 'qibla-framework'),
        'invalid_description' => esc_html_x('Invalid email format.', 'contact_form', 'qibla-framework'),
        'attrs'               => array(
            'required'    => 'required',
            'placeholder' => esc_html_x('Es. joe.doe@gmail.com', 'contact_form', 'qibla-framework'),
        ),
    )),

    /**
     * Content
     *
     * @since 1.5.0
     */
    'qibla_contact_form-content:textarea' => $fieldFactory->base(array(
        'type'                => 'textarea',
        'name'                => 'qibla_contact_form-content',
        'label'               => esc_html_x('Message', 'contact_form', 'qibla-framework'),
        'invalid_description' => esc_html_x('Only plain text is allowed.', 'contact_form', 'qibla-framework'),
        'attrs'               => array(
            'required' => 'required',
            'rows'     => 6,
        ),
    )),

    /**
     * Submit
     *
     * @since 1.5.0
     */
    'qibla_contact_form-submit:submit'    => $fieldFactory->base(array(
        'type'  => 'submit',
        'name'  => 'qibla_contact_form-submit',
        'attrs' => array(
            'value' => esc_html_x('Send', 'contact_form', 'qibla-framework'),
        ),
    )),
));
