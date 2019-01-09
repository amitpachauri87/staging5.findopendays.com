<?php
namespace QiblaImporter\Scripts;

/**
 * Scripts Register
 *
 * @package QiblaFramework\Scripts
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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

defined('WPINC') || die;

/**
 * Class Register
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class Register
{
    /**
     * Must Registered
     *
     * If the script / style must be enqueued or not
     *
     * @since  1.0.0
     * @access private
     *
     * @param array $item  The current script / style.
     * @param int   $index The index of the callback.
     *
     * @return bool True if must enqueue, false otherwise.
     */
    private function mustRegistered(array $item, $index)
    {
        $register = true;
        if (isset($item[$index]) && is_callable($item[$index])) {
            $register = $item[$index]();
        }

        return $register;
    }

    /**
     * Register Scripts and Styles
     *
     * @since  1.0.0
     * @access static
     *
     * @return void
     */
    public function register(Scripts $scripts)
    {
        foreach ($scripts->getList('styles') as $item) {
            if (! $this->mustRegistered($item, 5)) {
                continue;
            }
            call_user_func_array('wp_register_style', $item);
        }

        foreach ($scripts->getList('scripts') as $item) {
            if (! $this->mustRegistered($item, 5)) {
                continue;
            }
            call_user_func_array('wp_register_script', $item);
        }

        // Localize Scripts.
        $self = $this;
        $hook = is_admin() ? 'admin_print_footer_scripts' : 'wp_print_footer_scripts';
        add_action($hook, function () use ($scripts, $self) {
            $self->localizeScripts($scripts);
        }, 1);
    }

    /**
     * Localize Scripts
     *
     * This is not the wp_localized_script of WordPress.
     * Instead this is a json that will be available from all scripts within the theme.
     *
     * @since  1.0.0
     * @access public
     *
     * @return void
     */
    public function localizeScripts(Scripts $scripts)
    {
        // Get the list of the localized scripts.
        $list = $scripts->getList('localized');

        if (empty($list)) {
            return;
        }

        // Set the context.
        $context = is_admin() ? 'admin' : 'front';
        // Retrieve the correct elements based on context.
        $list = array_filter($list, function ($item) use ($context) {
            return $context === $item['handle'];
        });

        foreach ($list as $item) {
            // Check if the data should be print.
            // By default if the function doesn't exists we assume that the data must be printed in every page.
            $print = isset($item[1]) ? $item[1]() : true;

            if ($print) {
                printf(
                    '<script type="text/javascript">/* <![CDATA[ */%s/* ]]> */</script>',
                    'var ' . sanitize_key($item['name']) . ' = ' . wp_json_encode($item[0]) . ';'
                );
            }
        }
    }
}
