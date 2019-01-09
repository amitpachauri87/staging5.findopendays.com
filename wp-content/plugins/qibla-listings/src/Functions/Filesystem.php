<?php
namespace QiblaListings\Functions;

use QiblaListings\Plugin;

/**
 * Formatting Functions
 *
 * @package    QiblaListings\Functions
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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

    $path = ('/' !== $path) ? $path : '';

    return $path;
}

/**
 * Require
 *
 * Require function used to prevent to require files within the classes.
 * Additional add the plugin dir path to the file required.
 *
 * @since 1.0.0
 *
 * @param string $file The needed file path.
 * @param bool   $once True to require once, false otherwise. Optional. Default to false.
 *
 * @return void
 */
function qlRequire($file, $once = false)
{
    $file = ltrim($file, '/');

    // Add the base plugin path.
    $file = sanitizePath(Plugin::getPluginDirPath($file));

    if (! $once) {
        require $file;
    } else {
        require_once $file;
    }
}

/**
 * Switch Upload Dir Path
 *
 * @since  1.0.0
 *
 * @param string $file   The file path.
 * @param string $switch The switch method, Allowed 'dir>url' or 'url>dir'.
 *
 * @return string The new path or url
 */
function switchUploadUrlPath($file, $switch = 'dir>url')
{
    // Get upload dir data.
    $uploadDir = wp_upload_dir();
    // Initialize the new file path.
    $tmpFile = '';

    if ('dir>url' === $switch) {
        $tmpFile = str_replace($uploadDir['basedir'], $uploadDir['baseurl'], $file);
    } elseif ('url>dir' === $switch) {
        $tmpFile = str_replace($uploadDir['baseurl'], $uploadDir['basedir'], $file);

        $tmpFile = sanitizePath($tmpFile);
    } else {
        $tmpFile = $file;
    }

    return $tmpFile;
}
