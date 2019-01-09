<?php
/**
 * Template
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

namespace AppMapEvents\Functions;

use AppMapEvents\TemplateEngine\Engine;
use QiblaFramework\Exceptions\InvalidPostException;
use QiblaFramework\Listings\ListingLocation;
use QiblaFramework\Utils\TimeZone;
use QiblaFramework\Functions as Ffw;

/**
 * Map Marker InfoBox
 *
 * @since 1.0.0
 *
 * @param $engine
 *
 * @return Engine
 */
function eventInfoBoxTmpl($engine)
{
    $obj       = get_queried_object();
    $eventsTax = array(
        'event_categories',
        'event_locations',
        'event_tags',
    );

    // Filter Engine for Events Archive.
    if (is_post_type_archive('events') || $obj instanceof \WP_Term && in_array($obj->taxonomy, $eventsTax)) {
        $engine = new Engine('map_template_infobox', new \stdClass(), '/views/map/infoWindow.php');
    }

    return $engine;
}

/**
 * Add event Calendar template
 *
 * @since  1.0.0
 *
 * @throws \Exception
 */
function addEventCalendar()
{
    $engine = new Engine('the_add_event_calendar', new \stdClass(), 'views/events/eventCalendar.php');
    $engine->render();
}

/**
 * calendarScripts
 *
 * @since 1.0.0
 *
 *
 * @param null $post
 *
 * @throws InvalidPostException
 */
function calendarScripts($post = null)
{
    if (is_admin() || ! is_singular('events')) {
        return;
    }

    $post = get_post($post);

    if (! $post) {
        throw new InvalidPostException();
    }

    // Get post ID.
    $postID = intval($post->ID);

    // Data for View.
    $data = new \stdClass();

    // Set Time Zone.
    $timeZone = new TimeZone();
    $timeZone = new \DateTimeZone($timeZone->getTimeZone()->getName());
    $date     = new \DateTime();
    $date->setTimezone($timeZone);

    // Get Dates.
    $dateStart = Ffw\getPostMeta('_qibla_mb_event_dates_multidatespicker_start', '', $postID, true);
    $dateEnd   = Ffw\getPostMeta('_qibla_mb_event_dates_multidatespicker_end', '', $postID, true);
    $timeStart = Ffw\getPostMeta('_qibla_mb_event_start_time_timepicker', '', $postID, true);
    $timeEnd   = Ffw\getPostMeta('_qibla_mb_event_end_time_timepicker', '', $postID, true);

    if ($timeStart) {
        $dateTime           = setDateTimeFromTimeAndDate($timeStart, $dateStart);
        $data->eventsDateIn = $dateTime->format('M d, Y H:i');
    } else {
        $date               = new \DateTime($dateStart);
        $data->eventsDateIn = $date->format('M d, Y H:i');
    }

    if ($timeEnd) {
        $dateTime            = setDateTimeFromTimeAndDate($timeEnd, $dateEnd);
        $data->eventsDateOut = $dateTime->format('M d, Y H:i');
    } else {
        $date                = new \DateTime($dateEnd);
        $data->eventsDateOut = $date->format('M d, Y H:i');
    }

    // Retrieve the location data.
    $location = new ListingLocation(get_post($postID));

    $data->title       = esc_html($post->post_title);
    $content           = stripContent($post->post_content);
    $data->description = sanitize_text_field(wp_trim_words($content, 25, '...'));
    // Create and Add the location properties if a valid location is provided.
    if ($location->isValidLocation()) {
        $data->location = $location->address();
    }
   
    $title = sprintf('<i style="color:white;font-size:40px;" class="la la-calendar-plus-o" aria-hidden="true"></i> <span>%s</span>',
        esc_html__('', 'qibla-events')
    );
    print <<<SCRIPT
<script>
        ;(
            function (window, $) {
                var addCalendar = createCalendar({
                    options:{class:"dlevents-calendar",id:"dlevents-cal"},
                    data:{label:'$title',title:"$data->title",start:new Date('$data->eventsDateIn'),duration:"",
                    end:new Date('$data->eventsDateOut'),address:"$data->location",description:"$data->description"}});
                var calendar = document.querySelector('.dlevents__items--calendar');
                if (!calendar){return;}
                calendar.appendChild(addCalendar);
            }(window, jQuery)
        );
    </script>
SCRIPT;
}

/**
 * Events Location
 *
 * @since  1.0.0
 *
 * @param null $post
 *
 * @throws InvalidPostException
 * @throws \Exception
 */
function addEventLocation($post = null)
{
    $post = get_post($post);

    if (! $post) {
        throw new InvalidPostException();
    }

    if (! is_singular('events')) {
        return;
    }

    // Get post ID.
    $postID = intval($post->ID);

    // Data for View.
    $data = new \stdClass();

    // Retrieve the location data.
    $location = new ListingLocation(get_post($postID));

    // Create and Add the location properties if a valid location is provided.
    if ($location->isValidLocation()) {
        $data->location = $location->address();
    }

    $engine = new Engine('the_event_location', $data, 'views/events/eventLocation.php');
    $engine->render();
}

/**
 * Event Phone and Site url
 *
 * @since 1.1.0
 *
 * @param null $post
 *
 * @throws InvalidPostException
 * @throws \Exception
 */
function addEventSiteAndTel($post = null)
{
    if (is_admin() || ! is_singular('events')) {
        return;
    }

    $post = get_post($post);

    if (! $post) {
        throw new InvalidPostException();
    }

    // Get post ID.
    $postID = intval($post->ID);

    // Data for View.
    $data = new \stdClass();

    $data->phone   = null;
    $data->siteUrl = null;

    // Get the fields Values.
    $businessPhone = Ffw\getPostMeta('_qibla_mb_business_phone', '', $postID, true);
    $siteUrl       = Ffw\getPostMeta('_qibla_mb_site_url', '', $postID, true);

    if ($businessPhone) {
        $data->phone = $businessPhone;
    }
    if ($siteUrl) {
        $urlHost = wp_parse_url($siteUrl);
        $urlHost = $urlHost ? $urlHost['host'] : $siteUrl;
        $urlHost = str_replace(array('http://', 'https://', 'www.'), '', $urlHost);

        $data->siteUrl     = $siteUrl;
        $data->siteUrlHost = $urlHost;
    }

    $engine = new Engine('the_event_phone_and_site_url', $data, 'views/events/phoneAndSiteUrl.php');
    $engine->render();
}
