<?php

namespace AppMapEvents;

/**
 * Class Activate
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

use AppMapEvents\Taxonomy\Tags;
use AppMapEvents\Taxonomy\EventCategories;
use AppMapEvents\Taxonomy\EventDates;
use AppMapEvents\Taxonomy\Locations;
use QiblaFramework as Qfw;

/**
 * Class Activate
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class Activate
{
    /**
     * Register Post Types & Taxonomies
     *
     * @since  1.0.0
     *
     * @return void
     */
    private static function registerCptTax()
    {
        $register = new Qfw\PostType\Register(array(
            new PostType\Events(),
            new PostType\ListingPackage()
        ));
        $register->register();

        $register = new Qfw\Taxonomy\Register(array(
            new Locations(),
            new Tags(),
            new EventCategories(),
            new EventDates(),
        ));
        $register->register();
    }

    /**
     * Plugin Activate
     *
     * @since  1.0.0
     *
     * @return void
     */
    public static function activate()
    {
        if (\AppMapEvents\Functions\checkDependencies()) :
            // Register post types and Taxonomies.
            self::registerCptTax();
        endif;

        // Flush rules.
        flush_rewrite_rules();
    }
}
