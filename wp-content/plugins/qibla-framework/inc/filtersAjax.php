<?php

use QiblaFramework\Autocomplete;
use QiblaFramework\LoginRegister\Login\RequestFormLoginAjax;
use QiblaFramework\LoginRegister\Login\LoginFormFacade;
use QiblaFramework\LoginRegister\Register\RegisterFormFacade;
use QiblaFramework\LoginRegister\Register\RequestFormRegisterAjax;
use QiblaFramework\LoginRegister\LostPassword\LostPasswordFormFacade;
use QiblaFramework\LoginRegister\LostPassword\RequestFormLostPasswordAjax;

/**
 * Ajax Filters
 *
 * @since     1.0.0
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2016, Guido Scialfa
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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

/**
 * Filter Ajax Filters
 *
 * @since 1.0.0
 *
 * @param array $array The list of the filters list
 */
return apply_filters('qibla_filters_ajax', array(
    'ajax' => array(
        'action' => array(
            /**
             * Autocomplete
             *
             * @since 1.3.0
             */
            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaFramework\\Autocomplete\\AjaxRequest::handleRequestFilter',
                'priority' => 20,
            ),

            /**
             * Login
             *
             * @todo  Wrap Register/Login/LostPassword into a Dispatcher.
             *
             * Handle the login request via ajax
             *
             * @since 1.5.0
             */
            array(
                'filter'   => 'wp_loaded',
                'callback' => function () {
                    $rff     = LoginFormFacade::getInstance();
                    $request = new RequestFormLoginAjax($rff->getForm());
                    $request->handleRequest();
                },
                'priority' => 0,
            ),

            /**
             * Register
             *
             * @todo  Wrap Register/Login/LostPassword into a Dispatcher.
             *
             * Handle the register request via ajax
             *
             * @since 1.5.0
             */
            array(
                'filter'   => 'wp_loaded',
                'callback' => function () {
                    $rff     = RegisterFormFacade::getInstance();
                    $request = new RequestFormRegisterAjax($rff->getForm());
                    $request->handleRequest();
                },
                'priority' => 0,
            ),

            /**
             * Lost Password Form Ajax
             *
             * @todo  Wrap Register/Login/LostPassword into a Dispatcher.
             *
             * @since 1.5.0
             */
            array(
                'filter'   => 'wp_loaded',
                'callback' => function () {
                    $rff     = LostPasswordFormFacade::getInstance();
                    $request = new RequestFormLostPasswordAjax($rff->getForm());
                    $request->handleRequest();
                },
                'priority' => 0,
            ),

            /**
             * Contact Form & Modal
             *
             * @since 1.5.0
             */
            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaFramework\\Front\\ContactForm\\RequestFormContactFormAjax::handleFilter',
                'priority' => 0,
            ),
            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaFramework\\Front\\ContactForm\\Modal\\RequestModalContactFormAjax::handleFilter',
                'priority' => PHP_INT_MAX,
            ),

            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaFramework\\LoginRegister\\Modal\\RequestModalLoginRegisterAjax::handleFilter',
                'priority' => PHP_INT_MAX,
            ),

            /**
             * Listings Filter
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaFramework\\Filter\\Request\\RequestFilterAjax::handleFilter',
                'priority' => 20,
            ),

            /**
             * Wishlist
             *
             * @since 2.0.0
             */
            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaFramework\\Wishlist\\Request\\RequestCrud::handleFilter',
                'priority' => 20,
            ),

            /**
             * Request Task
             *
             * @since 1.7.0
             */
            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaFramework\\Task\\Request\\RequestTask::handleRequestFilter',
                'priority' => 20,
            ),
        ),
    ),
));
