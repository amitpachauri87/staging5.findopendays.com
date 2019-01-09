<?php
/**
 * Listings Package Post Type
 *
 * @since      1.0.0
 * @package    QiblaListings\PostType
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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

namespace QiblaListings\PostType;

/**
 * Class ListingPackage
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingPackage extends AbstractPostType
{
    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        parent::__construct(
            'listing_package',
            esc_html__('Listing Package', 'qibla-listings'),
            esc_html__('Packages', 'qibla-listings'),
            array(
                // Filter the location since other plugins may hide this or this may hide other menu items.
                'menu_position' => apply_filters('qibla_post_type_menu_position_listing_package', 3),
                'menu_icon'     => 'dashicons-store',
                'has_archive'   => false,
                'supports'      => array('title'),
                'show_in_menu'  => false,
                'rewrite'       => array(
                    'slug'       => 'package',
                    'with_front' => false,
                ),
            )
        );
    }
}
