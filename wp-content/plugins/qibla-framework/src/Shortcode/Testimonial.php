<?php
/**
 * Short-code Testimonial
 *
 * @since      1.0.0
 * @package    QiblaFramework\Shortcode
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

namespace QiblaFramework\Shortcode;

use QiblaFramework\Debug;
use QiblaFramework\Functions as F;
use QiblaFramework\Plugin;

/**
 * Class Testimonial
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class Testimonial extends AbstractShortcode implements ShortcodeVisualComposerInterface
{
    /**
     * Build Query Arguments List
     *
     * @since  1.0.0
     *
     * @param array $args The base arguments for the query.
     *
     * @return array The arguments to use for the query
     */
    protected function buildQueryArgsList(array $args)
    {
        // Retrieve the default arguments for the query.
        return wp_parse_args($args['additional_query_args'], array(
            'post_type' => 'testimonial',
        ));
    }

    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        $this->tag      = 'dl_testimonial';
        $this->defaults = array(
            'title'                 => '',
            'background-image'      => '',
            'background-color'      => 'transparent',
            'slider'                => 'no',
            'rating'                => 'yes',
            'avatar'                => 'yes',
            'additional_query_args' => array(
                'posts_per_page' => get_option('posts_per_page'),
            ),
        );
    }

    /**
     * Build Model
     *
     * @since  1.0.0
     *
     * @throws \Exception In case the posts cannot be retrieved.
     *
     * @param array  $atts    The short-code attributes.
     * @param string $content The content within the short-code.
     *
     * @return \stdClass The data instance or null otherwise.
     */
    public function buildData(array $atts, $content = '')
    {
        // Initialize Data.
        $data = new \stdClass();

        // Build the query.
        $query = new \WP_Query($this->buildQueryArgsList($atts));

        if (! $query->have_posts()) {
            throw new \Exception('Posts cannot be retrieved');
        }

        // Initialize the data properties.
        $data->posts       = array();
        $data->useSlider   = false;
        $data->sliderClass = array();

        // Use Carousel?
        if ('yes' === $atts['slider']) {
            $data->useSlider   = true;
            $data->sliderClass = array('owl-carousel', 'owl-theme');
        }

        foreach ($query->posts as $post) :
            setup_postdata($post);

            // Get the attachment src.
            $attach = wp_get_attachment_image_src(get_post_thumbnail_id($post), 'qibla-testimonial-loop');
            if ($attach) {
                $attach = array_combine(array(
                    'src',
                    'width',
                    'height',
                    'is_intermediate',
                ), $attach);
            }

            // Some properties are optional.
            $data->posts[sanitize_key($post->post_name)] = (object)array(
                'id'        => $post->ID,
                'title'     => $post->post_title,
                'content'   => apply_filters('the_content', $post->post_content),
                'rating'    => 'yes' === $atts['rating'] ?
                    F\getPostMeta('_qibla_mb_testimonial_rating', 0, $post->ID) :
                    '',
                'thumbnail' => ('yes' === $atts['avatar'] && $attach) ? array_merge($attach, array(
                    'alt' => F\getPostMeta('_wp_attachment_image_alt', '', $post->ID),
                )) : array(),
            );
        endforeach;
        wp_reset_postdata();

        return $data;
    }

    /**
     * Callback
     *
     * @since  1.0.0
     *
     * @param array  $atts    The short-code's attributes.
     * @param string $content The content within the short-code.
     *
     * @return string The short-code markup
     */
    public function callback($atts = array(), $content = '')
    {
        // Parse the arguments.
        $atts = $this->parseAttrsArgs($atts);
        if (! $atts) {
            return '';
        }

        try {
            // Build the data for the view.
            $data     = $this->buildData($atts);
            $template = $this->loadTemplate('dl_sc_testimonial', $data, '/views/shortcodes/testimonial.php');

            if ($atts['title']) {
                $sectionInstance = new Section();
                // Wrap with the Section Shortcode if have title.
                $template = $sectionInstance->callback(array(
                    'title'            => $atts['title'],
                    'background-color' => $atts['background-color'],
                    'background-image' => $atts['background-image'],
                ), $template);
            }
        } catch (\Exception $e) {
            $debugInstance = new Debug\Exception($e);
            'dev' === QB_ENV && $debugInstance->display();

            $template = '';
        }

        if ($template && 'yes' === $atts['slider']) {
            if (wp_style_is('owlcarousel2-theme', 'registered') && wp_script_is('dlowlcarousel', 'registered')) {
                wp_enqueue_style('owlcarousel2-theme');
                wp_enqueue_script('dlowlcarousel');
            }
        }

        return $template;
    }

    /**
     * @inheritDoc
     */
    public function visualComposerMap()
    {
        return include Plugin::getPluginDirPath('/inc/vcMapping/testimonial.php');
    }
}
