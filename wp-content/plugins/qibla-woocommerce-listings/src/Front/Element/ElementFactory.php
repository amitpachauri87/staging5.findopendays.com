<?php

namespace QiblaWcListings\Front\Element;

use QiblaFramework\Functions as Fw;
use QiblaFramework\ListingsContext\Types;
use QiblaWcListings\Functions as F;

/**
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaWcListings\Front\Element
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2017 Guido Scialfa <dev@guidoscialfa.com>
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
 * Class ElementFactory
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaWcListings\Front\Element
 */
class ElementFactory
{
    /**
     * Namespace
     *
     * @since  1.0.0
     */
    const NS = 'QiblaWcListings\\Front\\Element';

    /**
     * Meta key
     *
     * @since  1.0.0
     *
     * @var string The post (product) meta key
     */
    const META_KEY = '_qibla_mb_wc_listings_products';

    /**
     * Post
     *
     * @since 2.0.0
     *
     * @var \WP_Post The post instance
     */
    protected $post;

    /**
     * Get Meta
     *
     * @since  1.0.0
     *
     * @return mixed The name of the post product or false, empty string otherwise.
     */
    protected function getMeta()
    {
        return Fw\getPostMeta(self::META_KEY, '', $this->post->ID);
    }

    /**
     * Get Product
     *
     * @since  1.0.0
     *
     * @throws \Exception If the meta doesn't exists or is empty.
     *
     * @return \WC_Product|null The product retrieved by the listing post meta. Null if WooCommerce is not active.
     */
    protected function getProduct()
    {
        $meta = $this->getMeta();
        if (! $meta) {
            throw new \Exception(esc_html__('Empty Meta for Element Factory.', 'qibla-woocommerce-listings'));
        }

        return F\isWooCommerceActive() ? wc_get_product(Fw\getPostByName($meta, 'product')) : null;
    }

    /**
     * Get Element Class Name
     *
     * Retrieve the name of the Element class by the product type
     *
     * @since  1.0.0
     *
     * @param \WC_Product $product The product from which retrieve the type.
     *
     * @return string The class name for a Element class
     */
    protected function getElementClassName(\WC_Product $product)
    {
        $productType = $product->get_type();
        $productType = str_replace(' ', '', ucwords(preg_replace('[^a-z0-9]', ' ', $productType)));

        return $productType;
    }

    /**
     * ElementFactory constructor
     *
     * @since  1.0.0
     */
    public function __construct(\WP_Post $post)
    {
        $this->post = $post;
    }

    /**
     * Create Element
     *
     * @since  1.0.0
     * @since  1.1.3 Throw a \QiblaFramework\Exceptions\Error if class name doesn't exists.
     *
     * @throws  \QiblaFramework\Exceptions\Error If che class name we are building not exists.
     *
     * @return ElementInterface|\stdClass The element instance
     */
    public function createElement()
    {
        $product    = $this->getProduct();
        $theProduct = new \stdClass();

        if ($product instanceof \WC_Product) {
            // Build the class name.
            $className = self::NS . '\\' . $this->getElementClassName($product);

            // Create the new instance only if the class for that type of product exists.
            // Throw an exception if not. This is useful until php7 support.
            if (! class_exists($className)) {
                throw new \QiblaFramework\Exceptions\Error('Class ' . $className . ' does not exists.');
            }

            $theProduct = new $className($product);
        }

        return $theProduct;
    }
}
