<?php
namespace QiblaListings\Woocommerce\Product;

use QiblaListings\Functions as F;

/**
 * ProductListing
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaListings\Woocommerce\Product
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
 * Class ProductListing
 *
 * @todo    need interface for custom methods.
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Woocommerce\Product
 */
class ProductListing extends \WC_Product implements ProductPropsInterface
{
    /**
     * Stores product data.
     *
     * @since  1.0.0
     *
     * @var array The list of the own data
     */
    protected $listing_product_data = array(
        'listing_price'             => '',
        'listing_regular_price'     => '',
        'listing_sale_price'        => '',
        'listing_date_on_sale_from' => '',
        'listing_date_on_sale_to'   => '',
    );

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function __construct($product = 0)
    {
        $this->data = array_merge($this->data, $this->listing_product_data);
        parent::__construct($product);
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function set_reviews_allowed($review_allowed)
    {
        parent::set_reviews_allowed(false);
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function set_sold_individually($sold_individually)
    {
        parent::set_sold_individually(true);
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function set_virtual($virual)
    {
        parent::set_virtual(true);
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function set_price($price)
    {
        $this->set_prop('listing_price', wc_format_decimal($price));
    }

    /**
     * @see   set_price.
     *
     * @since 1.0.0
     */
    public function set_listing_price($price)
    {
        $this->set_price($price);
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function set_regular_price($price)
    {
        $this->set_prop('listing_regular_price', wc_format_decimal($price));
    }

    /**
     * @see   set_regular_price.
     *
     * @since 1.0.0
     */
    public function set_listing_regular_price($price)
    {
        $this->set_regular_price($price);
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function set_sale_price($price)
    {
        $this->set_prop('listing_sale_price', wc_format_decimal($price));
    }

    /**
     * @see   set_sale_price.
     *
     * @since 1.0.0
     */
    public function set_listing_sale_price($price)
    {
        $this->set_sale_price($price);
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function set_date_on_sale_from($date = null)
    {
        $this->set_date_prop('date_on_sale_from', $date);
    }

    /**
     * @see   set_date_on_sale_from.
     *
     * @since 1.0.0
     */
    public function set_listing_date_on_sale_from($date = null)
    {
        $this->set_date_on_sale_from($date);
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function set_date_on_sale_to($date = null)
    {
        $this->set_date_prop('date_on_sale_to', $date);
    }

    /**
     * @see   set_date_on_sale_to.
     *
     * @since 1.0.0
     */
    public function set_listing_date_on_sale_to($date = null)
    {
        $this->set_date_on_sale_to($date);
    }

    /**
     * Set Allow Featured Prop
     *
     * @since  1.0.0
     *
     * @param string $allow_featured
     */
    public function set_allow_featured($allow_featured)
    {
        $this->set_prop('allow_featured', wc_string_to_bool($allow_featured));
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function get_type()
    {
        return 'listing';
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function get_reviews_allowed($context = 'view')
    {
        return false;
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function get_sold_individually($context = 'view')
    {
        return true;
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function get_virtual($context = 'view')
    {
        return true;
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function get_price($context = 'view')
    {
        $price = parent::get_price($context);

        if (! $price) {
            $price = $this->get_prop('listing_price', $context);
        }

        return apply_filters('woocommerce_get_price', $price, $this);
    }

    /**
     * @see   get_price.
     *
     * @since 1.0.0
     */
    public function get_listing_price($context = 'view')
    {
        return $this->get_price($context);
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function get_regular_price($context = 'view')
    {
        return apply_filters(
            'woocommerce_get_regular_price',
            $this->get_prop('listing_regular_price', $context),
            $this
        );
    }

    /**
     * @see   get_regular_price.
     *
     * @since 1.0.0
     */
    public function get_listing_regular_price($context = 'view')
    {
        return $this->get_regular_price($context);
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function get_sale_price($context = 'view')
    {
        return apply_filters(
            'woocommerce_get_sale_price',
            $this->get_prop('listing_sale_price', $context),
            $this
        );
    }

    /**
     * @see   get_sale_price.
     *
     * @since 1.0.0
     */
    public function get_listing_sale_price($context = 'view')
    {
        return $this->get_sale_price($context);
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function get_date_on_sale_from($context = 'view')
    {
        return apply_filters(
            'woocommerce_get_on_sale_from',
            $this->get_prop('listing_on_sale_from', $context),
            $this
        );
    }

    /**
     * @see   get_date_on_sale_from.
     *
     * @since 1.0.0
     */
    public function get_listing_date_on_sale_from($context = 'view')
    {
        return $this->get_date_on_sale_from($context);
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function get_date_on_sale_to($context = 'view')
    {
        return apply_filters(
            'woocommerce_get_on_sale_to',
            $this->get_prop('listing_on_sale_to', $context),
            $this
        );
    }

    /**
     * @see   get_date_on_sale_to.
     *
     * @since 1.0.0
     */
    public function get_listing_date_on_sale_to($context = 'view')
    {
        return $this->get_date_on_sale_to($context);
    }

    /**
     * Set the props by the POST data
     *
     * @since  1.0.0
     *
     * @return mixed Whatever the set_props returns
     */
    public function clean_and_set_props_by_input()
    {
        if ('woocommerce_admin_process_product_object' === current_action()) :
            $allowed = array_filter(array_keys($this->listing_product_data), function ($key) {
                // @codingStandardsIgnoreStart
                return isset($_POST["_{$key}"]);
                // @codingStandardsIgnoreEnd
            });

            if ($allowed) :
                // @codingStandardsIgnoreStart
                return $this->set_props(array(
                    'listing_regular_price' => wc_clean(
                        F\filterInput($_POST, '_listing_regular_price', FILTER_SANITIZE_STRING)
                    ),
                    'listing_sale_price'    => wc_clean(
                        F\filterInput($_POST, '_listing_sale_price', FILTER_SANITIZE_STRING)
                    ),
                ));
                // @codingStandardsIgnoreEnd
            endif;
        endif;
    }
}
