<?php
namespace Qibla\Admin\Upgrade;

use QiblaFramework\Admin\Settings\HandlerCss;

/**
 * UpdateDynamicCss
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    Qibla\Admin\Upgrade
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
 * Class UpdateDynamicCss
 *
 * @since   1.4.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package Qibla\Admin\Upgrade
 */
class UpdateDynamicCss
{
    /**
     * Conditional check if the update can be performed
     *
     * @since 1.4.0
     *
     * @param array $extraHooks Additional information about the update.
     *
     * @return bool True if the update can be performed. False otherwise.
     */
    private static function canUpdatePerformed($extraHooks)
    {
        // Process only if the upgrade process is completed.
        // Is WordPress uprading our theme?
        // If the framework available?
        return 'upgrader_process_complete' === current_action() &&
               (isset($extraHooks['theme']) && 'qibla' === $extraHooks['theme']) &&
               (is_plugin_active('qibla-framework/index.php') &&
                class_exists('QiblaFramework\\Admin\\Settings\\HandlerCss'));
    }

    /**
     * Update the Dynamic Css
     *
     * @since 1.4.0
     *
     * @param \Theme_Upgrader $themeUpgrader The instance of the class.
     * @param  array          $extraHooks    Additional information about the upgrade.
     *
     * @return void
     */
    public static function updateDynamicCssOnProcessComplete($themeUpgrader, $extraHooks)
    {
        if (self::canUpdatePerformed($extraHooks)) {
            try {
                // Create the instance.
                $handlerCssInstance = new HandlerCss();
                // Regenerate the Css to reflect the new changes.
                // The parameter must be 'colors' or 'typography' but isn't necessary to use both since
                // using one context will regenerate the css.
                $handlerCssInstance->handle('colors');
            } catch (\Exception $e) {
                // Show the feedback message for debugging purpose.
                $themeUpgrader->skin->feedback($e->getMessage());
            }

            // Remove the filter, no longer needed.
            remove_filter('upgrader_process_complete', __METHOD__);
        }
    }
}
