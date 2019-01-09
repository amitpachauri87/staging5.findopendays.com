<?php
/**
 * Shortcode Params Builder
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2017, Guido Scialfa
 * @license   GNU General Public License, version 2
 *
 * Copyright (C) 2017 Guido Scialfa <dev@guidoscialfa.com>
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

namespace QiblaFramework\VisualComposer;

use QiblaFramework\Plugin;

/**
 * Class ShortcodeParamsBuilder
 *
 * @since   1.6.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\VisualComposer
 */
class ShortcodeParamsBuilder
{
    /**
     * List of shared Visual composer fields
     *
     * @since 1.6.0
     *
     * @var array The list of the shared visual composer fields
     */
    private static $list = array(
        'post-type' => '/inc/vcMapping/_postTypeFields.php',
    );

    /**
     * Get Fields
     *
     * @since 1.6.0
     *
     * @param string $key  The key of the fields to retrieve.
     * @param array  $base The basic list of fields accepted by the shortcode.
     *
     * @return array The request fields for the visual composer map
     */
    public static function get($key, $base)
    {
        // Required fields type not in list.
        if (! isset(self::$list[$key])) {
            return array();
        }

        // Retrieve the required fields map.
        $params   = include Plugin::getPluginDirPath(self::$list[$key]);
        $scParams = array();

        // Get only the needed ones.
        foreach (array_keys($base) as $key) {
            $scParams[] = isset($params[$key]) ? $params[$key] : array();
        }

        // Clean and reorder the items.
        $scParams = array_values(array_filter($scParams));

        return $scParams;
    }
}
