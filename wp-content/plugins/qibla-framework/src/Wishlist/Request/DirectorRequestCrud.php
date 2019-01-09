<?php
/**
 * DirectoryRequestCrud
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

namespace QiblaFramework\Wishlist\Request;

use QiblaFramework\Request\AbstractDirectorRequest;
use QiblaFramework\Request\RequestControllerInterface;

/**
 * Class DirectoryRequestCrud
 *
 * @since   2.0.0
 * @package QiblaFramework\Wishlist\Request
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class DirectorRequestCrud extends AbstractDirectorRequest
{
    /**
     * User
     *
     * @since 2.0.0
     *
     * @var int The user ID
     */
    private $user;

    /**
     * Post
     *
     * @since 2.0.0
     *
     * @var int The post ID
     */
    private $post;

    /**
     * Action
     *
     * @since 2.0.0
     *
     * @var string The action to take
     */
    private $action;

    /**
     * DirectorRequestCrud constructor
     *
     * @since 2.0.0
     *
     * @param RequestControllerInterface $controller The controller to use to process the request.
     * @param \WP_user                   $user       The user.
     * @param \WP_Post                   $post       The post.
     * @param string                     $action     The action to take.
     */
    public function __construct(RequestControllerInterface $controller, \WP_User $user, \WP_Post $post, $action)
    {
        $this->user   = $user;
        $this->post   = $post;
        $this->action = $action;

        parent::__construct($controller);
    }

    /**
     * @inheritDoc
     */
    public function director()
    {
        if (! $this->user->exists()) {
            throw new \UnexpectedValueException('Invalid User.', 403);
        }
        if (! $this->post) {
            throw new \UnexpectedValueException('Post does not exists', 400);
        }

        $this->injectDataIntoController(array(
            'user'   => $this->user,
            'post'   => $this->post,
            'action' => $this->action,
        ));

        return $this->dispatchToController();
    }
}
