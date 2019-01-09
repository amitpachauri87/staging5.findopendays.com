<?php
/**
 * Default Options
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

$defaultOptions = array(
    // Typography.
    'typography'  => array(
        'font_base'       => array(
            'family'   => 'Lato',
            'variants' => 'normal',
            'weights'  => 'w400',
            'color'    => '#414042',
        ),
        'font_heading_h1' => array(
            'family'   => 'Lato',
            'variants' => 'normal',
            'weights'  => 'w900',
            'color'    => '#414042',
        ),
        'font_heading_h2' => array(
            'family'   => 'Lato',
            'variants' => 'normal',
            'weights'  => 'w700',
            'color'    => '#414042',
        ),
        'font_heading_h3' => array(
            'family'   => 'Lato',
            'variants' => 'normal',
            'weights'  => 'w400',
            'color'    => '#414042',
        ),
        'font_heading_h4' => array(
            'family'   => 'Lato',
            'variants' => 'normal',
            'weights'  => 'w700',
            'color'    => '#414042',
        ),
        'font_heading_h5' => array(
            'family'   => 'Lato',
            'variants' => 'normal',
            'weights'  => 'w700',
            'color'    => '#414042',
        ),
        'font_heading_h6' => array(
            'family'   => 'Lato',
            'variants' => 'normal',
            'weights'  => 'w700',
            'color'    => '#414042',
        ),
        'navigation'      => array(
            'family'   => 'Lato',
            'variants' => 'normal',
            'weights'  => 'w700',
        ),
        'buttons'         => array(
            'family'   => 'Lato',
            'variants' => 'normal',
            'weights'  => 'w700',
        ),
    ),

    // Colors.
    'colors'      => array(
        'main'                     => '#f26522',
        'text_selected'            => '#ffffff',
        'background_text_selected' => '#f26522',
        'background'               => '#f5f5f5',
        'text_footer'              => '#ffffff',
        'background_footer'        => '#f47922',
        'text_colophon'            => '#ffffff',
        'background_colophon'      => '#f26522',
    ),

    // Header.
    'header'      => array(
        'sticky_header' => 'on',
    ),

    // Footer.
    'footer'      => array(
        'social_links'     => 'on',
        'google_analytics' => '',
        'copyright'        => sprintf(
            esc_html_x('Proudly by %1$s - Theme Name: %2$s', 'default-options', 'qibla-framework'),
            '<a href="https://www.southemes.com">' . __('Southemes', 'qibla-framework') . '</a>',
            '<a href="http://www.qibla.com">' . __('Qibla', 'qibla-framework') . '</a>'
        ),
    ),

    // Blog.
    'blog'        => array(
        'posts_per_page'   => 10,
        'limit_excerpt'    => 15,
        'sidebar_position' => 'right',
        'sidebar_sticky'   => 'off',
    ),

    // Pages.
    'page'        => array(
        'sidebar_position' => 'none',
        'sidebar_sticky'   => 'off',
        'disable_comments' => 'off',
    ),

    // Listings.
    'listings'    => array(
        'archive_show_map'       => 'on',
        'posts_per_page'         => 10,
        'related_post_cta_label' => esc_html_x('Discover all activities', 'default-options', 'qibla-framework'),
        'order_by_featured'      => 'on',
        'disable_reviews'        => 'off',
        'disable_map'            => 'off',
        'sidebar_sticky'         => 'off',
        'sidebar_position'       => 'right',
    ),

    // Events.
    'events' => array(
        'archive_show_map' => 'on',
        'search-type'      => 'simple',
    ),

    // Events.
    'tours' => array(
        'archive_show_map' => 'on',
        'travel-mode'      => 'DRIVING',
        'search-type'      => 'simple',
    ),

    // 404
    'page_404'    => array(
        'title'    => esc_html_x('Sorry this page doesn\'t exists', 'default-options', 'qibla-framework'),
        'subtitle' => esc_html_x(
            'It looks like nothing was found at this location. Maybe try a search?',
            'default-options',
            'qibla-framework'
        ),
    ),

    // Search.
    'search'      => array(
        'placeholder'          => esc_html_x('What are you looking for?', 'default-options', 'qibla-framework'),
        'geocoded_placeholder' => esc_html_x('Where about?', 'default-options', 'qibla-framework'),
        'submit_label'         => esc_html_x('Search', 'default-options', 'qibla-framework'),
        'submit_icon'          => 'Lineawesome::la-search',
        'type'                 => 'simple',
    ),

    // Google Map.
    'google_map' => array(
        'zoom'     => 15,
        'location' => '40.7127837,-74.00594130000002:New York, NY, USA',
    ),

    // Custom Code.
    'custom_code' => array(
        'javascript' => ';(function(){}());',
    ),
);

// @codingStandardsIgnoreStart
if (file_exists(\QiblaFramework\Functions\pathFromThemeFallbackToPlugins('/inc/listingsTypes.php'))) {
    $postTypes = include(\QiblaFramework\Functions\pathFromThemeFallbackToPlugins('/inc/listingsTypes.php'));
    // Default archive description.
    foreach ($postTypes as $type) {
        $defaultOptions['listings']["{$type}_archive_description"] = '';
    }
    foreach ($postTypes as $type) {
        $defaultOptions['listings']["{$type}_reviewer_template_id"] = '';
    }
}
// @codingStandardsIgnoreEnd

return $defaultOptions;
