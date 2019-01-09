<?php
/**
 * AuthorOverrideList
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

namespace QiblaListings\Admin\Metabox;

use QiblaFramework\ListingsContext\Types;

/**
 * Class AuthorOverrideList
 *
 * @since  1.0.1
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class AuthorOverrideList
{
    /**
     * Listings Author Role Slug
     *
     * @since  1.0.1
     *
     * @var string The listings author slug
     */
    private static $listingsAuthorRole = array(
        'listings_author',
        'manage_listings',
        'administrator',
    );

    /**
     * Override Author Listings Dropdown list
     *
     * @since  1.0.1
     *
     * @param array $queryArgs The query arguments.
     * @param array $args      The query arguments with the defaults.
     *
     * @return array The filtered query arguments
     */
    public static function authorOverrideListingsListFilter(array $queryArgs, array $args)
    {
        $types = new Types();

        $currentScreen = \QiblaFramework\Functions\currentScreen();
        $postType      = isset($currentScreen->post_type) ? $currentScreen->post_type : '';

        if (! $types->isListingsType($postType)) {
            return $queryArgs;
        }

        // Unset the `who` if set.
        unset($queryArgs['who']);

        // Check for context. Within the edit page it's `post_author_override` but in Quick Edit it's `post_author`.
        if (in_array($args['name'], array('post_author_override', 'post_author'), true)) :
            $roles = self::$listingsAuthorRole;

            if (! empty($queryArgs['role'])) {
                $rolesArgs = is_string($queryArgs['role']) ?
                    explode(',', $queryArgs['role']) :
                    $queryArgs['role'];

                $roles             = array_merge($roles, $rolesArgs);
                $queryArgs['role'] = '';
            }

            $queryArgs['role__in'] = array_filter(array_merge(
                $roles,
                (isset($queryArgs['role__in']) ? $queryArgs['role__in'] : array())
            ));

            $listingsRoleIndex = array_search(self::$listingsAuthorRole, $queryArgs['role__not_in'], true);
            if (false !== $listingsRoleIndex) {
                unset($queryArgs['role__not_in'][$listingsRoleIndex]);
            }
        endif;

        return $queryArgs;
    }
}
