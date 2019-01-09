<?php

use QiblaFramework\Functions as F;

/**
 * View Testimonial Short-code
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

<div class="dlsc-testimonial">

    <div class="dlsc-testimonial__list <?php echo($data->useSlider ? esc_attr(F\sanitizeHtmlClass($data->sliderClass)) : ''); ?>">
        <?php foreach ($data->posts as $post) :
            $noAvarar = empty($post->thumbnail) ? 'no-avatar' : '';
            $modifier = sanitize_title(F\sanitizeHtmlClass(strtolower($post->title))); ?>
            <div class="dlsc-testimonial__item dlsc-testimonial__item--<?php echo esc_attr($modifier) ?>">
                <?php if (! empty($post->thumbnail['src'])) : ?>
                    <header class="dlsc-testimonial__header">
                        <h2 class="dlsc-testimonial__title">
                            <?php echo esc_html($post->title) ?>
                        </h2>

                        <figure class="dlsc-testimonial__thumbnail">
                            <img src="<?php echo esc_url($post->thumbnail['src']) ?>"
                                 width="<?php echo intval($post->thumbnail['width']) ?>"
                                 height="<?php echo intval($post->thumbnail['height']) ?>"
                                 alt="<?php echo esc_html($post->thumbnail['alt']) ?>"
                            />
                        </figure>

                        <?php if ($post->rating) : ?>
                            <div class="dlsc-testimonial__rating">
                            <span style="width:<?php echo((floatval($post->rating) / 5) * 100) ?>%">
                                <strong itemprop="ratingValue" class="rating">
                                    <?php echo floatval($post->rating); ?>
                                </strong>
                                <?php printf(esc_html__('out of %1$s5%2$s', 'dramlist'), '<span>', '</span>'); ?>
                            </span>
                            </div>
                        <?php endif; ?>
                    </header>
                <?php endif; ?>

                <?php if ($post->content) : ?>
                    <div class="dlsc-testimonial__content <?php echo esc_attr($noAvarar); ?>">
                        <?php echo F\ksesPost($post->content) ?>
                    </div>
                    <h2 class="dlsc-testimonial__title <?php echo esc_attr($noAvarar); ?>">
                        <?php echo esc_html($post->title) ?>
                        <?php if ($post->rating) : ?>
                            <div class="dlsc-testimonial__rating">
                                <span style="width:<?php echo((floatval($post->rating) / 5) * 100) ?>%">
                                    <strong itemprop="ratingValue" class="rating">
                                        <?php echo floatval($post->rating); ?>
                                    </strong>
                                    <?php printf(esc_html__('out of %1$s5%2$s', 'dramlist'), '<span>', '</span>'); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </h2>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

</div>
