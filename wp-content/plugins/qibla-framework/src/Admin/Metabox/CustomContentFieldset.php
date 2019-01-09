<?php
/**
 * CustomContentFieldset
 *
 * @since      2.3.0
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

namespace QiblaFramework\Admin\Metabox;

use QiblaFramework\Form\Fieldsets\BaseFieldset;
use QiblaFramework\Plugin;

/**
 * Class CustomContentFieldset
 *
 * @since  2.3.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class CustomContentFieldset extends BaseFieldset
{
    /**
     * ProductFieldset constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct(include Plugin::getPluginDirPath('/inc/metaboxFields/customContentFieldsets.php'), array(
            'form'            => 'post',
            'id'              => 'listing_custom_code',
            'name'            => 'header',
            'legend'          => esc_html__('Custom Code', 'qibla-framework'),
            'container_class' => array('dl-metabox-fieldset'),
            'icon_class'      => 'la la-edit',
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
    public static function hookFieldsets($fieldsets)
    {
        if (! is_plugin_active('qibla-woocommerce-listings/index.php') &&
            ! is_plugin_active('woocommerce-bookings/woocommerce-bookings.php.php') &&
            'listings' === get_post_type()
        ) {
            $fieldsets[] = new self;
        }

        return $fieldsets;
    }
}
