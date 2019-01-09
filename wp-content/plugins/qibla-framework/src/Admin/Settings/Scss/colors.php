<?php
namespace QiblaFramework\Scss;

use QiblaFramework\Functions as F;

/**
 * Typography Scss
 *
 * @package   QiblaFramework
 * @copyright Copyright (c) 2016, Guido Scialfa
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
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

// Retrieve the options.
$colors = F\getThemeOption('colors', '', true);

if (! $colors) {
    return '';
}
?>

// Variables
$color-primary: <?php echo sanitize_hex_color($colors['main']) ?>;

// Selected Text Color
$color-selected-text: (
    background: <?php echo sanitize_hex_color($colors['background_text_selected']) ?>,
    color: <?php echo sanitize_hex_color($colors['text_selected']) ?>
);

// Body
$color-body-background: <?php echo sanitize_hex_color($colors['background']) ?>;

// Footer
$typography_footer: (
    color: <?php echo sanitize_hex_color($colors['text_footer']) ?>,
    link: (
        border-bottom-color: transparent,
        color: <?php echo sanitize_hex_color($colors['text_footer']) ?>,
    ),
    link-hover: (
         border-bottom-color: <?php echo sanitize_hex_color($colors['text_footer']) ?>
    )
);
$boxmodel_footer: (
    background-color: <?php echo sanitize_hex_color($colors['background_footer']) ?>,
);

$typography_colophon: (
    color: <?php echo sanitize_hex_color($colors['text_colophon']) ?>,
    link: (
        color: <?php echo sanitize_hex_color($colors['text_colophon']) ?>,
    ),
    link-hover: (
        border-bottom-color: <?php echo sanitize_hex_color($colors['text_colophon']) ?>
    )
);
$boxmodel_colophon: (
    background-color: <?php echo sanitize_hex_color($colors['background_colophon']) ?>,
);