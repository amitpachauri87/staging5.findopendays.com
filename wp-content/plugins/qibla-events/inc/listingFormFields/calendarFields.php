<?php
/**
 * calendarFields
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

use QiblaFramework\Functions as Fw;
use QiblaFramework\Form\Factories\FieldFactory;

$fieldFactory       = new FieldFactory();
$pluginFieldFactory = new \AppMapEvents\Factories\PluginFieldFactory();
$timeZone           = new \QiblaFramework\Utils\TimeZone();
$date               = new DateTime();
$timeZone           = new DateTimeZone($timeZone->getTimeZone()->getName());
$date->setTimezone($timeZone);

// Get time data.
$timeStart = Fw\getPostMeta('_qibla_mb_event_start_time_timepicker', '', $post);
$timeEnd   = Fw\getPostMeta('_qibla_mb_event_end_time_timepicker', '', $post);

/**
 * Listing Form Fields
 *
 * @since 1.0.0
 *
 * @param array $args The list of the fields
 */
return apply_filters('qibla_listings_form_calendar_fields', array(
    /**
     * Event Date In
     *
     * @since 1.0.0
     */
    'qibla_listing_form-meta-event_dates_multidatespicker:multidates' => $pluginFieldFactory->base(array(
        'type'                => 'multi_dates',
        'name'                => 'qibla_listing_form-meta-event_dates_multidatespicker',
        'label'               => esc_html__('Event Dates', 'qibla-events'),
        'container_class'     => array('dl-field', 'dl-field--dates', 'dl-field--in-column'),
        'description'         => '',
        'attrs'               => array(
            'required'    => 'required',
            'data-format' => 'dd-mm-yyyy',
            'placeholder' => esc_html_x('yy-mm-dd', 'placeholder', 'qibla-events'),
            'value'       => '' !== Fw\getPostMeta('_qibla_mb_event_dates_multidatespicker', '', $post) ?
                sanitize_text_field(Fw\getPostMeta('_qibla_mb_event_dates_multidatespicker', '', $post)) : array(),

        ),
        'invalid_description' => esc_html__('Please select at least one date from the calendar.', 'qibla-events'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
    )),

    /**
     * Event Start Time
     *
     * @since 1.0.0
     */
    'qibla_listing_form-meta-event_start_time_timepicker:time'        => $pluginFieldFactory->base(array(
        'type'            => 'time',
        'name'            => 'qibla_listing_form-meta-event_start_time_timepicker',
        'label'           => esc_html__('Start Time', 'qibla-events'),
        'container_class' => array('dl-field', 'dl-field--time', 'dl-field--in-column'),
        'description'     => '',
        'attrs'           => array(
            'value'    => '' !== $timeStart ? $date->setTimestamp($timeStart)->format('H:i') : '',
            'placeholder' => esc_html_x('14:00', 'placeholder', 'qibla-events'),
        ),
        'invalid_description' => esc_html__('Please select time.', 'qibla-events'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
    )),

    /**
     * Event End Time
     *
     * @since 1.0.0
     */
    'qibla_listing_form-meta-event_end_time_timepicker:time'          => $pluginFieldFactory->base(array(
        'type'            => 'time',
        'name'            => 'qibla_listing_form-meta-event_end_time_timepicker',
        'label'           => esc_html__('End Time', 'qibla-events'),
        'container_class' => array('dl-field', 'dl-field--time', 'dl-field--in-column'),
        'description'     => '',
        'attrs'           => array(
            'value'    => '' !== $timeEnd ? $date->setTimestamp($timeEnd)->format('H:i') : '',
            'placeholder' => esc_html_x('19:00', 'placeholder', 'qibla-events')
        ),
        'invalid_description' => esc_html__('Please select time.', 'qibla-events'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
    )),

), $fieldsValues, $post);