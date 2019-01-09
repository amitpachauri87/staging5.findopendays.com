<?php
/**
 * Package
 *
 * @since      1.0.0
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

namespace AppMapEvents\Shortcode;

use QiblaFramework\Functions as F;
use QiblaFramework\Debug;
use QiblaFramework\Exceptions\InvalidPostException;
use AppMapEvents\Plugin;
use AppMapEvents\TemplateEngine\Engine;

/**
 * Class PackageShortcode
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingPackage extends AbstractShortcode implements \QiblaFramework\Shortcode\ShortcodeVisualComposerInterface
{
    /**
     * Load View
     *
     * @since  1.0.0
     * @access protected
     *
     * @param string    $name         The name of the current template.
     * @param \stdClass $data         The data to use in view.
     * @param string    $templatePath The template to load.
     *
     * @return mixed Whatever the Engine::render().
     * @throws \Exception
     */
    protected function loadTemplate($name, $data, $templatePath)
    {
        ob_start();
        $engine = new Engine($name, $data, $templatePath);
        $engine->render();
        $output = ob_get_clean();

        return $output;
    }

    /**
     * Build Query Arguments List
     *
     * @since  1.0.0
     * @access protected
     *
     * @param array $args The base arguments for the query.
     *
     * @return array The arguments to use for the query
     */
    protected function buildQueryArgsList(array $args)
    {
        // Retrieve the default arguments for the query.
        $queryArgs = array(
            'post_type' => 'event_package',
            // Keep the user defined order.
            'orderby'   => 'post_name__in',
        );

        if ('all' !== $args['posts']) {
            $queryArgs['post_name__in'] = explode(',', $args['posts']);
        }

        return array_merge($queryArgs, $args['additional_query_args']);
    }

    /**
     * Build Fields List
     *
     * @since  1.0.0
     * @access protected
     *
     * @param \WP_Post $post The post from which retrieve the extra fields.
     *
     * @return array The extra fields
     */
    protected function buildFields(\WP_Post $post)
    {
        $list   = array();
        $fields = F\getPostMeta('_qibla_mb_listing_package_additional_info', '', $post);

        if ($fields) :
            // We have content, but need to extract the data.
            // So, first of all check if we obtained the correct format,
            // if not, we build the entire content as the only element for the fields list.
            if (false === strpos($fields, '|')) :
                $list[] = $fields;
            else :
                $oldValue = $fields;
                // Remember one element per line.
                // Es. icon-name|Text to use as description.
                $fields = preg_split("/\\r\\n|\\r|\\n/", $fields);
                // Again, if empty we have not the correct format.
                // So, get the old fields value.
                if (! $fields) {
                    $list[] = $oldValue;
                } else {
                    // Otherwise let's build the right list.
                    foreach ($fields as $field) {
                        // First of all separate the icon by the text.
                        $current = explode('|', $field);
                        // Then, remove the prefix 'icon-', used only to give the user the idea
                        // that they are adding an icon via text.
                        $current[0] = str_replace('icon-', '', $current[0]);
                        // Then, build the real icon class values.
                        $current[0] = "la la-{$current[0]}";
                        // And put it into the list.
                        $list = array_merge($list, array($current[0] => $current[1]));
                    }
                }
            endif;
        endif;

        return $list;
    }

    /**
     * Formatted Wc Price Filter
     *
     * @todo   Move out of the class.
     *
     * @since  1.0.0
     * @access public
     *
     * @param float  $number            The number formatted.
     * @param float  $price             The price.
     * @param string $decimals          The amount of decimal.
     * @param string $decimalSeparator  The decimal separator.
     * @param string $thousandSeparator The thousand separator.
     *
     * @return string
     */
    public function formattedWcPriceFilter($number, $price, $decimals, $decimalSeparator, $thousandSeparator)
    {
        $splitted = explode($decimalSeparator, $number);
        // Do nothing if we don't have decimal points.
        // Price will obtain the correct markup.
        if (2 === count($splitted)) {
            $number = "<span class=\"unit\">{$splitted[0]}</span>" .
                      "<span class=\"decimal\">{$decimalSeparator}{$splitted[1]}</span>";
        }

        // After done, remove the filter.
        remove_filter('formatted_woocommerce_price', array($this, 'formattedWcPrice'), 20, 5);

        return $number;
    }

    /**
     * Get Product Data
     *
     * @since  1.0.0
     * @access protected
     *
     * @param \WP_Post $post The post from which retrieve the product.
     *
     * @return \stdClass The product data for the view
     */
    protected function getProductData(\WP_Post $post)
    {
        // Initialize the product data.
        $product = new \stdClass();

        // Now get the product related if exists.
        // If not exists, we assume a free publishing package type.
        $productRelated = sanitize_title(F\getPostMeta('_qibla_mb_listings_products', '', $post));

        // Set the label as Free, if the package have a product related,
        // it will change to the product price.
        $product->priceLabel = esc_html__('Free', 'qibla-events');

        if ($productRelated) {
            // Get the product instance.
            $productRelated = wc_get_product(F\getPostByName($productRelated, 'product'));
            // Be sure we have a valid data.
            if ($productRelated instanceof \WC_Product) {
                add_filter('formatted_woocommerce_price', array($this, 'formattedWcPriceFilter'), 20, 5);
                $product->priceLabel = $productRelated->get_price_html();
            }
        }

        return $product;
    }

    /**
     * Construct
     *
     * @since  1.0.0
     * @access public
     */
    public function __construct()
    {
        $this->tag      = 'dl_ev_package';
        $this->defaults = array(
            'posts'                    => 'all',
            // Layout.
            'layout'                   => 'container-width',
            'section-background-color' => 'transparent',
            'section-padding-top'      => 'inherit',
            'section-padding-bottom'   => 'inherit',
            'additional_query_args'    => array(
                'post_status'   => 'publish',
                'no_found_rows' => true,
            ),
        );
    }

    /**
     * Build Data
     *
     * @since  1.0.0
     * @access public
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

        if (! $posts) {
            throw new InvalidPostException();
        }

        // Initialize Data.
        $data = new \stdClass();

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

            // Initialize the data.
            $postArgs = new \stdClass();

            // Set the post data arguments.
            $postArgs->ID               = intval($post->ID);
            $postArgs->permalink        = get_permalink($postArgs->ID);
            $postArgs->isHightlight     = F\stringToBool(
                F\getPostMeta('_qibla_mb_listing_package_highlight', 'no', $postArgs->ID)
            );
            $postArgs->hightlightLabel  = $post->post_title;
            $postArgs->submitLabel      = esc_html_x('Choose this plan', 'listing-package', 'qibla-events');
            $postArgs->fields           = $this->buildFields($post);
            $postArgs->shortDescription = F\getPostMeta('_qibla_mb_listing_package_short_description');

            // Clean and set the post classes.
            $postArgs->postClassModifiers = array_filter(array(
                'card',
                $post->post_type,
                ($postArgs->isHightlight ? 'is-featured' : ''),
            ));

            // Get the product data.
            $postArgs->product = $this->getProductData($post);

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
     * @access public
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

            return $this->loadTemplate('dl_sc_ev_package', $data, '/views/shortcodes/listingPackage.php');
        } catch (\Exception $e) {
            $debugInstance = new Debug\Exception($e);
            'dev' === QB_ENV && $debugInstance->display();

            return '';
        }
    }

    /**
     * @inheritdoc
     */
    public function visualComposerMap()
    {
        return include Plugin::getPluginDirPath('/inc/vcMapping/listingPackage.php');
    }
}
