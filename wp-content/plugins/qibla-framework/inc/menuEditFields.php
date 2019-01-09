<?php

/**
 * Menu Edit Fields
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

use QiblaFramework\Form\Factories\FieldFactory;
use QiblaFramework\Functions as F;

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

/**
 * Filter Edit Menu Item Fields
 *
 * @since 1.5.0
 *
 * @param array $array The list of the additional menu edit items
 */
return apply_filters('qibla_nb_additional_fields', array(
    'nav_main' => array(
        /**
         * Button Style
         *
         * @since 1.5.0
         */
        'qibla_nb_item_style:select' => $fieldFactory->base(array(
            'type'    => 'select',
            'name'    => "qibla_nb_item_style_{$itemID}",
            'label'   => esc_html__('Choose the style of the item', 'qibla-framework'),
            'options' => array(
                'none'   => esc_html__('Text', 'qibla-framework'),
                'button' => esc_html__('Button', 'qibla-framework'),
            ),
            'value'   => F\getPostMeta('_qibla_nb_item_style', 'none', $itemID),
        )),

        /**
         * Logged In Out Item
         *
         * Use the select because a checkbox will need to store the meta for every item even if not necessary.
         * That will increase the number of the database queries and storing useless data.
         *
         * @since 1.5.0
         */
        'qibla_nb_item_user_loggedinout:select' => $fieldFactory->base(array(
            'type'           => 'select',
            'name'           => "qibla_nb_item_user_loggedinout_{$itemID}",
            'label'          => esc_html__('Use this item as logged in user menu.', 'qibla-framework'),
            'label_position' => 'after',
            'exclude_none'   => true,
            'options'        => array(
                'off' => esc_html__('No', 'qibla-framework'),
                'on'  => esc_html__('Yes', 'qibla-framework'),
            ),
            'value'   => F\getPostMeta('_qibla_nb_item_user_loggedinout', 'off', $itemID),
        )),
    ),
));
