<?php
use Qibla\Functions as F;

/**
 * Sidebar My Account
 *
 * @since   1.1.0
 *
 * @license GNU General Public License, version 2
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

// Sidebar ID.
$sidebar = 'qibla-sidebar-myaccount';

if ('yes' === apply_filters('qibla_show_sidebar', 'yes')) : ?>
    <div <?php F\scopeID('sidebar-myaccount') ?> <?php F\sidebarClass('sidebar', '', 'myaccount') ?>>
        <?php
        /**
         * Before My Account Sidebar
         *
         * @since 1.1.0
         */
        do_action('qibla_before_myaccount_sidebar');

        if (is_active_sidebar($sidebar)) :
            dynamic_sidebar($sidebar);
        else :
            /**
             * Page sidebar content
             *
             * This function is fired if there is no default sidebar active
             *
             * @since 1.1.0
             *
             * @param string $sidebar The name of the sidebar
             */
            do_action('qibla_myaccount_sidebar_content', $sidebar);
        endif; ?>
    </div>
<?php endif;