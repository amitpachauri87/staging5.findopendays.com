<?php
namespace Qibla\Functions;

/**
 * Formatting Functions
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
 * Sanitize Html Class
 *
 * A wrapper function that enable to pass an array or a string of classes separated by spaces.
 *
 * @since 1.0.0
 *
 * @uses  sanitize_html_class() To sanitize the html class string
 *
 * @param mixed  $class    The classes as string or array.
 * @param string $fallback The value to return if the sanitization ends up as an empty string. Optional.
 * @param string $glue     The glue to use to explode the string list of classes. Optional default to space.
 *
 * @return string The sanitize class or classes list
 */
function sanitizeHtmlClass($class, $fallback = '', $glue = '')
{
    // Default to space.
    $glue = $glue ?: ' ';

    // If is a list and is represented as a string.
    if (is_string($class) && false !== strpos($class, $glue)) {
        $class = explode($glue, $class);
    }

    if (is_array($class)) {
        $newClass = $class;
        $class    = '';
        foreach ($newClass as $c) {
            $class .= ' ' . sanitizeHtmlClass($c, $fallback);
        }
    } else {
        $class = sanitize_html_class($class, $fallback);
    }

    return trim($class, ' ');
}
