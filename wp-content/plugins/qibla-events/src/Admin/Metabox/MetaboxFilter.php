<?php
/**
 * MetaboxFilter.php
 *
 * @since      1.0.0
 * @package    AppMapEvents\Admin\Metabox
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

namespace AppMapEvents\Admin\Metabox;

use AppMapEvents\FilterInterface;

/**
 * Class MetaboxFilter
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class MetaboxFilter implements FilterInterface
{
    /**
     * The Fields list
     *
     * @since 1.0.0
     *
     * @var array The list to filter
     */
    private $listFields;

    /**
     * Post
     *
     * @since 1.0.0
     *
     * @var object The \WP_Post
     */
    private $post;

    /**
     * MetaboxFilter constructor.
     *
     * @since 1.0.0
     *
     * @param array $listFields   The fields list
     * @param       $post         \WP_Post The Post
     */
    public function __construct(array $listFields, $post)
    {
        $this->listFields = $listFields;
        $this->post       = $post;
    }

    /**
     * @inheritdoc
     */
    public function filter()
    {
        if ($this->post instanceof \WP_Post && 'events' === $this->post->post_type) {
            // Unset Open Hours, Email, Phone, Site Url.
            unset($this->listFields['qibla_mb_open_hours:textarea']);
            unset($this->listFields['qibla_mb_opening_hours:select']);
            //unset($this->listFields['qibla_mb_business_email:text']);
            //unset($this->listFields['qibla_mb_business_phone:tel']);
            //unset($this->listFields['qibla_mb_site_url:url']);
        }

        return $this->listFields;
    }

    /**
     * Filter Helper
     *
     * @since 1.0.0
     *
     * @param $fields
     *
     * @return array|mixed
     */
    public static function filterFilter($fields)
    {
        $instance = new self($fields, get_post());

        return $instance->filter();
    }
}
