<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       3.2.0
 */

use Qibla\Functions as F;

if ($order) :
    $show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();

    if ($order->has_status('failed')) : ?>

        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
            <?php esc_html_e(
                'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.',
                'qibla'
            ); ?>
        </p>

        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
            <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay">
                <?php esc_html_e('Pay', 'qibla') ?>
            </a>
            <?php if (is_user_logged_in()) : ?>
                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button pay">
                    <?php esc_html_e('My Account', 'qibla'); ?>
                </a>
            <?php endif; ?>
        </p>

    <?php else : ?>

        <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
            <?php echo apply_filters(
                'woocommerce_thankyou_order_received_text',
                esc_html__('Thank you. Your order has been received.', 'qibla'),
                $order
            ); ?>
        </p>

        <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
            <li class="order">
                <?php esc_html_e('Order Number:', 'qibla'); ?>
                <strong>
                    <?php echo $order->get_order_number(); ?>
                </strong>
            </li>
            <li class="date">
                <?php esc_html_e('Date:', 'qibla'); ?>
                <strong>
                    <?php echo wc_format_datetime($order->get_date_created()); ?>
                </strong>
            </li>
            <li class="total">
                <?php esc_html_e('Total:', 'qibla'); ?>
                <strong>
                    <?php echo $order->get_formatted_order_total(); ?>
                </strong>
            </li>
            <?php if ($order->get_payment_method_title()) : ?>
                <li class="woocommerce-order-overview__payment-method method">
                    <?php esc_html_e('Payment Method:', 'qibla'); ?>
                    <strong>
                        <?php echo F\ksesPost($order->get_payment_method_title()); ?>
                    </strong>
                </li>
            <?php endif; ?>
        </ul>

        <?php
        /**
         * After Order Details list
         *
         * @since 1.0.0
         */
        do_action('qibla_after_thankyou_order_details_list', $order);

    endif;

    do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id());
    do_action('woocommerce_thankyou', $order->get_id()); ?>

    <ul class="dlorder-footer">
        <li class="dlorder-footer__item dlpayment-method dlpayment-method--<?php echo esc_attr($order->get_payment_method()) ?>">
            <?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
        </li>

        <li class="dlorder-footer__item dlorder-customer">
            <?php
            // Moved from order-details.php.
            if ($show_customer_details) :
                wc_get_template('order/order-details-customer.php', array('order' => $order));
            endif;
            ?>
        </li>

        <li class="dlorder-footer__item dlorder-customer-addresses">
            <div class="dlorder-customer-addresses--billing">
                <h2><?php esc_html_e('Billing Address', 'qibla'); ?></h2>
                <address>
                    <?php echo ($address = $order->get_formatted_billing_address()) ?
                        $address :
                        __('N/A', 'qibla');
                    ?>
                </address>
            </div>

            <?php if (! wc_ship_to_billing_address_only() && $order->needs_shipping_address()) : ?>
                <div class="dlorder-customer-addresses--shipping">
                    <h2><?php esc_html_e('Shipping Address', 'qibla'); ?></h2>
                    <address>
                        <?php echo ($address = $order->get_formatted_shipping_address()) ?
                            $address :
                            __('N/A', 'qibla');
                        ?>
                    </address>
                </div>
            <?php endif; ?>
        </li>
    </ul>

<?php else : ?>
    <p class="woocommerce-thankyou-order-received">
        <?php echo apply_filters(
            'woocommerce_thankyou_order_received_text',
            esc_html__('Thank you. Your order has been received.', 'qibla'),
            null
        ); ?>
    </p>
    <?php
endif; ?>
