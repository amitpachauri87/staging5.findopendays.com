<?php
/**
 * Settings Listings Fields
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

use QiblaFramework\Functions as F;
use QiblaFramework\Form\Factories\FieldFactory;
use QiblaFramework\ListingsContext\Types;

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

// Get Types.
$types = new Types();

/**
 * Filter Listings Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
$fields = array(
    /**
     * Show Map
     *
     * @since 1.7.0
     */
    'qibla_opt-listings-archive_show_map:checkbox'   => $fieldFactory->table(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_opt-listings-archive_show_map',
        'value'       => F\getThemeOption('listings', 'archive_show_map', true),
        'label'       => esc_html_x('Show Map on Archive', 'settings', 'qibla-framework'),
        'description' => esc_html_x(
            'Active to show or not the map within the listings archive.',
            'settings',
            'qibla-framework'
        ),
    )),

    /**
     * Posts per Page
     *
     * @since 1.0.0
     */
    'qibla_opt-listings-posts_per_page:number'       => $fieldFactory->table(array(
        'type'        => 'number',
        'name'        => 'qibla_opt-listings-posts_per_page',
        'label'       => esc_html_x('Listings per page', 'settings', 'qibla-framework'),
        'description' => esc_html_x(
            'Enter how many listing items must be showed per page.',
            'settings',
            'qibla-framework'
        ),
        'attrs'       => array(
            'value' => F\getThemeOption('listings', 'posts_per_page', true),
            'min'   => -1,
        ),
    )),

    /**
     * Cta label
     *
     * @since 1.6.0
     */
    'qibla_opt-listings-related_post_cta_label:text' => $fieldFactory->table(array(
        'type'        => 'text',
        'name'        => 'qibla_opt-listings-related_post_cta_label',
        'label'       => esc_html__('Single Listing Cta Label', 'qibla-framework'),
        'description' => esc_html__(
            'Type the custom Call to Action label text. Leave empty to not show the cta section.',
            'qibla-framework'
        ),
        'attrs'       => array(
            'value' => F\getThemeOption('listings', 'related_post_cta_label', true),
            'class' => 'widefat',
        ),
        'display'     => array($this, 'displayField'),
    )),

    /**
     * Order By Featured
     *
     * @since 1.0.0
     */
    'qibla_opt-listings-order_by_featured:checkbox'  => $fieldFactory->table(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_opt-listings-order_by_featured',
        'value'       => F\getThemeOption('listings', 'order_by_featured', true),
        'label'       => esc_html_x('Featured Listings First', 'settings', 'qibla-framework'),
        'description' => esc_html_x(
            'Check if you want to show the featured listings first in archive.',
            'settings',
            'qibla-framework'
        ),
    )),

    /**
     * Disable Reviews
     *
     * @since 1.0.0
     */
    'qibla_opt-listings-disable_reviews:checkbox'    => $fieldFactory->table(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_opt-listings-disable_reviews',
        'label'       => esc_html_x('Force Disable Reviews', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Force Disable reviews within single listings.', 'settings', 'qibla-framework'),
        'value'       => F\getThemeOption('listings', 'disable_reviews', true),
    )),

    /**
     * Sticky Sidebar
     *
     * @since 1.0.0
     */
    /*'qibla_opt-listings-sidebar_sticky:checkbox'       => $fieldFactory->table([
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_opt-listings-sidebar_sticky',
        'label'       => esc_html__('Sticky Sidebar', 'qibla-framework'),
        'description' => esc_html__('If you want to set the sidebar as sticky on scroll.', 'qibla-framework'),
        'value'       => F\getThemeOption('listings', 'sidebar_sticky', true),
    ]),*/

    /**
     * Sidebar Position
     *
     * @since 1.0.0
     */
    'qibla_opt-listings-sidebar_position:radio'      => $fieldFactory->table(array(
        'type'        => 'radio',
        'name'        => 'qibla_opt-listings-sidebar_position',
        'options'     => array(
            'left'  => esc_html__('Left', 'qibla-framework'),
            'right' => esc_html__('Right', 'qibla-framework'),
        ),
        'label'       => esc_html_x('Sidebar Position', 'settings', 'qibla-framework'),
        'description' => esc_html_x(
            'Select the default position of the sidebar for all single listings. Can be overwrite in edit listing.',
            'settings',
            'qibla-framework'
        ),
        'value'       => F\getThemeOption('listings', 'sidebar_position', true),
    )),

//    /**
//     * Archive Description
//     *
//     * @since 1.0.0
//     */
//    'qibla_opt-listings-archive_description:wysiwyg' => $fieldFactory->table(array(
//        'type'            => 'wysiwyg',
//        'name'            => 'qibla_opt-listings-archive_description',
//        'label'           => esc_html_x('Archive Description', 'settings', 'qibla-framework'),
//        'description'     => esc_html_x(
//            'Type the listings archive description. Leave blank to not show.',
//            'settings',
//            'qibla-framework'
//        ),
//        'value'           => F\getThemeOption('listings', 'archive_description', true),
//        'editor_settings' => array(
//            'tinymce'       => true,
//            'teeny'         => true,
//            'media_buttons' => false,
//            'quicktags'     => false,
//            'textarea_rows' => 8,
//            'paste_as_text' => true,
//        ),
//    )),
);

/**
 * Archive Listings Description
 *
 * @since 2.0.0
 */
foreach ($types->types() as $type) {
    $fields["qibla_opt-listings-{$type}_archive_description:wysiwyg"] = $fieldFactory->table(array(
        'type'            => 'wysiwyg',
        'name'            => "qibla_opt-listings-{$type}_archive_description",
        'label'           => sprintf(esc_html_x(
            'Archive %s Description',
            'settings',
            'qibla-framework'),
            $type),
        'description'     => sprintf(esc_html_x(
            'Type the listings %s archive description. Leave blank to not show.',
            'settings',
            'qibla-framework'
        ), $type),
        'value'           => F\getThemeOption('listings', $type . '_archive_description', true),
        'editor_settings' => array(
            'tinymce'       => true,
            'teeny'         => true,
            'media_buttons' => false,
            'quicktags'     => false,
            'textarea_rows' => 8,
            'paste_as_text' => true,
        ),
    ));
}

// Reviewer plugin.
if (\QiblaFramework\Reviewer\ReviewerData::checkDependencies()) {
    foreach ($types->types() as $type) {
        $fields["qibla_opt-listings-{$type}_reviewer_template_id:text"] = $fieldFactory->table(array(
            'type'        => 'text',
            'name'        => "qibla_opt-listings-{$type}_reviewer_template_id",
            'label'       => sprintf(esc_html_x('Reviewer template ID', 'settings', 'bbtrip')
            ),
            'description' => sprintf(esc_html_x('Type reviewer template ID for %s post type', 'settings', 'bbtrip'
            ), $type),
            'attrs'       => array(
                'value' => F\getThemeOption('listings', $type . '_reviewer_template_id', true),
            ),
            'display'     => array($this, 'displayField'),
        ));
    }
}

return apply_filters('qibla_opt_inc_listings_fields', $fields);
