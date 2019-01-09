<?php
/**
 * Detach Filters
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

namespace AppMapEvents;

/**
 * Class DetachFilters
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class DetachFilters
{
    /**
     * Filters To remove
     *
     * @since  1.0.0
     *
     * @var array The list of the filters to remove.
     */
    private $list;

    /**
     * Remove Filters
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function detach()
    {
        foreach ($this->list as $func => $list) {
            $func = 'remove_' . $func;
            if (! empty($list)) {
                foreach ($list as $data) {
                    call_user_func_array($func, array_values($data));
                }
            }
        }
    }

    /**
     * RemoveFilters constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->list = array(
            'action' => array(
            ),

            'filter' => array(
            ),
        );
    }
}
