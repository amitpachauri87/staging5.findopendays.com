<?php
namespace QiblaImporter\Scripts;

/**
 * Scripts Enqueuer
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
 * Class Enqueue
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class Enqueuer
{
    /**
     * Must Enqueued
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
    private function mustEnqueued(array $item, $index)
    {
        $enqueue = true;
        if (isset($item[$index]) && is_callable($item[$index])) {
            $enqueue = $item[$index]();
        }

        return $enqueue;
    }

    /**
     * Enqueue Scripts and Styles
     *
     * @since  1.0.0
     * @access static
     *
     * @return void
     */
    public function enqueuer(Scripts $scripts)
    {
        foreach ($scripts->getList('styles') as $item) {
            if (! $this->mustEnqueued($item, 6) || ! wp_style_is($item[0], 'registered')) {
                continue;
            }
            call_user_func_array('wp_enqueue_style', $item);
        }

        foreach ($scripts->getList('scripts') as $item) {
            if (! $this->mustEnqueued($item, 6) || ! wp_script_is($item[0], 'registered')) {
                continue;
            }
            call_user_func_array('wp_enqueue_script', $item);
        }
    }
}
