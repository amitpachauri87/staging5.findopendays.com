<?php
/**
 * Task Refactor Listings Meta Location
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

namespace QiblaFramework\Task;

use QiblaFramework\Listings\ListingLocation;
use QiblaFramework\Plugin;
use QiblaFramework\Request\ResponseAjax;

/**
 * Class TaskRefactorListingsMetaLocation
 *
 * @since  1.7.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class TaskRefactorListingsMetaLocation implements TaskInterface
{
    /**
     * Number of posts to process
     *
     * @since 1.7.0
     *
     * @var int The number of posts to process
     */
    private $number;

    /**
     * Offset
     *
     * @since 1.7.0
     *
     * @var int The Offset
     */
    private $offset;

    /**
     * WP_Query
     *
     * @since 1.7.0
     *
     * @var \WP_Query The WordPress query instance
     */
    private $query;

    /**
     * Perform Query
     *
     * @since 1.7.0
     *
     * @return void
     */
    private function query()
    {
        /**
         * Filter Query Arguments
         *
         * @since 1.7.0
         *
         * @param array $args The query arguments.
         */
        $args = apply_filters('task_refactor_listings_meta_location_query_args', array(
            'post_type'        => 'listings',
            'posts_per_page'   => $this->number,
            'offset'           => $this->offset,
            'fields'           => 'ids',
            'post_status'      => array('publish', 'trash', 'pending', 'future', 'private'),
            'order'            => 'ASC',
            'no_found_rows'    => true,
            'suppress_filters' => true,
        ));

        $this->query = new \WP_Query($args);
    }

    /**
     * TaskRefactorListingsMetaLocation constructor
     *
     * @since 1.7.0
     */
    public function __construct()
    {
        $this->number = 0;
        $this->offset = 0;
        $this->query  = null;
    }

    /**
     * Update Meta
     *
     * @since 1.7.0
     *
     * @param int $post The post ID for which update the meta.
     *
     * @return bool|null True on success, false on failure
     */
    private function updateMeta($post)
    {
        $post = intval($post);

        if (! $post) {
            return false;
        }

        // Get the location data.
        $meta = get_post_meta($post, '_qibla_mb_map_location', true);
        // Also get the lat and lng so we prevent to update multiple time the same post.
        if (metadata_exists('post', $post, '_qibla_mb_map_location_lat') &&
            metadata_exists('post', $post, '_qibla_mb_map_location_lng')) {
            return;
        }

        // Update only if taxonomy listings_address exists.
        if (! $meta || ! taxonomy_exists('listings_address')) {
            return false;
        }

        // Check that meta contains the separator for lat/lng and address.
        if (false === strpos($meta, ListingLocation::COORDS_ADDRESS_SEPARATOR)) {
            return false;
        }

        // Get Coords and address.
        $parts = explode(ListingLocation::COORDS_ADDRESS_SEPARATOR, $meta);

        if (1 >= count($parts)) {
            return false;
        }

        // Get the Latitude and Longitude.
        list($lat, $lng) = explode(ListingLocation::LATLNG_SEPARATOR, $parts[0]);
        // Get the address.
        $address = $parts[1];

        unset($meta, $parts);

        if (! $lat || ! $lng) {
            return false;
        }

        foreach (array('lat' => $lat, 'lng' => $lng) as $key => $coord) :
            // Try to update the latitude and longitude.
            if (! metadata_exists('post', $post, "_qibla_mb_map_location_{$key}")) {
                if (! add_post_meta($post, "_qibla_mb_map_location_{$key}", $coord, true)) {
                    return false;
                }
            } else {
                $meta = get_post_meta($post, "_qibla_mb_map_location_{$key}", true);

                // Update the value only if it's different than the one stored in db,
                // otherwise event if the meta can be updated since the new value is the same of the older one
                // the function will return false.
                if ($meta === $coord) {
                    break;
                }

                // Try to update the meta.
                if (! update_post_meta($post, "_qibla_mb_map_location_{$key}", $coord)) {
                    return false;
                }
            }
        endforeach;
        unset($key, $coord);

        $response = wp_set_object_terms($post, array($address), 'listings_address', false);
        if ($response instanceof \WP_Error) {
            return false;
        }
    }

    /**
     * @inheritdoc
     *
     * @since 1.7.0
     */
    public function can()
    {
        return version_compare('1.9.0', Plugin::PLUGIN_VERSION, 'gt') &&
               is_user_logged_in() &&
               current_user_can('manage_options') &&
               is_admin();
    }

    /**
     * @inheritdoc
     *
     * @since 1.7.0
     */
    public function setup(array $args)
    {
        $this->number = intval($args['number']);
        $this->offset = intval($args['offset']);

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @since 1.7.0
     */
    public function exec()
    {
        // If there are no posts to process, technically the task is performed correctly.
        if (! $this->number) {
            return array();
        }

        // Here the list for posts that are not updated correctly.
        $noUpdated = get_option('qibla_meta_locations_no_updated', array());
        // Set the ID's to use to prevent to add posts twice.
        $idsNoUpdated = $noUpdated ? (array)wp_list_pluck($noUpdated, 'id') : array();

        // Perform the query.
        $this->query();

        if ($this->completed()) :
            /**
             * Task Refactor Listings Meta Location Completed
             *
             * @since 1.7.0
             */
            do_action('qibla_task_refactor_listings_meta_location_completed');
        else :
            foreach ($this->query->posts as $post) {
                if (false === $this->updateMeta($post)) {
                    $post = get_post($post);
                    // Set the invalid post within the list.
                    // So we can inform the requester that there are posts not processed.
                    // Since this data will be stored in DB and consumed by the caller, we don't want to insert
                    // all of the properties.
                    (! in_array($post->ID, $idsNoUpdated, true)) and $noUpdated[] = array(
                        'id'        => $post->ID,
                        'title'     => $post->post_title,
                        'permalink' => esc_url(admin_url("/post.php?post={$post->ID}&action=edit")),
                    );
                }
            }
            unset($post);

            // Update the invalid posts option.
            update_option('qibla_meta_locations_no_updated', $noUpdated);
        endif;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @since 1.7.0
     */
    public function response()
    {
        $failed  = get_option('qibla_meta_locations_no_updated');
        $message = $failed ? esc_html__(
            'Task Completed: Unfortunately some posts has not processed correctly. See list below. You should edit them manually.',
            'qibla-framework'
        ) : esc_html__('Task Completed: Everything ok.', 'qibla-framework');

        return new ResponseAjax(200, $message, array(
            'completed' => $this->completed(),
            'failed'    => $failed,
        ));
    }

    /**
     * @inheritdoc
     *
     * @since 1.7.0
     */
    public function completed()
    {
        return ! (bool)count($this->query->posts);
    }
}
