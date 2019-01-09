<?php
use Qibla\Functions as F;

/**
 * Header Main Nav
 *
 * @since   1.0.0
 * @package Qibla\Views
 */

/**
 * Before Main Nav
 *
 * @since 1.0.0
 */
do_action('qibla_before_nav_main'); ?>

    <nav <?php F\scopeID('nav-main') ?> <?php F\scopeClass('nav-main') ?>>
        <?php
        if ($data->mainHref) : ?>
            <a href="<?php echo esc_url($data->mainHref) ?>" id="jump-to-content" class="screen-reader-text">
                <?php esc_html_e('Jump to the main content', 'qibla') ?>
            </a>
            <?php
        endif;

        wp_nav_menu($data->navArgs); ?>
    </nav>

<?php
/**
 * After Main Nav
 *
 * @since 1.0.0
 */
do_action('qibla_after_nav_main');
