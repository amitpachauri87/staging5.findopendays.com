<?php
/**
 * Filter Listing Author Recipient To Order Emails
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

namespace QiblaWcListings\Email;

use QiblaListings\Woocommerce\ListingExtraDataOrder;

/**
 * Class FilterListingAuthorRecipientToOrderEmails
 *
 * @since  1.2.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class FilterListingAuthorRecipientToOrderEmails
{
    /**
     * Recipient
     *
     * @since 1.2.0
     *
     * @var string The Recipient to filter
     */
    private $recipient;

    /**
     * Order
     *
     * @since 1.2.0
     *
     * @var \WC_Order The order from which retrieve data
     */
    private $order;

    /**
     * Post From Order Meta
     *
     * @since 1.2.0
     *
     * @return \WP_Post|null The post instance or null if the post cannot be found within the order meta
     */
    private function postFromOrderMeta()
    {
        $post = null;

        // Get the meta data from the order, so we can retrieve the post associated to the order.
        $metaData = $this->order->get_meta_data();

        if (! empty($metaData)) {
            foreach ($metaData as $meta) {
                // Check if the post data we need is in the order as meta.
                if (ListingExtraDataOrder::LISTING_META_KEY === $meta->key) {
                    // Order can have multiple post id's associated.
                    if (false !== strpos($meta->value, ',')) {
                        $value = explode(',', $meta->value);
                        $value = $value[0];
                    } else {
                        $value = $meta->value;
                    }

                    // Retrieve the post.
                    $post = get_post(intval($value));
                    break;
                }
            }
        }

        return $post;
    }

    /**
     * Retrieve user by Post author
     *
     * @since 1.2.0
     *
     * @param \WP_Post $post The post instance from which retrieve the user.
     *
     * @return \WP_User The user instance
     */
    private function userByPostAuthor(\WP_Post $post)
    {
        return new \WP_User(intval($post->post_author));
    }

    /**
     * FilterListingAuthorRecipientToOrderEmails constructor
     *
     * @since 1.2.0
     *
     * @param string    $recipient The Recipient to filter.
     * @param \WC_Order $order     The order from which retrieve data.
     */
    public function __construct($recipient, \WC_Order $order)
    {
        $this->recipient = $recipient;
        $this->order     = $order;
    }

    /**
     * Filter
     *
     * @since 1.2.0
     *
     * @return $this The instance of the class to chaining
     */
    public function filter()
    {
        // Retrieve the post by the order meta.
        $post = $this->postFromOrderMeta();

        // If the post is in the order item, let's build the instance and
        // add the extra recipient.
        if ($post instanceof \WP_Post) {
            $user            = $this->userByPostAuthor($post);
            $recipientFilter = new FilterEmailRecipientByListingAuthor($user);
            $recipientFilter->filter($this->recipient);
        }

        return $this;
    }

    /**
     * Recipient
     *
     * @since 1.2.0
     *
     * @return string The recipient value
     */
    public function recipient()
    {
        return $this->recipient;
    }

    /**
     * Filter Recipient Filter
     *
     * @since 1.2.0
     *
     * @param string    $recipient The recipient to filter.
     * @param \WC_Order $order     The order instance from which retrieve the data.
     *
     * @return string
     */
    public static function filterRecipientFilter($recipient, \WC_Order $order)
    {
        $instance = new self($recipient, $order);

        return $instance->filter()
                        ->recipient();
    }
}
