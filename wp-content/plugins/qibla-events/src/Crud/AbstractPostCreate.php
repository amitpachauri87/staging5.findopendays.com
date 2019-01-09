<?php
/**
 * Create
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

namespace AppMapEvents\Crud;

/**
 * Class Create
 *
 * @todo Move into framework
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class AbstractPostCreate extends AbstractCrudPost
{
    /**
     * Allowed Capability or Role
     *
     * @since  1.0.0
     *
     * @var string The capability or the user role that is allowed to CUD
     */
    protected static $userAllowedCapability = 'publish_posts';

    /**
     * Create the content
     *
     * @since  1.0.0
     *
     * @return mixed Whatever is needed by the subclass.
     */
    abstract public function create();

    /**
     * AbstractCreate constructor
     *
     * The constructor use the $title because when we create a new post we don't have a slug.
     *
     * @since 1.0.0
     *
     * @param \WP_User $author The author of the post.
     * @param string   $title  The title to assign to the post.
     * @param array    $args   The arguments to create the content
     */
    public function __construct(\WP_User $author, $title, array $args)
    {
        // Build the arguments.
        $args = wp_parse_args(array_merge($args, array(
            'post_title'  => $title,
        )));

        parent::__construct($author, sanitize_title($title), $args);
    }
}
