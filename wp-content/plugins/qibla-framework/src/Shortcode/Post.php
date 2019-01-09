<?php
/**
 * Short-code Post
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
use QiblaFramework\Exceptions\InvalidPostException;
use QiblaFramework\Functions as F;
use QiblaFramework\ListingsContext\Types;
use QiblaFramework\Plugin;
use QiblaFramework\Template\Thumbnail;

/**
 * Class Post
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class Post extends AbstractShortcode implements ShortcodeVisualComposerInterface
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
        $queryArgs = array_intersect_key($args, array(
            'post_type'      => '',
            'posts_per_page' => '',
            'orderby'        => '',
            'order'          => '',
        ));

        // Order by may be a list of comma separated values.
        // In this case make it as an array.
        $args['additional_query_args'] = false !== strpos($args['orderby'], ',') ?
            explode(',', $args['additional_query_args']) :
            $args['additional_query_args'];

        return wp_parse_args($args['additional_query_args'], $queryArgs);
    }

    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        $this->tag = 'dl_posts';
        // Initialize the default arguments.
        // The additional_query_args is not visible within the UI, it is allowed via code only.
        $this->defaults = array(
            'post_type'                => 'post',
            'posts_per_page'           => 10,
            'pubdate_format'           => get_option('date_format'),
            'show_title'               => 'yes',
            'show_thumbnail'           => 'yes',
            'show_meta'                => 'yes',
            'show_excerpt'             => 'yes',
            'thumbnail_size'           => 'qibla-post-thumbnail-loop',
            // The default grid class follow the layout mixin defined within the theme.
            'grid_class'               => 'col--md-6 col--lg-4',
            'orderby'                  => 'date',
            'order'                    => 'DESC',
            // Layout.
            'layout'                   => 'container-width',
            'section-background-color' => 'transparent',
            'section-padding-top'      => 'inherit',
            'section-padding-bottom'   => 'inherit',
            'additional_query_args'    => array(
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true,
            ),
        );
    }

    /**
     * Parse Attributes Arguments
     *
     * @since  1.0.0
     *
     * @param array $atts The short-code's attributes
     *
     * @return array The parsed arguments
     */
    public function parseAttrsArgs($atts = array())
    {
        $atts = parent::parseAttrsArgs($atts);

        // The grid classes are in string format when the atts are passed to the callback,
        // we want to work with array for to reasons:
        // 1 - We need to sanitize html classes, sanitize_html_class works only with one class at time.
        // 2 - Keep classes list coherent with the rest of the framework.
        $atts['grid_class'] = explode(' ', 'col ' . $atts['grid_class']);

        return $atts;
    }

    /**
     * Build Data
     *
     * @since  1.0.0
     *
     * @throws InvalidPostException In case the posts cannot be retrieved.
     *
     * @param array  $atts    The short-code attributes.
     * @param string $content The content within the short-code.
     *
     * @return \stdClass The data instance or null otherwise.
     */
    public function buildData(array $atts, $content = '')
    {
        // Build the Query Arguments List.
        // Since we allow to pass additional query args, we need to parse those arguments.
        $queryArgs = $this->buildQueryArgsList($atts);
        // Make the query.
        $query = new \WP_Query($queryArgs);
        // Retrieve the posts based on the query.
        $posts = $query->posts;
        // Types.
        $types = new Types();
        // Initialize Data.
        $data = new \stdClass();

        if (! $posts) {
            throw new InvalidPostException();
        }

        // Set the post type.
        $data->postType = $atts['post_type'];

        // Thumbnail size.
        $thumbSize = $atts['thumbnail_size'];

        /**
         * Filters Thumbnail Size.
         *
         * The filter is used to change the size of the featured image, based on the shortcode tag.
         *
         * @param $thumbSize string The thumbnail size.
         * @param $this      ->tag string The shortcode tags to check which shortcode to apply the filter
         *
         * @since 2.0.0
         */
        $size = apply_filters('qibla_posts_shortcode_thumbnail_size', $thumbSize, $this->tag);

        // Build the posts data, this will include an array of posts where every post has an array
        // containing all of the properties defined by the user by the use of short-code attributes array.
        $postsData = array();

        // Get layout.
        $data->layout    = $atts['layout'];
        $data->layoutSbg = isset($atts['section-background-color']) ?
            sanitize_hex_color($atts['section-background-color']) :
            'transparent';
        $data->layoutSpt = isset($atts['section-padding-top']) && '' !== $atts['section-padding-top'] ?
            $atts['section-padding-top'] : 'inherit';
        $data->layoutSpb = isset($atts['section-padding-bottom']) && '' !== $atts['section-padding-bottom'] ?
            $atts['section-padding-bottom'] : 'inherit';
        // Set style.
        $data->sectionStyle = sprintf(
            '%s;%s;%s;',
            "background-color:{$data->layoutSbg}",
            "padding-top:{$data->layoutSpt}",
            "padding-bottom:{$data->layoutSpb}"
        );

        global $post;
        foreach ($posts as $post) :
            setup_postdata($post);

            if (! $post) {
                continue;
            }

            $postArgs = new \stdClass();

            // Set the post data arguments.
            $postArgs->ID        = intval($post->ID);
            $postArgs->permalink = get_permalink($postArgs->ID);

            // We check if isset because inherited short-codes may not add this att.
            if ((isset($atts['show_title']) && 'yes' === $atts['show_title'])) {
                $postArgs->postTitle = $post->post_title;
                $postArgs->subtitle  = F\getPostMeta('_qibla_mb_sub_title', null, $postArgs->ID);
            } else {
                $postArgs->postTitle = '';
                $postArgs->subtitle  = '';
            }

            // Build Thumbnail Template.
            if (isset($atts['show_thumbnail']) && 'yes' === $atts['show_thumbnail']) {
                $postArgs->thumbnail = new Thumbnail($post, array(
                    'size' => $size,
                ));
            } else {
                $postArgs->thumbnail = '';
            }

            // Add the post classes list.
            $postArgs->postClass = array(
                'article',
                '',
                array(
                    'card',
                    'zoom',
                    get_post_type($postArgs->ID),
                    (! $postArgs->thumbnail ? 'text-only' : 'overlay'),
                ),
            );

            $postArgs->gridClass = $atts['grid_class'];

            // Initialize the meta for the current post.
            // Include:
            // - Published Date
            // - Assigned Terms.
            $postArgs->meta = array();
            // We check if isset because inherited short-codes may not add this att.
            if (isset($atts['show_meta']) && 'yes' === $atts['show_meta']) {
                // The post published date.
                $postArgs->meta['pubdate'] = true;
                // Taxonomy and Terms
                // Retrieve the taxonomy for the current post. We use the first taxonomy found.
                $taxonomy                = get_object_taxonomies($queryArgs['post_type']);
                $postArgs->meta['terms'] = array(
                    'taxonomy' => $taxonomy[0],
                );
            }

            // The post content/excerpt.
            $postArgs->showExcerpt = (isset($atts['show_excerpt']) && 'yes' === $atts['show_excerpt']);
            $postArgs->excerptData = new \stdClass();
            $postArgs->excerptData = F\getExcerptData();

            // Set the icon. Temporary there is no icon to set for posts, it is used by other post types
            // We set this here because of the logic consider that all post types can have icon.
            $postArgs->icon = null;

            // Is post type listings?
            $postArgs->isListings = $types->isListingsType($post->post_type);

            // Store the current post within the postsData.
            $postsData[sanitize_title($post->post_name)] = $postArgs;
        endforeach;

        // Reset post data.
        wp_reset_postdata();

        // Unset the used post within foreach.
        unset($post);

        // Fill the posts data.
        $data->posts = $postsData;

        return $data;
    }

    /**
     * Callback
     *
     * @since  1.0.0
     * x     *
     *
     * @param array  $atts    The short-code's attributes.
     * @param string $content The content within the short-code.
     *
     * @return string The short-code markup
     */
    public function callback($atts = array(), $content = '')
    {
        $atts = $this->parseAttrsArgs($atts);

        try {
            // Build the data object needed by this short-code.
            $data = $this->buildData($atts);

            return $this->loadTemplate('dl_sc_posts', $data, '/views/shortcodes/posts.php');
        } catch (\Exception $e) {
            $debugInstance = new Debug\Exception($e);
            'dev' === QB_ENV && $debugInstance->display();

            return '';
        }
    }

    /**
     * @inheritDoc
     */
    public function visualComposerMap()
    {
        return include Plugin::getPluginDirPath('/inc/vcMapping/post.php');
    }
}
