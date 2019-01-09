<?php
/**
 * Create
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
use QiblaFramework\Listings\ListingLocation;
use QiblaFramework\Listings\ListingLocationStore;

/**
 * Class ListingCreate
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingCreate extends AbstractPostCreate
{
    /**
     * User Allowed to create Listing
     *
     * @since 1.0.0
     */
    protected static $userAllowedCapability = 'publish_listingss';

    /**
     * Default post status
     *
     * @since 1.0.0
     */
    const DEFAULT_POST_STATUS = 'pending';

    /**
     * ListingCreate constructor
     *
     * @inheritdoc
     */
    public function __construct(\WP_User $author, $title, array $args)
    {
        // Force post type.
        // @fixme Listings Type must be injected to support multi post types.
        $args['post_type'] = 'listings';

        parent::__construct($author, $title, $args);
    }

    /**
     * @inheritdoc
     *
     * @uses wp_insert_post() To create the post.
     *
     * @throws UserCapabilityErrorException In case the user is not allowed to create the post.
     * @throws \Exception in case the response of the post insert is a WP_Error.
     *
     * @return mixed Whatever the wp_insert_post returns
     */
    public function create()
    {
        if (! $this->isUserAllowed()) {
            throw new UserCapabilityErrorException('User not allowed.');
        }

        // Insert the post.
        // Don't change the WP_Error parameter.
        $response = wp_insert_post($this->args, true);

        if (is_wp_error($response)) {
            throw new \Exception($response->get_error_message(), $response->get_error_code());
        }

        // Store the location.
        if (! empty($this->args['map_location'])) {
            $location = ListingLocationStore::createFromString(get_post($response), $this->args['map_location']);
            $location->store();
        }

        return $response;
    }
}
