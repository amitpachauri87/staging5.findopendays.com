<?php
namespace QiblaFramework\Widget;

use QiblaFramework\Functions as F;

/**
 * Contact Form 7
 *
 * @since      1.0.0
 * @package    QiblaFramework\Widget
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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

/**
 * Class ContactForm7
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class ContactForm7 extends AbstractWidget
{
    /**
     * Post Type
     *
     * @since  1.0.0
     *
     * @var string The post type
     */
    protected $postType;

    /**
     * Get the contact Form
     *
     * @since  1.0.0
     *
     * @param string $postName The name of the post to retrieve
     *
     * @return null|\WP_Post
     */
    protected function getContactForm($postName)
    {
        $query = new \WP_Query(array(
            'post_type'      => $this->postType,
            'post_name__in'  => array(sanitize_title($postName)),
            'nopaging'       => true,
            'posts_per_page' => 1,
        ));

        if (! $query->have_posts()) {
            return null;
        }

        return $query->posts[0];
    }

    /**
     * Construct
     *
     * @since 1.0.0
     *
     * {@inheritDoc}
     */
    public function __construct()
    {
        $this->postType   = 'wpcf7_contact_form';
        $this->fieldsPath = '/inc/widgetFields/cf7Fields.php';

        parent::__construct(
            'dlcontact_form_7',
            esc_html__('Dl Contact Form 7', 'qibla-framework')
        );
    }

    /**
     * The Widget
     *
     * @since  1.0.0
     *
     * {@inheritDoc}
     */
    public function widget($args, $widget)
    {
        // Get the contact form post.
        $cf7 = $this->getContactForm($widget['dlwidget_cf7_options']);
        // Retrieve the sanitized title with markup.
        $title = sanitize_text_field($widget['dlwidget_cf7_title']);

        if (! $cf7 instanceof \WP_Post) {
            return;
        }

        // Retrieve the widget title.
        $output = $args['before_title'] . $title . $args['after_title'];
        // Retrieve the form markup.
        $output .= do_shortcode(
            '[contact-form-7 id="' . $cf7->ID . '" title="' . $title . '"]'
        );

        // Strip annoying br tags that are between the labels and the input.
        $output = str_replace(array('<br>', '<br />', '<br/>'), '', $output);
        echo $args['before_widget'] . esc_html(sanitize_text_field($output)) . $args['after_widget'];

        if (wp_script_is('dlcf7', 'registered')) {
            wp_enqueue_script('dlcf7');
        }

        F\svgLoaderTmpl();
    }
}
