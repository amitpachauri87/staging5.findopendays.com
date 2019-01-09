<?php
namespace QiblaListings\Woocommerce\DataStore\Cpt;

/**
 * ProductListingDataStoreCpt
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaListings\Woocommerce\DataStore\Cpt
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    GNU General Public License, version 2
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
 * Class ProductListingDataStoreCpt
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Woocommerce\DataStore\Cpt
 */
class ProductListingDataStoreCpt extends \WC_Product_Data_Store_CPT
{
    /**
     * Data stored in meta keys, but not considered "meta".
     *
     * @since  1.0.0
     *
     * @var array
     */
    protected $listing_meta_key_props = array(
        '_listing_price'             => 'price',
        '_listing_regular_price'     => 'regular_price',
        '_listing_sale_price'        => 'sale_price',
        '_listing_date_on_sale_from' => 'date_on_sale_from',
        '_listing_date_on_sale_to'   => 'date_on_sale_to',
    );

    /**
     * @inheritdoc
     */
    public function update_post_meta(&$product, $force = false)
    {
        parent::update_post_meta($product, $force);

        // Make the props to store in updated_props.
        $meta_key_to_props = $this->listing_meta_key_props;
        foreach ($this->listing_meta_key_props as $key => $prop) {
            $meta_key_to_props[$key] = ltrim($key, '_');
        }

        $props_to_update = $force ? $meta_key_to_props : $this->get_props_to_update($product, $meta_key_to_props);

        // Custom post meta data.
        foreach ($props_to_update as $key => $prop) {
            $value = $product->{"get_$prop"}('edit');

            if (is_callable(array($product, "get_$prop"))) {
                switch ($prop) {
                    case 'allow_featured':
                    case 'allow_gallery':
                    case 'allow_website_url':
                    case 'allow_open_hours':
                        $value = wc_bool_to_string($value);
                        break;
                }

                update_post_meta($product->get_id(), $key, $value);
                // Set the updated_props property.
                // Allow to know which properties are updated during a cycle.
                $this->updated_props[] = $prop;
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function read_product_data(&$product)
    {
        parent::read_product_data($product);

        $id = $product->get_id();

        $product->set_props(array(
            'listing_price'                 => get_post_meta($id, '_listing_price', true),
            'listing_regular_price'         => get_post_meta($id, '_listing_regular_price', true),
            'listing_sale_price'            => get_post_meta($id, '_listing_sale_price', true),
            'listing_sale_price_dates_from' => get_post_meta($id, '_listing_date_on_sale_from', true),
            'listing_sale_price_dates_to'   => get_post_meta($id, '_listing_date_on_sale_to', true),
        ));
    }

    /**
     * @inheritdoc
     */
    protected function handle_updated_props(&$product)
    {
        if (in_array('listing_sale_price_dates_from', $this->updated_props, true) ||
            in_array('listing_sale_price_dates_to', $this->updated_props, true) ||
            in_array('listing_regular_price', $this->updated_props, true) ||
            in_array('listing_sale_price', $this->updated_props, true)
        ) {
            if ($product->is_on_sale('edit')) {
                update_post_meta($product->get_id(), '_listing_price', $product->get_sale_price('edit'));
                $product->set_price($product->get_sale_price('edit'));
            } else {
                update_post_meta($product->get_id(), '_listing_price', $product->get_regular_price('edit'));
                $product->set_price($product->get_regular_price('edit'));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function get_product_type($product_id)
    {
        return 'listing';
    }
}
