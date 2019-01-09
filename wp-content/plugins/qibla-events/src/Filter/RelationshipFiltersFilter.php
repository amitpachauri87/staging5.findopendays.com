<?php
/**
 * RelationshipFiltersFilter
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
namespace AppMapEvents\Filter;

use AppMapEvents\FilterInterface;

/**
 * Class RelationshipFiltersFilter
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class RelationshipFiltersFilter implements FilterInterface
{
    /**
     * Relation Ship
     *
     * @since 1.0.0
     *
     * @var array
     */
    private $relationship;

    /**
     * Own Filters
     *
     * @since 1.0.0
     *
     * @var array
     */
    private static $ownFilters = array(
        // Base Events Type.
        'events' => array(
            'qibla_event_dates_filter',
            'qibla_event_categories_filter',
            'qibla_event_locations_filter',
            'qibla_event_tags_filter',
        ),
    );

    /**
     * Filters
     *
     * @since 1.0.0
     *
     * @var array
     */
    private $filters;

    /**
     * RelationshipFiltersFilter constructor
     *
     * @since 1.0.0
     *
     * @param array $relationship
     */
    public function __construct(array $relationship = array())
    {
        $this->relationship = $relationship;
        $this->filters      = array();
    }

    /**
     * Filter
     *
     * @since 1.0.0
     *
     * @return $this
     */
    public function filter()
    {
        $this->filters = array_merge($this->relationship, self::$ownFilters);

        return $this;
    }

    /**
     * Filters
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function filters()
    {
        return $this->filters;
    }

    /**
     * Filters Helper
     *
     * @since 1.0.0
     *
     * @param array $relashionship
     *
     * @return array
     */
    public static function filterFilter(array $relashionship = array())
    {
        $instance = new self($relashionship);

        return $instance->filter()
                        ->filters();
    }
}
