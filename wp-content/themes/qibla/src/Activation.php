<?php
namespace Qibla;

use Qibla\Admin\Notice\Notice;

/**
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    Qibla
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
 * Class Activation
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package Qibla
 */
class Activation
{
    /**
     * Switch to the theme or the default theme
     *
     * @since  1.0.0
     *
     * @param $themeToSwitch
     */
    protected static function switchThemeOnRequirementsFail($themeToSwitch)
    {
        // May be needed.
        remove_action('after_switch_theme', array('Qibla\\Activation', 'checkRequirements'), 0);

        // Switch to the theme.
        switch_theme($themeToSwitch);
    }

    /**
     * Check Requirements
     *
     * @since  1.0.0
     *
     * @return void
     */
    public static function checkRequirements()
    {
        // Get the arguments for the method.
        // Because of the hook, WordPress may not pass two arguments but one.
        $args = func_get_args();
        // Set the old theme name.
        $oldTheme = (2 === count($args) ? $args[1] : \WP_Theme::get_core_default_theme());
        // Build the requirements and perform check.
        $req    = new Requirements(include Theme::getTemplateDirPath('/inc/requirements.php'));
        $checks = $req->check();

        // Some check not passed.
        if (! empty($checks) && class_exists('Qibla\\Admin\\Notice\\Notice')) {
            // Add the notice to inform the user.
            add_action('admin_notices', function () use ($checks, $oldTheme) {
                foreach ($checks as $what => $check) {
                    $message = '<strong>' . ucfirst($what) . ': </strong>' . $check['message'];
                    $notice = new Notice($message, 'error');
                    $notice->notice();

                    // Switch theme only if the php version is not compatible.
                    if ('php' === $what) {
                        // Switch to the old or default theme.
                        Activation::switchThemeOnRequirementsFail($oldTheme);
                        break;
                    }
                }
            });
        }
    }
}
