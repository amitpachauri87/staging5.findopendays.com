<?php
/**
 * PostDeleteInterface
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
 * Class PostDeleteInterface
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Crud
 */
interface PostDeleteInterface
{
    /**
     * Delete Post
     *
     * @since  1.0.0
     *
     * @param bool $soft  To soft delete the post. The post will go in trash and flagged as deleted.
     * @param bool $force To force without send the post to the trash.
     *
     * @return \WP_Post|false The post object removed or false on failure
     */
    public function delete($soft = true, $force = true);

    /**
     * Get the post
     *
     * @since  1.0.0
     *
     * @return \WP_Post The post instance
     */
    public function getPost();
}
