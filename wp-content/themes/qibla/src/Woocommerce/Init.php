<?php
namespace Qibla\Woocommerce;

use Qibla\LoaderInterface;

/**
 * WooCommerce Init
 *
 * @since      1.1.0
 * @package    Qibla\Woocommerce
 * @author     guido scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    GNU General Public License, version 2
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

/**
 * Class Init
 *
 * @since 1.1.0
 * @author     guido scialfa <dev@guidoscialfa.com>
 */
final class Init
{
    /**
     * Loader
     *
     * @since  1.1.0
     *
     * @var LoaderInterface Instance of the loader to use
     */
    private $loader;

    /**
     * Filters
     *
     * @since  1.1.0
     *
     * @var array A list of filter to load
     */
    private $filters;

    /**
     * To Remove Filters
     *
     * @since  1.1.0
     *
     * @var array The list of the filters and actions to remove
     */
    private $toRmFilters;

    /**
     * Did Init
     *
     * @since  1.1.0
     *
     * @var bool If did init
     */
    private static $didInit = false;

    /**
     * Init
     *
     * @since  1.1.0
     *
     * @param LoaderInterface $loader      The loader to use to load the filters.
     * @param array           $filters     The filters to add.
     * @param array           $toRmFilters The list of the actions and filters to remove.
     */
    public function __construct(LoaderInterface $loader, array $filters, array $toRmFilters)
    {
        $this->loader      = $loader;
        $this->filters     = $filters;
        $this->toRmFilters = $toRmFilters;
    }

    /**
     * Remove WooCommerce Actions
     *
     * @since  1.1.0
     *
     * @return void
     */
    private function removeWcActions()
    {
        foreach ($this->toRmFilters as $func => $list) {
            $func = 'remove_' . $func;
            if (! empty($list)) {
                foreach ($list as $data) {
                    call_user_func_array($func, array_values($data));
                }
            }
        }
    }

    /**
     * Init
     *
     * @since  1.1.0
     *
     * @throws \Exception If did init.
     *
     * @return void
     */
    public function init()
    {
        if (self::$didInit) {
            throw new \Exception('Did Wc Init.');
        }
        // Add Filters.
        $this->loader
            ->addFilters($this->filters)
            ->load();

        // Remove the WooCommerce actions not needed.
        $this->removeWcActions();
    }
}
