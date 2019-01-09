<?php
namespace Qibla\Functions;

/**
 * Functions Images
 *
 * @license GNU General Public License, version 2
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
 * Retrieves the attachment ID from the file URL
 *
 * @deprecated 1.2.0 No longer supported. Use attachment_url_to_postid instead.
 *
 * @author Pippin Williamson
 *
 * @since  1.0.0
 *
 * @link   https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
 *
 * @param  string $imageUrl The url of the image.
 *
 * @return mixed The ID of the attachment or false if is not possible to retrieve the data from database
 */
function getImageIdByUrl($imageUrl = '')
{
    if (empty($imageUrl)) {
        return false;
    }

    global $wpdb;

    $matched    = getFullImageUrl($imageUrl);
    $imageUrl   = $matched ? $matched : $imageUrl;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $imageUrl));

    if ($attachment) {
        return $attachment[0];
    }

    return false;
}

/**
 * Retrieve the original image url from the cropped one
 *
 * @since  1.0.0
 *
 * @param  string $imageUrl The cropped image url.
 *
 * @return bool|string The original image url
 */
function getFullImageUrl($imageUrl)
{
    preg_match('/(^http|https)\:\/\/([a-z0-9].*)(\-[0-9]+x[0-9]+)\.([a-z0-9]+$)/', $imageUrl, $matches);

    // A cropped image is found.
    if ($matches) {
        $imageUrl = $matches[1] . '://' . $matches[2] . '.' . $matches[4];

        return $imageUrl;
    }

    return false;
}

/**
 * Get attachment image alternate text
 *
 * @since  1.0.0
 *
 * @param  mixed $id The id of the attachment image.
 *
 * @return mixed     The alternative attachment text or any other get_post_meta value returned
 */
function getAttachmentImageAlt($id)
{
    $alt = trim(wp_strip_all_tags(get_post_meta($id, '_wp_attachment_image_alt', true)));

    return $alt;
}

/**
 * Get attachment image
 *
 * The function use the figure/figcaption if the theme support html5, div and p otherwise.
 *
 * @since 1.0.0
 *
 * @param int    $id      The id of the attachment to retrieve.
 * @param string $size    The size of the post thumbnail to retrieve. Optional. Default to 'post-thumbnail'.
 * @param string $scope   The scope to use for class attributes. Optional. Default to Figure.
 * @param string $caption The caption for the image. Optional.
 *
 * @return string The output image. Empty string if the attachment image cannot be retrieved.
 */
function getAttachmentFigureImage($id, $size = 'post-thumbnail', $scope = '', $caption = '')
{
    // Be sure to work with a correct value.
    $id = absint($id);

    if (! $id) {
        return '';
    }

    $scope        = $scope . '__figure';
    $html5Support = current_theme_supports('html5', 'caption');

    if ($caption) {
        $caption = sprintf(
            '<%1$s class="%2$s">%3$s</%1$s>',
            ($html5Support ? 'figcaption' : 'p'),
            getScopeClass($scope, 'caption'),
            esc_html(ksesPost($caption))
        );
    }

    return sprintf(
        '<%1$s class="%2$s">%3$s%4$s</%1$s>',
        ($html5Support ? 'figure' : 'div'),
        getScopeClass($scope),
        wp_get_attachment_image($id, $size),
        (isset($caption) ? $caption : '')
    );
}
