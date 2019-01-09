<?php
/**
 * WooCommerce My Account Navigation
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2017, Guido Scialfa
 * @license   GNU General Public License, version 2
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

namespace QiblaFramework\Woocommerce;

use QiblaFramework\Functions as F;
use QiblaListings\Listing\ManagerPosts\MyListingsEndPoint;

/**
 * Class MyAccountNavigation
 *
 * @since   2.0.0
 * @package QiblaFramework\Woocommerce
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class MyAccountNavigation
{
    /**
     * Navigation Items
     *
     * Additional navigation items to include within the WooCommerce MyAccount Navigation
     *
     * @since  2.0.0
     *
     * @var array The list of the additional items to include to the nav
     */
    protected static $navigationItems;

    /**
     * Items to remove
     *
     * @since  2.0.0
     *
     * @var array The list of items to remove from $navigationItems
     */
    protected static $toRemoveItems;

    /**
     * Custom Order
     *
     * @since  2.0.0
     *
     * @var array The items keys for ordering
     */
    protected static $customOrder = array(
        'dashboard',
        'edit-account',
        'my-listings',
        'my-favorites',
        'orders',
        'edit-address',
        'bookings',
        'downloads',
        'customer-logout',
    );

    /**
     * Reorder Items
     *
     * @since  2.0.0
     *
     * @return array
     */
    protected function reorderItems()
    {
        $newOrder = array();

        foreach (static::$customOrder as $key) {
            if ('my-favorites' === $key) {
                $key = sanitize_title_with_dashes(WishlistEndPoint::slug());
            }

            // @todo Remove the did_action conditional. It's used only until the plugin is merge into the framework.
            if ('my-listings' === $key && did_action('qibla_listings_did_init')) {
                $key = sanitize_title_with_dashes(MyListingsEndPoint::slug());
            }

            if (isset(static::$navigationItems[$key])) {
                $newOrder[$key] = static::$navigationItems[$key];
            }
        }

        return $newOrder;
    }

    /**
     * AddListingManagerNavigationItems constructor
     *
     * @since 2.0.0
     */
    public function __construct($items)
    {
        // This is needed to allow us to filter unwanted items.
        static::$toRemoveItems = array(
            'downloads' => '',
        );

        $myFavoritesKey = WishlistEndPoint::slug();
        $myListingsKey  = MyListingsEndPoint::slug();

        // Set the items list.
        static::$navigationItems = wp_parse_args(array(
            'dashboard'       => esc_html_x('My Profile', 'my-account', 'qibla-framework'),
            'edit-account'    => esc_html_x('Edit Profile', 'my-account', 'qibla-framework'),
            $myFavoritesKey   => esc_html_x('My Favorites', 'my-account', 'qibla-framework'),
            'orders'          => esc_html_x('Orders', 'my-account', 'qibla-framework'),
            'edit-address'    => esc_html_x('Billing Info', 'my-account', 'qibla-framework'),
            'customer-logout' => esc_html_x('Log out', 'my-account', 'qibla-framework'),
        ), $items);

        // Show the my listings only to users that are able to edit them.
        // @todo Remove the did_action conditional. It's used only until the plugin is merge into the framework.
        if (current_user_can('edit_listingss') && did_action('qibla_listings_did_init')) {
            static::$navigationItems[$myListingsKey] = esc_html_x('My Listings', 'my-account', 'qibla-framework');
        }
    }

    /**
     * Set Hooks
     *
     * Set additional hooks needed to accomplishing the task.
     *
     * @since  2.0.0
     *
     * @return void
     */
    public function setHooks()
    {
        add_filter('woocommerce_get_endpoint_url', array($this, 'filterEndpointAddress'), 20, 2);
    }

    /**
     * Filter Endpoint Address
     *
     * Make the edit-address endpoint link to 'billing' form page.
     *
     * @since  2.0.0
     *
     * @param string $url      The url to filter.
     * @param string $endpoint The current endpoint for the url.
     *
     * @return string The filtered url
     */
    public function filterEndpointAddress($url, $endpoint)
    {
        if (F\isAccountPage() && 'edit-address' === $endpoint) {
            // Remove before call again the function for which the filter is applied.
            remove_filter('woocommerce_get_endpoint_url', array($this, 'filterEndpointAddress'), 20);
            $url = wc_get_endpoint_url('edit-address', 'billing');
        }

        return $url;
    }

    /**
     * Remove Items
     *
     * Remove Items based by conditions, For example, don't show orders item if no order has been found.
     *
     * @since  2.0.0
     *
     * @return void
     */
    public function removeItemsIfNeeded()
    {
        // Remove Bookings Item if no booking are founds.
        if (class_exists('WC_Bookings_Controller')) {
            $bookingsController = \WC_Bookings_Controller::get_bookings_for_user(get_current_user_id(), array());
            if (empty($bookingsController)) {
                unset(static::$navigationItems['bookings']);
            }
        }

        // Remove Orders Item if no order are founds.
        $orders = wc_get_orders(array('customer' => get_current_user_id()));

        if (empty($orders)) {
            unset(static::$navigationItems['orders']);
        }
    }

    /**
     * Filter Navigation Items
     *
     * This function remove extra unwanted items for the user.
     *
     * @since  2.0.0
     *
     * @return array The only needed items.
     */
    public function filteredItems()
    {
        $items = $this->reorderItems();

        return array_diff_key($items, static::$toRemoveItems);
    }

    /**
     * Navigation Items Filter
     *
     * @since  2.0.0
     *
     * @param array $items The navigation items.
     *
     * @return array The filtered items
     */
    public static function navigationItemsFilter($items)
    {
        $instance = new static($items);

        $instance->setHooks();
        $instance->removeItemsIfNeeded();

        return $instance->filteredItems();
    }
}
