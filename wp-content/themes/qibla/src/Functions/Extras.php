<?php

namespace Qibla\Functions;

/**
 * Extra Functions
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

/**
 * Add body classes
 *
 * Extend the WordPress body class by adding more classes about the device.
 *
 * @since 1.0.0
 *
 * @param  array $classes The body classes.
 *
 * @return array $classes The body classes filtered
 */
function bodyClass($classes)
{
    global $is_lynx, $is_gecko, $is_winIE, $is_macIE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone,
           $is_edge, $mobileDetect;

    $is_ipad = $is_tablet = $is_mobile = $is_smartphone = $is_desktop = $is_android = false;

    if (wp_is_mobile() && class_exists('\Mobile_Detect')) {
        $mobileDetect = new \Mobile_Detect();
        $is_mobile    = true;

        // Apple products classes.
        if ($mobileDetect->isIpad()) {
            $is_ipad   = true;
            $is_iphone = ! $is_ipad;
        }

        // Android classes.
        if ($mobileDetect->isAndroidOs()) {
            $is_android = true;
        }

        // General mobile classes.
        if ($mobileDetect->isTablet()) {
            $is_tablet = true;
        } else {
            $is_smartphone = true;
        }
    } elseif (! wp_is_mobile()) {
        $is_desktop = true;
    }

    $class = compact(
        'is_lynx',
        'is_gecko',
        'is_winIE',
        'is_macIE',
        'is_opera',
        'is_NS4',
        'is_safari',
        'is_chrome',
        'is_iphone',
        'is_edge',
        'is_ipad',
        'is_tablet',
        'is_android',
        'is_smartphone',
        'is_mobile',
        'is_desktop'
    );

    // Only the correct classes.
    $class = array_filter($class, function ($v) {
        return (is_bool($v) && true === $v);
    });

    // Clean class name.
    $class = array_map(function ($v) {
        return str_replace('_', '-', $v);
    }, array_keys($class));

    // Add browser and device classes.
    $classes = array_merge($classes, $class);

    // Has Sidebar.
    // Every page has a sidebar even if the sidebar has no widgets in it.
    // That means that if you want to get the full width of the page content for your main content, you should
    // remove the dl-has-sidebar class by hooking in 'qibla_has_sidebar'.
    if ('yes' === hasSidebar()) {
        $classes[] = 'dl-has-sidebar';
    }

    // Front page
    // Avoid usage of .home.page and add .is-front-page instead.
    if (is_front_page()) {
        $classes[] = 'dl-is-front-page';
    }

    // Is Blog
    // Add custom class to know if one of the following pages are displayed.
    if (isBlog()) {
        $classes[] = 'dl-is-blog';
    }

    // Has Featured Image
    // Add extra class to the single post when the post has a featured image set.
    if (is_singular() && has_post_thumbnail()) {
        $classes[] = 'dl-has-post-thumbnail';
    }

    // Add class for jumbo-tron.
    if (isJumbotronAllowed()) {
        $classes[] = 'dl-has-jumbotron';
    }

    return $classes;
}

/**
 * Header Video Settings Filter
 *
 * @since 1.2.0
 *
 * @param array $settings The settings of the video.
 *
 * @return array The filtered settings
 */
function headerVideoSettings(array $settings)
{
    $settings['width']  = get_theme_support('custom-header', 'width');
    $settings['height'] = get_theme_support('custom-header', 'height');

    return $settings;
}
