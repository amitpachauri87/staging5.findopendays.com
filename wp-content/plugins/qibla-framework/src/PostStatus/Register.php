<?php
/**
 * Register
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

namespace QiblaFramework\PostStatus;

use QiblaFramework\RegisterInterface;

/**
 * Class Register
 *
 * @since   1.5.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class Register implements RegisterInterface
{
    /**
     * Post Status Instances List
     *
     * @since  1.5.0
     *
     * @var array The list of the post status instances
     */
    protected $list;

    /**
     * Register constructor
     *
     * @since 1.5.0
     *
     * @param array $list The list of the instances of the post status to register.
     */
    public function __construct(array $list)
    {
        $this->list = $list;
    }

    /**
     * Register
     *
     * @since  1.5.0
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->list as $list) {
            register_post_status($list->getSlug(), $list->getArgs());
        }
    }
}
