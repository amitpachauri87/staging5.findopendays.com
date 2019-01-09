<?php
/**
 * ListingUpdate
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

namespace QiblaListings\Crud;

use QiblaFramework\Exceptions\UserCapabilityErrorException;
use QiblaFramework\Listings\ListingLocationStore;

/**
 * Class ListingUpdate
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Crud
 */
class ListingUpdate extends AbstractPostUpdate
{
    /**
     * User Allowed to create Listing
     *
     * @since 1.0.0
     */
    protected static $userAllowedCapability = 'edit_listingss';

    /**
     * ListingUpdate constructor
     *
     * @inheritdoc
     */
    public function __construct(\WP_User $author, \WP_Post $post, array $args)
    {
        // The post_type must be explicitly set or WordPress will consider `post`.
        $args['post_type']   = $post->post_type;
        $args['post_status'] = $post->post_status;
        $args['ID']          = $post->ID;

        parent::__construct($author, $post->post_name, $args);
    }

    /**
     * @inheritdoc
     */
    public function isUserAllowed()
    {
        // Check for the current user and author of the post mismatch.
        if (wp_get_current_user() !== $this->author) {
            return false;
        }

        return parent::isUserAllowed();
    }

    /**
     * Update Post
     *
     * @since 1.0.0
     *
     * @throws UserCapabilityErrorException If the user is not allowed to make changes to the post.
     * @throws \Exception                   In case the post cannot be updated
     *
     * @return int The ID of the updated post
     */
    public function update()
    {
        if (! $this->isUserAllowed()) {
            throw new UserCapabilityErrorException('User not allowed.');
        }

        // Insert the post.
        // Don't change the WP_Error parameter.
        $response = wp_update_post($this->args, true);

        if (is_wp_error($response)) {
            throw new \Exception($response->get_error_message(), $response->get_error_code());
        }

        // Store the location.
        if (! empty($this->args['map_location'])) {
            $location = ListingLocationStore::createFromString(get_post($response), $this->args['map_location']);
            $location->store(true);
        }

        return $response;
    }
}
