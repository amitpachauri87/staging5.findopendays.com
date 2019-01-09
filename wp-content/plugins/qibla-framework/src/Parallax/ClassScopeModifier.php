<?php
namespace QiblaFramework\Parallax;

/**
 * Parallax Scope Modifier
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaFramework
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

/**
 * Class ClassScopeModifier
 *
 * @since   1.4.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework
 */
class ClassScopeModifier
{
    /**
     * Allowed class blocks to use parallax
     *
     * @since  1.4.0
     *
     * @var array
     */
    protected static $allowedBlocks = array(
        'section-cta',
        'sc-section',
    );

    /**
     * Parallax Modifier
     *
     * @since  1.4.0
     *
     * @param string $upxscope The scope prefix. Default 'upx'.
     * @param string $element  The current element of the scope.
     * @param string $block    The custom block scope. Default empty.
     * @param string $scope    The default scope prefix. Default 'upx'.
     * @param string $attr     The attribute for which the value has been build.
     * @param string $modifier The modifier string.
     *
     * @return string The class attribute values
     */
    public function setUseParallax($upxscope, $element, $block, $scope, $attr, $modifier)
    {
        // Be sure the modifier is threaded as an array.
        $modifier = (array)$modifier;

        // Get the settings for the parallax.
        $parallaxSettings = new Settings();

        // Only if the parallax is enabled and for first block levels.
        if ($parallaxSettings->isEnabled() &&
            in_array($block, static::$allowedBlocks, true) &&
            '' === $element &&
            in_array('has-background-image', $modifier, true)
        ) {
            $upxscope .= ' use-parallax ';

            // Enqueue the script for the parallax.
            if (wp_script_is('dlparallax', 'registered')) {
                wp_enqueue_script('dlparallax');
            }
        }

        return $upxscope;
    }
}
