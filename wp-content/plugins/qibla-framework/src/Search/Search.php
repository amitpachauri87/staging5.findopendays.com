<?php
/**
 * Search
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @package   dreamlist-framework
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

namespace QiblaFramework\Search;

use QiblaFramework\Form\Factories\FieldFactory;
use QiblaFramework\ListingsContext\Types;
use QiblaFramework\Front\Settings;
use QiblaFramework\Search\Field\Search as SearchField;

/**
 * Class Search
 *
 * @since   2.0.0
 * @package QiblaFramework\Search
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class Search
{
    /**
     * Search Filter
     *
     * Basic search form. Allow listings and post types.
     *
     * @since 2.0.0
     *
     * @return void
     */
    public static function searchFilter()
    {
        $types   = new Types();
        $types   = $types->types();
        $types[] = 'post';

        $search = new FormTemplate('search_form', $types, array(
            new SearchField(new FieldFactory(), array()),
        ), 'simple', 'get');
        $search->tmpl($search->getData());
    }

    /**
     * Search By Option
     *
     * @since 2.0.0
     *
     * @return void
     */
    public static function searchByOptionsFilter()
    {
        if (is_page_template('templates/homepage.php') ||
            is_page_template('templates/homepage-fullwidth.php') ||
            is_404()
        ) {
            $factory = new SearchFactory(
                Settings\Search::searchType(),
                // Unique type of listings in the search bar.
                array('events'),
                'search_homepage_form',
                'post'
            );

            $search = $factory->create();
            $search->tmpl($search->getData());
        }
    }
}
