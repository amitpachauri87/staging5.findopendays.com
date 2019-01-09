<?php
/**
 * UpdateLocationOptionOldMeta
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2017, Guido Scialfa
 * @license   GNU General Public License, version 2
 *
 * Copyright (C) 2017 Guido Scialfa <dev@guidoscialfa.com>
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

namespace QiblaFramework\Update;

use QiblaFramework\Listings\ListingLocation;

/**
 * Class UpdateLocationOptionOldMeta
 *
 * @since  1.7.1
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class UpdateLocationOptionOldMeta
{
    private static $option = 'qibla_meta_locations_no_updated';

    /**
     * @param $metaId
     * @param $objectId
     * @param $metaKey
     * @param $metaValue
     */
    public static function updateFilter($metaId, $objectId, $metaKey, $metaValue)
    {
        static $counter = 0;

        if (wp_doing_ajax()) {
            return;
        }

        $metaKeys = array(
            ListingLocation::LISTING_LAT_META_KEY,
            ListingLocation::LISTING_LNG_META_KEY,
        );

        if (! in_array($metaKey, $metaKeys, true)) {
            return;
        }

        $invalid = get_option(self::$option) ?: array();
        if (! $invalid) {
            return;
        }

        // Get the ID's of the options to check against the current we are saving.
        $ids = array_map('intval', (array)wp_list_pluck($invalid, 'id'));
        if (! $ids) {
            return;
        }

        // Check if post is one to be removed or not from the list.
        if (in_array(intval($objectId), $ids, true)) {
            if (1 === $counter) {
                // Try to get the meta to know if the post has been update with the new values.
                $index = array_search($objectId, $ids, true);
                unset($invalid[$index]);
                update_option(self::$option, $invalid);
            }

            ++$counter;
        }
    }
}
