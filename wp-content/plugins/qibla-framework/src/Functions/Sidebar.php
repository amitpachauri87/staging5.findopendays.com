<?php
/**
 * Front Functions Sidebar
 *
 * @todo    Move into a class.
 *
 * @since   1.0.0
 * @package QiblaFramework\Front
 *
 * Copyright (C) 2016 Guido Scialfa
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

namespace QiblaFramework\Functions;

use QiblaFramework\Functions as F;

/**
 * Has Sidebar
 *
 * Filter the has sidebar value based on meta/term data.
 *
 * @since 1.0.0
 *
 * @uses  getSidebarPosition() to retrieve the sidebar position.
 *
 * @param string $yesNo If the current post or term has sidebar.
 *
 * @return bool If the object include sidebar.
 */
function hasSidebar($yesNo)
{
    // Get the sidebar.
    $meta  = getSidebarPosition();
    $yesNo = ('none' === $meta ? 'no' : $yesNo);

    /**
     * Filter Has Sidebar
     *
     * @since 2.0.0
     *
     * @param string $yesNo 'yes' or 'no' value depending on sidebar position returned value.
     */
    $yesNo = apply_filters('qibla_fw_has_sidebar', $yesNo);

    return $yesNo;
}

/**
 * Get Sidebar
 *
 * @since  1.0.0
 *
 * @return string The sidebar position or 'none' if no sidebar has been specified for that object.
 */
function getSidebarPosition()
{
    $obj = get_queried_object();

    // Listings Archives doesn't have sidebars.
    if (isListingsArchive()) {
        return 'none';
    }

    $type = null;
    if (is_object($obj)) {
        // Retrieve the current object class name.
        $type = strtolower(get_class($obj));
    }

    // Perform the correct action based on instance type.
    switch ($type) {
        // Terms but Category, Tags.
        case 'wp_term':
            $meta = F\getTermMeta('_qibla_tb_sidebar_position', $obj->term_id, 'default');
            break;
        // Post, Page, Post types and Home.
        // Post type archives.
        case 'wp_post_type':
        case 'wp_post':
            if (isShop()) {
                $obj = get_post(get_option('woocommerce_shop_page_id'));
            }
            // Other archives may not have pages associated.
            $meta = isset($obj->ID) ? F\getPostMeta('_qibla_mb_sidebar_position', 'default', $obj->ID) : 'default';
            break;
    }


    if (! isset($meta) || 'default' === $meta) {
        if (isBlog()) {
            $meta = F\getThemeOption('blog', 'sidebar_position', true);
        } elseif (isset($obj->post_type)) {
            $meta = F\getThemeOption('listings', 'sidebar_position', true);
        } else {
            $meta = 'right';
        }
    }

    return $meta;
}

/**
 * Hide Title
 *
 * @since  1.0.0
 *
 * @param string $scope The html class string.
 * @param string $block The block class name.
 *
 * @return mixed The filtered argument
 */
function setModifier($scope, $block)
{
    // Get the post meta.
    $meta = getSidebarPosition();

    // If no sidebar has been specified for the queried page, return the scope as is.
    if (false === $meta) {
        return $scope;
    }

    return $scope . ' ' . $block . '--' . str_replace('_', '-', sanitize_key($meta));
}
