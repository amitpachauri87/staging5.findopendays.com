<?php
namespace QiblaListings\Admin\Woocommerce;

use QiblaListings\Plugin;

/**
 * MetaBoxAdapter
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaListings\Admin\Woocommerce
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
 * Class MetaBoxAdapter
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Admin\Woocommerce
 */
class MetaBoxAdapter
{
    /**
     * Class Map
     *
     * @since  1.0.0
     *
     * @var array The class map for custom product types
     */
    private static $classMap;

    /**
     * MetaBoxAdapter constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        self::$classMap = include Plugin::getPluginDirPath('/inc/productTypesList.php');
    }

    /**
     * Add Product Type to Selector
     *
     * Add the product type to the list of the product types within the admin in
     * product edit screen.
     *
     * @since  1.0.0
     *
     * @param array $typesList The list of the available product types.
     *
     * @return array The filtered input list
     */
    public function addProductTypeToSelectorFilter(array $typesList)
    {
        return array_merge($typesList, wp_list_pluck(self::$classMap, 'label'));
    }

    /**
     * Filter Product Data Tabs
     *
     * Filter the product data tabs based on the product map list.
     * Hide or show the tab based on the product type selector value.
     *
     * @since 1.0.0
     *
     * @param array $productDataTabs The product tabs list to filter.
     *
     * @return array The filtered product data tabs
     */
    public function filterProductDataTabsFilter(array $productDataTabs)
    {
        foreach (self::$classMap as $type => $def) {
            // Get only tabs that are not array, array are used to add tabs.
            $dataTabs = array_filter($def['allowed_product_data_tabs'], function ($el) {
                return ! is_array($el);
            });

            foreach (array_keys($productDataTabs) as $key) {
                if (! in_array($key, $dataTabs, true)) {
                    $showHide = 'hide_if_' . $type;
                } else {
                    $showHide = 'show_if_' . $type;
                }

                $productDataTabs[$key]['class'][] = $showHide;
            }
        }
        unset($type, $def, $key);

        return $productDataTabs;
    }

    /**
     * Add product Data Tabs
     *
     * Insert custom product data tabs by the class map list.
     * The additional product tabs defined by the productTypesList list.
     *
     * @since 1.0.0
     *
     * @param array $productDataTabs The product data tabs list.
     *
     * @return array The filtered product data tabs
     */
    public function addProductDataTabsFilter(array $productDataTabs)
    {
        foreach (self::$classMap as $type => $def) {
            // Get only tabs that are array, array are used to add tabs.
            $dataTabs = array_filter($def['allowed_product_data_tabs'], 'is_array');

            foreach ($dataTabs as $key => $data) {
                $productDataTabs[$key] = $data;
            }
        }
        unset($type, $def, $key, $data);

        return $productDataTabs;
    }

    /**
     * Show the custom product tabs
     * Load the template for the custom product tabs defined in productTypesList.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function showProductTabsTmplFilter()
    {
        // This is used within the view.
        // Don't remove.
        global $product_object;

        // We need to work temporary with another product type.
        // So, store the current object and restore it after the job is done.
        $tmp =& $product_object;

        foreach (self::$classMap as $key => $def) {
            $product_object = new $def['class_name']($product_object);

            // Get only tabs that are array, array are used to add tabs.
            $dataTabs = array_filter($def['allowed_product_data_tabs'], 'is_array');

            foreach ($dataTabs as $tab) {
                if (isset($tab['template']) && is_string($tab['template'])) {
                    // Set the path.
                    $path = '/views/admin/product/dataTabs/' . $tab['template'] . '.php';
                    // Get the template.
                    include Plugin::getPluginDirPath($path);
                }
            }
        }

        // Restore the object.
        $product_object =& $tmp;
    }

    /**
     * Process the product Object
     *
     * Generally this process set the props for extra meta data during save meta-boxes.
     *
     * @since  1.0.0
     *
     * @param \WC_Product $product The product to use to set the props.
     */
    public function processProductObjectFilter($product)
    {
        $productType = $product->get_type();

        if (! in_array($productType, array_keys(self::$classMap), true)) {
            return;
        }

        $errors = $product->clean_and_set_props_by_input();

        if (is_wp_error($errors)) {
            \WC_Admin_Meta_Boxes::add_error($errors->get_error_message());
        }
    }
}
