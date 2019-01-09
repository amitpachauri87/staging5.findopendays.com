<?php
/**
 * Listings
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
use QiblaFramework\Listings\ListingsPost;
use QiblaFramework\ListingsContext\Types;
use QiblaFramework\Plugin;
use QiblaFramework\Listings\ListingLocation;
use QiblaFramework\Template\Thumbnail;

/**
 * Class Listings
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class Listings extends AbstractShortcode implements ShortcodeVisualComposerInterface
{
    /**
     * Build Query Arguments List
     *
     * @since  2.1.0
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

        // Initialize tax query.
        $queryArgs['tax_query'] = array();
        // Add Tax Query arguments if taxonomy is listing_categories and d'not empty terms.
        if (! empty($args['listing_categories']) || ! empty($args['locations'])) :
            // Set Terms.
            $listingCategories = '' !== $args['listing_categories'] ? explode(',', $args['listing_categories']) : null;
            $locations         = '' !== $args['locations'] ? explode(',', $args['locations']) : null;

            // Listing categories tax query.
            $taxCategories = array(
                'taxonomy' => 'listing_categories',
                'field'    => 'slug',
                'terms'    => $listingCategories,
            );

            // Listing locations tax query.
            $taxLocations = array(
                'taxonomy' => 'locations',
                'field'    => 'slug',
                'terms'    => $locations,
            );

            if ($listingCategories) {
                $queryArgs['tax_query'][] = $taxCategories;
            }

            if ($locations) {
                // Relation.
                $queryArgs['tax_query']['relation'] = 'AND';
                $queryArgs['tax_query'][]           = $taxLocations;
            }
        endif;

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
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->tag      = 'dl_listings';
        $this->defaults = array(
            'post_type'                => 'listings',
            'posts_per_page'           => 10,
            'listing_categories'       => '',
            'locations'                => '',
            'featured'                 => 'no',
            'show_title'               => 'yes',
            'show_subtitle'            => 'yes',
            'show_thumbnail'           => 'yes',
            'show_address'             => 'yes',
            'thumbnail_size'           => 'qibla-post-thumbnail-loop',
            'grid_class'               => 'col--md-6 col--lg-4',
            'orderby'                  => 'date',
            'order'                    => 'DESC',
            // Layout.
            'layout'                   => 'container-width',
            'section-background-color' => 'transparent',
            'section-padding-top'      => 'inherit',
            'section-padding-bottom'   => 'inherit',
            'additional_query_args'    => array(
                'post_status' => 'publish',
            ),
        );
    }

    /**
     * Build Data
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
         * @since 1.0.0
         */
        $size = apply_filters('qibla_posts_shortcode_thumbnail_size', $thumbSize, $this->tag);

        // Build the posts data, this will include an array of posts where every post has an array
        // containing all of the properties defined by the user by the use of short-code attributes array.
        $postsData = array();

        // Force Article as Link.
        // Used to force the article element to be an anchor despite the content within it.
        $data->forceAnchor = true;

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
            $postArgs->postTitle = (isset($atts['show_title']) && 'yes' === $atts['show_title']) ? $post->post_title : '';

            // Get the subtitle.
            $postArgs->subtitle = (isset($atts['show_subtitle']) && 'yes' === $atts['show_subtitle']) ?
                F\getPostMeta('_qibla_mb_sub_title', null, $postArgs->ID) :
                '';

            // Build Thumbnail Template.
            $postArgs->thumbnail = (isset($atts['show_thumbnail']) && 'yes' === $atts['show_thumbnail']) ?
                new Thumbnail($post, array('size' => $size)) :
                '';

            // Get the Icon associated to the listing.
            $_post          = new ListingsPost(get_post($postArgs->ID));
            $postArgs->icon = $_post->icon();

            // Button.
            $button = F\getPostMeta('_qibla_mb_button_url');
            $class = $button ? 'has-button' : '';

            // Retrieve the meta.
            $postArgs->buttonMeta = array(
                'url'       => F\getPostMeta('_qibla_mb_button_url'),
                'text'      => F\getPostMeta('_qibla_mb_button_text'),
                'target'    => F\getPostMeta('_qibla_mb_target_link'),
                'btn_class' => 'dlbtn dlbtn--tiny',
            );

            // Add the post classes list.
            $postArgs->postClass = array(
                'article',
                '',
                array(
                    'card',
                    'zoom',
                    $class,
                    get_post_type($postArgs->ID),
                    (! $postArgs->thumbnail ? 'text-only' : 'overlay'),
                ),
            );

            $postArgs->gridClass = $atts['grid_class'];

            // Is post type listings?
            $postArgs->isListings = $types->isListingsType($post->post_type);

            // Retrieve the address by map location meta.
            // Remember that it is in the format lat,lng:address.
            if (isset($atts['show_address']) && 'yes' === $atts['show_address']) {
                $location = new ListingLocation(get_post($post));
                // This overwrite the parent data.
                $postArgs->meta['address'] = $location->address();
            }

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
     * Parse Attributes Arguments
     *
     * @since  1.0.0
     *
     * @link   https://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters
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

        // No excerpt for this shortcode.
        $atts['show_excerpt'] = 'no';

        // Force post type.
        $atts['post_type'] = 'listings';

        // Featured Listings?
        if ('yes' === $atts['featured']) {
            $metaQuery = array(
                'key'     => '_qibla_mb_is_featured',
                'value'   => 'on',
                'compare' => '=',
            );

            // Be sure the current query args has meta_query defined.
            if (! isset($atts['additional_query_args']['meta_query'])) {
                $atts['additional_query_args']['meta_query'] = array();
            }

            // Push the new meta query arguments.
            // Remember that if not provided the 'relation' is set to 'AND' by default.
            array_push($atts['additional_query_args']['meta_query'], $metaQuery);
        }

        return $atts;
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
        $atts = $this->parseAttrsArgs($atts);

        try {
            // Build the data object needed by this short-code.
            $data = $this->buildData($atts);

            return $this->loadTemplate('dl_sc_listings', $data, '/views/shortcodes/listings.php');
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
        return include Plugin::getPluginDirPath('/inc/vcMapping/listings.php');
    }
}
