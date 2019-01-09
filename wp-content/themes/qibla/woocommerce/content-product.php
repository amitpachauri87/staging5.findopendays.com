<?php
use Qibla\Functions as F;

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
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
 * @version 3.4.0
 */

global $product;

// Ensure visibility.
if (empty($product) || ! $product->is_visible()) {
    return;
}
?>
<article id="post-<?php the_ID() ?>" <?php wc_product_class(F\getScopeClass('article', '', array('overlay', 'card', 'product'))) ?>>

    <div <?php F\scopeClass('article-card-box') ?>>
        <?php
        /*
         * woocommerce_before_shop_loop_item hook.
         */
        do_action('woocommerce_before_shop_loop_item'); ?>

        <header <?php F\scopeClass('article', 'header') ?>>

            <a <?php F\scopeClass('article', 'link') ?> href="<?php echo esc_url(get_permalink()) ?>">
                <?php
                /*
                 * woocommerce_before_shop_loop_item_title hook.
                 *
                 * @hooked woocommerce_show_product_loop_sale_flash - 10
                 */
                do_action('woocommerce_before_shop_loop_item_title');

                /*
                 * woocommerce_shop_loop_item_title hook.
                 */
                do_action('woocommerce_shop_loop_item_title');

                /*
                 * woocommerce_after_shop_loop_item_title hook.
                 */
                do_action('woocommerce_after_shop_loop_item_title'); ?>
            </a>

        </header>

        <?php
        /**
         * woocommerce_after_shop_loop_item hook.
         *
         * @hooked woocommerce_template_loop_add_to_cart - 10
         */
        do_action('woocommerce_after_shop_loop_item'); ?>
    </div>

</article>
