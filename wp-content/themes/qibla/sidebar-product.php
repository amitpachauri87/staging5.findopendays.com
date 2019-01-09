<?php
/**
 * sidebar-single-product
 *
 * @since      2.1.0
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

// Sidebar ID.
$sidebar = 'qibla-sidebar-product';

if (is_active_sidebar($sidebar) && 'yes' === apply_filters('qibla_show_sidebar', 'yes')) : ?>
    <div <?php F\scopeID('sidebar-single-product') ?> <?php F\sidebarClass('sidebar', '', 'single-product') ?>>
        <?php
        /**
         * Before Product Sidebar
         *
         * @since 2.1.0
         */
        do_action('qibla_before_single_product_sidebar');

        if (is_active_sidebar($sidebar)) :
            dynamic_sidebar($sidebar);
        else :
            /**
             * Default sidebar content
             *
             * This function is fired if there is no default sidebar active
             *
             * @since 2.1.0
             *
             * @param string $sidebar The name of the default sidebar
             */
            do_action('qibla_single_product_sidebar_content', $sidebar);
        endif; ?>
    </div>
<?php endif; ?>