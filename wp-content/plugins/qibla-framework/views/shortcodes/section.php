<?php

use QiblaFramework\Functions as F;

/**
 * View Section Short-code
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

$style = F\implodeAssoc(';', ':', $data->style);
$style = $style ? ' style="' . $style . '"' : '';
?>

<section <?php F\scopeClass('sc-section', '', $data->scopeClassModifiers);
echo $style; ?>>
    <div class="dlcontainer">

        <?php if ($data->sectionTitle || $data->sectionSubTitle) : ?>
            <header class="dlsc-section__header">
                <h2 class="dlsc-section__title">
                    <?php
                    // @codingStandardsIgnoreLine
                    echo F\ksesPost($data->sectionTitle) ?>
                </h2>
                <?php if ($data->sectionSubTitle) : ?>
                    <p class="dlsc-section__subtitle">
                        <?php
                        // @codingStandardsIgnoreLine
                        echo F\ksesPost($data->sectionSubTitle) ?>
                    </p>
                <?php endif; ?>
            </header>
        <?php endif; ?>

        <?php if ($data->content) : ?>
            <div class="dlsc-section__content">
                <?php
                // @codingStandardsIgnoreLine
                echo F\ksesPost($data->content) ?>
            </div>
        <?php endif; ?>

        <?php
        if ($data->links[0]['linkUrl'] || $data->links[1]['linkUrl']) : ?>
            <p class="dlsc-section__link-wrapper">
                <?php foreach ($data->links as $link) :
                    if ($link && $link['linkUrl']) : ?>
                        <a href="<?php echo esc_url($link['linkUrl']) ?>"
                           class="<?php echo esc_attr(F\sanitizeHtmlClass($link['linkClass'])) ?>">

                            <?php if ('before' === $link['linkIconPosition']) : ?>
                                <i class="<?php echo esc_attr(F\sanitizeHtmlClass($link['linkIconClass'])) ?>"></i>
                                <?php
                            endif;

                            echo esc_html(sanitize_text_field($link['link']));

                            if ('after' === $link['linkIconPosition']) : ?>
                                <i class="<?php echo esc_attr(F\sanitizeHtmlClass($link['linkIconClass'])) ?>"></i>
                            <?php endif; ?>
                        </a>
                        <?php
                    endif;
                endforeach;
                ?>
            </p>
        <?php endif; ?>
    </div>
</section>
