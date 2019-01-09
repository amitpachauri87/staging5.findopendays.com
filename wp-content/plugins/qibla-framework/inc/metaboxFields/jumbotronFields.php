<?php
use \QiblaFramework\Functions as F;
use \QiblaFramework\Form\Factories\FieldFactory;

/**
 * Jumbo-tron Meta-box Fields
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

/**
 * Filter Jumbo-tron Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the jumbo-tron meta-box fields.
 */
return apply_filters('qibla_mb_inc_jumbotron_fields', array(
    /**
     * Jumbo-Tron Background Color
     *
     * @since 1.0.0
     */
    'qibla_mb_jumbotron_background_color:color_picker' => $fieldFactory->base(array(
        'type'        => 'color_picker',
        'name'        => 'qibla_mb_jumbotron_background_color',
        'attrs'       => array(
            'value' => F\getPostMeta('_qibla_mb_jumbotron_background_color', 'rgba(255, 255, 255, .3)'),
        ),
        'label'       => esc_html__('Hero Background Color', 'qibla-framework'),
        'description' => esc_html__('Select the background color.', 'qibla-framework'),
        'display'     => array($this, 'displayField'),
    )),

    /**
     * Jumbo-Tron Background Image
     *
     * @since 1.0.0
     */
    'qibla_mb_jumbotron_background_image:media_image'  => $fieldFactory->base(array(
        'type'        => 'media_image',
        'name'        => 'qibla_mb_jumbotron_background_image',
        'attrs'       => array(
            'value' => F\getPostMeta('_qibla_mb_jumbotron_background_image'),
        ),
        'label'       => esc_html__('Hero Background Image', 'qibla-framework'),
        'description' => esc_html__('Upload a background image', 'qibla-framework'),
        'display'     => array($this, 'displayField'),
    )),

    /**
     * Jumbo-tron Height
     *
     * @since 1.0.0
     */
    'qibla_mb_jumbotron_min_height:number'                 => $fieldFactory->base(array(
        'type'        => 'number',
        'name'        => 'qibla_mb_jumbotron_min_height',
        'attrs'       => array(
            'value' => F\getPostMeta('_qibla_mb_jumbotron_min_height'),
        ),
        'label'       => esc_html__('Min Height', 'qibla-framework'),
        'description' => esc_html__(
            'Set the minimum height for the hero image. Set 0 or less to not set. Unit in px.',
            'qibla-framework'
        ),
        'display'     => array($this, 'displayField'),
    )),

    /**
     * Disable Jumbo-tron
     *
     * @since 1.0.0
     */
    'qibla_mb_jumbotron_disable:checkbox'              => $fieldFactory->base(array(
        'type'        => 'checkbox',
        'name'        => 'qibla_mb_jumbotron_disable',
        'value'       => F\getPostMeta('_qibla_mb_jumbotron_disable', 'off'),
        'label'       => esc_html__('Disable Hero Image', 'qibla-framework'),
        'description' => esc_html__('If you want to disable the Hero Image but keep data', 'qibla-framework'),
        'display'     => array($this, 'displayField'),
    )),
));
