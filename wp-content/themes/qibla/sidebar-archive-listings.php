<?php
/**
 * sidebar-archive-listings
 *
 * @since      2.4.0
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

// Listings Sidebar.
$sidebar = 'qibla-sidebar-archive-listings';

/**
 * Load alternative template for sidebar
 *
 * @since 2.4.0
 */
do_action('qibla_load_template_archive_listings_sidebar');

if (! has_action('qibla_load_template_archive_listings_sidebar')) :
    if ('yes' === apply_filters('qibla_show_archive_listings_sidebar', 'yes')) :
        if (is_active_sidebar($sidebar)) : ?>
            <div <?php F\scopeID('sidebar-archive-listings') ?> <?php F\sidebarClass('sidebar', 'archive-listings', '') ?>>
                <div class="dlcontainer">
                    <?php dynamic_sidebar($sidebar); ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>