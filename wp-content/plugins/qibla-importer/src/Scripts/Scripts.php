<?php
namespace QiblaImporter\Scripts;

/**
 * Scripts
 *
 * @package QiblaFramework\Scripts
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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

defined('WPINC') || die;

/**
 * Class Scripts
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class Scripts
{
    /**
     * List
     *
     * @since  1.0.0
     * @access private
     *
     * @var array A list of scripts
     */
    private $list;

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @access public
     *
     * @param array $list The scripts and styles list.
     */
    public function __construct(array $list)
    {
        $this->list     = $list;
        $this->register = new Register();
        $this->enqueuer = new Enqueuer();
    }

    /**
     * Get List
     *
     * @since  1.0.0
     * @access public
     *
     * @throws \Exception In case the list to retrieve doesn't exists.
     *
     * @param string $list Which list to retrieve.
     *
     * @return array The list of the styles
     */
    public function getList($list = '')
    {
        if (! $list) {
            return $this->list;
        }

        if (! isset($this->list[$list])) {
            throw new \Exception(sprintf(
                esc_html__('The list %s does not exists', 'qibla-importer'),
                $list
            ));
        }

        return $this->list[$list];
    }

    /**
     * Register Scripts and Styles
     *
     * @since  1.0.0
     * @access public
     *
     * @return void
     */
    public function register()
    {
        $this->register->register($this);
    }

    /**
     * Enqueue Scripts and Styles
     *
     * @since  1.0.0
     * @access public
     *
     * @return void
     */
    public function enqueuer()
    {
        $this->enqueuer->enqueuer($this);
    }
}
