<?php
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

namespace QiblaWcListings\Front\Element;

use QiblaWcListings\TemplateEngine\Engine as TEngine;

/**
 * Class AbstractElement
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaWcListings\Front\Element
 */
abstract class AbstractElement implements ElementInterface
{
    /**
     * Product
     *
     * @since  1.0.0
     *
     * @var \WC_Product A product related to the element
     */
    protected $product;

    /**
     * Load Template
     *
     * @since  1.0.0
     *
     * @param string    $name The name of the template for filtering.
     * @param \stdClass $data The data to inject to the template.
     * @param string    $path The template path.
     */
    protected function loadTemplate($name, \stdClass $data, $path)
    {
        $engine = new TEngine("qibla_wc_listings_{$name}", $data, $path);
        $engine->render();
    }

    /**
     * AbstractElement constructor
     *
     * @since 1.0.0
     *
     * @param \WC_Product $product A product related to this element.
     */
    public function __construct($product)
    {
        $this->product = $product;
    }

    /**
     * Get product Price
     *
     * @since  1.0.0
     *
     * @return string The price of the product related to the element.
     */
    public function getPrice()
    {
        return $this->product->get_price();
    }

    /**
     * Get html price
     *
     * @since  1.0.0
     *
     * @return string The markup of the price
     */
    public function getPriceHtml()
    {
        $output = '';

        // Prevent to show empty product price element.
        if ($this->product->get_price_html()) {
            $output = sprintf('<span class="dlproduct-price">%s</span>', $this->product->get_price_html());
        }

        return $output;
    }

    /**
     * Get Formatted price
     *
     * @since  1.0.0
     *
     * @uses   wc_price() To retrieve the formatted price.
     *
     * @param bool $strip If the product price must be remove tags, to get a plain text.
     *
     * @return string The product price.
     */
    public function getFormattedPrice($strip = false)
    {
        $price = wc_price($this->getPrice());

        return $strip ? strip_tags($price) : $price;
    }
}
