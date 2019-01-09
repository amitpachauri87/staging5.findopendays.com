<?php

use Qibla\Functions as F;

/**
 * Brand Logo
 *
 * @since   1.0.0
 * @package Qibla\Views
 */
?>

<div <?php F\scopeClass('brand') ?>>

    <p <?php F\scopeClass('brand', 'title') ?>>
        <a href="<?php echo esc_url(home_url('/')) ?>" <?php F\scopeClass('brand', 'link') ?>>

            <?php if ($data->logo) : ?>
                <picture>
                    <?php
                    /**
                     * Before the Brand Logo
                     *
                     * @since 1.6.0
                     *
                     * @param \stdClass $data The data used within the view
                     */
                    do_action('qibla_brand_before_logo', $data);

                    echo F\ksesImage($data->logo);

                    /**
                     * After the Brand Logo
                     *
                     * @since 1.6.0
                     *
                     * @param \stdClass $data The data used within the view
                     */
                    do_action('qibla_brand_after_logo', $data) ?>
                </picture>

                <span class="screen-reader-text">
                    <?php echo sanitize_text_field($data->site->name) ?>
                </span>
                <?php
            else :
                echo esc_html($data->site->name);
            endif; ?>

        </a>
    </p>

    <?php
    if ($data->site->description) :
        printf(
            '<p class="%1$s">%2$s</p>',
            F\getScopeClass('brand', 'description') . ' screen-reader-text ',
            esc_html(sanitize_text_field($data->site->description))
        );
    endif; ?>

</div>
