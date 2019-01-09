<?php
/**
 * ListingsTypesListFilter
 *
 * @since      1.0.0
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

namespace AppMapEvents\PostType;

/**
 * Class ListingsTypesListFilter
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class ListingsTypesListFilter implements \AppMapEvents\FilterInterface
{
    /**
     * List To Filter
     *
     * @since 1.0.0
     *
     * @var array The list to filter
     */
    private $listToFilter;

    /**
     * List
     *
     * @since 1.0.0
     *
     * @var array The base list
     */
    private $list = array(
        'events',
    );

    /**
     * ListingsTypesListFilter constructor
     *
     * @since 1.0.0
     *
     * @param array $listToFilter
     */
    public function __construct(array $listToFilter)
    {
        $this->listToFilter = $listToFilter;
    }

    /**
     * @inheritdoc
     */
    public function filter()
    {
        return array_merge($this->listToFilter, $this->list);
    }

    /**
     * Filter Helper
     *
     * @since 1.0.0
     *
     * @param $list
     *
     * @return array|mixed
     */
    public static function filterFilter($list)
    {
        $instance = new self($list);

        return $instance->filter();
    }
}
