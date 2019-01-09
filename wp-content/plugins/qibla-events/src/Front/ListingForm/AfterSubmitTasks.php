<?php
/**
 * After Submit Tasks
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

namespace AppMapEvents\Front\ListingForm;

use QiblaFramework\Functions as F;
use QiblaFramework\Request\Response;
use AppMapEvents\Listing\Expire\ExpirationByDate;
use QiblaFramework\Functions as Ffw;

/**
 * Class AfterSubmitTasks
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package AppMapEvents\Front\ListingForm
 */
class AfterSubmitTasks
{
    /**
     * The Package
     *
     * Package related to the newly post
     *
     * @since  1.0.0
     *
     * @var \WP_Post The post instance
     */
    protected $package;

    /**
     * The Post
     *
     * The newly post.
     *
     * @since  1.0.0
     *
     * @var \WP_Post The post instance
     */
    protected $post;

    /**
     * The Current lang
     *
     * @since  2.1.0
     *
     * @var string The lang
     */
    protected $lang;

    /**
     * Internal Meta Keys
     *
     * @since  1.0.0
     *
     * @var array The list of the meta keys
     */
    protected static $metaKeys = array(
        'expire'             => '_qibla_listings_mb_restriction_duration',
        'listing_product'    => '_qibla_mb_listings_products',
        'package_related'    => '_qibla_mb_listing_package_related',
        'listing_expiration' => '_qibla_mb_listing_expiration',
    );

    /**
     * PostListingSubmitTasks constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Post $package The post instance
     * @param \WP_Post $post    The post instance
     * @param string   $lang    The current lang
     */
    public function __construct(\WP_Post $package, \WP_Post $post, $lang = '')
    {
        $this->package = $package;
        $this->post    = $post;
        $this->lang    = $lang;
    }

    /**
     * Set the Post Expire Date
     *
     * @since  1.0.0
     *
     * @return int Whatever the add_post_meta returns or 0 if the product is in the cart
     */
    public function setPostExpireDate()
    {
        $expire = F\getPostMeta(self::$metaKeys['expire'], ExpirationByDate::EXPIRE_UNLIMITED, $this->package);

        return add_post_meta($this->post->ID, static::$metaKeys['listing_expiration'], intval($expire), true);
    }

    /**
     * Add Product To Cart
     *
     * @since  1.0.0
     *
     * @return bool True if product has been stored in cart, false otherwise.
     */
    public function addProductToCart()
    {
        // Build the product.
        $meta    = F\getPostMeta(self::$metaKeys['listing_product'], '', $this->package->ID);
        $post    = F\getPostByName($meta, 'product');
        $product = wc_get_product($post);

        $cart = WC()->cart;
        // @fixme WC 3.3.x Use $cart = WC()->cart->get_cart(); See woocommerce/includes/class-wc-cart.php::get_cart()
        $cart->get_cart_from_session();

        // Clean the cart.
        $cart->empty_cart();

        // The product is optional.
        if (! $product instanceof \WC_Product || 0 === intval($product->get_price())) {
            return -1;
        }

        try {
            // Add the product to the cart.
            $response = (bool)$cart->add_to_cart($product->get_id(), 1, 0, array(), array(
                'listing_id' => $this->post->ID,
            ));
        } catch (\Exception $e) {
            $response = false;
        }

        return $response;
    }

    /**
     * Set Package Related Post
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function setPackageRelatedPost()
    {
        // Always set as unique.
        if (! metadata_exists('post', $this->post->ID, $this->package->post_name)) {
            add_post_meta($this->post->ID, self::$metaKeys['package_related'], $this->package->post_name, true);
        } else {
            update_post_meta($this->post->ID, self::$metaKeys['package_related'], $this->package->post_name);
        }
    }

    /**
     * Get Response
     *
     * When the cart is empty don't redirect the user to the checkout page.
     * Most probably the Submit is a free listing submit, means no payment is needed.
     * So, in this case redirect the user to the current page.
     *
     * @since  1.0.0
     *
     * @return Response The instance of the response tasks
     */
    public function getResponse()
    {
        $cart = WC()->cart;
        // @fixme WC 3.3.x Use $cart = WC()->cart->get_cart(); See woocommerce/includes/class-wc-cart.php::get_cart()
        $cart->get_cart_from_session();

        // Is WpMl active?
        $isWpMl = Ffw\isWpMlActive();
        global $sitepress;

        // Set the right location.
        if ($cart->is_empty()) {
            $location = get_permalink($this->post->ID);
            if($isWpMl) {
                // convert url location from current lang.
                $location = $sitepress->convert_url(get_permalink($this->post->ID), $this->lang);
            }
        } else {
            $location = wc_get_checkout_url();
            if($isWpMl) {
                // convert url location from current lang.
                $location = $sitepress->convert_url(wc_get_checkout_url(), $this->lang);
            }
        }

        // Sanitize and Validate the redirect.
        $location = wp_validate_redirect(wp_sanitize_redirect($location));
        $response = new Response(200, esc_html__('Your Post has been created succesfully.', 'qibla-events'), array(
            'location' => $location,
        ));

        return $response;
    }
}
