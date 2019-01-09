<?php
/**
 * BoxModelClassesAdapter
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
 * Class BoxModelClassesAdapter
 *
 * @since   1.6.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class BoxModelClassesAdapter
{
    /**
     * Replace Row Class Attribute
     *
     * @since 1.6.0
     *
     * @param string $class The class string attribute value.
     * @param string $tag   The shortcode name.
     *
     * @return string The filtered class attribute value
     */
    public function replaceRowClassAttribute($class, $tag)
    {
        return str_replace(
            array('vc_row-fluid', 'vc_row', 'wpb_row'),
            array('dlgrid dlgrid-vc', '', ''),
            $class
        );
    }

    /**
     * Replace Column Class Attribute
     *
     * @since 1.6.0
     *
     * @param string $class The class string attribute value.
     * @param string $tag   The shortcode name.
     *
     * @return string The filtered class attribute value
     */
    public function replaceColumnClassAttribute($class, $tag)
    {
        return str_replace(
            array('vc_col-', 'wpb_column', 'vc_column_container'),
            array('col col--', '', ''),
            $class
        );
    }

    /**
     * Filter Css Classes
     *
     * @since 1.6.0
     *
     * @param string $class The class string attribute value.
     * @param string $tag   The shortcode name.
     *
     * @return string The filtered class attribute value
     */
    public static function filterCssClassesFilter($class, $tag)
    {
        $instance = new static;

        switch ($tag) :
            // Columns.
            case 'vc_column':
            case 'vc_column_inner':
                $class = $instance->replaceColumnClassAttribute($class, $tag);
                break;
            // Rows.
            case 'vc_row':
            case 'vc_row_inner':
                $class = $instance->replaceRowClassAttribute($class, $tag);
                break;
        endswitch;

        return $class;
    }
}
