<?php
namespace QiblaImporter\Functions;

/**
 * Conditional Functions
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
 * Is JSON
 *
 * Check if a string is a valid json or not.
 *
 * @todo use the same code defined in framework.
 *
 * @since 1.0.0
 *
 * @param string $data The json string.
 *
 * @return bool True if the string is a json, false otherwise
 */
function isJSON($data)
{
    if (! is_string($data) || '' === $data) {
        return false;
    }

    return (json_decode($data) && JSON_ERROR_NONE === json_last_error());
}

/**
 * Is Events Plugin is Active.
 *
 * @since: ${SINCE}
 *
 * @return bool
 */
function isEventsPluginActive()
{
    return is_plugin_active('qibla-events/index.php');
}
