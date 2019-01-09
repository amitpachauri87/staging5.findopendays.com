<?php
/**
 * Login Form Fields
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

$fieldFactory = new FieldFactory();

return array(
    /**
     * Email
     *
     * @since 1.5.0
     */
    'qibla_login_form-username_email:text' => $fieldFactory->base(array(
        'type'                => 'text',
        'name'                => 'qibla_login_form-username_email',
        'label'               => esc_html__('Username or Email', 'qibla-framework'),
        'invalid_description' => esc_html__('Email or username not valid', 'qibla-framework'),
        'attrs'               => array(
            'required' => 'required',
        ),
    )),

    /**
     * Password
     *
     * @since 1.5.0
     */
    'qibla_login_form-password:password'   => $fieldFactory->base(array(
        'type'                => 'password',
        'name'                => 'qibla_login_form-password',
        'label'               => esc_html__('Your Password', 'qibla-framework'),
        'invalid_description' => esc_html__('Password is not valid', 'qibla-framework'),
        'attrs'               => array(
            'required' => 'required',
        ),
    )),

    /**
     * User Remember
     *
     * @since 1.5.0
     */
    'qibla_login_form-remember:checkbox'   => $fieldFactory->base(array(
        'type'           => 'checkbox',
        'name'           => 'qibla_login_form-remember',
        'label_position' => 'after',
        'label'          => esc_html__('Keep me signed in', 'qibla-framework'),
    )),

    /**
     * Submit Login
     *
     * @since 1.5.0
     */
    'qibla_login_form-submit:submit'       => $fieldFactory->base(array(
        'type'           => 'submit',
        'name'           => 'qibla_login_form-submit',
        'label_position' => 'after',
        'attrs'          => array(
            'value' => esc_html__('Log In', 'qibla-framework'),
        ),
    )),
);