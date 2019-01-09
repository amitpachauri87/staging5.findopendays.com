<?php
/**
 * View Listings Short-code
 *
 * @since 1.0.0
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
use Qibla\Debug;

$allowed = array(
    'templates/events-search.php',
    'templates/homepage-fullwidth.php',
    'templates/fullwidth.php'
);
// Get page template.
$pageTemplate = get_page_template_slug(get_the_ID());
?>

<?php if ('boxed' === $data->layout && in_array($pageTemplate, $allowed, true)) : ?>
    <section style="<?php echo $data->sectionStyle; ?>" class="dlsc-section-listings-wrap"><div class="dlcontainer">
<?php endif; ?>
    <div class="dlsc-posts dlsc-posts--<?php echo sanitize_key($data->postType) ?>">
        <div class="dlgrid">
            <?php
            global $post;
            foreach ($data->posts as $slug => $post) :
                setup_postdata($post);
                ?>
                <article id="post-<?php echo intval($post->ID) ?>"
                         class="<?php echo esc_attr(F\sanitizeHtmlClass(array(
                             call_user_func_array('QiblaFramework\\Functions\\getScopeClass', $post->postClass),
                             $post->gridClass,
                         ))) ?>">

                    <?php if ('no' === apply_filters('qibla_disabled_term_color_scoped_style', 'no')) {
                        // @codingStandardsIgnoreLine
                        echo F\getTermColorScopedStyle('listing_categories', $post->ID);
                    } ?>

                    <div class="dlarticle-card-box">
                        <?php $post->isListings and \QiblaFramework\Wishlist\Template::template(get_post($post->ID));

                        if (! empty($post->postTitle) || ! empty($post->thumbnail)) : ?>
                            <header class="dlarticle__header">
                                <a class="dlarticle__link" href="<?php echo esc_url($post->permalink) ?>">
                                    <?php
                                    // Thumbnail.
                                    if ($post->thumbnail instanceof QiblaFramework\Template\TemplateInterface) :
                                        $post->thumbnail->tmpl($post->thumbnail->getData());
                                    endif;

                                    if ($post->postTitle) : ?>
                                        <h2 class="dlarticle__title">
                                            <?php if (isset($post->icon)) : ?>
                                                <i class="dlarticle__icon <?php echo esc_attr(F\sanitizeHtmlClass($post->icon['icon_html_class'])) ?>"
                                                   aria-hidden="true"></i>
                                            <?php endif; ?>

                                            <?php
                                            // Reviewer Plugin.
                                            $reviewer = new \QiblaFramework\Reviewer\ReviewerData(
                                                get_post($post->ID),
                                                new \QiblaFramework\ListingsContext\Context(
                                                    F\getWpQuery(),
                                                    new \QiblaFramework\ListingsContext\Types()
                                                )
                                            );
                                            $rating = $reviewer::averageRating();
                                            ?>
                                            <?php if ($reviewer::checkDependencies() && $rating &&
                                                      isset($reviewer::$templateID) && '' !== $reviewer::$templateID
                                            ) : ?>
                                                <div class="star-rating">
                                                    <span style="width:<?php echo intval($rating->averageRatingwidth) ?>%">
                                                        <span class="screen-reader-text">
                                                            <?php echo esc_html(sanitize_text_field($rating->averageRatingLabel)); ?>
                                                        </span>
                                                    </span>
                                                </div>
                                            <?php else : ?>
                                                <?php
                                                // Rating.
                                                $post->isListings and \QiblaFramework\Review\AverageRating::averageRatingFilter();
                                            endif; ?>

                                            <?php echo esc_html(sanitize_text_field($post->postTitle)) ?>
                                        </h2>
                                    <?php endif; ?>
                                </a>
                                <?php
                                if (isset($post->buttonMeta) && is_array($post->buttonMeta)) {
                                    if ('' !== $post->buttonMeta['url'] && '' !== $post->buttonMeta['text']) {
                                        $btnUrl  = '' !== $post->buttonMeta['url'] ? $post->buttonMeta['url'] : '#';
                                        $btnText = '' !== $post->buttonMeta['text'] ? $post->buttonMeta['text'] :
                                            esc_html__('Placeholder text', 'qibla-framework');
                                        $target  = 'off' === $post->buttonMeta['target'] ?
                                            ' target="_blank" rel="noopener"' : '';

                                        echo '<div class="dllisting-ticket-wrapper">';
                                        echo sprintf(
                                            '<a class="%s listing-ticket-button" href="%s"%s>%s</a>',
                                            esc_attr(F\sanitizeHtmlClass($post->buttonMeta['btn_class'])),
                                            esc_url($btnUrl),
                                            esc_html($target),
                                            esc_html($btnText)
                                        );
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </header>
                        <?php endif; ?>

                        <?php if (isset($data->forceAnchor) && $data->forceAnchor) : ?>
                        <a class="dlarticle__link" href="<?php echo esc_url($post->permalink) ?>">
                            <?php endif; ?>

                            <?php if (! empty($post->subtitle)) : ?>
                                <p class="dlsubtitle">
                                    <?php echo esc_html(sanitize_text_field($post->subtitle)) ?>
                                </p>
                            <?php endif;

                            if ($post->meta) : ?>
                                <footer class="dlarticle__meta">
                                    <ul class="dlarticle__meta-list">
                                        <?php
                                        foreach ($post->meta as $key => $meta) :
                                            if ($meta) :
                                                ob_start();
                                                try {
                                                    // Must be sure to echo the correct data.
                                                    is_array($meta) && $meta = implode(', ', $meta);
                                                    echo esc_html($meta);
                                                } catch (\Exception $e) {
                                                    $debugInstance = new Debug\Exception($e);
                                                    'dev' === QB_ENV && $debugInstance->display();
                                                    continue;
                                                }
                                                $markup = trim(ob_get_clean());
                                                if ($markup) : ?>
                                                    <li class="dlarticle__meta-item dlarticle__meta-item--<?php echo esc_attr(sanitize_key($key)) ?>">
                                                        <?php echo F\ksesPost($markup); ?>
                                                    </li>
                                                <?php
                                                endif;
                                            endif;
                                        endforeach; ?>
                                    </ul>
                                </footer>
                            <?php endif; ?>

                            <?php if (isset($data->forceAnchor) && $data->forceAnchor) : ?>
                        </a>
                    <?php endif; ?>
                    </div>

                </article>
            <?php
            endforeach;
            wp_reset_postdata(); ?>
        </div>
    </div>
<?php if ('boxed' === $data->layout && in_array($pageTemplate, $allowed, true)) : ?>
    </div></section>
<?php endif; ?>