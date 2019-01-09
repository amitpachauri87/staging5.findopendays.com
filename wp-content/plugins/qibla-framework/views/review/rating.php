<?php
/**
 * Rating View
 *
 * @since      1.2.0
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

// Reviewer Plugin.
$reviewer = new \QiblaFramework\Reviewer\ReviewerData(
    get_post(),
    new \QiblaFramework\ListingsContext\Context(
        \QiblaFramework\Functions\getWpQuery(),
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
    <?php if (0 < intval($data->value)) : ?>
        <div class="star-rating">
            <span style="width:<?php echo intval($data->width) ?>%">
                <span class="screen-reader-text">
                    <?php echo esc_html(sanitize_text_field($data->label)); ?>
                </span>
            </span>
        </div>
    <?php endif; ?>
<?php endif; ?>
