<?php

namespace QiblaWcListings\Admin\MetaboxFieldset;

use QiblaFramework\Form\Fieldsets\BaseFieldset;
use QiblaFramework\ListingsContext\Types;
use QiblaWcListings\Plugin;

/**
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaWcListings\Admin\MetaboxFieldset
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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
 * Class Product
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaWcListings\Admin\MetaboxFieldset
 */
class ProductFieldset extends BaseFieldset
{
    /**
     * ProductFieldset constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct(include Plugin::getPluginDirPath('/inc/metaboxFieldsets/productFieldsets.php'), array(
            'form'            => 'post',
            'id'              => 'listing_related_product',
            'name'            => 'header',
            'legend'          => esc_html__('Product', 'qibla-woocommerce-listings'),
            'container_class' => array('dl-metabox-fieldset'),
            'icon_class'      => 'la la-shopping-cart',
        ));
    }

    /**
     * Add the class to the list of the fieldsets
     *
     * @since  1.0.0
     *
     * @param array $fieldsets The field-sets instances list.
     *
     * @return array The filtered field-sets
     */
    public static function hookFieldsetsFramework($fieldsets)
    {
        $types = new Types();

        /**
         * Filter Product listing related
         *
         * @since ${SINCE}
         */
        if ('yes' === apply_filters('qibla_wc_listings_listing_related_product', 'yes', $types->types())) {
            $fieldsets[] = new self;
        }

        return $fieldsets;
    }
}
