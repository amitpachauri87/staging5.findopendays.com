<?php
/**
 * SocialLoginPositionOverride
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

namespace QiblaFramework\SocialLogin;

/**
 * Class SocialLoginPositionOverride
 *
 * @since   1.6.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\SocialLogin
 */
class SocialLoginPluginTemplatePositionOverride
{
    /**
     * Remove Plugin Hooks
     *
     * @since 1.6.0
     *
     * @return $this
     */
    public function removePluginHooks()
    {
        remove_action('comment_form_top', 'wsl_render_auth_widget_in_comment_form');
        remove_action('comment_form_must_log_in_after', 'wsl_render_auth_widget_in_comment_form');

        return $this;
    }

    /**
     * Set new Plugin Hooks
     *
     * @since 1.6.0
     *
     * @return $this
     */
    public function setNewHooks()
    {
        add_action('comment_form_after_fields', 'wsl_render_auth_widget_in_comment_form');

        return $this;
    }

    /**
     * Reset Position Filter
     *
     * @since 1.6.0
     *
     * @return void
     */
    public static function resetPositionFilter()
    {
        if (defined('WORDPRESS_SOCIAL_LOGIN_ABS_PATH')) {
            $instance = new static;
            $instance->removePluginHooks()->setNewHooks();
        }
    }
}
