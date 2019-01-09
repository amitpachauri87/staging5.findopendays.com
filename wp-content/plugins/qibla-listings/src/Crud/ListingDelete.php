<?php
/**
 * ListingDelete
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

use QiblaListings\Debug;
use QiblaFramework\Listings\ListingsPost;
use QiblaListings\Woocommerce\RemovePostFromCart;

/**
 * Class ListingDelete
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingDelete extends AbstractPostDelete
{
    /**
     * User Allowed to create Listing
     *
     * @since 1.0.0
     */
    protected static $userAllowedCapability = 'delete_listingss';

    /**
     * ListingDelete constructor
     *
     * @inheritdoc
     */
    public function __construct(\WP_Post $post)
    {
        $this->post = $post;

        // Create the user author.
        $author = new \WP_User($post->post_author);

        parent::__construct($author, $post->post_name, array(
            'post_type' => $post->post_type,
        ));
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
     * Hard Delete Post
     *
     * @since  1.0.0
     *
     * @uses   wp_delete_post($force) To delete the posts and their attached files.
     *
     * @param bool $force To delete the attachment with the post or not. Optional. Default to true.
     */
    protected function hardDelete($force = true)
    {
        // Just to be sure that the entire site will not be erased.
        if ($this->post->ID) {
            if (false !== wp_delete_post($this->post->ID, $force)) {
                $this->deleteMediaAttached();
            }
        }
    }

    /**
     * Soft Delete
     *
     * @since  1.0.0
     *
     * @return mixed Whatever the wp_update_post returns
     */
    protected function softDelete()
    {
        return wp_update_post(array(
            'ID'          => $this->post->ID,
            'post_type'   => $this->post->post_type,
            'post_status' => ListingsPost::SOFT_DELETED_STATUS,
        ), true);
    }

    /**
     * @inheritdoc
     */
    public function delete($soft = true, $force = true)
    {
        $response = $soft ? $this->softDelete() : $this->hardDelete($force);

        // Perform extra tasks.
        if ($response) {
            try {
                $postCart = new RemovePostFromCart($this->post, 'listing_id');
                $postCart->remove();
            } catch (\Exception $e) {
                $debugInstance = new Debug\Exception($e);
                'dev' === QB_ENV && $debugInstance->display();
            }//end try
        }

        return $response;
    }
}
