<?php

use QiblaFramework\Functions as F;
use QiblaFramework\Form\Factories\FieldFactory;

/**
 * Settings Footer Fields
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
 * Filter Footer Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
return apply_filters('qibla_opt_inc_footer_fields', array(
    /**
     * Google Analytics
     *
     * @since 1.0.0
     */
    'qibla_opt-footer-google_analytics:text' => $fieldFactory->table(array(
        'type'        => 'text',
        'name'        => 'qibla_opt-footer-google_analytics',
        'label'       => esc_html_x('Google Analytics UA', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Insert only the UA code.', 'settings', 'qibla-framework') . '<br>' .
                         esc_html_x('For example: UA-123456789-1', 'settings', 'qibla-framework'),
        'attrs'       => array(
            'value' => F\getThemeOption('footer', 'google_analytics'),
        ),
    )),

    /**
     * Footer Social Links
     *
     * @since 1.0.0
     */
    'qibla_opt-footer-social_links:checkbox' => $fieldFactory->table(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_opt-footer-social_links',
        'label'       => esc_html_x('Show Social Links on footer', 'settings', 'qibla-framework'),
        'description' => esc_html_x(
            'Check it if you want to show the social links within the footer.',
            'settings',
            'qibla-framework'
        ),
        'value'       => F\getThemeOption('footer', 'social_links', true),
    )),

    /**
     * Footer Copyright
     *
     * @since 1.0.0
     */
    'qibla_opt-footer-copyright:text'        => $fieldFactory->table(array(
        'type'        => 'text',
        'name'        => 'qibla_opt-footer-copyright',
        'label'       => esc_html_x('Copyright Text', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Add your copyright text.', 'settings', 'qibla-framework'),
        'allow_html'  => true,
        'filter'      => FILTER_DEFAULT,
        'attrs'       => array(
            'value' => F\getThemeOption('footer', 'copyright', true),
        ),
    )),
));
