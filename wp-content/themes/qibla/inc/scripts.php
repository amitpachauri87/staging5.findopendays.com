<?php
use Qibla\Theme;

use Qibla\Functions as F;

/**
 * Scripts List
 *
 * @package Qibla
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

// Version for scripts and styles.
$dev = ! ! ('dev' === QB_ENV);

$scripts = array(
    'scripts' => array(
        array(
            'force-js',
            Theme::getTemplateDirUrl('/assets/js/vendor/force.min.js'),
            array(),
            $dev ? time() : '',
            true,
        )
    ),
    'styles'  => array(),
);

if (! is_admin()) :
    $scripts['scripts'] = array_merge($scripts['scripts'], array(
        // Modernizr.
        'modernizr'         => array(
            'modernizr',
            Theme::getTemplateDirUrl('/assets/js/vendor/modernizr.min.js'),
            array(),
            $dev ? time() : '3.3.1',
            true,
        ),
        // Backward old Browsers.
        'dl-bw-oldbrowsers' => array(
            'dl-bw-oldbrowsers',
            Theme::getTemplateDirUrl('/assets/js/vendor/backward-oldbrowsers.min.js'),
            array(),
            $dev ? time() : '3.3.1',
            true,
        ),
        // Utils.
        'dl-utils-scripts'  => array(
            'dl-utils-scripts',
            Theme::getTemplateDirUrl('/assets/js/utils.js'),
            array('underscore', 'jquery', 'force-js'),
            $dev ? time() : '',
            true,
        ),

        // Main Navigation.
        'dl-nav-main'       => array(
            'dl-nav-main',
            Theme::getTemplateDirUrl('/assets/js/nav-main.js'),
            array('underscore'),
            $dev ? time() : '',
            true,
            function () {
                return true;
            },
            function () {
                return true;
            },
            array(
                'async' => 'async',
            ),
        ),

        // Mobile Navigation.
        'dl-mobile-nav'     => array(
            'dl-mobile-nav',
            Theme::getTemplateDirUrl('/assets/js/nav-mobile.js'),
            array('underscore', 'jquery'),
            $dev ? time() : '',
            true,
            function () {
                return true;
            },
            function () {
                return true;
            },
            array(
                'async' => 'async',
            ),
        ),

        // Masonry.
        'dl-masonry'        => array(
            'dl-masonry',
            Theme::getTemplateDirUrl('/assets/js/masonry.js'),
            array('masonry'),
            $dev ? time() : '',
            true,
            function () {
                return true;
            },
            function () {
                return F\isBlog();
            },
            array(
                'async' => 'async',
            ),
        ),

        // Wc Quantity Input.
        // Extracted from https://wordpress.org/plugins/woocommerce-quantity-increment/.
        'wcqi-js'           => array(
            'wcqi-js',
            Theme::getTemplateDirUrl('/assets/js/wc-quantity-increment.js'),
            array('underscore'),
            $dev ? time() : '',
            true,
            function () {
                return is_singular('product') || F\isCart();
            },
            function () {
                return is_singular('product') || F\isCart();
            },
            array(
                'async' => 'async',
            ),
        ),
    ));

    $scripts['styles'] = array_merge($scripts['styles'], array(
        // Google Fonts
        // Lato: 100, 200, 300, 400, 700, 900.
        'google-font'  => array(
            'google-font',
            '//fonts.googleapis.com/css?family=Lato:100,300,300i,400,700,900,900i',
        ),

        // Vendor.
        'qibla-vendor' => array(
            'qibla-vendor',
            Theme::getTemplateDirUrl('/assets/css/vendor/vendor.min.css'),
            array(),
            $dev ? time() : '',
            'screen',
        ),

        // Dropzone.
        'dropzone' => array(
            'dropzone',
            Theme::getTemplateDirUrl('/assets/css/dropzone.min.css'),
            array(),
            $dev ? time() : '',
            'screen',
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),
    ));

    // Include Dynamic Style
    // Only if the environment is the 'prod' one.
    $mainStyleUrl = file_exists(Theme::getTemplateDirPath('/assets/css/dynamic.min.css')) && 'prod' === QB_ENV ?
        '/assets/css/dynamic.min.css' :
        '/assets/css/main.min.css';

    $scripts['styles']['qibla-main'] = array(
        'qibla-main',
        Theme::getTemplateDirUrl($mainStyleUrl),
        array('qibla-vendor'),
        $dev ? time() : '',
        'screen',
    );
endif;

/**
 * Theme Scripts List
 *
 * @since 1.0.0
 *
 * @param array $scripts The list of the scripts and style to register and enqueue
 */
return apply_filters('qibla_theme_scripts_list', $scripts);