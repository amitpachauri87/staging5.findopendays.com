<?php
use QiblaFramework\Functions as F;
use QiblaFramework\Form\Factories\FieldFactory;

/**
 * Settings Page Fields
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

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

/**
 * Filter Page Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
return apply_filters('qibla_opt_inc_page_fields', array(
    /**
     * Sticky Sidebar
     *
     * @since 1.0.0
     */
    /*'qibla_opt-pages-sidebar_sticky:checkbox'   => $fieldFactory->table([
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_opt-pages-sidebar_sticky',
        'label'       => esc_html__('Sticky Sidebar', 'qibla-framework'),
        'description' => esc_html__('If you want to set the sidebar as sticky on scroll.', 'qibla-framework'),
        'value'       => F\getThemeOption('page', 'sidebar_sticky', true),
    ]),*/

    /**
     * Sidebar Position
     *
     * @since 1.0.0
     */
    'qibla_opt-page-sidebar_position:radio'    => $fieldFactory->table(array(
        'type'        => 'radio',
        'name'        => 'qibla_opt-page-sidebar_position',
        'options'     => array(
            'none'  => esc_html__('No Sidebar', 'qibla-framework'),
            'left'  => esc_html__('Left', 'qibla-framework'),
            'right' => esc_html__('Right', 'qibla-framework'),
        ),
        'label'       => esc_html_x('Sidebar Position', 'settings', 'qibla-framework'),
        'description' => esc_html_x(
            'Select the position of the sidebar for all pages.',
            'settings',
            'qibla-framework'
        ),
        'value'       => F\getThemeOption('page', 'sidebar_position', true),
    )),

    /**
     * Disable Comments
     *
     * @since 1.0.0
     */
    'qibla_opt-page-disable_comments:checkbox' => $fieldFactory->table(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_opt-page-disable_comments',
        'label'       => esc_html_x('Force Disabled Comments', 'settings', 'qibla-framework'),
        'description' => esc_html_x(
            'Force comments to be disabled within all pages. This option override the allow comments options.',
            'settings',
            'qibla-framework'
        ),
        'value'       => F\getThemeOption('page', 'disable_comments', false),
    )),
));
