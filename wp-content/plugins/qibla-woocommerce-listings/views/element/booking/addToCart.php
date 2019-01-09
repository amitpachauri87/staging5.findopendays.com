<?php
/**
 * Add To Cart booking View
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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

defined('WPINC') || die;

do_action('woocommerce_before_add_to_cart_form'); ?>

<noscript>
    <?php _e('Your browser must support JavaScript in order to make a booking.', 'qibla-woocommerce-listings'); ?>
</noscript>

<div id="dlbookings-booking">
    <?php if ($data->bookingProduct->get_price_html()): ?>
        <div class="dlbookings-booking-price">
            <p class="dlbookings-booking-price__content">
                <?php echo wp_kses_post($data->bookingProduct->get_price_html()) ?>
            </p>
        </div>
    <?php endif; ?>

    <form class="cart dlbookings-form" method="post" enctype='multipart/form-data'>

        <div id="wc-bookings-booking-form" class="wc-bookings-booking-form" style="display:none">

            <?php do_action('woocommerce_before_booking_form'); ?>

            <div class="wc-bookings-booking-form__fields">
                <?php $data->bookingForm->output(); ?>
            </div>

            <?php do_action('woocommerce_before_add_to_cart_button'); ?>

            <div class="wc-bookings-booking-cost" style="display:none;"></div>
        </div>

        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($data->product->get_id()); ?>"/>

        <button type="submit"
                class="wc-bookings-booking-form-button single_add_to_cart_button button alt disabled"
                style="display:none">
            <?php echo esc_html(sanitize_text_field($data->product->single_add_to_cart_text())); ?>
            <i class="la la-long-arrow-right" aria-hidden="true"></i>
        </button>

        <?php do_action('woocommerce_after_add_to_cart_button'); ?>

    </form>

    <?php do_action('woocommerce_after_add_to_cart_form'); ?>
</div>

