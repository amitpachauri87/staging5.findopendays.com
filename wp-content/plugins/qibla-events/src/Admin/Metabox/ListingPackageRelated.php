<?php
/**
 * Package
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

namespace AppMapEvents\Admin\Metabox;

use QiblaFramework\Admin\Metabox\AbstractMetaboxFieldset;
use QiblaFramework\Form\Fieldsets\BaseFieldset;
use QiblaFramework\ListingsContext\Types;
use AppMapEvents\Plugin;

/**
 * Class Package
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package AppMapEvents\Admin\Metabox
 */
class ListingPackageRelated extends AbstractMetaboxFieldset
{
    /**
     * ListingPackage constructor
     *
     * @inheritdoc
     */
    public function __construct(array $args = array())
    {
        $types = new Types();

        parent::__construct(wp_parse_args($args, array(
            'id'       => 'listing_package_options',
            'title'    => esc_html__('Package Options', 'qibla-events'),
            'callback' => array($this, 'callBack'),
            /**
             * Filter listings package screen for exclude from particular types.
             *
             * @since 2.2.1
             */
            'screen'   => apply_filters('qibla_listing_package_options_screen', $types->types()),
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
                include Plugin::getPluginDirPath('/inc/metaboxFields/listingPackageRelated.php'),
                array(
                    'form'            => 'post',
                    'id'              => 'listing_package_related',
                    'name'            => 'listing_package_related',
                    'legend'          => esc_html__('Package', 'qibla-events'),
                    'container_class' => array('dl-metabox-fieldset'),
                    'icon_class'      => 'la la-archive',
                )
            ),
        ));
    }
}
