<?php
use QiblaFramework\Functions as F;
use QiblaFramework\Form\Factories\FieldFactory;

/**
 * Settings Logo Fields
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
 * Filter Logo Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
return apply_filters('qibla_opt_inc_logo_fields', array(
    /**
     * Custom Logo
     *
     * @since 1.0.0
     */
    'qibla_opt-logo-logo:media_image'        => $fieldFactory->table(array(
        'type'        => 'media_image',
        'name'        => 'qibla_opt-logo-logo',
        'label'       => esc_html_x('Site Logo', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Upload your custom logo', 'settings', 'qibla-framework'),
        'attrs'       => array(
            'value' => F\getThemeOption('logo', 'logo'),
        ),
    )),

    /**
     * Custom Logo Dark
     *
     * @since 1.0.0
     */
    'qibla_opt-logo-dark:media_image'        => $fieldFactory->table(array(
        'type'        => 'media_image',
        'name'        => 'qibla_opt-logo-dark',
        'label'       => esc_html_x('Site Logo Dark', 'settings', 'qibla-framework'),
        'description' => esc_html_x(
            'Upload your custom logo to use on dark background.',
            'settings',
            'qibla-framework'
        ),
        'attrs'       => array(
            'value' => F\getThemeOption('logo', 'dark'),
        ),
    )),

    /**
     * Custom Logo Retina
     *
     * @since 1.0.0
     */
    'qibla_opt-logo-logo_retina:media_image' => $fieldFactory->table(array(
        'type'        => 'media_image',
        'name'        => 'qibla_opt-logo-logo_retina',
        'label'       => esc_html_x('Site Logo Retina', 'settings', 'qibla-framework'),
        'description' => esc_html_x(
            'Upload your custom logo 2x for retina and high def display.',
            'settings',
            'qibla-framework'
        ),
        'attrs'       => array(
            'value' => F\getThemeOption('logo', 'logo_retina'),
        ),
    )),

    /**
     * Custom Logo Dark
     *
     * @since 1.0.0
     */
    'qibla_opt-logo-dark_retina:media_image' => $fieldFactory->table(array(
        'type'        => 'media_image',
        'name'        => 'qibla_opt-logo-dark_retina',
        'label'       => esc_html_x('Site Logo Dark Retina', 'settings', 'qibla-framework'),
        'description' => esc_html_x(
            'Upload your custom logo 2x for retina and high def display.',
            'settings',
            'qibla-framework'
        ),
        'attrs'       => array(
            'value' => F\getThemeOption('logo', 'dark_retina'),
        ),
    )),

    /**
     * Site Icon ( favicon )
     *
     * @since 1.0.0
     */
    'qibla_opt-logo-icon:media_image'        => $fieldFactory->table(array(
        'type'        => 'media_image',
        'name'        => 'qibla_opt-logo-icon',
        'label'       => esc_html_x('Site Icon', 'settings', 'qibla-framework'),
        'description' => esc_html_x(
            'The Site Icon is used as a browser and app icon for your site. Icons must be square, and at least 512 pixels wide and tall.',
            'settings',
            'qibla-framework'
        ),
        'attrs'       => array(
            'value' => get_option('site_icon'),
        ),
    )),
));
