<?php

use Qibla\Functions as F;
use Qibla\Theme;

/**
 * TGMPA Plugins List
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
 * Array of plugin arrays. Required keys are name and slug.
 * If the source is NOT from the .org repo, then source is also required.
 *
 * [
 *      'name'     => 'PluginName',
 *      'slug'     => 'plugin-slug',
 *      'required' => true|false
 * ]
 */
return array(
    // Qibla Framework.
    array(
        'name'               => esc_html__('Qibla Framework', 'qibla'),
        'slug'               => 'qibla-framework',
        'source'             => Theme::getTemplateDirPath('/libs/plugins/qibla-framework.zip'),
        'required'           => true,
        'version'            => '2.5.1',
        'force_activation'   => false,
        'force_deactivation' => true,
        'source_type'        => 'bundled',
    ),

    // Qibla Importer.
    array(
        'name'               => esc_html__('Qibla Importer', 'qibla'),
        'slug'               => 'qibla-importer',
        'source'             => Theme::getTemplateDirPath('/libs/plugins/qibla-importer.zip'),
        'required'           => false,
        'version'            => '1.2.7',
        'force_deactivation' => true,
        'source_type'        => 'bundled',
    ),

    // WooCommerce.
    array(
        'name'     => esc_html__('WooCommerce', 'qibla'),
        'slug'     => 'woocommerce',
        'required' => false,
        'version'  => '3.4.x',
    ),

    // Qibla Listings.
    array(
        'name'               => esc_html__('Qibla Listings', 'qibla'),
        'slug'               => 'qibla-listings',
        'source'             => Theme::getTemplateDirPath('/libs/plugins/qibla-listings.zip'),
        'required'           => false,
        'version'            => '2.4.1',
        'force_deactivation' => true,
        'source_type'        => 'bundled',
    ),

    // Qibla Events.
    array(
        'name'               => esc_html__('Qibla Events', 'qibla'),
        'slug'               => 'qibla-events',
        'source'             => Theme::getTemplateDirPath('/libs/plugins/qibla-events.zip'),
        'required'           => false,
        'version'            => '1.2.1',
        'force_deactivation' => true,
        'source_type'        => 'bundled',
    ),

    // Qibla WooCommerce Listings.
    array(
        'name'               => esc_html__('Qibla WooCommerce Listings', 'qibla'),
        'slug'               => 'qibla-woocommerce-listings',
        'source'             => Theme::getTemplateDirPath('/libs/plugins/qibla-woocommerce-listings.zip'),
        'required'           => false,
        'version'            => '1.2.4',
        'force_deactivation' => true,
        'source_type'        => 'bundled',
    ),

    // Envato Market WordPress Toolkit.
    array(
        'name'               => esc_html__('Envato Auto Updater', 'qibla'),
        'slug'               => 'envato-market',
        'source'             => Theme::getTemplateDirPath('/libs/plugins/envato-market.zip'),
        'required'           => false,
        'version'            => '1.0.0-RC2',
        'force_deactivation' => false,
        'source_type'        => 'bundled',
    ),

    // WP Social Login.
    array(
        'name'     => esc_html__('WP Social Login', 'qibla'),
        'slug'     => 'wordpress-social-login',
        'required' => false,
    ),

    // Visual Composer.
    array(
        'name'        => esc_html__('Visual Composer', 'qibla'),
        'source'      => Theme::getTemplateDirPath('/libs/plugins/js-composer.zip'),
        'version'     => '5.5.2',
        'slug'        => 'js_composer',
        'required'    => false,
        'source_type' => 'bundled',
    ),

    // Sassy Social Share.
    array(
        'name'        => esc_html__('Sassy Social Share', 'qibla'),
        'source'      => 'https://it.wordpress.org/plugins/sassy-social-share/',
        'version'     => '3.2.1',
        'slug'        => 'sassy-social-share',
        'required'    => false,
        'source_type' => 'repo',
    ),

    // Opening Hours.
    array(
        'name'        => esc_html__('Opening Hours', 'qibla'),
        'source'      => 'https://it.wordpress.org/plugins/wp-opening-hours/',
        'version'     => '2.1.3',
        'slug'        => 'wp-opening-hours',
        'required'    => false,
        'source_type' => 'repo',
    ),

    // Revolution Slider.
    array(
        'name'        => esc_html__('Revolution Slider', 'qibla'),
        'source'      => Theme::getTemplateDirPath('/libs/plugins/revslider.zip'),
        'version'     => '5.4.6.4',
        'slug'        => 'revslider',
        'required'    => false,
        'source_type' => 'bundled',
    ),
);
