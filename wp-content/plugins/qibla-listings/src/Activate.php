<?php
/**
 * Class Activate
 *
 * @since      1.0.0
 * @package    QiblaListings
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (c) 2017 Guido Scialfa <dev@guidoscialfa.com>
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

namespace QiblaListings;

use QiblaFramework\Capabilities\Register as FwCapRegister;
use QiblaFramework\EndPoint;
use QiblaListings\Capabilities;
use QiblaListings\Listing\ManagerPosts\MyListingsEndPoint;

/**
 * Class Activate
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class Activate
{
    /**
     * Add Role For Listings Authors
     *
     * @since  1.0.0
     * @access private static
     *
     * @return void
     */
    private static function addRoleListingsAuthor()
    {
        $list = array(
            new FwCapRegister(array(
                new Capabilities\ListingsAuthor(),
            )),
        );

        // Register the Roles/Capabilities.
        foreach ($list as $register) {
            $register->register();
        }
    }

    /**
     * Register Custom Endpoints
     *
     * @since  1.0.0
     * @access private static
     *
     * @return void
     */
    private static function addCustomEndPoints()
    {
        $endpointsRegister = new EndPoint\Register(array(
            new MyListingsEndPoint(),
        ));
        $endpointsRegister->register();
    }

    /**
     * Register Post Types & Taxonomies
     *
     * @since  1.0.0
     * @access private
     *
     * @return void
     */
    private static function registerCptTax()
    {
        $register = new PostType\Register(array(
            new PostType\ListingPackage(),
        ));
        $register->register();
    }

    /**
     * Plugin Activate
     *
     * @since  1.0.0
     * @access static
     *
     * @return void
     */
    public static function activate()
    {
        // Don't do anything if the check not pass.
        if (\QiblaListings\Functions\checkDependencies()) :
            // Register the post types.
            self::registerCptTax();
            // Register Custom Endpoints.
            self::addCustomEndPoints();
            // Add the User listings Author roles.
            self::addRoleListingsAuthor();
        endif;

        // Needed by Endpoints and other stuffs.
        flush_rewrite_rules();
    }
}
