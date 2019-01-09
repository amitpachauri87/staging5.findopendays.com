<?php
/**
 * Manager Template View
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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

use QiblaFramework\Functions as Fw;

$headings = array(
    'listing' => esc_html_x('Listing', 'account-posts-manager', 'qibla-listings'),
    'type'    => esc_html_x('Type', 'account-posts-manager', 'qibla-listings'),
    'status'  => esc_html_x('Status', 'account-posts-manager', 'qibla-listings'),
    'actions' => esc_html_x('Actions', 'account-posts-manager', 'qibla-listings'),
);

if ($data->posts) : ?>
    <table <?php Fw\scopeClass('manager-posts') ?>>
        <thead>
        <tr>
            <?php foreach ($headings as $key => $val) : ?>
                <th class="<?php echo esc_attr($key); ?>">
                    <span class="nobr">
                        <?php echo esc_html(sanitize_text_field($val)); ?>
                    </span>
                </th>
            <?php endforeach; ?>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($data->posts as $post) :
            $postContainerClass = array(
                Fw\getScopeClass('manager-posts-post'),
                $post->isFeatured ? Fw\getScopeClass('manager-posts-post', '', 'featured') : '',
            );
            ?>
            <tr class="<?php echo Fw\sanitizeHtmlClass($postContainerClass) ?>">

                <td <?php Fw\scopeClass('manager-posts-post', 'info') ?>>
                    <div <?php Fw\scopeClass('manager-posts-post', 'thumbnail') ?>>
                        <?php echo Fw\ksesImage($post->thumbnail) ?>
                    </div>

                    <div <?php Fw\scopeClass('manager-posts-post', 'title') ?>>
                        <a href="<?php echo esc_url($post->permalink) ?>">
                            <?php echo esc_html(sanitize_text_field($post->postTitle)) ?>
                        </a>
                        <span>
                            <?php printf(
                                esc_html_x('Expiry date: %s', 'manager-posts', 'qibla-listings'),
                                '<b>' . esc_html($post->expirationDate) . '</b>'
                            ); ?>
                        </span>
                    </div>
                </td>

                <td <?php Fw\scopeClass('manager-posts-post', 'type') ?>>
                    <?php echo ucfirst(esc_html(sanitize_text_field($post->postType))); ?>
                </td>

                <td <?php Fw\scopeClass('manager-posts-post', 'status') ?>>
                    <p <?php Fw\scopeClass('post-status', '', sanitize_title($post->status)) ?>>
                    <span>
                        <?php echo esc_html(sanitize_text_field($post->status)) ?>
                    </span>
                    </p>
                </td>

                <td <?php Fw\scopeClass('manager-posts-post', 'actions') ?>>
                    <?php if (isset($post->actions)) :
                        $post->actions->tmpl($post->actions->getData());
                    endif; ?>
                </td>
            </tr>
            <?php
        endforeach; ?>
        </tbody>
    </table>
    <?php
else : ?>
    <div <?php Fw\scopeClass('manager-posts', '', 'no-listings') ?>>
        <h3 <?php Fw\scopeClass('manager-posts', 'title') ?>>
            <?php echo esc_html_x('There are no listings right now.', 'manager-posts', 'qibla-listings') ?>
        </h3>
    </div>
    <?php
endif;
