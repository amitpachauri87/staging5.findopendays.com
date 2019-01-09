<?php
/**
 * Shortcode Visual Composer interface
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

namespace QiblaFramework\Shortcode;

/**
 * Interface ShortcodeVisualComposerInterface
 *
 * @since  1.6.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
interface ShortcodeVisualComposerInterface
{
    /**
     * The Visual composer map
     *
     * Return the data needed by the vc_map() function.
     *
     * @since 1.6.0
     * @link  https://wpbakery.atlassian.net/wiki/spaces/VC/pages/524332/vc+map
     *
     * @return array The data need by the wc_map() function
     */
    public function visualComposerMap();
}
