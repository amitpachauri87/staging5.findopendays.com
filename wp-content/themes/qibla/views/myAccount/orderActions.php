<?php
use Qibla\Functions as F;

/**
 * My Account Order Actions
 *
 * @since   1.0.0
 * @package Qibla\Views\MyAccount
 */
?>

<ul class="order-actions__list">
    <?php foreach ($data->actions as $key => $value) : ?>
        <li class="order-actions__item order-actions__item--<?php echo esc_html(F\sanitizeHtmlClass($key)) ?>">
            <a href="<?php echo esc_url($value['url']) ?>">
                <?php echo esc_html($value['name']) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
