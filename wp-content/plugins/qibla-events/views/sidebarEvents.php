<?php
/**
 * sidebarEvents
 *
 * @since      1.0.0
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

use Qibla\Functions as F;

// Set sidebar
$sidebar = $data && isset($data->postType) ? "qibla-sidebar-{$data->postType}" : 'qibla-sidebar-listings';

if ('yes' === apply_filters('qibla_show_sidebar', 'yes')) : ?>
    <div <?php F\scopeID('sidebar-listings') ?> <?php F\sidebarClass('sidebar', '', 'listings') ?>>

        <?php if ('events' === $data->postType): ?>
        <div class="dlsidebar-wrapper">
        <?php do_action('qibla_after_single_listings');?>
            <?php endif; ?>

            <?php
            /**
             * Before Listings Sidebar
             *
             * @since 1.0.0
             */
            do_action("qibla_before_{$data->postType}_sidebar"); ?>

            <?php if (is_active_sidebar($sidebar)) :
                dynamic_sidebar($sidebar);
            else :
                /**
                 * Default sidebar content
                 *
                 * This function is fired if there is no default sidebar active
                 *
                 * @since 1.0.0
                 *
                 * @param string $sidebar The name of the default sidebar
                 */
                do_action("qibla_{$data->postType}_sidebar_content", $sidebar);
            endif; ?>

            <?php if ('events' === $data->postType): ?>
        </div>
    <?php endif; ?>
    </div>
<?php endif; ?>
