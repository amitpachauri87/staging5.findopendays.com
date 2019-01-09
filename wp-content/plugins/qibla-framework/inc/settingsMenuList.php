<?php
/**
 * Settings Menu List
 *
 * @author  guido scialfa <dev@guidoscialfa.com>
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

/**
 * Filter Menu Settings list
 *
 * @since 1.0.0
 *
 * @param array $array The list of the menu settings
 */
$list = apply_filters('qibla_settings_menu_list', array(
    array(
        'page_title' => esc_html__('Logo', 'qibla-framework'),
        'menu_title' => esc_html__('Logo', 'qibla-framework'),
        'menu_slug'  => 'logo',
        'icon_class' => array('la', 'la-apple'),
    ),
    array(
        'page_title' => esc_html__('Typography', 'qibla-framework'),
        'menu_title' => esc_html__('Typography', 'qibla-framework'),
        'menu_slug'  => 'typography',
        'icon_class' => array('la', 'la-font'),
    ),
    array(
        'page_title' => esc_html__('Colors', 'qibla-framework'),
        'menu_title' => esc_html__('Colors', 'qibla-framework'),
        'menu_slug'  => 'colors',
        'icon_class' => array('la', 'la-paint-brush'),
    ),
    array(
        'page_title' => esc_html__('Header', 'qibla-framework'),
        'menu_title' => esc_html__('Header', 'qibla-framework'),
        'menu_slug'  => 'header',
        'icon_class' => array('la', 'la-columns'),
    ),
    array(
        'page_title' => esc_html__('Footer', 'qibla-framework'),
        'menu_title' => esc_html__('Footer', 'qibla-framework'),
        'menu_slug'  => 'footer',
        'icon_class' => array('la', 'la-columns'),
    ),
    array(
        'page_title' => esc_html__('Blog', 'qibla-framework'),
        'menu_title' => esc_html__('Blog', 'qibla-framework'),
        'menu_slug'  => 'blog',
        'icon_class' => array('la', 'la-newspaper-o'),
    ),
    array(
        'page_title' => esc_html__('Pages', 'qibla-framework'),
        'menu_title' => esc_html__('Pages', 'qibla-framework'),
        'menu_slug'  => 'page',
        'icon_class' => array('la', 'la-file-o'),
    ),
    array(
        'page_title' => esc_html__('Listings', 'qibla-framework'),
        'menu_title' => esc_html__('Listings', 'qibla-framework'),
        'menu_slug'  => 'listings',
        'icon_class' => array('la', 'la-map-marker'),
    ),
    array(
        'page_title' => esc_html__('404 Page Error', 'qibla-framework'),
        'menu_title' => esc_html__('404 Page Error', 'qibla-framework'),
        'menu_slug'  => 'page-404',
        'icon_class' => array('la', 'la-unlink'),
    ),
    array(
        'page_title' => esc_html__('Google Map', 'qibla-framework'),
        'menu_title' => esc_html__('Google Map', 'qibla-framework'),
        'menu_slug'  => 'google-map',
        'icon_class' => array('la', 'la-map'),
    ),
    array(
        'page_title' => esc_html__('Search', 'qibla-framework'),
        'menu_title' => esc_html__('Search', 'qibla-framework'),
        'menu_slug'  => 'search',
        'icon_class' => array('la', 'la-search'),
    ),
    array(
        'page_title' => esc_html__('Socials', 'qibla-framework'),
        'menu_title' => esc_html__('Socials', 'qibla-framework'),
        'menu_slug'  => 'socials',
        'icon_class' => array('la', 'la-users'),
    ),
    array(
        'page_title' => esc_html__('Custom Code', 'qibla-framework'),
        'menu_title' => esc_html__('Custom Code', 'qibla-framework'),
        'menu_slug'  => 'custom-code',
        'icon_class' => array('la', 'la-code'),
    ),
    array(
        'page_title' => esc_html__('Import / Export', 'qibla-framework'),
        'menu_title' => esc_html__('Import / Export', 'qibla-framework'),
        'menu_slug'  => 'import-export',
        'icon_class' => array('la', 'la-wrench'),
    ),
));

// Events Menu list.
if (is_plugin_active('qibla-events/index.php')) {
    $list = \QiblaFramework\Functions\arrayInsertInPos(
        array(
            array(
                'page_title' => esc_html__('Events', 'qibla-framework'),
                'menu_title' => esc_html__('Events', 'qibla-framework'),
                'menu_slug'  => 'events',
                'icon_class' => array('la', 'la-calendar'),
            ),
        ),
        $list,
        8
    );
}

// Events Menu list.
if (is_plugin_active('qibla-tours/index.php')) {
    $list = \QiblaFramework\Functions\arrayInsertInPos(
        array(
            array(
                'page_title' => esc_html__('Tours', 'qibla-framework'),
                'menu_title' => esc_html__('Tours', 'qibla-framework'),
                'menu_slug'  => 'tours',
                'icon_class' => array('la', 'la-map-pin'),
            ),
        ),
        $list,
        9
    );
}

return $list;
