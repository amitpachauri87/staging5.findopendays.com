<?php
/**
 * Filesystem Functions
 *
 * @package QiblaFramework\Functions
 * @author  guido scialfa <dev@guidoscialfa.com>
 *
 * @license GPL 2
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

namespace QiblaFramework\Functions;

use QiblaFramework\Plugin;

/**
 * Switch Upload dir Path url
 *
 * Interchange the url and dir path for files within the upload directory
 *
 * @since 1.0.0
 *
 * @param string $file   The file path.
 * @param string $switch The reference to the string to replace. Allowed 'dir>url', 'url>dir'.
 *
 * @return string The file url
 */
function switchUploadDirPathUrl($file, $switch = 'dir>url')
{
    // Get upload dir data.
    $uploadDir = wp_upload_dir();

    // Initialize the new file path.
    $newPath = '';

    if ('dir>url' === $switch) {
        $newPath = str_replace($uploadDir['basedir'], $uploadDir['baseurl'], $file);
    } elseif ('url>dir' === $switch) {
        $newPath = str_replace($uploadDir['baseurl'], $uploadDir['basedir'], $file);
    } else {
        $newPath = $file;
    }

    return $newPath;
}

/**
 * Require WP_Filesystem function
 *
 * @since 1.0.0
 *
 * @return void
 */
function requireWPFileSystem()
{
    if (! function_exists('WP_Filesystem')) {
        require_once untrailingslashit(ABSPATH) . '/wp-admin/includes/file.php';
    }
}

/**
 * Sanitize path
 *
 * @since 1.5.0
 *
 * @param string $path The path to sanitize
 *
 * @return string The sanitized path.
 */
function sanitizePath($path)
{
    while (false !== strpos($path, '..')) {
        $path = str_replace('..', '', $path);
    }

    $path = ('/' !== $path) ? $path : '';

    return $path;
}

/**
 * Path From Theme Fallback To Plugins
 *
 * @since 2.0.0
 *
 * @param string $path The string path to search for.
 *
 * @return string The located path
 */
function pathFromThemeFallbackToPlugins($path)
{
    $located = get_theme_file_path($path);

    if (! file_exists($located)) {
        $located = Plugin::getPluginDirPath($path);
    }

    return $located;
}
