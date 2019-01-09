<?php
/**
 * EventSidebarFilter.php
 *
 * @since      1.0.0
 * @package    AppMapEvents\Sidebars
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

namespace AppMapEvents\Sidebars;

use AppMapEvents\FilterInterface;
use QiblaFramework\Admin\Metabox\Sidebar;
use QiblaFramework\ListingsContext\Types;

/**
 * Class EventSidebarFilter
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class EventSidebarFilter implements FilterInterface
{
    /**
     * Sidebar
     *
     * @since 1.0.0
     *
     * @var string Sidebar type
     */
    private $sidebar;

    /**
     * Types
     *
     * @since 1.0.0
     *
     * @var Types The Types of listings
     */
    private $types;

    /**
     * EventSidebarFilter constructor.
     *
     * @since 1.0.0
     *
     * @param       $sidebar Sidebar name.
     * @param Types $types   The Types of listings
     */
    public function __construct($sidebar, Types $types)
    {
        $this->sidebar = $sidebar;
        $this->types   = $types;
    }

    /**
     * @inheritdoc
     */
    public function filter()
    {
        // Same sidebar of listings.
        if (in_array($this->sidebar, $this->types->types())) {
            return 'listings';
        }
    }

    /**
     * Filter
     *
     * @since 1.0.0
     *
     * @param $data
     *
     * @return EventSidebarFilter
     */
    public static function filterFilter($data)
    {
        $instance = new self($data, new Types());

        return $instance->filter();
    }
}
