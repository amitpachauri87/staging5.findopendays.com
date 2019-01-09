<?php
namespace QiblaFramework\Front\Settings;

use QiblaFramework\Functions as F;

/**
 * Class Front-end Settings Custom Code
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
 * Class CustomCode
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class CustomCode
{
    /**
     * Custom JavaScript
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function js()
    {
        // Retrieve the option.
        $js = F\getThemeOption('custom_code', 'javascript');

        if (! $js || 0 === strcmp(F\getDefaultOptions('custom_code', 'javascript'), $js)) {
            return;
        }

        // Strip the LF by the string, that useful only within the text-area.
        $js = str_replace("\n", '', $js);

        // Show the google analytics code.
        printf('<script type="text/javascript">/* <![CDATA[ */%s/* ]]> */</script>', $js);
    }
}
