<?php
/**
 * Sidebars
 *
 * @since      1.0.0
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


namespace AppMapEvents\Sidebars;

use AppMapEvents\RegisterInterface;

/**
 * Class Register
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class Register implements RegisterInterface
{
    /**
     * Sidebars Arguments
     *
     * @since  1.0.0
     *
     * @var array The list of the sidebar arguments
     */
    private $args;

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param array $list The list of the sidebars with arguments.
     */
    public function __construct(array $list)
    {
        $this->args = $list;
    }

    /**
     * Register
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->args as $key => $args) {
            // Set the current scope for sidebar classes.
            $scope = 'dlsidebar';

            // Parse Arguments.
            $args = wp_parse_args($args, array(
                'id'            => 'appmapev-sidebar-' . $key,
                'class'         => $scope,
                'before_widget' => '<div id="%1$s" class="' . $scope . '__widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4 class="' . $scope . '__widget__title">',
                'after_title'   => '</h4>',
            ));

            /**
             * Filter Sidebar arguments
             *
             * @since 1.0.0
             *
             * @param array  $args  The arguments for the sidebar.
             * @param string $key   The key of the current sidebar arguments.
             * @param string $scope The current scope class for the sidebar.
             */
            $args = apply_filters('appmap_ev_sidebars_register_arguments', $args, $key, $scope);

            // Register the sidebar.
            register_sidebar($args);
        }
    }
}
