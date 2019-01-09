<?php
/**
 * AbstractPostStatus
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

/**
 * Class AbstractPostStatus
 *
 * @since   1.5.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class AbstractPostStatus
{
    /**
     * Post Status Slug
     *
     * @since  1.5.0
     *
     * @var string The post status slug
     */
    protected $slug;

    /**
     * Arguments
     *
     * @since  1.5.0
     *
     * @var array The list of the arguments to pass to the register function
     */
    protected $args;

    /**
     * AbstractPostStatus constructor
     *
     * @since 1.5.0
     *
     * @param string $slug  The slug for the post status.
     * @param string $label The translatable label for the post status.
     * @param array  $args  The arguments to pass to the register function.
     */
    public function __construct($slug, $label, array $args = array())
    {
        // Clean and set the slug. Add prefix to prevent conflicts.
        $this->slug = 'qibla-' . str_replace('qibla-', '', sanitize_key($slug));
        $this->args = wp_parse_args($args, array(
            'label'                     => $label,
            'public'                    => false,
            'internal'                  => false,
            'private'                   => false,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => array(),
        ));
    }

    /**
     * Get Slug
     *
     * @since  1.5.0
     *
     * @return string The slug of the post status
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Arguments
     *
     * @since  1.5.0
     *
     * @return array The arguments used to register the post status
     */
    public function getArgs()
    {
        return $this->args;
    }
}
