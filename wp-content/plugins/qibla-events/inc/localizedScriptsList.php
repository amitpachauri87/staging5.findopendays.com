<?php
/**
 * Localized Scripts List
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

use QiblaFramework\Functions as F;

$permalink = F\getOption('qibla_permalinks');

// Get saved events dates.
$datesEvents = F\getTermsList(array(
    'taxonomy'   => 'event_dates',
    'hide_empty' => true,
));

// Get current saved date.
$currentDate = F\getPostMeta('_qibla_mb_event_dates_multidatespicker_start', get_the_ID());

// Filter dates: delete dates before today.
//$datesAfterToday = array_filter($datesEvents, function($v) {
//    $today = new DateTime('now');
//    $day = new DateTime($v);
//    if ($day->getTimestamp() > $today->getTimestamp()) {
//        return $v;
//    }
//});

$list = array(
    'localized' => array(
        array(
            'handle' => is_admin() ? 'admin' : 'front',
            'name'   => 'evlocalized',
            array(
                'lang'                     => get_bloginfo('language'),
                'date_format'              => get_option('date_format'),
                'time_format'              => get_option('time_format'),
                'site_url'                 => esc_url(site_url()),
                'charset'                  => get_option('blog_charset') ?: 'UTF-8',
                'loggedin'                 => is_user_logged_in(),
                'events_permalink'         => ! empty($permalink['permalink_events_cpt']) ? $permalink['permalink_events_cpt'] : 'events',
                'dates_permalink'          => ! empty($permalink['permalink_event_dates_tax']) ? $permalink['permalink_event_dates_tax'] : 'event-dates',
                'label_calendar'           => esc_html__('Calendar', 'qibla-events'),
                'dates_saved_events'       => is_array($datesEvents) ? array_values($datesEvents) : array(),
                'first_date_events'        => is_array($datesEvents) ? reset($datesEvents) : '',
                'event_saved_default_date' => is_admin() && isset($currentDate) && ! is_int($currentDate) ? $currentDate : '',
                'event_date_month_year'    => is_admin() ? true : false,
            ),
        ),
    ),
);

/**
 * Filter Localized Scripts
 *
 * @since 1.0.0
 *
 * @param array $list The list of the localized scripts data
 */
return apply_filters('appmap_ev_localized_scripts_list', $list);
