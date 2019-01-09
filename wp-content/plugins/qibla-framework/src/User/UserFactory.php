<?php
/**
 * UserFactory
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

namespace QiblaFramework\User;

/**
 * Class UserFactory
 *
 * @since   2.0.0
 * @package QiblaFramework\User
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class UserFactory
{
    /**
     * Get User
     *
     * @since  2.0.0
     *
     * @param string $user User email or nice name (slug)
     *
     * @return \WP_User|false The user instance or false if the user doesn't exists.
     */
    public static function create($user)
    {
        // Default by slug.
        $field = 'slug';

        // By ID.
        if (is_numeric($user)) {
            $user  = intval($user);
            $field = 'id';
            // By Email.
        } elseif (is_email($user)) {
            $field = 'email';
            // By slug.
        } else {
            // Always sanitize the user.
            $user = sanitize_user($user);
        }

        // Get the user.
        $user = get_user_by($field, $user);

        return $user;
    }
}
