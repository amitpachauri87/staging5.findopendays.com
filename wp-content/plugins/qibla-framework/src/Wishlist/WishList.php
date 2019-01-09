<?php
/**
 * WishList
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @package   dreamlist-framework
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

namespace QiblaFramework\Wishlist;

use QiblaFramework\Meta\UserMeta;

/**
 * Class WishList
 *
 * @since   2.0.0
 * @package QiblaFramework\Wishlist
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class WishList
{
    /**
     * Meta Key
     *
     * @since 2.0.0
     *
     * @var string The meta key where store the items in
     */
    const META_KEY = '_qibla_wishlist_listings';

    /**
     * User
     *
     * @since 2.0.0
     *
     * @var \WP_User The user to work with
     */
    private $user;

    /**
     * WishList constructor
     *
     * @since 2.0.0
     *
     * @param \WP_User $user The user to work with
     */
    public function __construct(\WP_User $user)
    {
        $this->user = $user;
    }

    /**
     * Delete
     *
     * @since 2.0.0
     *
     * @return mixed Whatever the UserMeta returns.
     */
    public function delete()
    {
        $meta = new UserMeta($this->user, WishList::META_KEY);

        return $meta->delete();
    }

    /**
     * Store Item
     *
     * @since 2.0.0
     *
     * @param \WP_Post $post The post to store.
     *
     * @return mixed Whatever the ItemStore::store returns
     */
    public function storeItem(\WP_Post $post)
    {
        $store = new ItemStore($this->user, $post, self::META_KEY);

        return $store->store();
    }

    /**
     * Remove Item
     *
     * @since 2.0.0
     *
     * @param \WP_Post|int $post Data used to retrieve the ID of the post to remove.
     *
     * @return bool Whatever the UserMeta::update returns
     */
    public function removeItem($post)
    {
        $post = get_post($post);

        if (! $post) {
            return false;
        }

        $list = wp_list_pluck($this->read(), 'ID');

        // Get the index of the element if exists.
        $index = array_search($post->ID, $list, true);

        if (false !== $index) {
            // Use splice so we keep indexing.
            array_splice($list, $index, 1);
        }

        // Update the meta without the item we wanted to remove.
        $meta = new UserMeta($this->user, self::META_KEY, $list);

        return $meta->update();
    }

    /**
     * Has Item
     *
     * @since 2.0.0
     *
     * @param \WP_Post|int $post The data to check against.
     *
     * @return bool True if element exists, false otherwise
     */
    public function hasItem($post)
    {
        $post = get_post($post);

        if (! $post) {
            return false;
        }

        // Extract the ID's.
        $list = wp_list_pluck($this->read(), 'ID');

        return (false !== array_search($post->ID, $list, true));
    }

    /**
     * Retrieve the meta list
     *
     * @since 2.0.0
     *
     * @return array The list of the items
     */
    public function read()
    {
        // Retrieve the list cached if set.
        $cache = wp_cache_get('wishlist', 'user');

        if ($cache) {
            return $cache;
        }

        $meta = new UserMeta($this->user, self::META_KEY);
        // Clean the meta because a false cast into an array become an array with only one element.
        $list = array_filter((array)$meta->read());

        if (! $list) {
            return array();
        }

        // Be sure the data read from db is an integer.
        $list = array_map('get_post', $list);

        // Add to cache.
        wp_cache_add('wishlist', $list, 'user');

        return $list;
    }
}
