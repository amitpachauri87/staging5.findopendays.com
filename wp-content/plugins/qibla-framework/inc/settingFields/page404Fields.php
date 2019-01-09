<?php
use QiblaFramework\Functions as F;
use QiblaFramework\Form\Factories\FieldFactory;

/**
 * Settings Page404 Fields
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
 * Filter 404 Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
return apply_filters('qibla_opt_inc_404_fields', array(
    /**
     * Background Image
     *
     * @since 1.0.0
     */
    'qibla_opt-page_404-background_image:media_image' => $fieldFactory->table(array(
        'type'        => 'media_image',
        'name'        => 'qibla_opt-page_404-background_image',
        'attrs'       => array(
            'value' => F\getThemeOption('page_404', 'background_image', true),
        ),
        'label'       => esc_html_x('Background Image', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Choice the background image.', 'settings', 'qibla-framework'),
    )),

    /**
     * Page Title
     *
     * @since 1.0.0
     */
    'qibla_opt-page_404-title:text'                   => $fieldFactory->table(array(
        'type'  => 'text',
        'name'  => 'qibla_opt-page_404-title',
        'label' => esc_html_x('Page Title', 'settings', 'qibla-framework'),
        'attrs' => array(
            'value' => F\getThemeOption('page_404', 'title', true),
        ),
    )),

    /**
     * Page Sub-title
     *
     * @since 1.0.0
     */
    'qibla_opt-page_404-subtitle:text'                => $fieldFactory->table(array(
        'type'  => 'text',
        'name'  => 'qibla_opt-page_404-subtitle',
        'label' => esc_html_x('Page Subtitle', 'settings', 'qibla-framework'),
        'attrs' => array(
            'value' => F\getThemeOption('page_404', 'subtitle', true),
        ),
    )),
));
