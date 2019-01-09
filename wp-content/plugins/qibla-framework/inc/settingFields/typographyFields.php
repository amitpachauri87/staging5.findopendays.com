<?php
use QiblaFramework\Functions as F;
use QiblaFramework\Form\Factories\FieldFactory;

/**
 * Settings Typography Fields
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

// Get the list of the default Options.
$fontBaseDefault = F\getDefaultOptions('typography', 'font_base');
$fontH1Default   = F\getDefaultOptions('typography', 'font_heading_h1');
$fontH2Default   = F\getDefaultOptions('typography', 'font_heading_h2');
$fontH3Default   = F\getDefaultOptions('typography', 'font_heading_h3');
$fontH4Default   = F\getDefaultOptions('typography', 'font_heading_h4');
$fontH5Default   = F\getDefaultOptions('typography', 'font_heading_h5');
$fontH6Default   = F\getDefaultOptions('typography', 'font_heading_h6');

/**
 * Filter Typography Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
return apply_filters('qibla_opt_inc_typography_fields', array(
    /**
     * Typography Heading h1
     *
     * @since 1.0.0
     */
    'qibla_opt-typography-font_base:typography'       => $fieldFactory->table(array(
        'type'          => 'typography',
        'name'          => 'qibla_opt-typography-font_base',
        'label'         => esc_html__('Base Font', 'qibla-framework'),
        'description'   => esc_html__('Select the font style for the document.', 'qibla-framework'),
        'value'         => F\getThemeOption('typography', 'font_base', true),
        'default_color' => $fontBaseDefault['color'],
    )),

    /**
     * Typography Heading h1
     *
     * @since 1.0.0
     */
    'qibla_opt-typography-font_heading_h1:typography' => $fieldFactory->table(array(
        'type'          => 'typography',
        'name'          => 'qibla_opt-typography-font_heading_h1',
        'label'         => esc_html__('Heading H1', 'qibla-framework'),
        'description'   => esc_html__('Select the font style for H1.', 'qibla-framework'),
        'value'         => F\getThemeOption('typography', 'font_heading_h1', true),
        'default_color' => $fontH1Default['color'],
    )),

    /**
     * Typography Heading h2
     *
     * @since 1.0.0
     */
    'qibla_opt-typography-font_heading_h2:typography' => $fieldFactory->table(array(
        'type'          => 'typography',
        'name'          => 'qibla_opt-typography-font_heading_h2',
        'label'         => esc_html__('Heading H2', 'qibla-framework'),
        'description'   => esc_html__('Select the font style for H2.', 'qibla-framework'),
        'value'         => F\getThemeOption('typography', 'font_heading_h2', true),
        'default_color' => $fontH2Default['color'],
    )),

    /**
     * Typography Heading h3
     *
     * @since 1.0.0
     */
    'qibla_opt-typography-font_heading_h3:typography' => $fieldFactory->table(array(
        'type'          => 'typography',
        'name'          => 'qibla_opt-typography-font_heading_h3',
        'label'         => esc_html__('Heading H3', 'qibla-framework'),
        'description'   => esc_html__('Select the font style for H3.', 'qibla-framework'),
        'value'         => F\getThemeOption('typography', 'font_heading_h3', true),
        'default_color' => $fontH3Default['color'],
    )),

    /**
     * Typography Heading h4
     *
     * @since 1.0.0
     */
    'qibla_opt-typography-font_heading_h4:typography' => $fieldFactory->table(array(
        'type'          => 'typography',
        'name'          => 'qibla_opt-typography-font_heading_h4',
        'label'         => esc_html__('Heading H4', 'qibla-framework'),
        'description'   => esc_html__('Select the font style for H4.', 'qibla-framework'),
        'value'         => F\getThemeOption('typography', 'font_heading_h4', true),
        'default_color' => $fontH4Default['color'],
    )),

    /**
     * Typography Heading h5
     *
     * @since 1.0.0
     */
    'qibla_opt-typography-font_heading_h5:typography' => $fieldFactory->table(array(
        'type'          => 'typography',
        'name'          => 'qibla_opt-typography-font_heading_h5',
        'label'         => esc_html__('Heading H5', 'qibla-framework'),
        'description'   => esc_html__('Select the font style for H5.', 'qibla-framework'),
        'value'         => F\getThemeOption('typography', 'font_heading_h5', true),
        'default_color' => $fontH5Default['color'],
    )),

    /**
     * Typography Heading h6
     *
     * @since 1.0.0
     */
    'qibla_opt-typography-font_heading_h6:typography' => $fieldFactory->table(array(
        'type'          => 'typography',
        'name'          => 'qibla_opt-typography-font_heading_h6',
        'label'         => esc_html__('Heading H6', 'qibla-framework'),
        'description'   => esc_html__('Select the font style for H6.', 'qibla-framework'),
        'value'         => F\getThemeOption('typography', 'font_heading_h6', true),
        'default_color' => $fontH6Default['color'],
    )),

    /**
     * Typography Navigation
     *
     * @since 1.0.0
     */
    'qibla_opt-typography-navigation:typography'      => $fieldFactory->table(array(
        'type'        => 'typography',
        'name'        => 'qibla_opt-typography-navigation',
        'label'       => esc_html__('Main Navigation', 'qibla-framework'),
        'description' => esc_html__('Select the font style for the main navigation.', 'qibla-framework'),
        'value'       => F\getThemeOption('typography', 'navigation', true),
        'exclude'     => array('color'),
    )),

    /**
     * Typography Buttons
     *
     * @since 1.0.0
     */
    'qibla_opt-typography-buttons:typography'         => $fieldFactory->table(array(
        'type'        => 'typography',
        'name'        => 'qibla_opt-typography-buttons',
        'label'       => esc_html__('Buttons', 'qibla-framework'),
        'description' => esc_html__('Select the font style for the buttons.', 'qibla-framework'),
        'value'       => F\getThemeOption('typography', 'buttons', true),
        'exclude'     => array('color'),
    )),
));
