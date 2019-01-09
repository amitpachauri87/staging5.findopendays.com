<?php

use QiblaFramework\Functions as F;

/**
 * View Terms Short-code
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

$allowed = array(
    'templates/events-search.php',
    'templates/homepage-fullwidth.php',
    'templates/fullwidth.php',
);
// Get page template.
$pageTemplate = get_page_template_slug(get_the_ID());
?>

<?php if ('boxed' === $data->layout && in_array($pageTemplate, $allowed, true)) : ?>
<section style="<?php echo $data->sectionStyle; ?>" class="dlsc-section-terms-wrap"><div class="dlcontainer">
    <?php endif; ?>
    <div class="dlsc-terms">
        <div class="dlgrid dlgrid--masonry dlu-same-height">
            <?php foreach ($data->terms as $term) : ?>
                <article class="<?php echo esc_attr(F\sanitizeHtmlClass($term->termClass)) ?>">
                    <header class="dlarticle__header">
                        <a class="dlarticle__link" href="<?php echo esc_url($term->permalink) ?>">
                            <?php if ($term->thumbnail) : ?>
                                <figure class="dlthumbnail">
                                    <?php
                                    // @codingStandardsIgnoreLine
                                    echo F\ksesImage($term->thumbnail) ?>
                                </figure>
                            <?php endif; ?>
                            <?php if ($term->termTitle) : ?>
                                <h2 class="dlarticle__title">
                                <span class="dlarticle__title-label">
                                    <?php if ($term->iconHtmlClass) : ?>
                                        <i class="<?php echo esc_attr(F\sanitizeHtmlClass($term->iconHtmlClass)) ?>"
                                           aria-hidden="true"></i>
                                    <?php endif; ?>
                                    <?php echo esc_html(sanitize_text_field($term->termTitle)) ?>
                                </span>
                                </h2>
                            <?php endif; ?>
                        </a>
                    </header>

                    <?php if ($term->termContent) : ?>
                        <p class="dlarticle__content">
                            <?php
                            // @codingStandardsIgnoreLine
                            echo F\ksesPost($term->termContent) ?>
                        </p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
    <?php if ('boxed' === $data->layout && in_array($pageTemplate, $allowed, true)) : ?>
    </div></section>
<?php endif; ?>
