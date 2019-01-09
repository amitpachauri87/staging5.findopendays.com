<?php
/**
 * Remove Wc Filters List
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

return array(
    'action' => array(

        /*
         * Before Main Content
         *
         * - remove breadcrumb @since 1.1.0
         */
        array(
            'filter'   => 'woocommerce_before_main_content',
            'callback' => 'woocommerce_breadcrumb',
            'priority' => 20,
        ),

        /*
         * Before Shop Loop
         *
         * - remove result count     @since 1.1.0
         * - remove catalog ordering @since 1.1.0
         */
        array(
            'filter'   => 'woocommerce_before_shop_loop',
            'callback' => 'woocommerce_result_count',
            'priority' => 20,
        ),
        array(
            'filter'   => 'woocommerce_before_shop_loop',
            'callback' => 'woocommerce_catalog_ordering',
            'priority' => 30,
        ),

        /*
         * Shop Loop
         *
         * - remove pagination @since 1.1.0
         */
        array(
            // Replaced with archivePaginationTmpl.
            'filter'   => 'woocommerce_after_shop_loop',
            'callback' => 'woocommerce_pagination',
            'priority' => 10,
        ),

        /*
         * Categories
         *
         * Remove the subcategories Hooks and implements custom one
         * to make the article coherent with the theme terms shortcode.
         *
         * @since 1.1.0
         */
        array(
            'filter'   => 'woocommerce_before_subcategory',
            'callback' => 'woocommerce_template_loop_category_link_open',
            'priority' => 10,
        ),
        array(
            'filter'   => 'woocommerce_before_subcategory_title',
            'callback' => 'woocommerce_subcategory_thumbnail',
            'priority' => 10,
        ),
        array(
            'filter'   => 'woocommerce_shop_loop_subcategory_title',
            'callback' => 'woocommerce_template_loop_category_title',
            'priority' => 10,
        ),
        array(
            'filter'   => 'woocommerce_after_subcategory_title',
            'callback' => 'woocommerce_after_subcategory_title',
            'priority' => 10,
        ),
        array(
            'filter'   => 'woocommerce_after_subcategory',
            'callback' => 'woocommerce_template_loop_category_link_close',
            'priority' => 10,
        ),

        /**
         * Taxonomies
         *
         * - Remove Archive Description from taxonomy page @since 2.0.0
         * - Remove Archive Description from products page @since 2.0.0
         */
        array(
            'filter'   => 'woocommerce_archive_description',
            'callback' => 'woocommerce_taxonomy_archive_description',
            'priority' => 10,
        ),
        array(
            'filter'   => 'woocommerce_archive_description',
            'callback' => 'woocommerce_product_archive_description',
            'priority' => 10,
        ),

        /*
         * Loop Product
         *
         * - remove the link open      @since 1.1.0
         * - remove the link close     @since 1.1.0
         * - remove the post thumbnail @since 1.1.0
         * - remove the rating         @since 1.1.0
         * - remove the price          @since 1.1.0
         * - remove the add to cart    @since 1.1.0
         */
        array(
            'filter'   => 'woocommerce_before_shop_loop_item',
            'callback' => 'woocommerce_template_loop_product_link_open',
            'priority' => 10,
        ),
        array(
            'filter'   => 'woocommerce_after_shop_loop_item',
            'callback' => 'woocommerce_template_loop_product_link_close',
            'priority' => 10,
        ),
        array(
            // Replaced with wcTemplateLoopProductThumbnail().
            'filter'   => 'woocommerce_before_shop_loop_item_title',
            'callback' => 'woocommerce_template_loop_product_thumbnail',
            'priority' => 10,
        ),
        array(
            // Replaced with thePostTitleTmpl.
            'filter'   => 'woocommerce_shop_loop_item_title',
            'callback' => 'woocommerce_template_loop_product_title',
            'priority' => 10,
        ),
        array(
            'filter'   => 'woocommerce_after_shop_loop_item_title',
            'callback' => 'woocommerce_template_loop_rating',
            'priority' => 5,
        ),
        array(
            'filter'   => 'woocommerce_after_shop_loop_item_title',
            'callback' => 'woocommerce_template_loop_price',
            'priority' => 10,
        ),

        /*
         * Single Product
         *
         * - remove the rating template @since 1.1.0
         * - remove the meta            @since 1.1.0
         */
        array(
            'filter'   => 'woocommerce_single_product_summary',
            'callback' => 'woocommerce_template_single_rating',
            'priority' => 10,
        ),
        array(
            'filter'   => 'woocommerce_single_product_summary',
            'callback' => 'woocommerce_template_single_meta',
            'priority' => 40,
        ),

        /**
         * Reviews
         *
         * - remove the thumbnail of the commenter @since 1.2.0
         */
        array(
            'filter'   => 'woocommerce_review_before',
            'callback' => 'woocommerce_review_display_gravatar',
            'priority' => 10,
        ),
        array(
            'filter'   => 'woocommerce_review_before_comment_meta',
            'callback' => 'woocommerce_review_display_rating',
            'priority' => 10,
        ),

        /*
         * My Account
         *
         * - remove the navigation @since 1.1.0
         */
        array(
            'filter'   => 'woocommerce_account_navigation',
            'callback' => 'woocommerce_account_navigation',
            'priority' => 10,
        ),

        /*
         * Sidebar
         *
         * - remove the sidebar @since 1.1.0
         */
        array(
            'filter'   => 'woocommerce_sidebar',
            'callback' => 'woocommerce_get_sidebar',
            'priority' => 10,
        ),

        /*
         * Cart
         *
         * - remove the cart totals @since 1.1.0
         */
        array(
            'filter'   => 'woocommerce_cart_collaterals',
            'callback' => 'woocommerce_cart_totals',
            'priority' => 10,
        ),
    ),
    'filter' => array(),
);
