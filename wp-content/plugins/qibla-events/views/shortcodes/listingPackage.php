<?php
/**
 * Listing Package Shortcode View
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2017, Guido Scialfa
 * @license   GNU General Public License, version 2
 *
 * Copyright (C) 2017 Guido Scialfa <dev@guidoscialfa.com>
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

$allowed = array(
    'templates/events-search.php',
    'templates/homepage-fullwidth.php',
    'templates/fullwidth.php',
);
// Get page template.
$pageTemplate = get_page_template_slug(get_the_ID());
?>

<?php if ('boxed' === $data->layout && in_array($pageTemplate, $allowed, true)) : ?>
<section style="<?php echo $data->sectionStyle; ?>" class="dlsc-section-events-package-table-wrap">
    <div class="dlcontainer">
        <?php endif; ?>
        <div <?php Fw\scopeClass('sc-listing-packages') ?>>
            <div <?php Fw\scopeClass('grid') ?>>

                <?php foreach ($data->posts as $slug => $post) : ?>
                    <article <?php Fw\scopeClass('article', '', $post->postClassModifiers) ?>>

                        <div <?php Fw\scopeClass('article-card-box') ?>>

                            <header <?php Fw\scopeClass('article', 'header') ?>>

                        <span <?php Fw\scopeClass('article', 'type-label') ?>>
                            <?php echo esc_html(sanitize_text_field($post->hightlightLabel)) ?>
                        </span>

                                <p <?php Fw\scopeClass('article', 'package-price') ?>>
                                    <?php echo Fw\ksesPost($post->product->priceLabel) ?>
                                </p>

                                <h2 <?php Fw\scopeClass('article', 'subtitle') ?>>
                                    <?php echo esc_html(sanitize_text_field($post->shortDescription)) ?>
                                </h2>
                            </header>

                            <?php if ($post->fields) : ?>
                                <ul <?php Fw\scopeClass('package-fields') ?>>

                                    <?php foreach ($post->fields as $icon => $text) : ?>
                                        <li <?php Fw\scopeClass('package-fields', 'item') ?>>
                                    <span>
                                        <?php if (! is_numeric($icon)) : ?>
                                            <i class="<?php echo Fw\sanitizeHtmlClass($icon) ?>"></i>
                                        <?php endif; ?>
                                        <span><?php echo esc_html(sanitize_text_field($text)) ?></span>
                                    </span>
                                        </li>
                                    <?php endforeach; ?>

                                </ul>
                            <?php endif; ?>

                            <a href="<?php echo esc_url($post->permalink) ?>"<?php Fw\scopeClass('article', 'link') ?>>
                                <?php echo esc_html(sanitize_text_field($post->submitLabel)) ?>
                            </a>
                        </div>

                    </article>
                <?php endforeach; ?>

            </div>
        </div>
        <?php if ('boxed' === $data->layout && in_array($pageTemplate, $allowed, true)) : ?>
    </div>
</section>
<?php endif; ?>
