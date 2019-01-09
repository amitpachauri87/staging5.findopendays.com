<?php
/**
 * VisualComposerTypeInterface
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

namespace QiblaFramework\VisualComposer\Type;

/**
 * Interface VisualComposerTypeInterface
 *
 * @since  1.6.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
interface VisualComposerTypeInterface
{
    /**
     * Type Slug
     *
     * @since 1.6.0
     *
     * @return string The slug of the type
     */
    public function slug();

    /**
     * Field Callback
     *
     * @since 1.6.0
     *
     * @param array  $settings The settings for the field.
     * @param string $value    The current value for the field.
     *
     * @return string The field markup
     */
    public function callback($settings, $value);

    /**
     * Script Url
     *
     * @since 1.6.0
     *
     * @return string The script absolute Url
     */
    public function scriptUrl();
}
