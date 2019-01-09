<?php
/**
 * WordPress Backward Functions
 *
 * @since   1.0.0
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

if (! function_exists('wp_get_attachment_caption')) {
    /**
     * Retrieves the caption for an attachment.
     *
     * @since WP4.6.0
     * @since 1.0.0
     *
     * @todo  Remove when 4.8.0
     *
     * @param int $post_id Optional. Attachment ID. Default 0.
     *
     * @return string|false False on failure. Attachment caption on success.
     */
    function wp_get_attachment_caption($post_id = 0)
    {
        $post_id = (int)$post_id;
        if (! $post = get_post($post_id)) {
            return false;
        }

        if ('attachment' !== $post->post_type) {
            return false;
        }

        $caption = $post->post_excerpt;

        /**
         * Filters the attachment caption.
         *
         * @since 4.6.0
         *
         * @param string $caption Caption for the given attachment.
         * @param int    $post_id Attachment ID.
         */
        return apply_filters('wp_get_attachment_caption', $caption, $post->ID);
    }
}

if (! function_exists('get_the_post_thumbnail_caption')) {
    /**
     * Returns the post thumbnail caption.
     *
     * @since WP4.6.0
     * @since 1.0.0
     *
     * @todo  Remove when 4.8.0
     *
     * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
     *
     * @return string Post thumbnail caption.
     */
    function get_the_post_thumbnail_caption($post = null)
    {
        $post_thumbnail_id = get_post_thumbnail_id($post);
        if (! $post_thumbnail_id) {
            return '';
        }

        $caption = wp_get_attachment_caption($post_thumbnail_id);

        if (! $caption) {
            $caption = '';
        }

        return $caption;
    }
}

if (! function_exists('the_post_thumbnail_caption')) {
    /**
     * Displays the post thumbnail caption.
     *
     * @since WP4.6.0
     * @since 1.0.0
     *
     * @todo  Remove when 4.8.0
     *
     * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
     */
    function the_post_thumbnail_caption($post = null)
    {
        /**
         * Filters the displayed post thumbnail caption.
         *
         * @since 4.6.0
         *
         * @param string $caption Caption for the given attachment.
         */
        echo wp_kses_post(apply_filters('the_post_thumbnail_caption', get_the_post_thumbnail_caption($post)));
    }
}

if (! function_exists('wp_doing_ajax')) {
    /**
     * WP Doing Ajax
     *
     * Determines whether the current request is a WordPress Ajax request.
     *
     * @todo  remove on 4.9.0
     *
     * @since WP4.7.0
     *
     * @return bool True if it's a WordPress Ajax request, false otherwise.
     */
    function wp_doing_ajax()
    {
        /**
         * Filters whether the current request is a WordPress Ajax request.
         *
         * @since 4.7.0
         *
         * @param bool $wp_doing_ajax Whether the current request is a WordPress Ajax request.
         */
        return apply_filters('wp_doing_ajax', defined('DOING_AJAX') && DOING_AJAX);
    }
}

if (! function_exists('get_theme_file_uri')) {
    /**
     * Retrieves the URL of a file in the theme.
     *
     * Searches in the stylesheet directory before the template directory so themes
     * which inherit from a parent theme can just override one file.
     *
     * @since WP4.7.0
     * @todo  remove on wp 4.9.0
     *
     * @param string $file Optional. File to search for in the stylesheet directory.
     *
     * @return string The URL of the file.
     */
    function get_theme_file_uri($file = '')
    {
        $file = ltrim($file, '/');

        if (empty($file)) {
            $url = get_stylesheet_directory_uri();
        } elseif (file_exists(get_stylesheet_directory() . '/' . $file)) {
            $url = get_stylesheet_directory_uri() . '/' . $file;
        } else {
            $url = get_template_directory_uri() . '/' . $file;
        }

        /**
         * Filters the URL to a file in the theme.
         *
         * @since 4.7.0
         *
         * @param string $url  The file URL.
         * @param string $file The requested file to search for.
         */
        return apply_filters('theme_file_uri', $url, $file);
    }
}

if (! function_exists('get_theme_file_path')) {
    /**
     * Retrieves the path of a file in the theme.
     *
     * Searches in the stylesheet directory before the template directory so themes
     * which inherit from a parent theme can just override one file.
     *
     * @since WP4.7.0
     * @todo  remove on wp 4.9.0
     *
     * @param string $file Optional. File to search for in the stylesheet directory.
     *
     * @return string The path of the file.
     */
    function get_theme_file_path($file = '')
    {
        $file = ltrim($file, '/');

        if (empty($file)) {
            $path = get_stylesheet_directory();
        } elseif (file_exists(get_stylesheet_directory() . '/' . $file)) {
            $path = get_stylesheet_directory() . '/' . $file;
        } else {
            $path = get_template_directory() . '/' . $file;
        }

        /**
         * Filters the path to a file in the theme.
         *
         * @since 4.7.0
         *
         * @param string $path The file path.
         * @param string $file The requested file to search for.
         */
        return apply_filters('theme_file_path', $path, $file);
    }
}
