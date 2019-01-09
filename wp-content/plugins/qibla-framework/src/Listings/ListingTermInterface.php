<?php
/**
 * ListingTermInterface
 *
 * @since      2.0.0
 * @package    dreamlist-framework
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2017, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2017 alfiopiccione <alfio.piccione@gmail.com>
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
 * Class SubTitleInterface
 *
 * @since   2.0.0
 * @package QiblaFramework\Listings
 * @author  alfiopiccione <alfio.piccione@gmail.com>
 */
interface ListingTermInterface
{
    /**
     * Color
     *
     * @since 2.2.0
     *
     * @return string The post term color
     */
    public function color();
}
