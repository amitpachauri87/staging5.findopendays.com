<?php
/**
 * Child Field Factory
 *
 * @since      1.0.0
 *             
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2017, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2017 alfiopiccione <alfio.piccione@gmail.com>
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

namespace AppMapEvents\Factories;

/**
 * Class ChildFieldFactory
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class PluginFieldFactory extends \QiblaFramework\Form\Factories\FieldFactory
{
    /**
     * Child Base Namespace
     *
     * @since  1.0.0
     * @access protected
     *
     * @var string The base namespace
     */
    const PLUGIN_NS = 'AppMapEvents';

    /**
     * Build Namespace
     *
     * @since 1.0.0
     *
     * @param string $class The classname to append
     * @param string $typo  The
     *
     * @return string The namespace string
     */
    protected function buildNamespace($class, $typo = 'type')
    {
        $ns = self::NS . '\\';

        $class = ucwords(preg_replace('/[^a-z0-9]/', ' ', $class));
        $class = str_replace(' ', '', $class);

        switch ($typo) {
            case 'field':
                // Child Types
                if (class_exists(self::PLUGIN_NS . '\\' . self::FIELD_NS . '\\' . $class . 'Field')) {
                    $ns = self::PLUGIN_NS . '\\';
                }

                $ns .= self::FIELD_NS;
                break;
            default:
                // Child Types
                if (class_exists(self::PLUGIN_NS . '\\' . self::TYPE_NS . '\\' . $class)) {
                    $ns = self::PLUGIN_NS . '\\';
                }

                $ns .= self::TYPE_NS;
                break;
        }

        $ns .= '\\' . str_replace(' ', '', $class);

        return $ns;
    }
}