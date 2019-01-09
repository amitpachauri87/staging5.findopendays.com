<?php
/**
 * ListingRestrictions
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

namespace QiblaListings\Admin\Metabox;

use QiblaFramework\Admin\Metabox\AbstractMetaboxFieldset;
use QiblaFramework\Form\Fieldsets\BaseFieldset;
use QiblaListings\Plugin;

/**
 * Class ListingRestrictions
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Admin\Metabox
 */
class ListingRestrictions extends AbstractMetaboxFieldset
{
    /**
     * ListingPackage constructor
     *
     * @inheritdoc
     */
    public function __construct(array $args = array())
    {
        parent::__construct(wp_parse_args($args, array(
            'id'       => 'listing_package',
            'title'    => esc_html__('Listing Restrictions', 'qibla-framework'),
            'callback' => array($this, 'callBack'),
            'screen'   => array('listing_package'),
            'context'  => 'normal',
            'priority' => 'high',
        )), array(
            'dlui-metabox-tabs',
        ));

        parent::setFieldsets(array(
            /**
             * Base Restrictions
             *
             * @since 1.0.0
             */
            new BaseFieldset(
                include Plugin::getPluginDirPath('/inc/metaboxFields/listingPackageRestrictions/baseFields.php'),
                array(
                    'form'            => 'post',
                    'id'              => 'listing_package_base_restrictions',
                    'name'            => 'listing_package_base_restrictions',
                    'legend'          => esc_html__('Base Restrictions', 'qibla-listings'),
                    'container_class' => array('dl-metabox-fieldset'),
                    'icon_class'      => 'la la-unlock-alt',
                )
            ),

            /**
             * Additional Info Restrictions
             *
             * @since 1.0.0
             */
            new BaseFieldset(
                include Plugin::getPluginDirPath('/inc/metaboxFields/listingPackageRestrictions/additionalInfoFields.php'),
                array(
                    'form'            => 'post',
                    'id'              => 'listing_package_additional_restrictions',
                    'name'            => 'listing_package_additional_restrictions',
                    'legend'          => esc_html__('Additional Info Restrictions', 'qibla-listings'),
                    'container_class' => array('dl-metabox-fieldset'),
                    'icon_class'      => 'la la-unlock-alt',
                )
            ),

            /**
             * Socials Restrictions
             *
             * @since 1.0.0
             */
            new BaseFieldset(
                include Plugin::getPluginDirPath('/inc/metaboxFields/listingPackageRestrictions/socialsFields.php'),
                array(
                    'form'            => 'post',
                    'id'              => 'listing_package_socials_restrictions',
                    'name'            => 'listing_package_socials_restrictions',
                    'legend'          => esc_html__('Socials Restrictions', 'qibla-listings'),
                    'container_class' => array('dl-metabox-fieldset'),
                    'icon_class'      => 'la la-unlock-alt',
                )
            ),

            /**
             * Media Restrictions
             *
             * @since 1.0.0
             */
            new BaseFieldset(
                include Plugin::getPluginDirPath('/inc/metaboxFields/listingPackageRestrictions/mediaFields.php'),
                array(
                    'form'            => 'post',
                    'id'              => 'listing_package_media_restrictions',
                    'name'            => 'listing_package_media_restrictions',
                    'legend'          => esc_html__('Media Restrictions', 'qibla-listings'),
                    'container_class' => array('dl-metabox-fieldset'),
                    'icon_class'      => 'la la-unlock-alt',
                )
            ),
        ));
    }
}
