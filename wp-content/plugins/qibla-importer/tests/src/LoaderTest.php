<?php
/**
 * Loader Test
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    Test
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    GNU General Public License, version 2
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

namespace QiblaImporter\Tests;

use QiblaImporter\Loader;

/**
 * Class LoaderTest
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package Test
 */
class LoaderTest extends QiblaTestCase
{
    /**
     * Test the loader filters and actions are filled correctly.
     */
    public function testLoaderArrayFilling()
    {
        $array = array(
            'inc'   => array(
                'filter' => array(),
                'action' => array(),
            ),
            'front' => array(
                'filter' => array(),
                'action' => array(),
            ),
            'admin' => array(
                'filter' => array(
                    array(
                        'filter'   => 'filter_name',
                        'callback' => 'filter_callback_name',
                        'priority' => 20,
                    ),
                    array(
                        'filter'   => 'filter_name_2',
                        'callback' => 'filter_callback_name_2',
                        'priority' => 30,
                    ),
                ),
                'action' => array(
                    array(
                        'filter'   => 'action_name',
                        'callback' => 'action_callback_name',
                        'priority' => 20,
                    ),
                    array(
                        'filter'   => 'action_filter_name_2',
                        'callback' => 'action_callback_name_2',
                        'priority' => 30,
                    ),
                ),
            ),
        );

        $loader = new Loader();
        $loader->addFilters($array);

        $refFilters = new \ReflectionProperty($loader, 'filters');
        $refFilters->setAccessible(true);

        $refActions = new \ReflectionProperty($loader, 'actions');
        $refActions->setAccessible(true);

        $this->assertNotEmpty($refActions->getValue($loader));
        $this->assertNotEmpty($refFilters->getValue($loader));
    }
}
