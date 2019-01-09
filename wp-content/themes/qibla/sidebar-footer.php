<?php
use Qibla\Functions as F;

/**
 * Footer Sidebar
 *
 * This is a little bit different than a sidebar, it is loaded via our custom
 * template engine due to the fact that is not a sidebar and also, we have multiple footer widgets area
 * registered.
 *
 * @since   1.0.0
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

if (is_active_sidebar($data->slug) && 'yes' === apply_filters('qibla_show_footer_sidebar', 'yes')) : ?>
    <div id="<?php echo esc_attr($data->scopeID) ?>" <?php F\sidebarClass('sidebar', 'footer', 'footer') ?>>
        <div <?php F\scopeClass('container', '', 'flex') ?>>
            <?php dynamic_sidebar($data->slug); ?>
        </div>
    </div>
<?php else :
    /**
     * Footer sidebar content
     *
     * This function is fired if there is no default sidebar active
     *
     * @since 1.0.0
     *
     * @param string $sidebar The name of the sidebar
     */
    do_action('qibla_footer_sidebar_content', $data->slug);
endif;
