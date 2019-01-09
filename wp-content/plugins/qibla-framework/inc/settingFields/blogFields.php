<?php
use QiblaFramework\Functions as F;
use QiblaFramework\Form\Factories\FieldFactory;

/**
 * Settings Blog Fields
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
 * Filter Blog Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
return apply_filters('qibla_opt_inc_blog_fields', array(
    /**
     * Posts per Page
     *
     * @since 1.0.0
     */
    // @todo Need info by Envato.
//    'qibla_opt-blog-posts_per_page:number'   => $fieldFactory->table([
//        'type'        => 'number',
//        'name'        => 'qibla_opt-blog-posts_per_page',
//        'label'       => esc_html__('Posts per page', 'qibla-framework'),
//        'description' => esc_html__('Enter how many posts must be showed per page.', 'qibla-framework'),
//        'attrs'       => [
//            'value' => F\getThemeOption('blog', 'posts_per_page', true),
//        ],
//    ]),

    /**
     * Limit Excerpt
     *
     * @since 1.0.0
     */
    'qibla_opt-blog-limit_excerpt:number'   => $fieldFactory->table(array(
        'type'        => 'number',
        'name'        => 'qibla_opt-blog-limit_excerpt',
        'label'       => esc_html_x('Limit Excerpt', 'settings', 'qibla-framework'),
        'description' => esc_html_x('How many words must show for excerpt article.', 'settings', 'qibla-framework'),
        'attrs'       => array(
            'value' => F\getThemeOption('blog', 'limit_excerpt', true),
        ),
    )),

    /**
     * Sidebar Position
     *
     * @since 1.0.0
     */
    'qibla_opt-blog-sidebar_position:radio' => $fieldFactory->table(array(
        'type'        => 'radio',
        'name'        => 'qibla_opt-blog-sidebar_position',
        'options'     => array(
            'none'  => esc_html__('No Sidebar', 'qibla-framework'),
            'left'  => esc_html__('Left', 'qibla-framework'),
            'right' => esc_html__('Right', 'qibla-framework'),
        ),
        'label'       => esc_html_x('Sidebar Position', 'settings', 'qibla-framework'),
        'description' => esc_html_x(
            'Select the position of the sidebar for (home) posts page.',
            'settings',
            'qibla-framework'
        ),
        'value'       => F\getThemeOption('blog', 'sidebar_position', true),
    )),

    /**
     * Sticky Sidebar
     *
     * @since 1.0.0
     */
//    'qibla_opt-blog-sidebar_sticky:checkbox' => $fieldFactory->table([
//        'type'        => 'checkbox',
//        'style'       => 'toggler',
//        'name'        => 'qibla_opt-blog-sidebar_sticky',
//        'label'       => esc_html__('Sticky Sidebar', 'qibla-framework'),
//        'description' => esc_html__('If you want to set the sidebar as sticky on scroll.', 'qibla-framework'),
//        'value'       => F\getThemeOption('blog', 'sidebar_sticky', true),
//    ]),
));
