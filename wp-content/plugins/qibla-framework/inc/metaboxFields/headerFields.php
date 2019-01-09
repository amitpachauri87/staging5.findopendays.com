<?php

use \QiblaFramework\Functions as F;
use \QiblaFramework\Form\Factories\FieldFactory;
use \QiblaFramework\ListingsContext\Types;

/**
 * Header Meta-box Fields
 *
 * @author  guido scialfa <dev@guidoscialfa.com>
 *
 * @license GPL 2
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

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();
// Context.
$types = new Types();

/**
 * Filter Header Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the header meta-box fields.
 */
return apply_filters('qibla_mb_inc_header_fields', array(
    /**
     * Header Skin
     *
     * @since 1.0.0
     */
    'qibla_mb_header_skin:radio'        => $fieldFactory->base(array(
        'type'        => 'radio',
        'name'        => 'qibla_mb_header_skin',
        'value'       => F\getPostMeta(
            '_qibla_mb_header_skin',
            ($types->isListingsType(get_post_type()) ? 'transparent' : 'light')
        ),
        'label'       => esc_html__('Header Skin', 'qibla-framework'),
        'description' => esc_html__('The skin for the header to use.', 'qibla-framework'),
        'display'     => array($this, 'displayField'),
        'options'     => array(
            'transparent' => esc_html__('Transparent', 'qibla-framework'),
            'light'       => esc_html__('Light', 'qibla-framework'),
        ),
    )),

    /**
     * Sub Title
     *
     * @since 1.0.0
     */
    'qibla_mb_sub_title:text'           => $fieldFactory->base(array(
        'type'        => 'text',
        'name'        => 'qibla_mb_sub_title',
        'attrs'       => array(
            'value' => F\getPostMeta('_qibla_mb_sub_title'),
            'class' => array('widefat'),
        ),
        'label'       => esc_html__('Sub Title', 'qibla-framework'),
        'description' => esc_html__('The sub-title for this listing item.', 'qibla-framework'),
        'display'     => array($this, 'displayField'),
    )),

    /**
     * Hide Breadcrumb
     *
     * @since 1.5.0
     */
    'qibla_mb_hide_breadcrumb:checkbox' => $fieldFactory->base(array(
        'type'        => 'checkbox',
        'name'        => 'qibla_mb_hide_breadcrumb',
        'style'       => 'toggler',
        'label'       => esc_html__('Disable Breadcrumb', 'qibla-framework'),
        'description' => esc_html__('This will remove the breadcrumb within the page.', 'qibla-framework'),
        'value'       => F\getPostMeta('_qibla_mb_hide_breadcrumb', 'off'),
        'display'     => array($this, 'displayField'),
    )),
));
