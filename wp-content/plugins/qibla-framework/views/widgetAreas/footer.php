<?php
use Qibla\Functions as F;

/**
 * Footer Widgets Area
 *
 * This is a little bit different than a sidebar, it is loaded via our custom
 * template engine due to the fact that is not a sidebar and also, we have multiple footer widgets area
 * registered, so, use the sidebar-footer.php template will create incongruences with the WordPress load_template
 * mechanism.
 *
 * @since   1.0.0
 *
 * @license GPL 2
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
?>

<div id="<?php echo esc_attr($data->scopeID) ?>" <?php F\sidebarClass('sidebar', 'footer', 'footer') ?>>
    <div <?php F\scopeClass('container', '', 'flex') ?>>
        <?php dynamic_sidebar($data->slug); ?>
    </div>
</div>
