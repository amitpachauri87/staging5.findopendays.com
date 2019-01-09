<?php
/**
 * Short-code Terms
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
use QiblaFramework\IconsSet\Icon;
use QiblaFramework\Plugin;

/**
 * Class Terms
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class Terms extends AbstractShortcode implements ShortcodeVisualComposerInterface
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
            'taxonomy' => '',
            'orderby'  => '',
            'slug'     => '',
        ));

        return wp_parse_args($args['additional_query_args'], $queryArgs);
    }

    /**
     * Reorder Terms list
     *
     * @since  1.4.0
     *
     * @param array  $terms    A list of \WP_Terms object to re-order.
     * @param string $order    The order type.
     * @param array  $orderMap The order map. A list of key => value pairs that define the wanted order.
     *
     * @return array The filtered $terms object
     */
    protected function orderList($terms, $order, array $orderMap = array())
    {
        if ($orderMap) :
            $tmpTermsList      = array();
            $originalTermsList = array_flip(wp_list_pluck($terms, 'slug'));
            $orderMap          = array_flip($orderMap);

            switch ($order) {
                // Custom order defined by the shortcode attribute.
                case 'listorder':
                    foreach ($originalTermsList as $key => $index) {
                        $newPos                = $orderMap[$key];
                        $tmpTermsList[$newPos] = $terms[$index];
                    }

                    ksort($tmpTermsList, SORT_NUMERIC);
                    break;
                default:
                    // Don't do anything by default.
                    // The order was performed by the query.
                    $tmpTermsList = $terms;
                    break;
            }

            $terms = $tmpTermsList;
        endif;

        return $terms;
    }

    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        $this->tag = 'dl_terms';
        // Initialize the default arguments.
        // The additional_query_args is not visible within the UI, it is allowed via code only.
        $this->defaults = array(
            'taxonomy'                 => '',
            'orderby'                  => 'name',
            // Cast to array.
            'slug'                     => '',
            'grid'                     => '1,2,1',
            // Layout.
            'layout'                   => 'container-width',
            'section-background-color' => 'transparent',
            'section-padding-top'      => 'inherit',
            'section-padding-bottom'   => 'inherit',
            'additional_query_args'    => array(
                'update_term_meta_cache' => false,
                'hide_empty'             => false,
                'fields'                 => 'all',
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
        // Convert slug data to an array.
        $atts['slug'] = array_unique(explode(',', $atts['slug']));
        // Let's remove extra whitespaces.
        $atts['slug'] = array_map('trim', $atts['slug']);

        // Define the grid pattern.
        $atts['grid_class'] = array(
            '1' => 'col--xs-12 col--sm-6 col--md-3',
            '2' => 'col--xs-12 col--sm-6 col--md-6',
        );

        // Relation between grid and thumbnails size.
        $atts['grid_thumbnail_size'] = array(
            '1' => 'qibla-post-thumbnail-square',
            '2' => 'qibla-post-thumbnail-wide',
        );

        $atts['grid'] = explode(',', $atts['grid']);

        return $atts;
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
        // Build the query args.
        $queryArgs = $this->buildQueryArgsList($atts);
        // Retrieve the terms.
        $terms = get_terms($queryArgs);

        if (is_wp_error($terms)) {
            throw new \Exception(
                sprintf('%s Cannot retrieve terms.', __METHOD__)
            );
        }

        // Initialize the data instance.
        $data = new \stdClass();

        $termsData   = array();
        $gridCounter = 0;

        // Reorder list if needed.
        $terms = $this->orderList($terms, $atts['orderby'], $atts['slug']);

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

        // Loop through the terms.
        foreach ($terms as $term) :
            $termArgs     = new \stdClass();
            $termArgs->ID = $term->term_id;
            // Set the title.
            $termArgs->termTitle = $term->name;
            // The term description, such as a post content.
            $termArgs->termContent = $term->description;

            // Add the term classes list.
            $termArgs->termClass = array(
                'dlarticle',
                'dlarticle--square',
                'col',
                $atts['grid_class'][$atts['grid'][$gridCounter]],
            );

            // The thumbnail associated to the term.
            // The wp_get_attachment_image return false on failure, so we don't need to worry about it within the view.
            $termArgs->thumbnail = wp_get_attachment_image(
                intval(F\getTermMeta('_qibla_tb_thumbnail', $term->term_id)),
                $atts['grid_thumbnail_size'][$atts['grid'][$gridCounter]],
                false,
                array('class' => 'dlthumbnail__image')
            );

            // Set the term permalink.
            $termLink            = get_term_link($term->term_id);
            $termArgs->permalink = (! is_wp_error($termLink) ? $termLink : '');

            // Set the term icon.
            $termArgs->iconHtmlClass = '';
            $termIcon                = F\getTermMeta('_qibla_tb_icon', $term->term_id);
            if ($termIcon) {
                $iconTermInstance        = new Icon($termIcon, 'Lineawesome::la-check');
                $termArgs->iconHtmlClass = array(
                    'dlarticle__icon',
                    $iconTermInstance->getHtmlClass(),
                );
            }

            // If the number of terms are greater than the elements within the grid,
            // the counter will be reset to -1, then will be increased for a new cycle.
            if ($gridCounter === count($atts['grid']) - 1) {
                $gridCounter = -1;
            }

            ++$gridCounter;

            // Add the current term arguments data to the collection.
            $termsData[sanitize_key($term->slug)] = $termArgs;
        endforeach;

        // Add terms data.
        $data->terms = $termsData;

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
        $atts = $this->parseAttrsArgs($atts);

        // Do nothing if the taxonomy doesn't exists.
        if (! taxonomy_exists($atts['taxonomy'])) {
            return '';
        }

        try {
            $data     = $this->buildData($atts);
            $template = $this->loadTemplate('dl_sc_terms', $data, '/views/shortcodes/terms.php');
        } catch (\Exception $e) {
            $debugInstance = new Debug\Exception($e);
            'dev' === QB_ENV && $debugInstance->display();

            $template = '';
        }

        // Enqueue masonry only if the template was loaded successfully.
        if ($template) {
            wp_enqueue_script('dl-masonry');
        }

        return $template;
    }

    /**
     * @inheritDoc
     */
    public function visualComposerMap()
    {
        return include Plugin::getPluginDirPath('/inc/vcMapping/terms.php');
    }
}
