<?php

namespace Qibla\Functions;

use Qibla as D;

/**
 * After Setup Theme Functions
 *
 * @package    Qibla\Functions
 * @author     guido scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    GNU General Public License, version 2
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
 * After Setup Theme
 *
 * @todo  Move into a separate class and add a filter? There is the remove_theme_support().
 *
 * Add basic theme supports
 *
 * @since 1.0.0
 */
function setupTheme()
{
    // Load theme text domain.
    load_theme_textdomain('qibla', untrailingslashit(get_template_directory()) . '/languages');
    // Add title tag support.
    add_theme_support('title-tag');
    // Add custom logo support.
    add_theme_support('custom-logo', array(
        'width'  => 133,
        'height' => 53,
    ));
    // Add Custom Header.
    add_theme_support('custom-header', apply_filters('qibla_custom_header_defaults', array(
        'width'                 => 1920,
        'height'                => 1080,
        'uploads'               => false,
        'video'                 => true,
        'header-text'           => false,
        'video-active-callback' => function () {
            return is_page_template('templates/homepage.php') ||
                   is_page_template('templates/homepage-fullwidth.php') ||
                   is_page_template('templates/events-search.php');
        },
    )));
    // Add Custom Background Support.
//    add_theme_support('custom-background', apply_filters('qibla_custom_background_defaults', array()));
    // Add post thumbnail support.
    add_theme_support('post-thumbnails');
    // Feed Links Support.
    add_theme_support('automatic-feed-links');
    // Add Html5 support.
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
//    // Add post formats support.
//    add_theme_support('post-formats', [
//        'aside',
//        'gallery',
//        'link',
//        'image',
//        'quote',
//        'status',
//        'video',
//        'audio',
//        'chat',
//    ]);
    // Add excerpt to pages.
    add_post_type_support('page', 'excerpt');
    // Seo By Yoast Breadcrumb Support.
    add_theme_support('yoast-seo-breadcrumbs');
    // BreadCrumb Trail Support.
    add_theme_support('breadcrumb-trail');
    // Add support for WooCommerce.
    add_theme_support('woocommerce');
    // WooCommerce 3.0.x need this to enable photoswipe, zoom and slider.
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    // Register Menus.
    register_nav_menus(array(
        'nav_main' => esc_html__('Main Nav', 'qibla'),
    ));
    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    // Image Sizes.
    set_post_thumbnail_size(1200, 816, true);
    add_image_size('qibla_thumbnail', 150, 100, true);
    add_image_size('qibla_large', 960, 640, true);

    // This image size is set even within the framework.
    if (! has_image_size('qibla-post-thumbnail-loop')) {
        add_image_size('qibla-post-thumbnail-loop', 346, 295, true);
    }
}
