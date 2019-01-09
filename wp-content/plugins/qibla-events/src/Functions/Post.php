<?php
/**
 * Post.php
 *
 * @since      1.0.0
 * @package    ${NAMESPACE}
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
use QiblaFramework\Listings\ListingLocation;
use QiblaFramework\ListingsContext\Types;

/**
 * LooFooter events post article
 *
 * @since 1.0.0
 *
 * @param null $post
 *
 * @throws \Exception
 */
function loopFooter($post = null)
{
    $post = get_post($post);

    // Default condition.
    $isPostType = false;
    if (class_exists('QiblaFramework\\ListingsContext\\Types')) {
        $types      = new Types();
        $isPostType = $types->isListingsType($post->post_type);
    }

    if (! $post && ! $isPostType) {
        return;
    }

    $data = new \stdClass();

    // Get address.
    $location = new ListingLocation(get_post($post));
    // This overwrite the parent data.
    $data->address = $location->address();

    // Initialized.
    $data->eventsDateStart        = null;
    $data->eventsDateStartDay     = null;
    $data->eventsDateStartMouth   = null;
    $data->eventsDateStartDayText = null;
    $data->equalDate              = false;
    $startTimestamp               = $endTimestamp = false;

    // Get Date start.
    $dateStart = \QiblaFramework\Functions\getPostMeta('_qibla_mb_event_dates_multidatespicker_start', '');
    if (isset($dateStart) && '' !== $dateStart) {
        $date                         = new \DateTime($dateStart);
        $startTimestamp               = intval($date->getTimestamp());
        $data->eventsDateStart        = date_i18n('c', intval($date->getTimestamp())) ?: '';
        $data->eventsDateStartDay     = date_i18n('d', intval($date->getTimestamp())) ?: '';
        $data->eventsDateStartMouth   = date_i18n('M', intval($date->getTimestamp())) ?: '';
        $data->eventsDateStartDayText = date_i18n('D', intval($date->getTimestamp())) ?: '';
    }

    // Get Date end.
    $dateEnd = \QiblaFramework\Functions\getPostMeta('_qibla_mb_event_dates_multidatespicker_end', '');
    if (isset($dateEnd) && '' !== $dateEnd) {
        $date                       = new \DateTime($dateEnd);
        $endTimestamp               = intval($date->getTimestamp());
        $data->eventsDateEnd        = date_i18n('c', intval($date->getTimestamp())) ?: '';
        $data->eventsDateEndDay     = date_i18n('d', intval($date->getTimestamp())) ?: '';
        $data->eventsDateEndMouth   = date_i18n('M', intval($date->getTimestamp())) ?: '';
        $data->eventsDateEndDayText = date_i18n('D', intval($date->getTimestamp())) ?: '';
    }

    $data->equalDate = is_int($startTimestamp) && is_int($endTimestamp) && $startTimestamp === $endTimestamp ? true : false;

    $engine = new Engine('events_loop_footer', $data, '/views/loopFooter.php');
    $engine->render();
}