<?php
use Qibla\Post;

/**
 * Wc Filters List
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
    'front' => array(
        'action' => array(
            /*
             * WooCommerce Before Shop Loop
             *
             * - Subcategories              @since 1.1.0
             *  - WooCommerce Archive Title @since 1.1.0
             */
            array(
                'filter'   => 'woocommerce_before_shop_loop',
                'callback' => 'Qibla\\Functions\\Woocommerce\\subcategoriesTmpl',
                'priority' => 20,
            ),
            array(
                'filter'   => 'woocommerce_before_shop_loop',
                'callback' => 'Qibla\\Archive\\Title::archiveTitleFilter',
                'priority' => 30,
            ),

            /*
             * WooCommerce Archive Description
             *
             * - wrap start       @since 2.1.0
             * - result count     @since 2.1.0
             * - catalog ordering @since 2.1.0
             * - wrap end         @since 2.1.0
             */
            array(
                'filter'   => 'woocommerce_archive_description',
                'callback' => 'Qibla\\Functions\\Woocommerce\\countAndOrderWrapStart',
                'priority' => 20,
            ),
            array(
                'filter'   => 'woocommerce_archive_description',
                'callback' => 'woocommerce_result_count',
                'priority' => 25,
            ),
            array(
                'filter'   => 'woocommerce_archive_description',
                'callback' => 'woocommerce_catalog_ordering',
                'priority' => 30,
            ),
            array(
                'filter'   => 'woocommerce_archive_description',
                'callback' => 'Qibla\\Functions\\Woocommerce\\countAndOrderWrapEnd',
                'priority' => 35,
            ),

            /*
             * WooCommerce Categories
             *
             * - Show Sub Category Thumbnail @since 1.1.0
             * - Show Sub Category Title     @since 1.1.0
             */
            array(
                'filter'   => 'woocommerce_before_subcategory_title',
                'callback' => 'Qibla\\Functions\\Woocommerce\\subcategoryThumbnailTmpl',
                'priority' => 20,
            ),
            array(
                'filter'   => 'woocommerce_before_subcategory_title',
                'callback' => 'Qibla\\Functions\\Woocommerce\\subcategoryTitleTmpl',
                'priority' => 20,
            ),
        ),
        'filter' => array(
            /*
             * Shop Loop
             *
             * - Add pagination @since 1.1.0
             */
            array(
                'filter'   => 'woocommerce_after_shop_loop',
                'callback' => 'Qibla\\Functions\\archivePaginationTmpl',
                'priority' => 20,
            ),

            /*
             * Loop Product
             *
             * - Add post thumbnail   @since 1.1.0
             * - Add post title       @since 1.1.0
             * - Add price            @since 1.1.0
             */
            array(
                'filter'   => 'woocommerce_before_shop_loop_item_title',
                'callback' => 'Qibla\\Functions\\Woocommerce\\wcTemplateLoopProductThumbnail',
                'priority' => 20,
            ),
            array(
                'filter'   => 'woocommerce_shop_loop_item_title',
                'callback' => array(new Post\Title(), 'postTitleTmpl'),
                'priority' => 20,
            ),
            array(
                'filter'   => 'woocommerce_after_shop_loop_item',
                'callback' => 'woocommerce_template_loop_price',
                // Before the add to cart.
                'priority' => 9,
            ),

            /*
             * Single Product
             *
             * - Remove heading tabs @since 1.1.0
             */
            array(
                'filter'   => array(
                    'woocommerce_product_additional_information_heading',
                    'woocommerce_product_description_heading',
                ),
                'callback' => 'Qibla\\Functions\\Woocommerce\\removeSingleHeadingTabs',
                'priority' => 20,
            ),

            /*
             * Reviews
             *
             * - Reviews Title      @since 1.1.0
             * - Reviews Pagination @since 1.1.0
             */
            array(
                'filter'   => 'qibla_before_reviews_list',
                'callback' => 'Qibla\\Functions\\Woocommerce\\reviewsTitleTmpl',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_after_reviews_list',
                'callback' => 'Qibla\\Functions\\Woocommerce\\paginateReviewsLinksTmpl',
                'priority' => 20,
            ),

            /*
             * Cart
             *
             * - Move the cart totals woocommerce_after_cart_table @since 1.1.0
             * - Update Fragment Cart                              @since 1.1.0
             */
            array(
                'filter'   => 'woocommerce_after_cart_table',
                'callback' => 'woocommerce_cart_totals',
                'priority' => 30,
            ),
            array(
                'filter'   => 'woocommerce_add_to_cart_fragments',
                'callback' => 'Qibla\\Functions\\Woocommerce\\CartFragmentUpdater',
                'priority' => 10,
            ),

            /*
             * Sidebars
             *
             * - shop & tax sidebars @since 1.1.0
             * - My account sidebar  @since 1.1.0
             */
            array(
                'filter'   => 'qibla_shop_after_wrapper_main_end',
                'callback' => 'woocommerce_get_sidebar',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_before_myaccount_sidebar',
                'callback' => 'Qibla\\Functions\\Woocommerce\\myAccountSidebar',
                'priority' => 20,
            ),
        ),
    ),
);
