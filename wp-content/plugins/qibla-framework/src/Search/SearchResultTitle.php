<?php
/**
 * SearchResultTitle
 *
 * @since      2.3.0
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

namespace QiblaFramework\Search;

use QiblaFramework\Functions as F;

/**
 * Class SearchResultTitle
 *
 * @since  2.3.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class SearchResultTitle
{
    /**
     * Title
     *
     * @since 1.0.0
     *
     * @var string The title to filter
     */
    private $title;

    /**
     * ArchiveTitleFilter constructor
     *
     * @since 1.0.0
     *
     * @param string $title The title to filter
     */
    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * @inheritdoc
     */
    public function filter()
    {
        if (is_search()) {
            // @codingStandardsIgnoreStart
            $taxonomyFilter = F\filterInput($_POST, 'qibla_taxonomy_filter_taxonomy', FILTER_SANITIZE_STRING);
            $value          = F\filterInput($_POST, 'qibla_' . $taxonomyFilter . '_filter', FILTER_SANITIZE_STRING);
            $geocoded       = F\filterInput($_POST, 'geocoded', FILTER_SANITIZE_STRING);

            if (! $geocoded && ! $taxonomyFilter) {
                $geocoded = isset($_POST['geocoded']) && isset($_POST['geocoded']['address']) ? $_POST['geocoded']['address'] : '';
            }
            // @codingStandardsIgnoreEnd

            if ($geocoded) {
                $value = $geocoded;
            }

            if ($value) {
                $value = ucfirst(str_replace('-', ' ', $value));
            } else {
                $value = get_search_query();
            }

            $title['title'] = sprintf(
                __('Search Results for &#8220;%s&#8221;'),
                $value
            );

            return $title;
        }

        return $this->title;
    }

    /**
     * Filter Helper
     *
     * @since 1.0.0
     *
     * @param string $title The string to filter
     *
     * @return string the filtered string
     */
    public static function filterFilter($title)
    {
        $instance = new self($title);

        return $instance->filter();
    }
}
