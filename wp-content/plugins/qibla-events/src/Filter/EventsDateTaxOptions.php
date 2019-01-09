<?php
/**
 * EventsDateTaxOptions
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

use QiblaFramework\Query\MetaQueryArguments;
use QiblaFramework\Utils\TimeZone;

/**
 * Class EventsDateTaxOptions
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class EventsDateTaxOptions
{
    /**
     * Relative format option value
     *
     * @since 1.0.0
     *
     * @var string The relative format for option value
     */
    private $date, $dateStart, $dateEnd = 'now';

    /**
     * Relative format option tex
     *
     * @since 1.0.0
     *
     * @var string The relative format for option text
     */
    private $formatString;

    /**
     * Dates
     *
     * @since 1.0.0
     *
     * @var array The Dates array for select options
     */
    private $datesOption = array();

    /**
     * Options Dates
     *
     * @since 1.0.0
     *
     * @return array The Dates fot the select option
     */
    public function optionsDates()
    {
        // Set TimeZone and DateTime.
        $timeZone = new TimeZone();
        $timeZone = new \DateTimeZone($timeZone->getTimeZone()->getName());
        $today    = new \DateTime('today');
        $today->setTimezone($timeZone);

        // Create select options array.
        $this->datesOption = array(
            'all' => array(
                'attrs' => array(),
                'label' => esc_attr__('All Dates', 'qibla-events'),
            ),

            $this->datesOptionValue('today', 'today +1 year') => array(
                'attrs' => array('data-markup' => esc_attr__('All Dates', 'qibla-events')),
                'label' => esc_attr__('from today to a year', 'qibla-events'),
            ),

            $this->dateOptionValue('today') => array(
                'attrs' => array('data-markup' => esc_attr__('To Day', 'qibla-events')),
                'label' => $this->dateOptionString('today'),
            ),

            $this->dateOptionValue('tomorrow') => array(
                'attrs' => array('data-markup' => esc_attr__('Tomorrow', 'qibla-events')),
                'label' => $this->dateOptionString('tomorrow'),
            ),

            $this->datesOptionValue('this week +4 days', 'this week +6 days') => array(
                'attrs' => array('data-markup' => esc_attr__('Week End', 'qibla-events')),
                'label' => $this->datesOptionString('this week +4 days', 'this week +6 days', 'j F'),
            ),

            $this->datesOptionValue('this week', 'this week +6 days') => array(
                'attrs' => array('data-markup' => esc_attr__('This Week', 'qibla-events')),
                'label' => $this->datesOptionString('this week', 'this week +6 days', 'j F'),
            ),

            $this->datesOptionValue('next week', 'next week +6 days') => array(
                'attrs' => array('data-markup' => esc_attr__('Next Week', 'qibla-events')),
                'label' => $this->datesOptionString('next week', 'next week +6 days', 'j F'),
            ),

            $this->datesOptionValue('first day of this month', 'last day of this month') => array(
                'attrs' => array('data-markup' => esc_attr__('This Month', 'qibla-events')),
                'label' => $this->datesOptionString('first day of this month', 'last day of this month', 'j F'),
            ),
        );

        /* Override option to activate only the calendar. -------------------------------------- */
        $obj   = get_queried_object();
        $value = isset($obj->taxonomy) && 'event_dates' === $obj->taxonomy ? $obj->slug : 'all';
        $label = esc_attr__('All Dates', 'qibla-events');

        if ('all' !== $value) {
            $date  = new \DateTime($value);
            $label = date_i18n('Y-m-d', $date->getTimestamp());
        }

        // Override options
        $this->datesOption = array(
            'all' => array(
                'attrs' => array(),
                'label' => esc_attr__('All Dates', 'qibla-events'),
            ),
        );

        // Add dates in event_dates taxonomy.
        $this->datesOption = array_merge($this->datesOption,
            array(
                'days' => \QiblaFramework\Functions\getTermsList(array(
                    'taxonomy'   => 'event_dates',
                    'hide_empty' => false,
                )),
            ));

        if ('all' !== $value) {
            $this->datesOption = array_merge(array(
                'all' => array(
                    'attrs' => array(),
                    'label' => esc_attr__('All Dates', 'qibla-events'),
                ),
            ), $this->datesOption);
        }
        /* Override option to activate only the calendar. -------------------------------------- */

        return $this->datesOption;
    }

    /**
     * Calendar Scripts.
     *
     * @since 1.1.0
     */
    public function calendarScripts()
    {
        // Script
        if (wp_script_is('appmap-ev-calendar-filter', 'registered')) {
            wp_enqueue_script('jquery-ui-datepicker', array('jquery'));
            wp_enqueue_script('appmap-ev-calendar-filter', array('jquery-ui-datepicker'));
        }
        // Lang
        if (wp_script_is('datepicker-lang', 'registered')) {
            wp_enqueue_script('datepicker-lang', array('jquery', 'jquery-ui-datepicker'));
        }
    }

    /**
     * Date Time
     *
     * @since 1.0.0
     *
     * @param $date string The relative format date
     *
     * @return \DateTime The DateTime object
     */
    private function dateTime($date)
    {
        $this->date = $date;
        $date       = new \DateTime($this->date);

        return $date;
    }

    /**
     * Date option value
     *
     * @since 1.0.0
     *
     * @param $date
     *
     * @return string
     */
    private function dateOptionValue($date)
    {
        $date        = $this->dateTime($date);
        $optionValue = $date->format('Y-m-d');

        return $optionValue;
    }

    /**
     * Date option text
     *
     * @since 1.0.0
     *
     * @param        $date   string The relative format date
     * @param string $format string The format
     *
     * @return string
     */
    private function dateOptionString($date, $format = 'l j F')
    {
        $this->formatString = $format;

        $date         = $this->dateTime($date);
        $optionString = date_i18n($this->formatString, intval($date->getTimestamp()));

        return $optionString;
    }

    /**
     * Dates option value
     *
     * @since 1.0.0
     *
     * @param        $dateStart string The relative format start date
     * @param        $dateEnd   string The relative format end date
     * @param string $sep       string The date separator
     *
     * @return string
     */
    private function datesOptionValue($dateStart, $dateEnd, $sep = ',')
    {
        $this->dateStart = $dateStart;
        $this->dateEnd   = $dateEnd;

        $optionValue = $this->dateOptionValue($this->dateStart) . $sep . $this->dateOptionValue($this->dateEnd);

        return $optionValue;
    }

    /**
     * Dates option text
     *
     * @since 1.0.0
     *
     * @param        $dateStart string The relative format start date
     * @param        $dateEnd   string The relative format end date
     * @param string $format    string The format
     * @param string $sep       string The date separator
     *
     * @return string
     */
    private function datesOptionString($dateStart, $dateEnd, $format = 'l j F', $sep = ' - ')
    {
        $this->dateStart = $dateStart;
        $this->dateEnd   = $dateEnd;

        $optionString = $this->dateOptionString($this->dateStart,
                $format) . $sep . $this->dateOptionString($this->dateEnd, $format);

        return $optionString;
    }

    /**
     * Build Meta Query
     *
     * @since 1.0.0
     *
     * @param \WP_Query $wpQuery
     * @param           $args
     */
    public function buildMetaQuery(\WP_Query &$wpQuery, $args)
    {
        $stringValue = implode(',', $args);
        // Check if exist two date.
        if (false !== strpos($stringValue, ',')) {
            $value = explode(',', $stringValue);
        } else {
            $value = $stringValue;
        }

        $metaQueryArgs = array();
        // One Date Value.
        if (! is_array($value)) {
            $metaQueryArgs = array(
                'relation' => 'AND',
                array(
                    'key'     => '_qibla_mb_event_dates_multidatespicker_start',
                    'value'   => $value,
                    'type'    => 'DATE',
                    'compare' => '<=',
                ),
                array(
                    'key'     => '_qibla_mb_event_dates_multidatespicker_end',
                    'value'   => $value,
                    'type'    => 'DATE',
                    'compare' => '>=',
                ),
            );
        }

        // Array Date Value.
        if (is_array($value)) {
            $metaQueryArgs = array(
                'relation' => 'AND',
                array(
                    'relation' => 'OR',
                    array(
                        'key'     => '_qibla_mb_event_dates_multidatespicker_start',
                        'value'   => $value[0],
                        'type'    => 'DATE',
                        'compare' => '<=',
                    ),
                    array(
                        'key'     => '_qibla_mb_event_dates_multidatespicker_start',
                        'value'   => $value,
                        'type'    => 'DATE',
                        'compare' => 'BETWEEN',
                    ),
                ),
                array(
                    'relation' => 'OR',
                    array(
                        'key'     => '_qibla_mb_event_dates_multidatespicker_end',
                        'value'   => $value,
                        'type'    => 'DATE',
                        'compare' => 'BETWEEN',
                    ),
                    array(
                        'key'     => '_qibla_mb_event_dates_multidatespicker_end',
                        'value'   => $value[1],
                        'type'    => 'DATE',
                        'compare' => '>=',
                    ),

                ),
            );
        }

        $metaQuery = new MetaQueryArguments($metaQueryArgs);
        $metaQuery->buildQueryArgs($wpQuery);
    }
}
