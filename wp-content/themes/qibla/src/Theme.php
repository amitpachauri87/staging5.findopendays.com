<?php
namespace Qibla;

/**
 * Theme
 *
 * @since      1.0.0
 * @package    Qibla
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    GNU General Public License, version 2
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
 * Class Theme
 *
 * @since      1.0.0
 * @package    Qibla
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Theme
{
    /**
     * Plugin Version
     *
     * @since  1.0.0
     *
     * @var string The plugin current version
     */
    const THEME_VERSION = '2.5.1';

    /**
     * Sanitize path
     *
     * @since 1.0.0
     *
     * @param string $path The path to sanitize
     *
     * @return string The sanitized path.
     */
    private static function sanitizePath($path)
    {
        while (false !== strpos($path, '..')) {
            $path = str_replace('..', '', $path);
        }

        $path = ('/' !== $path) ? $path : '';

        return $path;
    }

    /**
     * Get Template Dir Path
     *
     * @since  1.0.0
     *
     * @param string $path The path to append to the template directory. Optional. Default to '/'.
     *
     * @return string The plugin dir path
     */
    public static function getTemplateDirPath($path = '/')
    {
        $path = untrailingslashit(get_theme_file_path('/' . trim($path, '/')));
        $path = realpath($path);

        return self::sanitizePath($path);
    }

    /**
     * Get Template Dir Url
     *
     * @since  1.0.0
     *
     * @param string $path The path to append to the template dir url. Optional. Default to '/'.
     *
     * @return string The plugin dir url
     */
    public static function getTemplateDirUrl($path = '/')
    {
        $path = untrailingslashit(get_theme_file_uri('/' . trim($path, '/')));

        return self::sanitizePath($path);
    }
}
