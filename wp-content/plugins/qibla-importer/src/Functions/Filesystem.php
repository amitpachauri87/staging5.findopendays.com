<?php
namespace QiblaImporter\Functions;

/**
 * Filesystem Functions
 *
 * @package QiblaImporter\Functions
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

defined('WPINC') || die;

/**
 * Sanitize path
 *
 * @since 1.0.0
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

    return $path;
}

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
        // @todo use the dlRequire when the function will look into other directories other than /src/.
        require_once untrailingslashit(ABSPATH) . '/wp-admin/includes/file.php';
    }
}

/**
 * Get Filesystem
 *
 * @since  1.0.0
 *
 * @return \WP_Filesystem_Base
 */
function getFileSystem()
{
    static $fs = null;

    if (null !== $fs) {
        return $fs;
    }

    global $wp_filesystem;

    if (! $wp_filesystem) {
        requireWPFileSystem();
        WP_Filesystem();
    }

    $fs = clone $wp_filesystem;

    return $fs;
}

/**
 * Get Content
 *
 * @since  1.0.0
 *
 * @throws \Exception If teh file doesn't exists or not a valid json file.
 *
 * @param string $file The file path for which decode the content.
 *
 * @return array|\stdClass The json decoded data
 */
function getJsonContent($file)
{
    $fileSystem = getFileSystem();

    if (
        ! $fileSystem->is_file($file) ||
        ! $fileSystem->exists($file)
    ) {
        throw new \Exception('Cannot import file. File doesn\'t exists or not a file.');
    }

    // Get file contents and decode.
    $data = $fileSystem->get_contents($file);
    if (! isJSON($data)) {
        throw new \Exception('File is not a valid JSON format.');
    }

    return json_decode($data);
}
