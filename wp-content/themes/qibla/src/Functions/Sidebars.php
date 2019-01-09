<?php

namespace Qibla\Functions;

use Qibla\TemplateEngine\Engine as TEngine;

/**
 * Sidebars Functions
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

/**
 * Add Sidebar to Templates
 *
 * The function try to load different sidebar depend by the page.
 *
 * @since 1.0.0
 */
function sidebar()
{
    // Which sidebar?
    $sidebar = '';

    $postType = array();
    if (checkDependencies()) {
        $types    = new \QiblaFramework\ListingsContext\Types();
        $postType = $types->types();
    }

    if (is_author()) {
        $sidebar = 'author';
    } elseif (isBlog() || is_search()) {
        $sidebar = 'blog';
    } elseif (is_archive()) {
        $sidebar = 'archive';
    } elseif (isAccountPage()) {
        $sidebar = 'myaccount';
    } elseif (is_page()) {
        $sidebar = 'page';
    } elseif (is_single() && is_singular('post')) {
        $sidebar = 'blog';
    } elseif (is_singular($postType)) {
        /**
         * Filter the sidebar name in singular post type.
         *
         * @since 2.2.1
         */
        $sidebar = apply_filters('qibla_sidebar_in_singular_post_type', get_post_type());
    }

    // Archive Listings Sidebar.
    if (is_post_type_archive()) {
        /**
         * Filter the sidebar name in archive post type.
         *
         * @since 2.2.1
         */
        $type    = apply_filters('qibla_sidebar_in_archive_post_type', 'listings');
        $sidebar = 'archive-' . $type;
    }

    if ($sidebar) {
        get_sidebar($sidebar);
    }
}

/**
 * Show Footer Widgets Area
 *
 * @since 1.0.0
 */
function footerSidebar()
{
    // @todo Will be $count <= 4 when multiple row widgets will be implemented.
    for ($count = 1; $count <= 1; ++$count) {
        $data = new \stdClass();

        $data->slug    = 'qibla-sidebar-footer-row-' . $count;
        $data->counter = $count;
        $data->scopeID = getScopeClass('sidebar-footer');
        $data->scopeID = $data->counter ? $data->scopeID . '-' . $data->counter : $data->scopeID;

        $engine = new TEngine('sidebar_footer', $data, '/sidebar-footer.php');
        $engine->render();
    }
}

/**
 * Has Sidebar
 *
 * @since 1.0.0
 *
 * @return string 'yes' if is active 'no' otherwise.
 */
function hasSidebar()
{
    $hasSidebar = 'yes';

    // No sidebar templates.
    $noSidebarTemplates = array('templates/homepage.php', 'templates/homepage-fullwidth.php', 'templates/events-search.php');

    if (// This check needed because the My account have his own sidebar. See sidebar-myaccount.php.
        (! isAccountPage() && is_page() && ! is_active_sidebar('qibla-sidebar-page')) ||
        (isBlog() && ! is_active_sidebar('qibla-sidebar-blog'))
    ) {
        $hasSidebar = 'no';
    }
    if ((is_singular() && in_array(get_page_template_slug(), $noSidebarTemplates, true)) || is_404()) {
        $hasSidebar = 'no';
    }
    // When the user is not logged in, avoid to show unnecessary content.
    if (isAccountPage() && ! is_user_logged_in()) {
        $hasSidebar = 'no';
    }

    // Single Product sidebar.
    if (is_singular('product')) {
        $hasSidebar = 'no';
    }

    // Shop Sidebar.
    if (isWooCommerceArchive()) {
        $hasSidebar = is_active_sidebar('qibla-sidebar-shop') ? 'yes' : 'no';
    }

    /**
     * Filter Has Sidebar
     *
     * @since 1.0.0
     *
     * @param string $hasSidebar If the current template has sidebar or not. Allowed values 'yes', 'no'.
     */
    $hasSidebar = apply_filters('qibla_has_sidebar', $hasSidebar);

    return $hasSidebar;
}

/**
 * Get Sidebar Class
 *
 * @since 1.0.0
 *
 * @uses  getScopeClass()
 *
 * @param string $block    The custom block scope.
 * @param string $modifier The block modifier key.
 * @param string $name     The name of the sidebar.
 *
 * @return string          The sidebar class value
 */
function getSidebarClass($block, $modifier = '', $name = '')
{
    // Current sidebar name.
    $block = $scope = getScopeClass($block);

    /**
     * Filter Modifier
     *
     * @since 1.0.0
     *
     * @param string $modifier The block modifier key.
     * @param string $block    The custom block scope.
     * @param string $name     The name of the sidebar.
     */
    $modifier = apply_filters('qibla_sidebar_class_modifier', $modifier, $block, $name);

    if ($name) {
        /**
         * Filter Modifier By Name
         *
         * @since 1.0.0
         *
         * @param string $modifier The block modifier key.
         * @param string $block    The custom block scope.
         */
        $modifier = apply_filters("qibla_sidebar_{$name}_class_modifier", $modifier, $block);
    }

    if ($modifier) {
        // Current scope modifier.
        $scope = $block . ' ' . $block . '--' . $modifier;
    }

    /**
     * Filter Sidebar Class Scope
     *
     * @since 1.0.0
     *
     * @param string $scope The scope class.
     * @param string $block The block class name.
     * @param string $name  The name of the sidebar.
     */
    $scope = apply_filters('qibla_sidebar_scope_class', $scope, $block, $name);

    if ($name) {
        /**
         * Filter Sidebar Class Scope By Name
         *
         * @since 1.0.0
         *
         * @param string $scope The scope class.
         * @param string $block The block class name.
         */
        $scope = apply_filters("qibla_sidebar_{$name}_scope_class", $scope, $block);
    }

    return trim($scope, ' ');
}

/**
 * Sidebar Class
 *
 * @since 1.0.0
 *
 * @uses  getSidebarClass()
 *
 * @param string $block    The custom block scope.
 * @param string $modifier The block modifier key.
 * @param string $name     The name of the sidebar.
 *
 * @return void
 */
function sidebarClass($block, $modifier = '', $name = '')
{
    echo 'class="' . getSidebarClass($block, $modifier, $name) . '"';
}

/**
 * Sidebar Scope ID
 *
 * @since 2.1.0
 *
 * @uses  getSidebarClass()
 *
 * @param string $block    The custom block scope.
 * @param string $element  The element within the scope.
 * @param string $modifier The block modifier key.
 *
 * @return void
 */
function sidebarID($block = '', $element = '', $modifier = '')
{
    echo 'id="' . getSidebarClass($block, $element, $modifier) . '"';
}
