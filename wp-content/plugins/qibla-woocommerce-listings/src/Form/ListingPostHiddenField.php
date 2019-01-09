<?php
/**
 * Listing Post Hidden Field
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

namespace QiblaWcListings\Form;

use QiblaFramework\Form\Types\Hidden;
use QiblaFramework\Functions as Fw;
use QiblaFramework\ListingsContext\Context;

/**
 * Class ListingPostHiddenField
 *
 * @since   1.2.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package Woocommerce
 */
class ListingPostHiddenField
{
    /**
     * Post
     *
     * @since 1.2.0
     *
     * @var \WP_Post The post instance used to build the hidden field value
     */
    private $post;

    /**
     * Build and return the Input Type instance
     *
     * @since 1.2.0
     *
     * @return Hidden The type instance
     */
    private function type()
    {
        return new Hidden(array(
            'name'  => 'listing_id',
            'attrs' => array(
                'value' => $this->post->ID,
            ),
        ));
    }

    /**
     * ListingPostHiddenField constructor
     *
     * @since 1.2.0
     *
     * @param \WP_Post $post The post instance used to build the hidden field value.
     */
    public function __construct(\WP_Post $post)
    {
        $this->post = $post;
    }

    /**
     * Show Field
     *
     * @since 1.2.0
     *
     * @return void Echo the input markup
     */
    public function show()
    {
        // @codingStandardsIgnoreLine
        echo Fw\ksesPost($this->type());
    }

    /**
     * Add Post Hidden Field Filter
     *
     * Show the hidden field only if it's a singular listings post type page and it's the main query.
     *
     * @since 1.2.0
     *
     * @return void
     */
    public static function addPostHiddenFieldFilter()
    {
        if (! Context::isSingleListings() || ! is_main_query()) {
            return;
        }

        $instance = new self(get_post());

        $instance->show();
    }
}
