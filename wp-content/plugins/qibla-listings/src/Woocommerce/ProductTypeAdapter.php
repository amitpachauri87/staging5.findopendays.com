<?php
namespace QiblaListings\Woocommerce;

use QiblaListings\Plugin;
use QiblaListings\Admin\Woocommerce\MetaBoxAdapter;

/**
 * Handle Product Types
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaListings\Woocommerce
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
 * Class ProductTypesAdapter
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Woocommerce
 */
class ProductTypeAdapter
{
    /**
     * Additional WC Stores
     *
     * @since  1.0.0
     * @see    /woocommerce/includes/class-wc-data-store.php
     *
     * @var array The list of the additional WC stores.
     */
    private static $stores = array(
        'product-listing' => 'QiblaListings\\Woocommerce\\DataStore\\Cpt\\ProductListingDataStoreCpt',
    );

    /**
     * Class Map
     *
     * We don't use the same standard of WooCommerce, so a mapped class names is needed.
     *
     * @since  1.0.0
     *
     * @var array A list of mapped classes associated to the additional product types.
     */
    private static $classMap;

    /**
     * HookProductTypes constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        self::$classMap = include Plugin::getPluginDirPath('/inc/productTypesList.php');
    }

    /**
     * Set Hooks
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function setHooks()
    {
        add_filter('woocommerce_data_stores', array($this, 'setDataStoreCptFilter'), 20, 2);
        add_filter('woocommerce_product_class', array($this, 'productClassNameAdapterFilter'), 20, 2);

        // Based on context.
        if (is_admin()) {
            $metaBoxAdapter = new MetaBoxAdapter;

            add_action('woocommerce_product_data_tabs', array($metaBoxAdapter, 'filterProductDataTabsFilter'), 20);
            add_action('woocommerce_product_data_tabs', array($metaBoxAdapter, 'addProductDataTabsFilter'), 30);
            add_action('woocommerce_product_data_panels', array($metaBoxAdapter, 'showProductTabsTmplFilter'), 20);
            add_action(
                'woocommerce_admin_process_product_object',
                array($metaBoxAdapter, 'processProductObjectFilter'),
                20
            );

            add_filter('product_type_selector', array($metaBoxAdapter, 'addProductTypeToSelectorFilter'), 20);
        }
    }

    /**
     * Set Data Store CPT
     *
     * Set the additional data store for Custom post types
     *
     * @since  1.0.0
     *
     * @param array $stores The list of the available data stores.
     *
     * @return array The filtered input list
     */
    public function setDataStoreCptFilter(array $stores)
    {
        $stores = array_merge($stores, self::$stores);

        return $stores;
    }

    /**
     * Product Class Name Adapter
     *
     * Retrieve the class name for the product type.
     * Needed because of the differences between WooCommerce and Qibla.
     *
     * @since  1.0.0
     *
     * @param string $className   The current class name.
     * @param string $productType The product type.
     *
     * @return string The filtered class name or the original one if the product type is not found within the internal
     *                classmap
     */
    public function productClassNameAdapterFilter($className, $productType)
    {
        if (in_array($productType, array_keys(self::$classMap), true)) {
            $className = self::$classMap[$productType]['class_name'];
        }

        return $className;
    }
}
