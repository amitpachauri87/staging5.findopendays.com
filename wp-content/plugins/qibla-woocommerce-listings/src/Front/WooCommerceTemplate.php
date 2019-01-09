<?php

namespace QiblaWcListings\Front;

/**
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaWcListings\Front
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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
use QiblaWcListings\Plugin;

/**
 * Class WooCommerceTemplate
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaWcListings\Front
 */
class WooCommerceTemplate
{
    /**
     * Templates List
     *
     * @since  1.0.0
     *
     * @var array The list of the template to override
     */
    private static $templatesList = array(
        'booking-form/datetime-picker.php',
    );

    /**
     * Get Template
     *
     * This is the callback for the filter wc_get_template.
     *
     * @since 1.0.0
     *
     * @param string $located      The located template.
     * @param string $templateName The template name.
     * @param array  $args         The arguments to pass to the template.
     * @param string $templatePath The template Path.
     * @param string $defaultPath  The default template path
     *
     * @return string The new located template path
     */
    public function filterTemplate($located, $templateName, $args, $templatePath, $defaultPath)
    {
        if (in_array($templateName, self::$templatesList, true)) {
            $fileName = basename($located);
            $newPath  = Plugin::getPluginDirPath("/views/woocommerce/{$fileName}");

            if ($newPath) {
                $located = $newPath;
            }
        }

        return $located;
    }
}
