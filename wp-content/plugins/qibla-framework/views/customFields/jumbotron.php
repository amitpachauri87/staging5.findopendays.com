<?php

use QiblaFramework\Functions as F;
use QiblaFramework\ListingsContext\Context;

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

/**
 * Disable jumbotron template
 *
 * @since 2.4.0
 */
if ('yes' === apply_filters('disable_jumbotron_template', 'no')) {
    return;
}

// The Slider Shortcode.
if ($data->slider->shortcode) :
    echo do_shortcode($data->slider->shortcode);
else : ?>

    <div class="<?php echo esc_attr(F\sanitizeHtmlClass($data->class)) ?>"
         data-gallerylabel="<?php echo esc_attr($data->dataLabel) ?>">

        <?php if (! Context::isSingleListings()) : ?>
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
                        esc_attr(sanitize_html_class($data->class[0] . '__title')),
                        wp_kses_post($data->title)
                    );
                endif;

                if ($data->subtitle instanceof \QiblaFramework\Template\Subtitle) :
                    $data->subtitle->tmpl($data->subtitle->getData());
                endif;

                /**
                 * After Jumbo-tron
                 *
                 * @since 1.0.0
                 */
                do_action('qibla_after_jumbotron'); ?>
            </div>
            <?php
        endif;

        if (F\isHeaderVideoEligible() && ! $data->parallaxIsEnabled) :
            wp_enqueue_script('wp-custom-header');
            wp_localize_script('wp-custom-header', '_wpCustomHeaderSettings', get_header_video_settings());
            ?>
            <div id="wp-custom-header" class="wp-custom-header"></div><?php
        endif; ?>

    </div>
    <?php
endif;