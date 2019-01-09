<?php
use \QiblaFramework\Functions as Fw;
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

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

try {
    // Retrieve products.
    $productsList = array_flip(Fw\getPostList('product'));
    $productsList = array_filter($productsList, function ($name) {
        $bool = false;
        $prod = wc_get_product(Fw\getPostByName($name, 'product'));

        if ($prod instanceof \WC_Product) {
            $bool = 'listing' === $prod->get_type();
        }

        return $bool;
    });
    $productsList = array_flip($productsList);

    $productsList = array_merge(array('none' => esc_html__('Free Listing', 'qibla-litings')), $productsList);
} catch (\InvalidArgumentException $e) {
    $productsList = array();
}

/**
 * Filter Listing Package Product Meta
 *
 * @since 1.0.0
 *
 * @param array $array The list of the fields.
 */
return apply_filters('qibla_mb_inc_listings_package_product', array(
    /**
     * Related Product
     *
     * @since 1.0.0
     */
    'qibla_mb_listings_products:select'           => $fieldFactory->base(array(
        'type'        => 'select',
        'name'        => 'qibla_mb_listings_products',
        'value'       => Fw\getPostMeta('_qibla_mb_listings_products'),
        'label'       => esc_html__('Related Product', 'qibla-listings'),
        'select2'     => true,
        'description' => sprintf(
        /* Translate % is the link of the new product page */
            esc_html__(
                "Select what product must be related with this listing. Listing product only.\nChoose the listing product related to this package or %s.",
                'qibla-listings'
            ),
            '<a href="' . esc_url(admin_url('/post-new.php?post_type=product')) . '">' .
            esc_html__('create a new one', 'qibla-listings') . '</a>'
        ),
        'options'     => $productsList,
        'display'     => array($this, 'displayField'),
        'attrs'       => array(
            'class' => array('dlselect2--wide'),
        ),
    )),
));
