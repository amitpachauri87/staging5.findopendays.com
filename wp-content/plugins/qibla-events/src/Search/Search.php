<?php
/**
 * Search
 *
 * @since      1.1.0
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

namespace AppMapEvents\Search;

/**
 * Class Search
 *
 * @since  1.1.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class Search
{
    /**
     * Search Type
     *
     * @since 1.1.0
     *
     * @return string The search type slug.
     */
    public static function searchType()
    {
        return \QiblaFramework\Functions\getThemeOption('events', 'search-type', true);
    }

    /**
     * Search By Option
     *
     * @since 1.1.0
     *
     * @return void
     * @throws \Exception
     */
    public static function searchByOptionsFilter()
    {
        if (is_page_template('templates/events-search.php')) {
            $factory = new SearchFactory(
                self::searchType(),
                // Unique type of listings in the search bar.
                array('events'),
                'search_events_form',
                'post'
            );

            $search = $factory->create();
            $search->tmpl($search->getData());
        }
    }
}
