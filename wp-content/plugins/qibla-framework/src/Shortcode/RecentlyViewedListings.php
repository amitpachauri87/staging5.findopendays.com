<?php
/**
 * Recently Viewed Listings
 *
 * @since      2.1.0
 * @package    QiblaFramework\Shortcode
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

use QiblaFramework\Plugin;

/**
 * Class RecentlyViewedListings
 *
 * @since  2.1.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class RecentlyViewedListings extends Listings implements ShortcodeVisualComposerInterface
{
    /**
     * Build Query Arguments List
     *
     * @since  2.1.0
     * @access protected
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

        // Set post type array.
        $postType               = explode(',', $args['post_type']);
        $queryArgs['post_type'] = (array)$postType;

        if (! empty($_COOKIE['qibla_listings_recently_viewed']) &&
            count(unserialize($_COOKIE['qibla_listings_recently_viewed'])) > 0 &&
            array_key_exists('qibla_listings_recently_viewed', $_COOKIE)) {
            $queryArgs['post__in'] = unserialize($_COOKIE['qibla_listings_recently_viewed']);
        } else {
            $queryArgs = array();
        }

        return $queryArgs;
    }

    /**
     * Construct
     *
     * @since 2.1.0
     */
    public function __construct()
    {
        $this->tag      = 'dl_recently_viewed';
        $this->defaults = array(
            'post_type'             => 'listings',
            'posts_per_page'        => 10,
            'featured'              => 'no',
            'show_title'            => 'yes',
            'show_subtitle'         => 'yes',
            'show_thumbnail'        => 'yes',
            'show_address'          => 'yes',
            'thumbnail_size'        => 'qibla-post-thumbnail-loop',
            'grid_class'            => 'col--md-6 col--lg-4',
            'orderby'               => 'post__in',
            'order'                 => 'DESC',
            'additional_query_args' => array(
                'post_status' => 'publish',
            ),
        );
    }

    /**
     * Parse Attributes Arguments
     *
     * @since  2.1.0
     * @access public
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

        $atts['listing_categories'] = '';
        $atts['locations']          = '';

        return $atts;
    }

    /**
     * Build Data
     *
     * @since  2.1.0
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
        // Retrieve the base data.
        $data = parent::buildData($atts);

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function visualComposerMap()
    {
        return include Plugin::getPluginDirPath('/inc/vcMapping/recentlyViewedListings.php');
    }
}
