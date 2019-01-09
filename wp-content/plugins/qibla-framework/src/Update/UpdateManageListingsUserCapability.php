<?php
/**
 * UpdateManageListingsUserCapability
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

namespace QiblaFramework\Update;

use QiblaListings\Capabilities;

/**
 * Class UpdateManageListingsUserCapability
 *
 * @since   2.0.0
 * @package QiblaFramework\Update
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class UpdateManageListingsUserCapability
{
    /**
     * Reset Administrator Listings Roles
     *
     * @since 2.0.0
     *
     * @return void
     */
    public static function resetAdministratorListingsRoles(\WP_User $user)
    {
        if (! $user->exists() || ! class_exists('QiblaListings\\Capabilities\\ListingsAuthor')) {
            return;
        }

        $user = wp_get_current_user();
        $caps = new Capabilities\ListingsAuthor();

        // Only Administrators.
        if (! in_array('administrator', $user->roles, true)) {
            return;
        }

        // Remove the `manage_listings` role, administrator can administrate everything by caps.
        $user->remove_role('manage_listings');
        // Remove all roles set for `ListingsAuthor`.
        // This prevent issues when checking for listings_author to no grant permission.
        // If the administrator has the same caps, he will not able to accomplishing the tasks.
        foreach ($caps->roles() as $role) {
            $user->remove_role($role);
        }

        // Administrator don't need `manage_listings` role.
        $user->remove_cap('manage_listings');

        // Remove all specific caps if set.
        foreach ($caps->caps() as $cap => $grant) {
            if ($user->has_cap($cap)) {
                $user->remove_cap($cap);
            }
        }
    }

    /**
     * Reset Listings Author Roles
     *
     * @since 2.0.0
     *
     * @return void
     */
    public static function resetListingsAuthorRoles(\WP_User $user)
    {
        if (! $user->exists()) {
            return;
        }

        if (! in_array('listings_author', $user->roles, true)) {
            return;
        }

        $caps = array(
            'read'                       => false,
            'publish_listingss'          => true,
            'edit_listings'              => true,
            'edit_listingss'             => true,
            'delete_listings'            => true,
            'delete_listingss'           => true,
            'upload_files'               => true,
            'listings_author'            => true,
            'edit_published_listingss'   => true,
            'delete_published_listingss' => true,
            'assign_terms'               => true,
        );

        $roles = wp_roles();

        // Remove all old privileges and set the new ones.
        if (! $roles->is_role('listings_author')) {
            return;
        }

        // Get the role.
        $role = $roles->get_role('listings_author');

        if ($role->has_cap('manage_listings')) {
            // Delete the old role to be sure the old one get update with new capabilities.
            $roles->remove_role('listings_author');
            $roles->add_role(
                'listings_author',
                esc_html__('Listings Author', 'qibla-listings'),
                $caps
            );
        }

        // Remove old role, so we can assign the new ones.
        $user->remove_role('listings_author');
        $user->add_role('listings_author');
    }
}
