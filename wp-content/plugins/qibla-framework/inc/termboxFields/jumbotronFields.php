<?php
/**
 * Jumbo-tron Term-box Fields
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

use \QiblaFramework\Functions as F;
use \QiblaFramework\Form\Factories\FieldFactory;

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

// Get the WooCommerce taxonomies.
// For WooCommerce we don't want to have the jumbotron allowed by default.
$taxonomies               = array('edit-product_tag', 'edit-product_cat');
$currentScreen            = F\currentScreen();
$jumbotronDisabledDefault = ($currentScreen and in_array($currentScreen->id, $taxonomies, true)) ? 'on' : 'off';

/**
 * Filter Jumbo-tron Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the jumbo-tron meta-box fields.
 */
return apply_filters('qibla_tb_inc_jumbotron_fields', array(
    /**
     * Jumbo-Tron Background Color
     *
     * @since 1.0.0
     */
    'qibla_tb_jumbotron_background_color:color_picker' => $fieldFactory->termbox(array(
        'type'        => 'color_picker',
        'name'        => 'qibla_tb_jumbotron_background_color',
        'attrs'       => array(
            'value' => F\getTermMeta(
                '_qibla_tb_jumbotron_background_color',
                self::$currTerm->term_id,
                'rgba(255, 255, 255, .3)'
            ),
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
    'qibla_tb_jumbotron_background_image:media_image'  => $fieldFactory->termbox(array(
        'type'        => 'media_image',
        'name'        => 'qibla_tb_jumbotron_background_image',
        'attrs'       => array(
            'value' => F\getTermMeta('_qibla_tb_jumbotron_background_image', self::$currTerm->term_id),
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
    'qibla_tb_jumbotron_min_height:number'             => $fieldFactory->termbox(array(
        'type'        => 'number',
        'name'        => 'qibla_tb_jumbotron_min_height',
        'attrs'       => array(
            'value' => F\getTermMeta('_qibla_tb_jumbotron_min_height', self::$currTerm->term_id),
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
    'qibla_tb_jumbotron_disable:checkbox'              => $fieldFactory->termbox(array(
        'type'        => 'checkbox',
        'name'        => 'qibla_tb_jumbotron_disable',
        'value'       => F\getTermMeta(
            '_qibla_tb_jumbotron_disable',
            self::$currTerm->term_id,
            $jumbotronDisabledDefault
        ),
        'label'       => esc_html__('Disable Hero Image', 'qibla-framework'),
        'description' => esc_html__('If you want to disable the Hero Image but keep data', 'qibla-framework'),
        'display'     => array($this, 'displayField'),
    )),
));
