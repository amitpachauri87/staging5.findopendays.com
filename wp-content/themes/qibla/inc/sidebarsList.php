<?php
use Qibla\Functions as F;

/**
 * Sidebars Lists
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

$list = array(
    'blog'         => array(
        'name'        => esc_html__('Blog Sidebar', 'qibla'),
        'description' => esc_html__('This sidebar will appear in blog archives.', 'qibla'),
    ),
    'page'         => array(
        'name'        => esc_html__('Page Sidebar', 'qibla'),
        'description' => esc_html__('This sidebar will appear within the pages.', 'qibla'),
    ),
    'footer-row-1' => array(
        'name'         => esc_html__('Footer Row 1', 'qibla'),
        'description'  => esc_html__('This sidebar will appear at the footer of the site.', 'qibla'),
        'before_title' => '<h5 class="' . F\getSidebarClass('sidebar') . '__widget__title">',
        'after_title'  => '</h5>',
    ),
);

// Additional WooCommerce Sidebars.
if (F\isWooCommerceActive()) {
    $list = array_merge($list, array(
        'myaccount' => array(
            'name'        => esc_html__('My Account Sidebar', 'qibla'),
            'description' => esc_html__('This sidebar will appear within the WooCommerce My Account pages.', 'qibla'),
        ),
        'shop'      => array(
            'name'        => esc_html__('Shop Sidebar', 'qibla'),
            'description' => esc_html__('This sidebar will appear within the shop pages.', 'qibla'),
        ),
        'product' => array(
            'name'        => esc_html__('Single Product Sidebar', 'qibla'),
            'description' => esc_html__('This sidebar will appear within the single product page', 'qibla'),
        ),
    ));
}

return $list;
