<?php

use \QiblaFramework\Functions as F;
use \QiblaFramework\Form\Factories\FieldFactory;

/**
 * Additional Listings Info Meta-box Fields
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

$opHours = \QiblaFramework\Template\OpeningHours::getOpeningHoursList();

/**
 * Filter Listings Additional Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the additional meta-box fields.
 */
$lists = array(
    /**
     * Featured Hours
     *
     * @since 1.0.0
     */
    'qibla_mb_is_featured:checkbox' => $fieldFactory->base(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_mb_is_featured',
        'value'       => F\getPostMeta('_qibla_mb_is_featured'),
        'label'       => esc_html__('Featured', 'qibla-framework'),
        'description' => esc_html__('Make this listing featured.', 'qibla-framework'),
        'display'     => array($this, 'displayField'),
    )),

//    /**
//     * Open Hours
//     *
//     * @since 1.0.0
//     */
//    'qibla_mb_open_hours:textarea'  => $fieldFactory->base(array(
//        'type'        => 'textarea',
//        'name'        => 'qibla_mb_open_hours',
//        'attrs'       => array(
//            'value'       => F\getPostMeta('_qibla_mb_open_hours'),
//            'placeholder' => __('Es. Mon-Fry 8:30-13:30 15:00-19:30'),
//            'class'       => array('widefat'),
//            'rows'        => 10,
//        ),
//        'label'       => esc_html__('Open Hours', 'qibla-framework'),
//        'description' => esc_html__('Add your local business hours', 'qibla-framework'),
//        'display'     => array($this, 'displayField'),
//    )),

    /**
     * Business Email
     *
     * @since 1.0.0
     */
    'qibla_mb_business_email:text'  => $fieldFactory->base(array(
        'type'        => 'text',
        'name'        => 'qibla_mb_business_email',
        'attrs'       => array(
            'value' => F\getPostMeta('_qibla_mb_business_email'),
            'class' => array('widefat'),
        ),
        'label'       => esc_html__('Email', 'qibla-framework'),
        'description' => esc_html__('Add your local business email', 'qibla-framework'),
        'display'     => array($this, 'displayField'),
    )),

    /**
     * Business Phone
     *
     * @since 1.0.0
     */
    'qibla_mb_business_phone:tel'   => $fieldFactory->base(array(
        'type'        => 'tel',
        'name'        => 'qibla_mb_business_phone',
        'attrs'       => array(
            'value' => F\getPostMeta('_qibla_mb_business_phone'),
            'class' => array('widefat'),
        ),
        'label'       => esc_html__('Phone', 'qibla-framework'),
        'description' => esc_html__('Add your local business phone', 'qibla-framework'),
        'display'     => array($this, 'displayField'),
    )),

    /**
     * Site Url
     *
     * @since 1.0.0
     */
    'qibla_mb_site_url:url'         => $fieldFactory->base(array(
        'type'        => 'url',
        'name'        => 'qibla_mb_site_url',
        'attrs'       => array(
            'value' => F\getPostMeta('_qibla_mb_site_url'),
            'class' => array('widefat'),
        ),
        'label'       => esc_html__('WebSite Url', 'qibla-framework'),
        'description' => esc_html__('Add your web site url', 'qibla-framework'),
        'display'     => array($this, 'displayField'),
    )),
);

/**
 * Opening Hours - Plugin
 *
 * @since 1.0.0
 */
if (\QiblaFramework\Template\OpeningHours::checkDependencies()) {
    $lists = F\arrayInsertInPos(array(
        'qibla_mb_opening_hours:select' => $fieldFactory->base(array(
            'type'        => 'select',
            'name'        => 'qibla_mb_opening_hours',
            'options'     => (array)$opHours,
            'save_always' => true,
            'std'         => '',
            'attrs'       => array(
                'value' => F\getPostMeta('_qibla_mb_opening_hours'),
                'class' => array('widefat'),
            ),
            'label'       => esc_html__('Opening Hours', 'qibla-framework'),
            'description' => esc_html__('Add your local business hours (visible in the sidebar)', 'qibla-framework'),
            'display'     => array($this, 'displayField'),
        )),
    ), $lists, 1);
} else {
    $lists = F\arrayInsertInPos(array(
        'qibla_mb_open_hours:textarea'  => $fieldFactory->base(array(
            'type'        => 'textarea',
            'name'        => 'qibla_mb_open_hours',
            'attrs'       => array(
                'value'       => F\getPostMeta('_qibla_mb_open_hours'),
                'placeholder' => __('Es. Mon-Fry 8:30-13:30 15:00-19:30'),
                'class'       => array('widefat'),
                'rows'        => 10,
            ),
            'label'       => esc_html__('Open Hours', 'qibla-framework'),
            'description' => esc_html__('Add your local business hours', 'qibla-framework'),
            'display'     => array($this, 'displayField'),
        )),
    ), $lists, 1);
}

return apply_filters('qibla_mb_inc_listings_additional_fields', $lists);
