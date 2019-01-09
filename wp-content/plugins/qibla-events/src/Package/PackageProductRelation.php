<?php
/**
 * Package Product Relation
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

namespace AppMapEvents\Package;

use QiblaFramework\Admin\Metabox\Store;
use QiblaFramework\Functions as Fw;
use AppMapEvents\Debug;

/**
 * Class PackageProductRelation
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class PackageProductRelation
{
    /**
     * Product Meta Key
     *
     * @since  1.0.0
     */
    const PROD_META_KEY = '_qibla_mb_reflection_listings_products';

    /**
     * Post
     *
     * @since  1.0.0
     *
     * @var \WP_Post The package post instance
     */
    protected $post;

    /**
     * Product
     *
     * @since  1.0.0
     *
     * @var \WC_Product The product instance
     */
    protected $product;

    /**
     * PackageProductRelation constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Post    $post    The package post instance.
     * @param \WC_Product $product The product instance.
     */
    public function __construct(\WP_Post $post, \WC_Product $product)
    {
        $this->post    = $post;
        $this->product = $product;
    }

    /**
     * Remove Relation
     *
     * @since  1.0.0
     *
     * @return bool True on success false otherwise. May be the meta doesn't exists.
     */
    public function deleteRelation()
    {
        global $wpdb;

        // Make a query to get the meta_id.
        $row = $wpdb->get_row($wpdb->prepare(
            "
            SELECT * 
            FROM $wpdb->postmeta
            WHERE meta_key = '%s' 
            AND meta_value = %d 
            AND post_id != %d
            ",
            static::PROD_META_KEY,
            $this->post->ID,
            $this->product->get_id()
        ), 0);

        // Delete the meta data.
        return $row ? delete_metadata_by_mid('post', intval($row->meta_id)) : false;
    }

    /**
     * Update the Relation
     *
     * @since  1.0.0
     *
     * @return mixed Whatever the update_post_meta returns
     */
    public function updateRelation()
    {
        if ($this->product instanceof \WC_Product) {
            // Update the post meta.
            return update_post_meta($this->product->get_id(), static::PROD_META_KEY, $this->post->ID);
        }
    }

    /**
     * Update Product meta on post meta store
     *
     * We may want to not set any product related with the package.
     * To do that we need to get the old value from the post meta and in case just remove the relation.
     *
     * @since  1.0.0
     *
     * @throws \LogicException In case no meta value has been provided.
     *
     * @param string   $metakey         The metakey from which retrieve the product.
     * @param mixed    $value           The current value of the meta.
     * @param \WP_Post $post            The current saving post.
     * @param bool     $update          If the post is updating or creating.
     * @param mixed    $oldValue        The old meta value.
     * @param Store    $metaBoxInstance The metabox store instance.
     */
    public static function updateRelatedProductMetaWithPackageIDFilter(
        $metakey,
        $value,
        $post,
        $update,
        $oldValue,
        Store $metaBoxInstance
    ) {
        if ('qibla_mb_listings_products' === ltrim($metakey, '_')) :
            try {
                // Retrieve the current product name related with the package.
                $meta = Fw\getPostMeta("_{$metakey}", null, $post);

                // If the old value and new value are the same, don't do anything.
                if ($meta === $oldValue) {
                    return;
                }

                // Update the meta with the correct value.
                // Depending on if we are changing or removing the relation.
                // Old value means, remove the relation only.
                if ('none' === $meta) {
                    $meta = $oldValue;
                }

                if (! $oldValue && ! $meta) {
                    throw new \LogicException(
                        'No way to change the relation for the package post. No valid data provided.'
                    );
                }

                // Get the product by the post meta of the package post.
                $product = Fw\getPostByName(sanitize_title($meta), 'product');
                $product = wc_get_product($product);

                // Build the relation instance.
                $instance = new static($post, $product);
                // Delete the Relation.
                // Even if we are updating it with a new value, the old value must be removed.
                $instance->deleteRelation();
                // So, if the old value is different than the new product name,
                // we can update the relation, otherwise the only action needed was to remove the relation.
                if ($meta !== $oldValue) {
                    $instance->updateRelation();
                }
            } catch (\Exception $e) {
                $debugInstance = new Debug\Exception($e);
                'dev' === QB_ENV && $debugInstance->display();
            }//end try
        endif;
    }
}
