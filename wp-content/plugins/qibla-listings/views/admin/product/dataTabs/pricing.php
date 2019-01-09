<?php
/**
 * Listing price Tab View
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
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

?>

<div id="listing_pricing_product_data" class="panel woocommerce_options_panel">
    <div class="options_group pricing show_if_listing hidden">
        <?php
        woocommerce_wp_text_input(array(
            'id'        => '_listing_regular_price',
            'value'     => $product_object->get_regular_price('edit'),
            'label'     => esc_html__('Regular price', 'qibla-listings') . ' (' . get_woocommerce_currency_symbol() . ')',
            'data_type' => 'price',
        ));

        woocommerce_wp_text_input(array(
            'id'          => '_listing_sale_price',
            'value'       => $product_object->get_sale_price('edit'),
            'data_type'   => 'price',
            'label'       => esc_html__('Sale price', 'qibla-listings') . ' (' . get_woocommerce_currency_symbol() . ')',
            'description' => '<a href="#" class="sale_schedule">' . __('Schedule', 'qibla-listings') . '</a>',
        ));

        $sale_price_dates_from = '';
        $sale_price_dates_to   = '';
        if ($product_object->get_date_on_sale_from('edit')) {
            $date                  = $product_object->get_date_on_sale_to('edit')->getOffsetTimestamp();
            $sale_price_dates_from = $date ? date_i18n('Y-m-d', $date) : '';
            $sale_price_dates_to   = $date ? date_i18n('Y-m-d', $date) : '';
        }

        echo '<p class="form-field sale_price_dates_fields">
                    <label for="listing_sale_price_dates_from">' . __('Sale price dates', 'qibla-listings') . '</label>
                    <input type="text" class="short" name="_listing_date_on_sale_from" id="listing_sale_price_dates_from" value="' .
             esc_attr($sale_price_dates_from) . '" placeholder="' .
             esc_html_x('From&hellip;', 'placeholder', 'qibla-listings') .
             ' YYYY-MM-DD" maxlength="10" pattern="' .
             esc_attr(apply_filters(
                 'woocommerce_date_input_html_pattern',
                 '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])')) .
             '" /><input type="text" class="short" name="__listing_date_on_sale_to" id="listing_sale_price_dates_to" value="' .
             esc_attr($sale_price_dates_to) . '" placeholder="' .
             esc_html_x('To&hellip;', 'placeholder', 'qibla-listings') .
             '  YYYY-MM-DD" maxlength="10" pattern="' .
             esc_attr(apply_filters('woocommerce_date_input_html_pattern',
                 '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])')) .
             '" /><a href="#" class="description cancel_sale_schedule">' .
             esc_html__('Cancel', 'qibla-listings') . '</a>' .
             wc_help_tip(esc_html__('The sale will end at the beginning of the set date.', 'qibla-listings')) . '</p>';

        do_action('woocommerce_product_options_pricing');
        ?>
    </div>
</div>
