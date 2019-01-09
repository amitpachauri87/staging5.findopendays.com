<?php
use Qibla\Functions as F;

/**
 * View Jumbotron
 *
 * @since 1.0.0
 *
 * Copyright (C) 2016 Guido Scialfa
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

<div class="<?php echo esc_attr(F\sanitizeHtmlClass($data->class)) ?>">

    <div class="dlcontainer">
        <?php
        /**
         * Before Jumbo-tron
         *
         * @since 1.0.0
         */
        do_action('qibla_before_jumbotron');

        if ($data->title) :
            printf(
                '<p class="%s">%s</p>',
                F\getScopeClass('jumbotron', 'title'),
                F\ksesPost($data->title)
            );
        endif;

        if ($data->subtitle) : ?>
            <p <?php F\scopeClass('jumbotron', 'subtitle') ?>>
                <?php echo F\ksesPost($data->subtitle) ?>
            </p>
            <?php
        endif;

        /**
         * After Jumbo-tron
         *
         * @since 1.0.0
         */
        do_action('qibla_after_jumbotron'); ?>
    </div>

    <?php
    if (F\isHeaderVideoEligible()) :
        wp_enqueue_script('wp-custom-header');
        wp_localize_script('wp-custom-header', '_wpCustomHeaderSettings', get_header_video_settings());
        ?>
        <div id="wp-custom-header" class="wp-custom-header"></div><?php
    endif; ?>

</div>

