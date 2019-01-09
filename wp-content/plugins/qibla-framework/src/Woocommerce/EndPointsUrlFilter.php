<?php
/**
 * EndPointsUrlFilter
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

namespace QiblaFramework\Woocommerce;

use QiblaFramework\User\User;

/**
 * Class EndPointsUrlFilter
 *
 * @since  1.7.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class EndPointsUrlFilter
{
    /**
     * Customer Logout Url
     *
     * @since 1.7.0
     *
     * @uses  User::userLogoutRedirect() To build the url in the same way of the framework.
     *
     * @param string $url      The current customer endpoint url.
     * @param string $endpoint The current endppoint.
     *
     * @return string The filter url endpoint
     */
    public static function userLogoutWcRedirect($url, $endpoint)
    {
        $customerLogoutEndPoint = get_option('woocommerce_logout_endpoint', 'customer-logout');
        if ($customerLogoutEndPoint === $endpoint) {
            $url = User::userLogoutRedirect($url);
        }

        return $url;
    }
}
