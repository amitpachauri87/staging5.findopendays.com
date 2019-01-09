<?php
/**
 * Update Status
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

use QiblaFramework\Exceptions\UpdatePostException;
use QiblaFramework\ListingsContext\Types;
use QiblaFramework\Utils\TimeZone;
use QiblaListings\Debug\Exception;
use QiblaListings\Listing\Expire\ExpirationByDate;
use QiblaFramework\Listings\ListingsPost;
use QiblaListings\Woocommerce\ListingExtraDataOrder;

/**
 * Class ListingUpdateStatus
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingUpdateStatus
{
    /**
     * Post
     *
     * @since  1.0.0
     *
     * @var \WP_Post The instance of the post
     */
    private $post;

    /**
     * Status constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Post $post The post instance.
     */
    public function __construct(\WP_Post $post)
    {
        $this->post = $post;
    }

    /**
     * Update Status
     *
     * Handle the status update for the post.
     *
     * @since  1.0.0
     *
     * @param string $newStatus The status we want to set for the post.
     *
     * @throws UpdatePostException In case the post has not been updated successfully
     *
     * @return string The newly post status after the update
     */
    private function updateStatus($newStatus)
    {
        $response = wp_update_post(array(
            'ID'          => $this->post->ID,
            'post_status' => $newStatus,
        ));

        if (is_wp_error($response)) {
            throw new UpdatePostException($response->get_error_message());
        } else {
            // Update the current post instance.
            // If the post is not updated the properties will be wrong.
            $this->post = get_post($response);
        }

        // Set the new status.
        $status = $this->post->post_status;

        return $status;
    }

    /**
     * Expire By Date
     *
     * Start by get the post meta that define the days after which the listing expire.
     * Then, calculate if the listing has been expired and in this case update the status of the post in "Trash".
     *
     * @since  1.0.0
     *
     * @throws UpdatePostException If the post cannot be updated correctly.
     *
     * @return string The status of the post after the update.
     */
    public function expireByDate()
    {
        // Timezone.
        $timeZone = new TimeZone();
        // Get the expiration instance.
        $expired = new ExpirationByDate(
            $this->post,
            \QiblaFramework\Functions\getPostMeta(
                '_qibla_mb_listing_expiration',
                ExpirationByDate::EXPIRE_UNLIMITED,
                $this->post
            ),
            $timeZone->getTimeZone()
        );

        $status = $this->post->post_status;
        // Update the status of the post if post is expired.
        // Update only the published posts, others doesn't need to be processed.
        if ($expired->isExpired() && 'publish' === $this->post->post_status) {
            $status = $this->updateStatus(ListingsPost::EXPIRED_STATUS);

            /**
             * Listing Expire By Date
             *
             * @since 1.0.0
             *
             * @param \WP_Post The post for which the status has been updated.
             */
            do_action('qibla_listings_expire_by_date', $this->post);
        }

        return $status;
    }

    /**
     * Pending to Published Status
     *
     * @since  1.0.0
     *
     * @throws UpdatePostException If the status of the post cannot be changed.
     *
     * @return string The status of the post.
     */
    public function pendingToPublished()
    {
        $status = $this->post->post_status;
        if (ListingsPost::PENDING_STATUS === $this->post->post_status) {
            $status = $this->updateStatus(ListingsPost::PUBLISHED_STATUS);

            /**
             * Listings Pending to Publish Status
             *
             * @since 1.0.0
             *
             * @param \WP_Post $post The expired post.
             */
            do_action('qibla_listings_pending_to_publish_status', $this->post);
        }

        return $status;
    }

    /**
     * Update the Post status Filter
     *
     * Hook in post_results (before the status check and the caching data).
     *
     * @since 1.0.0
     *
     * @param array $posts The list of the posts to filter.
     *
     * @return array The filtered posts.
     */
    public static function expireDuringPostResultsFilter(array $posts, \WP_Query $query)
    {
        try {
            // Allow admin to manage the posts even if they are expired.
            if (! is_admin() && $posts) :
                // Filter the posts by taking only the ones that are not expired.
                // Update the post status and then check of the response status.
                $posts = array_filter($posts, function (&$post) use ($query) {
                    // Retrieve the types to check the current post against.
                    $types = new Types();

                    if ($types->isListingsType($post->post_type)) {
                        $instance = new ListingUpdateStatus($post);
                        // Expire and update the post status.
                        $post->post_status = $instance->expireByDate();

                        // Get the query statuses.
                        $queryStatus = isset($query->query_vars['post_status']) ?
                            (array)$query->query_vars['post_status'] :
                            array();

                        // Keep the expired posts if requested.
                        if (! in_array(ListingsPost::EXPIRED_STATUS, $queryStatus, true)) {
                            // Want to keep only the published posts, not expired ones.
                            return ListingsPost::EXPIRED_STATUS !== $post->post_status;
                        }
                    }

                    return true;
                });
            endif;
        } catch (\Exception $e) {
            $debugInstance = new Exception($e);
            'dev' === QB_ENV && $debugInstance->display();
        }//end try

        return $posts;
    }

    /**
     * Publish post on order completed
     *
     * @since  1.0.0
     *
     * @param int       $orderID       The order ID
     * @param string    $from          The old status of the order.
     * @param string    $to            The newly status of the order.
     * @param \WC_Order $orderInstance The order instance.
     *
     * @return void
     */
    public static function publishOnOrderCompletedFilter($orderID, $from, $to, $orderInstance)
    {
        try {
            if ('completed' === $to) {
                $listingExtraDataOrder = new ListingExtraDataOrder($orderInstance);
                $listingPosts          = array_filter($listingExtraDataOrder->getListingsIDsFromOrder());

                // Be sure we are treating an order with a listing post associated.
                if ($listingPosts) {
                    // Get the posts.
                    $listingPosts = array_map('get_post', $listingPosts);
                    // Update status.
                    foreach ($listingPosts as $listing) {
                        $instance = new static($listing);
                        $instance->pendingToPublished();
                    }
                }
            }
        } catch (\Exception $e) {
            $debugInstance = new Exception($e);
            'dev' === QB_ENV && $debugInstance->display();
        }
    }
}
