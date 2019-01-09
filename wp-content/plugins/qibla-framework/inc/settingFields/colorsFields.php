<?php
use QiblaFramework\Functions as F;
use QiblaFramework\Form\Factories\FieldFactory;

/**
 * Settings Color Fields
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
// Get the values.
$optionValues = F\getThemeOption('colors', '', true);
$defaults     = F\getDefaultOptions('colors');

/**
 * Filter Colors Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
return apply_filters('qibla_opt_inc_colors_fields', array(
    /**
     * Main Brand Color
     *
     * @since 1.0.0
     */
    'qibla_opt-colors-main:color_picker'                     => $fieldFactory->table(array(
        'type'        => 'color_picker',
        'name'        => 'qibla_opt-colors-main',
        'label'       => esc_html_x('Main Color', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Select the Main color for the site', 'settings', 'qibla-framework'),
        'attrs'       => array(
            'value'              => $optionValues['main'],
            'data-default-color' => $defaults['main'],
        ),
    )),

    /**
     * Selected Text Color
     *
     * @since 1.0.0
     */
    'qibla_opt-colors-text_selected:color_picker'            => $fieldFactory->table(array(
        'type'        => 'color_picker',
        'name'        => 'qibla_opt-colors-text_selected',
        'label'       => esc_html_x('Selected Text', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Select the color for the selected text.', 'settings', 'qibla-framework'),
        'attrs'       => array(
            'value'              => $optionValues['text_selected'],
            'data-default-color' => $defaults['text_selected'],
        ),
    )),

    /**
     * Selected text background
     *
     * @since 1.0.0
     */
    'qibla_opt-colors-background_text_selected:color_picker' => $fieldFactory->table(array(
        'type'        => 'color_picker',
        'name'        => 'qibla_opt-colors-background_text_selected',
        'label'       => esc_html_x('Background Selected Text', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Select the color for the background text when selected.', 'settings',
            'qibla-framework'),
        'attrs'       => array(
            'value'              => $optionValues['background_text_selected'],
            'data-default-color' => $defaults['background_text_selected'],
        ),
    )),

    /**
     * Body Background Color
     *
     * @since 1.0.0
     */
    'qibla_opt-colors-background:color_picker'               => $fieldFactory->table(array(
        'type'        => 'color_picker',
        'name'        => 'qibla_opt-colors-background',
        'label'       => esc_html_x('Background Body Color', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Select the color for the background of the page.', 'settings', 'qibla-framework'),
        'attrs'       => array(
            'value'              => $optionValues['background'],
            'data-default-color' => $defaults['background'],
        ),
    )),

    /**
     * Footer Color
     *
     * @since 1.0.0
     */
    'qibla_opt-colors-text_footer:color_picker'              => $fieldFactory->table(array(
        'type'        => 'color_picker',
        'name'        => 'qibla_opt-colors-text_footer',
        'label'       => esc_html_x('Footer Text', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Select the color for the footer text.', 'settings', 'qibla-framework'),
        'attrs'       => array(
            'value'              => $optionValues['text_footer'],
            'data-default-color' => $defaults['text_footer'],
        ),
    )),

    /**
     * Footer Background Color
     *
     * @since 1.0.0
     */
    'qibla_opt-colors-background_footer:color_picker'        => $fieldFactory->table(array(
        'type'        => 'color_picker',
        'name'        => 'qibla_opt-colors-background_footer',
        'label'       => esc_html_x('Background Footer Color', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Select the color for the footer background.', 'settings', 'qibla-framework'),
        'attrs'       => array(
            'value'              => $optionValues['background_footer'],
            'data-default-color' => $defaults['background_footer'],
        ),
    )),

    /**
     * Colophon Color
     *
     * @since 1.0.0
     */
    'qibla_opt-colors-text_colophon:color_picker'            => $fieldFactory->table(array(
        'type'        => 'color_picker',
        'name'        => 'qibla_opt-colors-text_colophon',
        'label'       => esc_html_x('Colophon Text', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Select the color for the colophon text.', 'settings', 'qibla-framework'),
        'attrs'       => array(
            'value'              => $optionValues['text_colophon'],
            'data-default-color' => $defaults['text_colophon'],
        ),
    )),

    /**
     * Colophon Background Color
     *
     * @since 1.0.0
     */
    'qibla_opt-colors-background_colophon:color_picker'      => $fieldFactory->table(array(
        'type'        => 'color_picker',
        'name'        => 'qibla_opt-colors-background_colophon',
        'label'       => esc_html_x('Background Colophon Color', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Select the color for the background.', 'settings', 'qibla-framework'),
        'attrs'       => array(
            'value'              => $optionValues['background_colophon'],
            'data-default-color' => $defaults['background_colophon'],
        ),
    )),
));
