<?php
/**
 * FiltersFieldsFilter
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
 * Class FiltersFieldsFilter
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class FiltersFieldsFilter implements FilterInterface
{
    /**
     * Own Filters
     *
     * @since 1.0.0
     *
     * @var array
     */
    private static $ownFiltersFields = array(
        'qibla_event_tags_filter'  => array(
            'type' => 'multi-check',
        ),
        'qibla_event_categories_filter' => array(
            'type' => 'select',
        ),
        'qibla_event_locations_filter'  => array(
            'type' => 'select',
        ),
        'qibla_event_dates_filter'  => array(
            'type' => 'select',
        ),
    );

    /**
     * Own Filters Fields Data
     *
     * @since 1.0.0
     *
     * @var array
     */
    private static $ownFiltersFieldsData = array(
        'filter' => '\\AppMapEvents\\Filter\\EventsGeneralTaxFilter',
        'field'  => '\\AppMapEvents\\Filter\\EventsGeneralTaxField',
    );

    /**
     * Filters Fields
     *
     * @since 1.0.0
     *
     * @var array
     */
    private $filtersFields;

    /**
     * Filters
     *
     * @since 1.0.0
     *
     * @var array
     */
    private $filters;

    /**
     * Build Filters
     *
     * @since 1.0.0
     */
    private function buildFilters()
    {
        foreach (self::$ownFiltersFields as $name => $data) {
            $this->filters[$name] = array_merge($data, self::$ownFiltersFieldsData);
        }
    }

    /**
     * FiltersFieldsFilter constructor
     *
     * @since 1.0.0
     *
     * @param array $filtersFields
     */
    public function __construct(array $filtersFields = array())
    {
        $this->filtersFields = $filtersFields;

        $this->buildFilters();
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
        $this->filters = array_merge($this->filtersFields, $this->filters);

        return $this;
    }

    /**
     * Filters Fields
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function filtersFields()
    {
        return $this->filters;
    }

    /**
     * Filter Fields Helper
     *
     * @since 1.0.0
     *
     * @param array $filtersFields
     *
     * @return array The filters fields filtered list
     */
    public static function filterFilter(array $filtersFields = array())
    {
        $instance = new self($filtersFields);

        return $instance->filter()
                        ->filtersFields();
    }
}
