<?php
use QiblaWcListings\Plugin;
use QiblaWcListings\Requirements;
use QiblaWcListings\Admin;

/**
 * Admin Filters
 *
 * @since     1.0.0
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2017, Guido Scialfa
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
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

defined('WPINC') || die;

// Build the requirements.
$req = new Requirements(include Plugin::getPluginDirPath('/inc/requirements.php'));

/**
 * Ex.
 * 'context' => [
 *      'type' => [
 *          [
 *              'filter'             => 'filter_name',
 *              'callback'           => theCallback,
 *              'priority'           => Number,
 *              'accepted_arguments' => Number
 *          ],
 *      ],
 * ],
 */
return array(
    'admin' => array(
        'filter' => array(),
        'action' => array(
            /**
             * Requirements Notice
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'admin_notices',
                'callback' => array(
                    new Admin\Notice\NoticeList(
                        '<strong>' . esc_html__('Qibla Warnings', 'qibla-woocommerce-listings') . '</strong>',
                        $req->check(),
                        'error'
                    ),
                    'notice',
                ),
                'priority' => 20,
            ),

            /**
             * Additional Field-sets
             *
             * Additional field-sets for Listings Meta-box
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'qibla_metabox_set_fieldsets_listing_option',
                'callback' => array(
                    '\\QiblaWcListings\\Admin\\MetaboxFieldset\\ProductFieldset',
                    'hookFieldsetsFramework',
                ),
                'priority' => 20,
            ),
        ),
    ),
);
