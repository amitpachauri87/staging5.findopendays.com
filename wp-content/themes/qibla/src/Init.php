<?php
namespace Qibla;

/**
 * Init
 *
 * @since      1.0.0
 * @package    Qibla
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
 * @since 1.0.0
 */
final class Init
{
    /**
     * Loader
     *
     * @since  1.0.0
     *
     * @var LoaderInterface Instance of the loader to use
     */
    private $loader;

    /**
     * Filters
     *
     * @since  1.0.0
     *
     * @var array A list of filter to load
     */
    private $filters;

    /**
     * Constructor
     *
     * @todo   Make to interfaces
     *
     * @since  1.0.0
     */
    public function __construct(LoaderInterface $loader, array $filters)
    {
        $this->loader  = $loader;
        $this->filters = $filters;
    }

    /**
     * Init
     *
     * @since  1.0.0
     *
     * @throws \Exception If did init.
     *
     * @return void
     */
    public function init()
    {
        // Add Filters.
        $this->loader
            ->addFilters($this->filters)
            ->load();
    }
}
