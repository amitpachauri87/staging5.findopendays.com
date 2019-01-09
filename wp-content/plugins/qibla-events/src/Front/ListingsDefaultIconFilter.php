<?php
/**
 * ListingsDefaultIconFilter.php
 *
 * @since      1.0.0
 * @package    AppMapEvents\Front
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

namespace AppMapEvents\Front;

use AppMapEvents\FilterInterface;

/**
 * Class ListingsDefaultIconFilter
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class ListingsDefaultIconFilter implements FilterInterface
{
    /**
     * The Fields list
     *
     * @since 1.0.0
     *
     * @var array The list to filter
     */
    private $defaultIcon;

    /**
     * Post
     *
     * @since 1.0.0
     *
     * @var object The \WP_Post
     */
    private $post;

    /**
     * ListingsDefaultIconFilter constructor.
     *
     * @param string $defaultIcon The default icon
     * @param        $post        \WP_Post The Post
     */
    public function __construct($defaultIcon, $post)
    {
        $this->defaultIcon = $defaultIcon;
        $this->post        = $post;
    }

    /**
     * @inheritdoc
     */
    public function filter()
    {
        if ('events' === $this->post->post_type) {
            $this->defaultIcon = 'Lineawesome::la-calendar';
        }

        return $this->defaultIcon;
    }

    /**
     * Filter Helper
     *
     * @since 1.0.0
     *
     * @param $object
     *
     * @return array|mixed
     */
    public static function filterFilter($object)
    {
        $instance = new self($object, get_post());

        return $instance->filter();
    }
}
