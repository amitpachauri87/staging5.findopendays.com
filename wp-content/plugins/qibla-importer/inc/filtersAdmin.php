<?php

use QiblaImporter\Admin\Functions as Af;
use QiblaImporter\Plugin;
use QiblaImporter\Scripts\Scripts;
use QiblaImporter\Requirements;
use QiblaImporter\Admin;
use QiblaImporter\Functions as F;

/**
 * Admin Filters
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

defined('WPINC') || die;

// Build the requirements.
$req = new Requirements(include Plugin::getPluginDirPath('/inc/requirements.php'));
// Admin Scripts.
$scripts = new Scripts(include Plugin::getPluginDirPath('/inc/scripts.php'));

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
        'filter' => array(
            /**
             * Events Demo view
             *
             * @since ${SINCE}
             */
            array(
                'filter'        => 'qibla_importer_exclude_demo_view',
                'callback'      => function ($bool, $demoSlug) {
                    if ('events' === $demoSlug && ! F\isEventsPluginActive()) {
                        $bool = 'yes';
                    }

                    return $bool;
                },
                'priority'      => 20,
                'accepted_args' => 2,
            ),
        ),
        'action' => array(
            /**
             * Requirements Notices
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'admin_notices',
                'callback' => array(
                    new Admin\Notices\NoticeList(
                        '<strong>' . esc_html__('Qibla Warnings', 'qibla-importer') . '</strong>',
                        $req->check(),
                        'error'
                    ),
                    'notice',
                ),
                'priority' => 20,
            ),

            /*[
                'filter'   => 'tgmpa_register',
                'callback' => [__NAMESPACE__ . '\\TGMRegister', 'registerRequiredPlugins'],
                'priority' => 20,
            ],*/

            /**
             * Register/Enqueue/Localize Scripts
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'admin_enqueue_scripts',
                'callback' => array($scripts, 'register'),
                'priority' => 20,
            ),
            array(
                'filter'   => 'admin_enqueue_scripts',
                'callback' => array($scripts, 'enqueuer'),
                'priority' => 30,
            ),

            /**
             * Menu Pages
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'admin_menu',
                'callback' => array(
                    new Admin\Page\Register(array(
                        new Admin\Page\Import(),
                    )),
                    'addMenuPages',
                ),
                'priority' => 20,
            ),

            /**
             * Import content
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'wp_loaded',
                'callback' => function () {
                    if (
                        'qb-importer' === filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING) &&
                        ! wp_doing_ajax()
                    ) {
                        $controllerInstance = new \QiblaImporter\Importer\Controller();
                        $controllerInstance->import();
                    }
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'admin_init',
                'callback' => function () {
                    add_action('wp_ajax_import_demo', function () {
                        $ajaxControllerInstance = new \QiblaImporter\Importer\AjaxController();
                        $ajaxControllerInstance->import();
                    });
                },
                'priority' => 0,
            ),

            /**
             * Svg Loader
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'admin_footer',
                'callback' => function () {
                    if (Af\isImporterPage()) {
                        QiblaImporter\Functions\svgLoaderTmpl();
                    }
                },
                'proprity' => 20,
            ),
        ),
    ),
);
