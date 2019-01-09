<?php
/**
 * View Amenities Groups
 *
 * @since 2.4.0
 *
 * Copyright (C) 2018 Alfio Piccione
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

use QiblaFramework\Functions as F;

?>

<div <?php F\scopeClass('listing-main-section') ?>>
    <div <?php F\scopeClass('container', '', 'flex') ?>>
        <div <?php F\scopeClass('terms-section') ?>>
            <?php if ($data->title && false === $data->haveGroups) : ?>
                <header <?php F\scopeClass('terms-list', 'header') ?>>
                    <h5 <?php F\scopeClass('terms-list', 'title') ?>>
                        <?php echo esc_html(sanitize_text_field($data->title)) ?>
                    </h5>
                </header>
            <?php endif; ?>
            <?php if ($data->list) : ?>
                <ul class="terms-list">
                    <?php foreach ($data->list as $value => $items) : ?>
                        <?php if (is_array($items) && array_key_exists('title', $items)) :
                            foreach ($items as $key => $subList) : ?>
                                <?php if ('title' === $key) : ?>
                                    <li class="groups-list-item__title">
                                    <span><?php echo esc_html($subList); ?></span>
                                    <ul class="groups-list__sublist">
                                <?php else : ?>
                                    <li class="terms-list__item">
                                        <i class="<?php echo esc_attr(F\sanitizeHtmlClass($subList['icon'])) ?>"></i>
                                        <a <?php F\scopeClass('terms-list', 'item-label') ?>
                                                href="<?php echo esc_url($subList['href']) ?>">
                                            <?php echo esc_html(sanitize_text_field($subList['label'])) ?>
                                        </a>
                                    </li>
                                <?php endif;
                            endforeach; ?>
                            </ul></li>
                        <?php else : ?>
                            <li class="terms-list__item">
                                <i class="<?php echo esc_attr(F\sanitizeHtmlClass($items['icon'])) ?>"></i>
                                <a <?php F\scopeClass('terms-list', 'item-label') ?>
                                        href="<?php echo esc_url($items['href']) ?>">
                                    <?php echo esc_html(sanitize_text_field($items['label'])) ?>
                                </a>
                            </li>
                        <?php endif;
                    endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
