<?php
namespace QiblaListings\Package;

/**
 * Package Restriction List
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaListings\Front\ListingForm
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
 * Class PackageRestrictionsList
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class PackageRestrictionsList implements \ArrayAccess
{
    /**
     * Restrictions List
     *
     * @since  1.0.0
     *
     * @var array The list of the restrictions
     */
    protected $restrictions;

    /**
     * Restriction Key Prefix
     *
     * @since  1.0.0
     *
     * @var string The prefix for the restrictions meta
     */
    protected static $restrictionKeyPrefix = 'qibla_listings_mb_restriction_';

    /**
     * Restrictions Keys
     *
     * @since  1.0.0
     *
     * @var array The restriction's list
     */
    protected static $restrictionsKeys = array(
        // Additional Fields.
        'allow_featured',
        'allow_open_hours',
        'allow_business_email',
        'allow_business_phone',
        'allow_website_url',

        // Base Fields.
        'allow_sub_title',

        // Gallery.
        'allow_gallery',

        // Socials.
        'allow_social_facebook',
        'allow_social_twitter',
        'allow_social_instagram',
        'allow_social_linkedin',
        'allow_social_tripadvisor',
    );

    /**
     * Build Restrictions By Post
     *
     * @since  1.0.0
     *
     * @param \WP_Post $post The post from which retrieve the restrictions values.
     *
     * @return array The restrictions values
     */
    protected function buildRestrictionsByPost(\WP_Post $post)
    {
        $restrictions = array();
        foreach (static::$restrictionsKeys as $key) {
            $restrictions[$key] = \QiblaFramework\Functions\getPostMeta(
                '_' . static::$restrictionKeyPrefix . $key,
                'on',
                $post->ID
            );
        }

        return $restrictions;
    }

    /**
     * FormRestrictions constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Post $post The post for the restrictions data.
     */
    public function __construct(\WP_Post $post)
    {
        $this->restrictions = $this->buildRestrictionsByPost($post);
    }

    /**
     * Get Restrictions
     *
     * @since  1.0.0
     *
     * @return array The list of the restrictions
     */
    public function getRestrictions()
    {
        return $this->restrictions;
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return isset($this->restrictions[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->restrictions[$offset];
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        $this->restrictions[$offset] = $value;
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        unset($this->restrictions[$offset]);
    }
}
