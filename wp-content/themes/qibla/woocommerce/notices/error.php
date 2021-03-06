<?php
/**
 * Show error messages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/notices/error.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

use Qibla\Functions as F;

if ($messages) : ?>
        <?php foreach ($messages as $message) : ?>
        <div class="woocommerce-error" role="alert">
            <p class="woocommerce-error__content">
                <?php echo F\ksesPost($message); ?>
            </p>
        </div>
        <?php endforeach; ?>
    <?php
endif;