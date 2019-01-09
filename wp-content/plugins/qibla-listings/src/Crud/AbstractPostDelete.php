<?php
/**
 * AbstractPostDelete
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

/**
 * Class AbstractPostDelete
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Crud
 */
abstract class AbstractPostDelete extends AbstractCrudPost implements PostDeleteInterface
{
    /**
     * Post
     *
     * @since  1.0.0
     *
     * @var \WP_Post The post to delete
     */
    protected $post;

    /**
     * Allowed Capability or Role
     *
     * @since  1.0.0
     *
     * @var string The capability or the user role that is allowed to CUD
     */
    protected static $userAllowedCapability = 'delete_posts';

    /**
     * Delete Media Attached
     *
     * Delete the media attached to the post that is begin to delete
     *
     * @since  1.0.0
     *
     * @return void
     */
    protected function deleteMediaAttached()
    {
        $attachments = get_posts(array(
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'post_parent'    => $this->post->ID,
        ));

        foreach ($attachments as $attachment) {
            wp_delete_attachment($attachment->ID);
        }
    }

    /**
     * @inheritdoc
     */
    public function getPost()
    {
        return $this->post;
    }
}
