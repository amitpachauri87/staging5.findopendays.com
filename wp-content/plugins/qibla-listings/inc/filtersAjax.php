<?php
/**
 * Ajax Filters
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

return apply_filters('qibla_listings_ajax_filters', array(
    'ajax' => array(
        'action' => array(
            /**
             * Process Listing Form
             *
             * Execute it before the template_redirect where is hooked the Director.
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaListings\\Front\\ListingForm\\RequestAjax::handleFilter',
                'priority' => 0,
            ),
            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaListings\\Front\\ListingForm\\RequestFileHandlerAjax::handleFilter',
                'priority' => 0,
            ),
            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaListings\\Front\\ListingForm\\RequestAjaxAfterSubmit::handleFilter',
                'priority' => 1,
            ),
            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaListings\\Crud\\Request\\RequestPostDeleteAjax::handleFilter',
                'priority' => PHP_INT_MAX,
            ),
        ),
        'filter' => array(),
    ),
));
