<?php
/**
 * TypeRegister
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

namespace QiblaFramework\VisualComposer;

/**
 * Class TypeRegister
 *
 * @since   1.6.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\VisualComposer
 */
class TypeRegister
{
    /**
     * Type List
     *
     * @since 1.6.0
     *
     * @var array The list of the types to register
     */
    private $list;

    /**
     * TypeRegister constructor
     *
     * @since 1.6.0
     *
     * @param array $types The types list to register.
     */
    public function __construct(array $types = array())
    {
        $this->list = $types;
    }

    /**
     * Register
     *
     * @since 1.6.0
     */
    public function register()
    {
        if (! empty($this->list)) {
            foreach ($this->list as $type) {
                // Add the param type to Visual Composer.
                function_exists('vc_add_shortcode_param') and vc_add_shortcode_param(
                    $type->slug(),
                    array($type, 'callback'),
                    $type->scriptUrl()
                );
            }
        }
    }
}
