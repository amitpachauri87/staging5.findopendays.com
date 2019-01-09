<?php
namespace QiblaWcListings\Functions;

/**
 * Formatting Functions
 *
 * @package    QiblaWcListings\Functions
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
 * Switch Upload Dir Path
 *
 * @since  1.0.0
 *
 * @param string $file   The file path.
 * @param string $switch The switch method, Allowed 'dir>url' or 'url>dir'.
 *
 * @return string The new path or url
 */
function switchUploadDirPath($file, $switch = 'dir>url')
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
