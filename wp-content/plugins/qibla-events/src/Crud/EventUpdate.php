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

namespace AppMapEvents\Crud;

use QiblaFramework\Exceptions\UserCapabilityErrorException;
use QiblaFramework\Listings\ListingLocationStore;

/**
 * Class ListingUpdate
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package AppMapEvents\Crud
 */
class EventUpdate extends AbstractPostUpdate
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
     * Update Hidden Meta and Event Dates Term
     *
     * @since 1.0.0
     *
     * @param $post \WP_Post  The response post
     * @param $data array     The data Args
     */
    public function updateMetaHidden($post, $data)
    {
        if (is_array($data) && isset($data['meta_input'])) {
            $meta = $data['meta_input'];

            if (isset($meta['event_dates_multidatespicker'])) {
                $multiDates = explode(',', $meta['event_dates_multidatespicker']);

                $dates = array();
                foreach ($multiDates as $date) {
                    $date    = new \DateTime($date);
                    $dates[] = (string)$date->format('Y-m-d');
                }

                // Set the dates term.
                // Save the post meta as a term to be used for filtering and have a url.
                wp_set_object_terms($post->ID, $dates, 'event_dates', false);

                $startDate = reset($dates);
                $endDates  = end($dates);

                // Start date post meta.
                update_post_meta($post->ID, '_qibla_mb_event_dates_multidatespicker_start', $startDate);
                // End date post meta.
                update_post_meta($post->ID, '_qibla_mb_event_dates_multidatespicker_end', $endDates);
            }
        }
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
