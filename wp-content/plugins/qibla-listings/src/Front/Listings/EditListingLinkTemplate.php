<?php
/**
 * EditListingLink
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

namespace QiblaListings\Front\Listings;

use QiblaFramework\Functions as F;
use QiblaFramework\ListingsContext\Context;
use QiblaListings\TemplateEngine\Engine;
use QiblaListings\Package\Package;

/**
 * Class EditListingLink
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\Front\Listing
 */
class EditListingLinkTemplate
{
    /**
     * Post
     *
     * The listing post
     *
     * @since  1.0.0
     *
     * @var \WP_Post The listing post instance
     */
    protected $post;

    /**
     * Get Package Post from the Listing
     *
     * @since  1.0.0
     *
     * @return \WP_Post|string The package post instance if the post have the package related or 'none' if not.
     */
    protected function getPackageFromPost()
    {
        // Default to none, no package associated.
        $meta = F\getPostMeta('_qibla_mb_listing_package_related', 'none', $this->post);

        $postTypePackage = 'listing_package';
        if ('listings' !== $this->post->post_type) {
            /**
             * Filter post type package for other type of package.
             *
             * @since 2.3.0
             */
            $postTypePackage = apply_filters('qibla_listings_post_type_package', $postTypePackage, $this->post->post_type);
        }

        if ('none' !== $meta) {
            $meta = F\getPostByName(sanitize_title($meta), $postTypePackage);
        }

        return $meta;
    }

    /**
     * EditListingLinkTemplate constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Post $post The listing post instance.
     */
    public function __construct(\WP_Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get Data
     *
     * @since  1.0.0
     *
     * @return \stdClass The data to use within the template
     */
    public function getData()
    {
        // Initialize Data.
        $data = new \stdClass();

        // Initialize the edit link.
        // If the listing post doesn't have an associated package the link will be empty.
        $editLink = '';

        // Get the package post.
        $meta = $this->getPackageFromPost();

        if ($meta instanceof \WP_Post) {
            // Get the Package Instance.
            $package  = new Package($meta);
            $editLink = $package->getListingEditFormPermalink($this->post);
        }

        // Set the edit link, to redirect to the form.
        $data->editLink = $editLink;
        // Set the edit link label.
        /**
         * Filter edit listing link label
         *
         * @since 2.3.0
         */
        $data->editLinkLabel = apply_filters('qibla_listings_edit_link_label',
            esc_html__('Edit this listing', 'qibla-listings'), $this->post->post_type);

        return $data;
    }

    /**
     * Template
     *
     * @since  1.0.0
     *
     * @param \stdClass $data The data to use within the template.
     *
     * @return void
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new Engine('edit_listing_post_link', $data, '/views/listings/editListingLink.php');
        $engine->render();
    }

    /**
     * Template Filter
     *
     * @since  1.0.0
     *
     * @return void
     */
    public static function templateFilter()
    {
        // Retrieve the post.
        $post = get_post();

        if (Context::isSingleListings() &&
            is_main_query() &&
            current_user_can('edit_listings', $post->ID)
        ) {
            if (wp_get_current_user()->ID === intval($post->post_author)) {
                $instance = new static($post);
                // Edit the listing on frontend only if the listing have a package associated.
                if ('none' !== $instance->getPackageFromPost()) {
                    $instance->tmpl($instance->getData());
                }
            }
        }
    }
}
