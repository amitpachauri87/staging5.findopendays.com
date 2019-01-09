<?php

namespace QiblaWcListings\Front;

use QiblaFramework\ListingsContext\Context;
use QiblaFramework\ListingsContext\Types;
use QiblaWcListings\Front\Element\ElementFacade;
use \QiblaFramework\Functions as Fw;

/**
 * Listing Related Product Loop Hook
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaWcListings\Front
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
 * Class ListingRelatedProductLoopHook
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaWcListings\Front
 */
class ListingRelatedProductLoopHook
{
    /**
     * Post
     *
     * @since 2.0.0
     *
     * @var \WP_Post The post instance
     */
    private $post;

    /**
     * ListingRelatedProductLoopHook constructor
     *
     * @since 2.0.0
     *
     * @param \WP_Post $post The post instance
     */
    public function __construct(\WP_Post $post)
    {
        $this->post = $post;
    }

    /**
     * Featured Scope Class
     *
     * @since  1.0.0
     *
     * @param string $upxscope The scope prefix. Default 'upx'.
     * @param string $element  The current element of the scope.
     * @param string $block    The custom block scope. Default empty.
     * @param string $scope    The default scope prefix. Default 'upx'.
     * @param string $attr     The attribute for which scope we are filtering the string.
     *
     * @return string The list of the header class filtered
     */
    public static function scopeClassFilter($upxscope, $element, $block, $scope, $attr)
    {
        $post = get_post();

        if (! $post) {
            return $upxscope;
        }

        $instance = new static($post);

        if ('class' !== $attr || 'article' !== $block || '' !== $element) {
            return $upxscope;
        }

        if ($instance->meetCondition()) {
            // Apply the modifier.
            $upxscope .= " {$scope}{$block}--has-product";
            // Add the hooks that will be performed later.
            add_action(
                'qibla_listings_loop_entry_content',
                '\\QiblaWcListings\\Front\\ListingRelatedProductLoopHook::addProductPriceInArticleLoop',
                10
            );
        }

        // Remove after done.
        remove_filter(
            'qibla_scope_attribute',
            '\\QiblaWcListings\\Front\\ListingRelatedProductLoopHook::scopeClassFilter',
            20
        );
        remove_filter(
            'qibla_fw_scope_attribute',
            '\\QiblaWcListings\\Front\\ListingRelatedProductLoopHook::scopeClassFilter',
            20
        );

        return $upxscope;
    }

    /**
     * Add product price to the article listing
     *
     * @since  1.0.0
     *
     * @param \WP_Post $post The post instance.
     *
     * @return void
     */
    public static function addProductPriceInArticleLoop(\WP_Post $post)
    {
        $instance = new static($post);

        if ($instance->meetCondition()) {
            try {
                $facadeInstance = new ElementFacade($post);
                echo wp_kses_post($facadeInstance->getPriceHtml());
            } catch (\Exception $e) {
                // @todo Add Debug
                // @todo Need a destruct?
            }

            // Other posts may not need to show the product price.
            remove_action(
                'qibla_listings_loop_entry_content',
                '\\QiblaWcListings\\Front\\ListingRelatedProductLoopHook::addProductPriceInArticleLoop',
                10
            );
        }
    }

    /**
     * Meet Condition
     *
     * @since 1.0.0
     *
     * @return bool True if allowed, false otherwise
     */
    private function meetCondition()
    {
        $types = new Types();

        return (in_the_loop() &&
                $types->isListingsType($this->post->post_type) &&
                ! Context::isSingleListings() &&
                $this->hasRelatedProduct());
    }

    /**
     * If the current listing has product associated
     *
     * @since  1.0.0
     *
     * @return bool If has product associated, false otherwise
     */
    private function hasRelatedProduct()
    {
        // @todo Patch to remove in 1.2.1 See Bitbucket Issue #5.
        $meta = Fw\getPostMeta('_qibla_mb_wc_listings_products', 'none', $this->post->ID);
        if ('none' !== $meta && (
                ! Fw\getPostByName($meta, 'product') instanceof \WP_Post || ! class_exists('WC_Bookings')
            )
        ) {
            update_post_meta($this->post->ID, '_qibla_mb_wc_listings_products', 'none');
            $meta = 'none';
        }

        return ('none' !== $meta);
    }
}
