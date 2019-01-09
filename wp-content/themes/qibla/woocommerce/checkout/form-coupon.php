<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

use Qibla\Functions as F;

if ( ! wc_coupons_enabled() || ! empty( WC()->cart->applied_coupons ) ) { // @codingStandardsIgnoreLine.
    return;
}

if (empty(WC()->cart->applied_coupons)) :

    $info_message = apply_filters(
        'woocommerce_checkout_coupon_message',
        esc_html__('Have a coupon?', 'qibla') . ' <a href="#" class="showcoupon button">' .
        esc_html__('Click here to enter your code', 'qibla') . '</a>'
    );
    ?>
    <div class="woocommerce-form-coupon-toggle">
        <?php wc_print_notice($info_message, 'notice'); ?>
    </div>
<?php endif; ?>

<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none">

    <div class="coupon">
        <label for="coupon_code" class="screen-reader-text">
            <?php esc_html_e('Coupon:', 'qibla'); ?>
        </label>

        <input type="text"
               name="coupon_code"
               class="coupon-code"
               id="coupon_code"
               value=""
               placeholder="<?php esc_attr_e('Coupon code', 'qibla'); ?>"/>

        <div <?php F\scopeClass('apply-coupon-button-wrapper') ?>>
            <button type="submit"
                    class="button"
                    name="apply_coupon"
                    value="<?php esc_attr_e( 'Apply Coupon', 'qibla'); ?>">
                <?php esc_html_e( 'Apply Coupon', 'qibla'); ?>
            </button>
        </div>
    </div>
</form>
