<?php
/**
 * Package
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

namespace AppMapEvents\Package;

use QiblaFramework\Request\Nonce;

/**
 * Class Package
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class Package
{
    /**
     * The Package Post
     *
     * @since  1.0.0
     * @access protected
     *
     * @var \WP_Post The instance of the package post
     */
    protected $post;

    /**
     * Package constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Post $post The instance of the package post to use internally.
     */
    public function __construct(\WP_Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get Listing Edit Post Permalink
     *
     * @since  1.0.0
     * @access public
     *
     * @param \WP_Post $listingPost The instance of the listing post
     *
     * @return string The permalink of the listing edit page
     */
    public function getListingEditFormPermalink(\WP_Post $listingPost)
    {
        $nonce = new Nonce('_nonce');

        return add_query_arg(
            array(
                'dlaction'     => 'edit',
                'postid'       => $listingPost->ID,
                'package_post' => $this->post->ID,
                '_nonce_nonce' => $nonce->nonce(),
            ),
            get_permalink($this->post)
        );
    }
}
