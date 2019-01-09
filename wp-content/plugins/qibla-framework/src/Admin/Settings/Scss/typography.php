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
$typography = F\getThemeOption('typography', '', true);

if (! $typography) {
    return '';
}
?>

// Font Family
$font-family: <?php echo sanitize_text_field($typography['font_base']['family']) ?>;

// Typography Base
$typography_body: (
    color: <?php echo sanitize_hex_color($typography['font_base']['color']) ?>,
    font-family: $font-family,
);

// Jumbotron title
$typography-jumbotron-title: (
    color: <?php echo sanitize_hex_color($typography['font_heading_h1']['color']) ?>
);

// Typography Headings
$typography_headings: (
    h1: (
        color: <?php echo sanitize_hex_color($typography['font_heading_h1']['color']) ?>,
        font-family: <?php echo sanitize_text_field($typography['font_heading_h1']['family']) ?>,
        font-weight: <?php echo sanitize_text_field(str_replace('w', '', $typography['font_heading_h1']['weights'])) ?>,
        font-style: <?php echo sanitize_text_field($typography['font_heading_h1']['variants']) ?>,
    ),
    h2: (
        color: <?php echo sanitize_hex_color($typography['font_heading_h2']['color']) ?>,
        font-family: <?php echo sanitize_text_field($typography['font_heading_h2']['family']) ?>,
        font-weight: <?php echo sanitize_text_field(str_replace('w', '', $typography['font_heading_h2']['weights'])) ?>,
        font-style: <?php echo sanitize_text_field($typography['font_heading_h2']['variants']) ?>,
    ),
    h3: (
        color: <?php echo sanitize_hex_color($typography['font_heading_h3']['color']) ?>,
        font-family: <?php echo sanitize_text_field($typography['font_heading_h3']['family']) ?>,
        font-weight: <?php echo sanitize_text_field(str_replace('w', '', $typography['font_heading_h3']['weights'])) ?>,
        font-style: <?php echo sanitize_text_field($typography['font_heading_h3']['variants']) ?>,
    ),
    h4: (
        color: <?php echo sanitize_hex_color($typography['font_heading_h4']['color']) ?>,
        font-family: <?php echo sanitize_text_field($typography['font_heading_h4']['family']) ?>,
        font-weight: <?php echo sanitize_text_field(str_replace('w', '', $typography['font_heading_h4']['weights'])) ?>,
        font-style: <?php echo sanitize_text_field($typography['font_heading_h4']['variants']) ?>,
    ),
    h5: (
        color: <?php echo sanitize_hex_color($typography['font_heading_h5']['color']) ?>,
        font-family: <?php echo sanitize_text_field($typography['font_heading_h5']['family']) ?>,
        font-weight: <?php echo sanitize_text_field(str_replace('w', '', $typography['font_heading_h5']['weights'])) ?>,
        font-style: <?php echo sanitize_text_field($typography['font_heading_h5']['variants']) ?>,
    ),
    h6: (
        color: <?php echo sanitize_hex_color($typography['font_heading_h6']['color']) ?>,
        font-family: <?php echo sanitize_text_field($typography['font_heading_h6']['family']) ?>,
        font-weight: <?php echo sanitize_text_field(str_replace('w', '', $typography['font_heading_h6']['weights'])) ?>,
        font-style: <?php echo sanitize_text_field($typography['font_heading_h6']['variants']) ?>,
    )
);

$typography_nav-main: (
    font-family: <?php echo sanitize_text_field($typography['navigation']['family']) ?>,
    font-weight: <?php echo sanitize_text_field(str_replace('w', '', $typography['navigation']['weights'])) ?>,
    font-style: <?php echo sanitize_text_field($typography['navigation']['variants']) ?>,
);

$typography_btn: (
    font-family: <?php echo sanitize_text_field($typography['buttons']['family']) ?>,
    font-weight: <?php echo sanitize_text_field(str_replace('w', '', $typography['buttons']['weights'])) ?>,
    font-style: <?php echo sanitize_text_field($typography['buttons']['variants']) ?>,
);