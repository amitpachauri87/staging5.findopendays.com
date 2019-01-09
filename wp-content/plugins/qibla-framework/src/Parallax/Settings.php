<?php
namespace QiblaFramework\Parallax;

/**
 * Settings
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaFramework\Parallax
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    GNU General Public License, version 2
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

/**
 * Class Settings
 *
 * @since   1.4.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\Parallax
 */
class Settings
{
    /**
     * Get Parallax Options
     *
     * Temporary function until the options will dynamically via Theme Options or something else.
     *
     * @since  1.4.0
     *
     * @return array The options for the parallax.
     */
    public function getOptions()
    {
        return array(
            'speed'           => '0.4',
            'type'            => 'scroll',
            'enableTransform' => true,
            'noAndroid'       => true,
            'noIos'           => true,
        );
    }

    /**
     * Is Parallax Enabled
     *
     * @since  1.4.0
     *
     * @return bool true if allowed, false otherwise
     */
    public function isEnabled()
    {
        /**
         * Is Parallax Enabled
         *
         * @since 1.4.0
         *
         * @param string 'yes' by default to enable the parallax.
         */
        $enabled = apply_filters('qibla_fw_parallax_enabled', 'yes');

        return ('yes' === $enabled && ! wp_is_mobile());
    }
}
