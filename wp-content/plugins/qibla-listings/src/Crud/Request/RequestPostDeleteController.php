<?php
/**
 * RequestPostDeleteController
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

namespace QiblaListings\Crud\Request;

use QiblaFramework\Request\AbstractRequestController;
use QiblaFramework\Request\Response;
use QiblaListings\Crud\PostDeleteInterface;

/**
 * Class RequestPostDeleteController
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class RequestPostDeleteController extends AbstractRequestController
{
    /**
     * Delete Handler
     *
     * @since  1.0.0
     *
     * @var PostDeleteInterface The instance used to delete the post
     */
    protected $postDeleteInstance;

    /**
     * RequestPostDeleteController constructor
     *
     * @since 1.0.0
     *
     * @param PostDeleteInterface $postDeleteHandler Instance of the class that delete posts.
     */
    public function __construct(PostDeleteInterface $postDeleteHandler)
    {
        $this->postDeleteInstance = $postDeleteHandler;
    }

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $response = $this->postDeleteInstance->delete(true);

        if (false === $response) {
            $response = new Response(500, esc_html_x(
                'There was an error trying to delete the post. Try in a few minutes',
                'manager-posts',
                'qibla-listings'
            ));
        } else {
            $response = new Response(200, sprintf(
            /* Translators: The %s is the name of the deleted post */
                esc_html_x('Post %s has been deleted successfully', 'manage-posts', 'qibla-listings'),
                $this->postDeleteInstance->getPost()->post_title
            ));
        }

        return $response;
    }
}
