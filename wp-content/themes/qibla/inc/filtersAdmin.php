<?php
use Qibla\Admin\TGMRegister;

/**
 * Admin Filters List
 *
 * @since     1.0.0
 * @author    guido scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2016, Guido Scialfa
 * @license   GNU General Public License, version 2
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

/*
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
        'action' => array(
            /**
             * Requirements
             *
             * Test minimum requirements on theme activation
             *
             * @since 1.0.0
             */
            array(
                'filter'        => 'after_switch_theme',
                'callback'      => array('Qibla\\Activation', 'checkRequirements'),
                'priority'      => 0,
                'accepted_args' => 2,
            ),
            /**
             * TGMRegister
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'tgmpa_register',
                'callback' => array(new TGMRegister(), 'registerRequiredPlugins'),
                'priority' => 20,
            ),
            /**
             * Update Dynamic Css
             *
             * @since 1.4.0
             */
            array(
                'filter'        => 'upgrader_process_complete',
                'callback'      => 'Qibla\\Admin\\Upgrade\\UpdateDynamicCss::updateDynamicCssOnProcessComplete',
                'priority'      => 0,
                'accepted_args' => 2,
            ),
        ),
        'filter' => array(),
    ),
);
