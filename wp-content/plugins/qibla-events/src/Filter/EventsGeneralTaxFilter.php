<?php
/**
 * Filters Categories
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

use QiblaFramework\Filter\FilterFieldInterface;
use QiblaFramework\Filter\FilterInterface;
use QiblaFramework\Filter\Positionable;
use QiblaFramework\Query\TaxQueryArguments;

/**
 * Class BBtripGeneralTaxFilter
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class EventsGeneralTaxFilter extends EventsDateTaxOptions implements FilterInterface, Positionable
{
    /**
     * Position
     *
     * @since 1.0.0
     *
     * @var string The position where the filter should appear
     */
    private static $position = array('normal', 'hidden');

    /**
     * Filter
     *
     * @since 1.0.0
     */
    private $field;

    /**
     * Name of the filter
     *
     * @since 1.0.0
     *
     * @var string The name of the filter
     */
    private $name;

    /**
     * Taxonomy
     *
     * @since 1.0.0
     *
     * @var mixed The taxonomy associated to the filter
     */
    private $taxonomy;

    /**
     * EventsGeneralTaxFilter constructor.
     *
     * @since 1.0.0
     *
     * @param FilterFieldInterface $field
     */
    public function __construct(FilterFieldInterface $field)
    {
        $this->field    = $field;
        $this->name     = $field->field()->getArg('name');
        $this->taxonomy = str_replace(array('qibla_', '_filter'), '', $this->name);
    }

    /**
     * @inheritdoc
     */
    public function queryFilter(\WP_Query &$wpQuery, $args)
    {
        if (! $args || 'all' === $args[0]) {
            return;
        }

        if ('event_dates' === $this->taxonomy) {
            // Meta Query.
            $this->buildMetaQuery($wpQuery, $args);
        } else {
            $taxQuery = new TaxQueryArguments(array(
                'relation' => 'AND',
                array(
                    'taxonomy' => $this->taxonomy,
                    'field'    => 'slug',
                    'terms'    => $args,
                ),
            ));
            $taxQuery->buildQueryArgs($wpQuery);
        }
    }

    /**
     * @inheritdoc
     */
    public function position()
    {
        if ('event_tags' === $this->taxonomy) {
            return self::$position[1];
        }

        return self::$position[0];
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function field()
    {
        return $this->field->field();
    }
}
