<?php
/**
 * WcSidedar
 *
 * @since      2.1.0
 * @package    Qibla\Sidebar
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

namespace QiblaFramework\Sidebars;

use QiblaFramework\Functions as F;

/**
 * Class WooCommerceSidebar
 *
 * @since  2.1.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class WooCommerceSidebar
{
    /**
     * Set Hooks Actions
     *
     * @since 2.1.0
     *
     * @return $this For concatenation
     */
    public function hookAction()
    {
        // Remove global shop sidebar.
        remove_action('qibla_shop_after_wrapper_main_end', 'woocommerce_get_sidebar', 20);
        // Add product sidebar.
        add_action('qibla_shop_after_wrapper_main_end', array($this, 'sidebar'), 9);

        return $this;
    }

    /**
     * Set Hooks Filters
     *
     * @since 2.1.0
     *
     * @return $this For concatenation
     */
    public function hookFilter()
    {
        // Set has sidebar.
        add_filter('qibla_has_sidebar', array($this, 'hasSidebar'), 10);

        return $this;
    }

    /**
     * Has Sidebar
     *
     * @since 2.1.0
     *
     * @param $position string The position sidebar filter.
     *
     * @return string
     */
    public function hasSidebar($position)
    {
        if (\QiblaFramework\Functions\isProduct()) {
            $obj  = get_queried_object();
            $meta = isset($obj->ID) ? F\getPostMeta('_qibla_mb_sidebar_position', 'default', $obj->ID) : 'default';

            $position = ('none' === $meta || 'default' === $meta) ? 'no' : 'yes';
        }

        return $position;
    }

    /**
     * Sidebar
     *
     * @since 2.1.0
     *
     * @uses  get_sidebar() To retrieve the sidebar
     *
     * @return void
     */
    public function sidebar()
    {
        // Single.
        if (\QiblaFramework\Functions\isProduct()) {
            get_sidebar('product');
        }

        // WooCommerce Archive.
        if (\QiblaFramework\Functions\isWooCommerceArchive()) {
            get_sidebar('shop');
        }
    }

    /**
     * Helper action action
     *
     * @since 2.1.0
     */
    public static function actionAction()
    {
        if (! \QiblaFramework\Functions\isWooCommerceActive()) {
            return;
        }

        $instance = new self;
        $instance->hookAction();
        $instance->filterFilter();
    }

    /**
     * Helper action filter
     *
     * @since 2.1.0
     */
    public static function filterFilter()
    {
        if (! \QiblaFramework\Functions\isWooCommerceActive()) {
            return;
        }

        $instance = new self;
        $instance->hookFilter();
    }
}
