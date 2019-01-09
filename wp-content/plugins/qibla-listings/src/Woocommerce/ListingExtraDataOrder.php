<?php
/**
 * ListingExtraDataOrder
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

namespace QiblaListings\Woocommerce;

use QiblaFramework\Functions as Fw;

/**
 * Class ListingExtraDataOrder
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingExtraDataOrder
{
    /**
     * Listing Meta
     *
     * @since  1.0.0
     *
     * @var string The listing meta-key for the meta order
     */
    const LISTING_META_KEY = '_listing_ids';

    /**
     * Order
     *
     * The order where store the meta values.
     *
     * @since  1.0.0
     *
     * @var \WC_Order
     */
    protected $order;

    /**
     * ListingExtraDataOrder constructor
     *
     * @since 1.0.0
     *
     * @param \WC_Order $order The order where store the meta values
     */
    public function __construct(\WC_Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get Listings ID's from Cart
     *
     * @todo   Move into a separated class as per issue 8
     *
     * @since  1.0.0
     *
     * @return array The listings id's list
     */
    public function getListingsIDsFromCart()
    {
        $cart = WC()->cart;
        // @fixme WC 3.3.x Use $cart = WC()->cart->get_cart(); See woocommerce/includes/class-wc-cart.php::get_cart()
        $cart->get_cart_from_session();

        $listingsList = array();

        // Push the listings id's.
        foreach ($cart->cart_contents as $key => $item) {
            if (! empty($item['listing_id'])) {
                // Sanitize the value.
                $id = intval($item['listing_id']);
                // Check if the post exists, don't add incorrect values.
                $listingsList[] = get_post($id) ? $id : 0;
            }
        }

        // Clean the list, may we added some invalid post id's.
        $listingsList = array_filter($listingsList);

        return $listingsList;
    }

    /**
     * Get Listings ID's from Order
     *
     * @since  1.0.0
     *
     * @return array The list of the listings ID's associated to the order
     */
    public function getListingsIDsFromOrder()
    {
        return explode(',', Fw\getPostMeta(self::LISTING_META_KEY, '', $this->order->get_id(), true));
    }

    /**
     * Store Listing ID's Meta
     *
     * @since  1.0.0
     *
     * @param array $listingsList The list of the listings id's
     *
     * @return void
     */
    public function storeListingIDsMeta(array $listingsList)
    {
        if (! empty($listingsList)) {
            add_post_meta($this->order->get_id(), self::LISTING_META_KEY, implode(',', $listingsList), true);
        }
    }

    /**
     * Add listing To order Filter
     *
     * @since  1.0.0
     *
     * @param int $orderID The order id.
     *
     * @return void
     */
    public static function addListingToOrderFilter($orderID)
    {
        $instance = new static(wc_get_order($orderID));
        $listings = $instance->getListingsIDsFromCart();

        $instance->storeListingIDsMeta($listings);
    }
}
