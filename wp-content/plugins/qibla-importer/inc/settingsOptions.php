<?php
/**
 * Settings Options
 *
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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

return array(
    // WordPress.
    'posts_per_page'                       => 10,
    'thread_comments_depth'                => 2,
    'show_on_front'                        => 'page',

    // WooCommerce.
    'woocommerce_enable_review_rating'     => 'yes',
    'woocommerce_shop_page_display'        => '',
    'woocommerce_category_archive_display' => '',
    'woocommerce_default_catalog_orderby'  => 'menu_order',
    'shop_catalog_image_size'              => array(
        'width'  => 346,
        'height' => 295,
        'crop'   => 1,
    ),
    'shop_single_image_size'               => array(
        'width'  => 600,
        'height' => 600,
        'crop'   => 1,
    ),
    'shop_thumbnail_image_size'            => array(
        'width'  => 180,
        'height' => 180,
        'crop'   => 1,
    ),
);
