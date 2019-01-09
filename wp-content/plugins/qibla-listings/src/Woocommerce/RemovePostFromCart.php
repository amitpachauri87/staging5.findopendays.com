<?php
/**
 * Remove Post From Cart
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
 * Class RemovePostFromCart
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class RemovePostFromCart
{
    /**
     * Post
     *
     * @since  1.0.0
     *
     * @var \WP_Post The post to remove from cart
     */
    private $post;

    /**
     * Post Item Key
     *
     * The key used to retrieve the post id from the cart item data.
     *
     * @since 1.1.0
     *
     * @var string The post item key used to retrieve teh post id from the cart item data.
     */
    private $postItemKey;

    /**
     * RemovePostFromCart constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Post $post        The listing post related with the product.
     * @param string   $postItemKey The post item key used to retrieve teh post id from the cart item data.
     */
    public function __construct(\WP_Post $post, $postItemKey)
    {
        $this->post        = $post;
        $this->postItemKey = $postItemKey;
    }

    /**
     * Remove Related Product From Cart
     *
     * @since  2.0.0
     *
     * @return bool True if removed, false otherwise
     */
    public function remove()
    {
        $removed = false;

        // Always check to prevent issues.
        if (! Fw\isWooCommerceActive()) {
            return false;
        }

        // Get Cart.
        $cart = WC()->cart;
        // @fixme WC 3.3.x Use $cart = WC()->cart->get_cart(); See woocommerce/includes/class-wc-cart.php::get_cart()
        $cart->get_cart_from_session();

        foreach ($cart->cart_contents as $key => $item) {
            // If the current item has the post ID as item data.
            if (isset($item[$this->postItemKey]) && $this->post->ID === intval($item[$this->postItemKey])) {
                // Remove the item from the cart.
                $removed = $cart->remove_cart_item($key);
                break;
            }
        }

        return $removed;
    }
}
