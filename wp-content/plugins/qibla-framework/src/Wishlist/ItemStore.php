<?php
/**
 * Item Store
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
 * Class ItemStore
 *
 * @since   2.0.0
 * @package QiblaFramework\Wishlist
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class ItemStore
{
    /**
     * User
     *
     * @since 2.0.0
     *
     * @var \WP_User The user in which store or remove the item
     */
    public $user;

    /**
     * Post
     *
     * @since 2.0.0
     *
     * @var \WP_Post The post to store or remove from the list.
     */
    public $post;

    /**
     * MetaKey
     *
     * @since 2.0.0
     *
     * @var string The metakey
     */
    private $metaKey;

    /**
     * ItemStore constructor
     *
     * @since 2.0.0
     *
     * @param \WP_User $user    The user in which store or remove the item.
     * @param \WP_Post $post    The post to store or remove from the list.
     * @param string   $metaKey The metakey.
     */
    public function __construct(\WP_User $user, \WP_Post $post, $metaKey)
    {
        $this->user    = $user;
        $this->post    = $post;
        $this->metaKey = $metaKey;
    }

    /**
     * Store
     *
     * @since 2.0.0
     *
     * @return mixed Whatever the UserMeta functions returns.
     */
    public function store()
    {
        $meta = new UserMeta($this->user, $this->metaKey, array($this->post->ID));

        // If meta not exists, just add it and return.
        if ($meta->exists()) {
            return $this->update();
        }

        return $meta->create();
    }

    /**
     * Update
     *
     * @since 2.0.0
     *
     * @return mixed|null Whatever the update method of UserMeta instance returns. Null if any update has been
     *                    performed.
     */
    private function update()
    {
        $meta = new UserMeta($this->user, $this->metaKey, array($this->post->ID));

        // Try to retrieve the list to include the newly post.
        $list = array_map('intval', $meta->read());

        // Don't save the same element twice.
        if (! in_array($this->post->ID, $list, true)) {
            $list[] = $this->post->ID;

            $meta = new UserMeta($this->user, $this->metaKey, $list);

            return $meta->update();
        }
        
        return false;
    }
}
