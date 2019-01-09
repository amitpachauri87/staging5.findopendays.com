<?php
/**
 * Maps
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

use QiblaFramework\Debug;
use QiblaFramework\Filter\JsonBuilder;
use QiblaFramework\Plugin;

/**
 * Class Maps
 *
 * @since  2.1.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class Maps extends AbstractShortcode implements ShortcodeVisualComposerInterface
{
    /**
     * The Query
     *
     * @since  2.1.0
     *
     * @var \WP_Query
     */
    private $query;

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

        // Post type.
        $postType = isset($args['post_type']) && '' !== $args['post_type'] ? $args['post_type'] : 'listings';

        // Initialize tax query.
        $queryArgs['tax_query'] = array();
        // Add Tax Query arguments if taxonomy is listing_categories and d'not empty terms.
        if (! empty($args['categories']) || ! empty($args['locations'])) :
            // Set Terms.
            $listingCategories = '' !== $args['categories'] ? explode(',', $args['categories']) : null;
            $locations         = '' !== $args['locations'] ? explode(',', $args['locations']) : null;

            // Listing categories tax query.
            $taxCategories = array(
                'taxonomy' => $postType === 'listings' ? 'listing_categories' : 'event_categories',
                'field'    => 'slug',
                'terms'    => $listingCategories,
            );

            // Listing locations tax query.
            $taxLocations = array(
                'taxonomy' => $postType === 'listings' ? 'locations' : 'event_locations',
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
     * @since 2.1.0
     */
    public function __construct()
    {
        $this->tag      = 'dl_maps';
        $this->defaults = array(
            'post_type'                => 'listings',
            'posts_per_page'           => -1,
            'orderby'                  => 'date',
            'order'                    => 'DESC',
            'categories'       => '',
            'locations'                => '',
            'height'                   => '100vh',
            'width'                    => '100%',
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
        // Build the Query Arguments List.
        // Since we allow to pass additional query args, we need to parse those arguments.
        $queryArgs = $this->buildQueryArgsList($atts);
        // Make the query.
        $query = new \WP_Query($queryArgs);
        // Initialize Data.
        $data = new \stdClass();
        // Set Query.
        $this->query = $query;

        $data->height = '' !== $atts['height'] ? $atts['height'] : '100vh';
        $data->width  = '' !== $atts['width'] ? $atts['width'] : '100%';

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

        return $data;
    }

    /**
     * Parse Attributes Arguments
     *
     * @since  2.1.0
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

        return $atts;
    }

    /**
     * Create Json
     *
     * @since  2.1.0
     */
    public function json()
    {
        $jsonBuilder = new JsonBuilder();
        $jsonBuilder->prepare($this->query);

        printf(
            "<script type=\"text/javascript\">//<![CDATA[\n var jsonListings = %s; var optionsMap = %s; \n//]]></script>",
            wp_json_encode($jsonBuilder->json()),
            wp_json_encode(array('scrollwheel' => false))
        );
    }

    /**
     * Callback
     *
     * @since  2.1.0
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

            // Enqueue scripts.
            if (wp_script_is('dlmap-listings', 'registered')) {
                wp_enqueue_script('dlmap-listings');
            }

            // Add json in footer.
            add_action('wp_footer', array($this, 'json'));

            return $this->loadTemplate('dl_sc_maps', $data, '/views/shortcodes/maps.php');
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
        return include Plugin::getPluginDirPath('/inc/vcMapping/maps.php');
    }
}
