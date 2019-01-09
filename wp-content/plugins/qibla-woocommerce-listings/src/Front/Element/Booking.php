<?php

namespace QiblaWcListings\Front\Element;

/**
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaWcListings\Front\Element
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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

/**
 * Class Booking
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaWcListings\Front\Element
 */
class Booking extends AbstractElement
{
    /**
     * @inheritdoc
     */
    public function getCart()
    {
        // Initialize Data.
        $data = new \stdClass();

        $data->product = $this->product;
        // Create the booking form instance by booking product.
        $data->bookingForm = new \WC_Booking_Form(new \WC_Product_Booking($this->product));
        // Get the booking product..
        $bookingProduct       = new \WC_Product_Booking($this->product);
        $data->bookingProduct = $bookingProduct;

        if ( // Only purchasable.
            ! $this->product->is_purchasable() ||
            // Public.
            'private' === get_post_status($this->product->get_id())
        ) {
            return;
        }

        // Show the element markup.
        $this->loadTemplate('booking_get_cart', $data, '/views/element/booking/addToCart.php');
    }
}
