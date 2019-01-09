<?php
use \QiblaFramework\Functions as F;
use \QiblaFramework\Form\Factories\FieldFactory;

/**
 * @author     Guido Scialfa <dev@guidoscialfa.com>
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

defined('WPINC') || die;

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

try {
    // Retrieve products.
    $products = array_flip(F\getPostList('product'));
    $products = array_filter($products, function ($name) {
        $bool = false;
        $prod = wc_get_product(F\getPostByName($name, 'product'));

        if ($prod instanceof \WC_Product) {
            $bool = 'booking' === $prod->get_type();
        }

        return $bool;
    });
    $products = array_flip($products);
} catch (\InvalidArgumentException $e) {
    $products = array();
}

/**
 * Filter WooCommerce Products List
 *
 * @since 1.0.0
 *
 * @param array $array The list of the fields.
 */
return apply_filters('qibla_mb_inc_wc_listings_products', array(
    /**
     * Featured Hours
     *
     * @since 1.0.0
     */
    'qibla_mb_wc_listings_products:select' => $fieldFactory->base(array(
        'type'        => 'select',
        'name'        => 'qibla_mb_wc_listings_products',
        'value'       => F\getPostMeta('_qibla_mb_wc_listings_products'),
        'label'       => esc_html__('Related Product', 'qibla-woocommerce-listings'),
        'select2'     => true,
        'description' => esc_html__(
            'Select what product must be related with this listing. Bookable product only.',
            'qibla-woocommerce-listings'
        ),
        'options'     => $products,
        'display'     => array($this, 'displayField'),
        'attrs'       => array(
            'class' => array('dlselect2--wide'),
        ),
    )),
));

 