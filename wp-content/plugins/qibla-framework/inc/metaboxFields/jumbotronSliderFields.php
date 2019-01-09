<?php
/**
 * Slider Fields
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

use \QiblaFramework\Functions as F;
use \QiblaFramework\Form\Factories\FieldFactory;
use \QiblaFramework\Slider\RevolutionSlider;

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();
// Initialize Fields.
$fields = array();

// Only if revolution slider plugin is enabled.
if (RevolutionSlider::sliderIsActive()) {
    // Get Slider.
    $slider = new RevolutionSlider(new RevSliderSlider);
    // Set Fields.
    $fields = array(
        /**
         * Slider
         *
         * @since 1.6.0
         */
        'qibla_mb_jumbotron_slider:select' => $fieldFactory->base(array(
            'type'        => 'select',
            'select2'     => true,
            'name'        => 'qibla_mb_jumbotron_slider',
            'value'       => F\getPostMeta('_qibla_mb_jumbotron_slider', 'none'),
            'options'     => $slider->getSliderList(),
            'label'       => esc_html__('Choose a slider', 'qibla-framework'),
            'description' => esc_html__('The slider will override the hero image option.', 'qibla-framework'),
            'display'     => array($this, 'displayField'),
            'attrs'       => array(
                'class' => array('widefat'),
            ),
        )),
    );
}

/**
 * Filter Slider Fields
 *
 * @since 1.6.0
 *
 * @param array $array The list of the jumbo-tron meta-box fields.
 */
return apply_filters('qibla_mb_inc_jumbotron_slider_fields', $fields);
