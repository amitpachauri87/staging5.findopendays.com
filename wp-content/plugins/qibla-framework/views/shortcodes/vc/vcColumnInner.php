<?php
/**
 * Visual Composer Column Inner View
 *
 * @since      1.6.0
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

/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $el_class
 * @var $el_id
 * @var $width
 * @var $css
 * @var $offset
 * @var $content - shortcode content
 * Shortcode class
 * @var $this    WPBakeryShortCode_VC_Column_Inner
 */
$el_class = $width = $el_id = $css = $offset = '';
$output   = '';
$atts     = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);

$width = wpb_translateColumnWidthToSpan($width);
$width = vc_column_offset_class_merge($offset, $width);

$css_classes = array(
    $this->getExtraClass($el_class),
    'wpb_column',
    'vc_column_container',
    $width,
);

if (vc_shortcode_custom_css_has_property($css, array(
    'border',
    'background',
))) {
    $css_classes[] = 'vc_col-has-fill';
}

$wrapper_attributes = array();

$css_class = preg_replace(
    '/\s+/',
    ' ',
    apply_filters(
        VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
        implode(' ', array_filter($css_classes)),
        $this->settings['base'],
        $atts
    )
);

// QIBLA NOTE. Add the custom css classes for the column here because we override the column and row classes
// so, our columns add margin bottom that cannot be removed if the box model classes by vc are added to the inner
// column element.
$css_class = trim($css_class) . ' ' . vc_shortcode_custom_css_class($css);

$wrapper_attributes[] = 'class="' . esc_attr(trim($css_class)) . '"';
if (! empty($el_id)) {
    $wrapper_attributes[] = 'id="' . esc_attr($el_id) . '"';
}

$output .= '<div ' . implode(' ', $wrapper_attributes) . '>';
$output .= '<div class="vc_column-inner">';
$output .= '<div class="wpb_wrapper">';
$output .= wpb_js_remove_wpautop($content);
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';

echo $output;
