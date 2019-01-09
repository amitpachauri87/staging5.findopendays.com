<?php
/**
 * EventsQuery
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
use QiblaFramework\ListingsContext\Context;
use QiblaFramework\ListingsContext\Types;
use QiblaFramework\Utils\TimeZone;

/**
 * Class EventsQuery
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class EventsQuery implements FilterInterface
{
    /**
     * Query
     *
     * @since 1.0.0
     *
     * @var \WP_Query The query
     */
    private $query;

    /**
     * Context
     *
     * @since 1.0.0
     *
     * @var Context The context
     */
    private $context;

    /**
     * EventsQuery constructor.
     *
     * @since 1.0.0
     *
     * @param $query \WP_Query The query
     */
    public function __construct($query, Context $context)
    {
        $this->query   = $query;
        $this->context = $context;
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
        $eventsTax = array(
            'event_categories',
            'event_locations',
            'event_tags',
        );

        if ($this->query->is_main_query() &&
            $this->query->is_post_type_archive('events') ||
            $this->query->queried_object instanceof \WP_Term && in_array($this->query->queried_object->taxonomy, $eventsTax)
        ) {

            // Get current meta key.
            $currentMetaKey = $this->query->get('meta_key');
            $currMetaQuery  = (array)$this->query->get('meta_query');

            // Today Datetime.
            $timeZone = new TimeZone();
            $timeZone = new \DateTimeZone($timeZone->getTimeZone()->getName());
            $today    = new \DateTime('today');
            $today->setTimezone($timeZone);

            // Set orderby meta_key and order.
            if ('' === $currentMetaKey) {
                $this->query->set('orderby', 'meta_value_num');
                $this->query->set('meta_key', '_qibla_mb_event_dates_start_for_orderby');
                $this->query->set('order', 'ASC');
            }

            $metaQuery = array(
                'key'     => '_qibla_mb_event_dates_multidatespicker_end',
                'value'   => $today->format('Y-m-d'),
                'type'    => 'DATE',
                'compare' => '>',
            );

            if (! empty($currMetaQuery)) {
                $currMetaQuery = array_filter($currMetaQuery);
                array_push($currMetaQuery, $metaQuery);
            } else {
                $currMetaQuery = $metaQuery;
            }

            if (! isset($currMetaQuery['relation'])) {
                $currMetaQuery['relation'] = 'AND';
            }

            // Set the query arguments.
            $this->query->set('meta_query', $currMetaQuery);
        }

        return $this;
    }

    /**
     * @param $query
     *
     * @since 1.0.0
     *
     * @return EventsQuery
     */
    public static function filterFilter($query)
    {
        $instance = new self($query, new Context(\QiblaFramework\Functions\getWpQuery(), new Types()));

        return $instance->filter();
    }
}