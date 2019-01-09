<?php
namespace QiblaFramework\Front\Settings;

use QiblaFramework\Functions as F;

/**
 * Class Front-end Settings Page
 *
 * @since      1.0.0
 * @package    QiblaFramework\Front\Settings
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa
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
 * Class Page
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Page
{
    /**
     * Force Disable Page Comments
     *
     * @since  1.0.0
     *
     * @param string $disable The current value of the disable status
     *
     * @return string The filtered status, 'yes' to disable, 'no' to keep.
     */
    public function forceDisableComments($disable)
    {
        if (! is_singular('page')) {
            return $disable;
        }

        // Retrieve the option.
        $option = F\getThemeOption('page', 'disable_comments', true);
        // 'on' => 'yes', off => 'no'
        $disable = 'on' === $option ? 'yes' : 'no';

        return $disable;
    }
}
