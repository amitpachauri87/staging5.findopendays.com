<?php
/**
 * FrontEnd Filters
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

use QiblaFramework\Functions as Fw;
use QiblaFramework\ListingsContext\Context;
use QiblaWcListings\Functions as F;
use QiblaWcListings\Front\WooCommerceTemplate;
use QiblaWcListings\Front\Element\ElementFacade;

return array(
    'front' => array(
        'action' => array(
            /*
             * Add WooCommerce notices to the single listings post
             * Only if the current post has the product associated.
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'qibla_before_single_listing_loop_entry_content',
                'callback' => function () {
                    if (Fw\getPostMeta('_qibla_mb_wc_listings_products', false)) {
                        F\isWooCommerceActive() && wc_print_notices();
                    }
                },
                'priority' => 10,
            ),

            /*
             * WooCommerce Product in single listing
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'qibla_before_listings_sidebar',
                'callback' => function () {
                    if (Context::isSingleListings()) {
                        try {
                            $facadeInstance = new ElementFacade(get_post());
                            $facadeInstance->getCart();
                        } catch (\Exception $e) {
                            // @todo Add Debug
                            // @todo Need a destruct?
                        }
                    }
                },
                // Before the sidebar elements by theme and framework.
                'priority' => 10,
            ),

            /*
             * WooCommerce add to cart on Booking Listing
             *
             * Add the post id if singular post listing to able to add and retrieve the listing id from cart
             * when needed.
             *
             * @since 1.2.0
             */
            array(
                'filter'   => 'woocommerce_after_add_to_cart_button',
                'callback' => 'QiblaWcListings\\Form\\ListingPostHiddenField::addPostHiddenFieldFilter',
                'priority' => 20,
            ),
            array(
                'filter'        => 'woocommerce_add_cart_item',
                'callback'      => 'QiblaWcListings\\Front\\AddListingToCartOnBooking::addListingToCartOnBookingFilter',
                'priority'      => 20,
            ),
        ),
        'filter' => array(
            /*
             * Add Product price to listing article
             *
             * - Apply the scope attribute @since 1.0.0
             */
            array(
                'filter'        => array(
                    'qibla_scope_attribute',
                    'qibla_fw_scope_attribute',
                ),
                'callback'      => '\\QiblaWcListings\\Front\\ListingRelatedProductLoopHook::scopeClassFilter',
                'priority'      => 20,
                'accepted_args' => 5,
            ),

            /*
             * Extra
             *
             * - Extra body Classes @since 1.0.0
             */
            array(
                'filter'   => 'body_class',
                'callback' => '\\QiblaWcListings\\Functions\\bodyClass',
                'priority' => 30,
            ),

            /*
             * WooCommerce Custom Templates
             *
             * @since 1.0.0
             */
            array(
                'filter'        => 'wc_get_template',
                'callback'      => array(new WooCommerceTemplate(), 'filterTemplate'),
                'priority'      => 20,
                'accepted_args' => 5,
            ),

            /*
             * Add Listing Author email to the list of the recipient when a new booking order is created.
             *
             * @since 1.2.0
             */
            array(
                'filter'        => array(
                    'woocommerce_email_recipient_new_order',
                    'woocommerce_email_recipient_new_booking'
                ),
                'callback'      => 'QiblaWcListings\\Email\\FilterListingAuthorRecipientToOrderEmails::filterRecipientFilter',
                'priority'      => 20,
                'accepted_args' => 2,
            ),
        ),
    ),
);
