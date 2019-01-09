<?php
/**
 * Header
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

namespace QiblaFramework\Front\Settings;

use QiblaFramework\Functions as F;

/**
 * Class Header
 *
 * @since  1.6.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class Header
{
    /**
     * Header Sticky
     *
     * @since  1.6.0
     *
     * @return bool True if option is enabled. False otherwise.
     */
    public static function headerSticky()
    {
        return 'on' === F\getThemeOption('header', 'sticky_header', true);
    }

    /**
     * Set Header Fixed if Sticky
     *
     * @since 1.7.0
     *
     * @param string $upxscope The scope prefix. Default 'upx'.
     * @param string $element  The current element of the scope.
     * @param string $block    The custom block scope. Default empty.
     * @param string $scope    The default scope prefix. Default 'upx'.
     * @param string $attr     The attribute for which the value has been build.
     *
     * @return string The filtered scope class value
     */
    public static function setHeaderFixedIfStickyFilter($upxscope, $element, $block, $scope, $attr)
    {
        if (self::headerSticky() && 'class' === $attr && 'header' === $block && '' === $element) {
            $upxscope .= " {$scope}{$block}--fixed";
        }

        // Remove after done.
        remove_action('qibla_scope_attribute', 'QiblaFramework\\Front\\Settings\\Header::setHeaderFixedIfSticky', 20);

        return $upxscope;
    }
}
