<?php
/**
 * InconizeInterface
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @package   dreamlist-framework
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

namespace QiblaFramework\Listings;

/**
 * Class InconizeInterface
 *
 * @since   2.0.0
 * @package QiblaFramework\Listings
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
interface IconizeInterface
{
    /**
     * Icon
     *
     * @since 2.0.0
     *
     * @param string $default The default compact version of the icon.
     *
     * @return array The icon data in array format
     */
    public function icon($default);
}
