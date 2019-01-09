<?php
/**
 * Listing Setting Fields
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

use QiblaFramework\Functions as Fw;
use QiblaFramework\Form\Factories\FieldFactory;

$fieldFactory = new FieldFactory();

/**
 * Filter Listings Extra Fields
 *
 * @since 1.0.0
 *
 * @param array $args The fields list
 */
return apply_filters('qibla_opt_inc_listings_fields_extra', array(
    /**
     * Listing Expired Email
     *
     * @since 1.0.0
     */
    'qibla_opt-listings-expired_listing_email:wysiwyg' => $fieldFactory->table(array(
        'type'            => 'wysiwyg',
        'name'            => 'qibla_opt-listings-expired_listing_email',
        'label'           => esc_html__('Expired listing email', 'qibla-framework'),
        'description'     => esc_html__(
            "You can use the following placeholder:\n{{username}} where you want to show the username\n{{listing_name}} where show the listing post title",
            'qibla-framework'
        ),
        'value'           => Fw\getThemeOption('listings', 'expired_listing_email', true),
        'editor_settings' => array(
            'tinymce'       => true,
            'teeny'         => true,
            'media_buttons' => false,
            'quicktags'     => false,
            'textarea_rows' => 8,
            'paste_as_text' => true,
        ),
    )),
));
