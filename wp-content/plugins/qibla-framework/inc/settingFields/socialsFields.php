<?php
use QiblaFramework\Functions as F;
use QiblaFramework\Form\Factories\FieldFactory;

/**
 * Settings Socials Fields
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
 * Filter Socials Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
return apply_filters('qibla_opt_inc_socials_fields', array(
    /**
     * Facebook
     *
     * @since 1.0.0
     */
    'qibla_opt-socials-facebook:url'  => $fieldFactory->table(array(
        'type'        => 'url',
        'name'        => 'qibla_opt-socials-facebook',
        'attrs'       => array(
            'value' => F\getThemeOption('socials', 'facebook'),
        ),
        'label'       => esc_html_x('Facebook Page', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Don\'t forget the http[s]:// string.', 'settings', 'qibla-framework'),
    )),

    /**
     * Twitter
     *
     * @since 1.0.0
     */
    'qibla_opt-socials-twitter:url'   => $fieldFactory->table(array(
        'type'        => 'url',
        'name'        => 'qibla_opt-socials-twitter',
        'attrs'       => array(
            'value' => F\getThemeOption('socials', 'twitter'),
        ),
        'label'       => esc_html_x('Twitter Account', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Don\'t forget the http[s]:// string.', 'settings', 'qibla-framework'),
    )),
    /**
     * Instagram
     *
     * @since 1.0.0
     */
    'qibla_opt-socials-instagram:url' => $fieldFactory->table(array(
        'type'        => 'url',
        'name'        => 'qibla_opt-socials-instagram',
        'attrs'       => array(
            'value' => F\getThemeOption('socials', 'instagram'),
        ),
        'label'       => esc_html_x('Instagram Account', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Don\'t forget the http[s]:// string.', 'settings', 'qibla-framework'),
    )),

    /**
     * Pinterest
     *
     * @since 1.0.0
     */
    'qibla_opt-socials-pinterest:url' => $fieldFactory->table(array(
        'type'        => 'url',
        'name'        => 'qibla_opt-socials-pinterest',
        'attrs'       => array(
            'value' => F\getThemeOption('socials', 'pinterest'),
        ),
        'label'       => esc_html_x('Pinterest page', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Don\'t forget the http[s]:// string.', 'settings', 'qibla-framework'),
    )),

    /**
     * Linked-in
     *
     * @since 1.0.0
     */
    'qibla_opt-socials-linkedin:url'  => $fieldFactory->table(array(
        'type'        => 'url',
        'name'        => 'qibla_opt-socials-linkedin',
        'attrs'       => array(
            'value' => F\getThemeOption('socials', 'linkedin'),
        ),
        'label'       => esc_html_x('Linkedin Account', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Don\'t forget the http[s]:// string.', 'settings', 'qibla-framework'),
    )),

    /**
     * Youtube
     *
     * @since 1.0.0
     */
    'qibla_opt-socials-youtube:url'   => $fieldFactory->table(array(
        'type'        => 'url',
        'name'        => 'qibla_opt-socials-youtube',
        'attrs'       => array(
            'value' => F\getThemeOption('socials', 'youtube'),
        ),
        'label'       => esc_html_x('Youtube Account', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Don\'t forget the http[s]:// string.', 'settings', 'qibla-framework'),
    )),

    /**
     * Vimeo
     *
     * @since 1.0.0
     */
    'qibla_opt-socials-vimeo:url'     => $fieldFactory->table(array(
        'type'        => 'url',
        'name'        => 'qibla_opt-socials-vimeo',
        'attrs'       => array(
            'value' => F\getThemeOption('socials', 'vimeo'),
        ),
        'label'       => esc_html_x('Vimeo Account', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Don\'t forget the http[s]:// string.', 'settings', 'qibla-framework'),
    )),
));
