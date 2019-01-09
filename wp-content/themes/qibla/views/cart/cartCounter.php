<?php
use Qibla\Functions as F;

/**
 * Mini Cart
 *
 * @since   1.0.0
 * @package Qibla\Views\Cart
 */
?>

<div <?php F\scopeClass('cart-counter') ?>>
    <a <?php F\scopeClass('cart-counter', 'link') ?> href="<?php echo esc_url($data->cartUrl) ?>">
        <span <?php F\scopeClass('cart-counter', 'label') ?>>
            <?php echo esc_html($data->cartLabel) ?>
        </span>

        <i class="la la-shopping-cart" aria-hidden="true">
            <span <?php F\scopeClass('cart-counter', 'quantity') ?>>
                <?php echo esc_html($data->counter); ?>
            </span>
        </i>
    </a>
</div>
