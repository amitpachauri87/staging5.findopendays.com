<?php
/**
 * ListingProduct
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
use AppMapEvents\Plugin;

/**
 * Class ListingProduct
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package AppMapEvents\Admin\Metabox
 */
class PackageOptions extends AbstractMetaboxFieldset
{
    /**
     * ListingProduct constructor
     *
     * @inheritdoc
     */
    public function __construct(array $args = array())
    {
        parent::__construct(wp_parse_args($args, array(
            'id'       => 'listing_product',
            'title'    => esc_html__('Package Options', 'qibla-events'),
            'callback' => array($this, 'callBack'),
            'screen'   => array('event_package'),
            'context'  => 'normal',
            'priority' => 'high',
        )), array(
            'dlui-metabox-tabs',
        ));

        parent::setFieldsets(array(
            /**
             * Options Fields
             *
             * @since 1.0.0
             */
            new BaseFieldset(
                include Plugin::getPluginDirPath('/inc/metaboxFields/packageFields/baseFields.php'),
                array(
                    'form'            => 'post',
                    'id'              => 'event_package',
                    'name'            => 'event_package',
                    'legend'          => esc_html__('Base Options', 'qibla-events'),
                    'container_class' => array('dl-metabox-fieldset'),
                    'icon_class'      => 'la la-gears',
                )
            ),

            /**
             * Product Fields
             *
             * @since 1.0.0
             */
            new BaseFieldset(
                include Plugin::getPluginDirPath('/inc/metaboxFields/packageFields/productFields.php'),
                array(
                    'form'            => 'post',
                    'id'              => 'listing_product_package',
                    'name'            => 'listing_product_package',
                    'legend'          => esc_html__('Product', 'qibla-events'),
                    'container_class' => array('dl-metabox-fieldset'),
                    'icon_class'      => 'la la-shopping-cart',
                )
            ),
        ));
    }
}
