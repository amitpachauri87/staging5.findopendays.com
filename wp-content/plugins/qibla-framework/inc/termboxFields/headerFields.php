<?php
/**
 * Header Term-box Fields
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

/**
 * Filter Header Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the header meta-box fields.
 */
return apply_filters('qibla_tb_inc_header_fields', array(
    /**
     * Header Skin
     *
     * @since 1.0.0
     */
    'qibla_tb_header_skin:radio' => $fieldFactory->termbox(array(
        'type'        => 'radio',
        'name'        => 'qibla_tb_header_skin',
        'value'       => F\getTermMeta('_qibla_tb_header_skin', self::$currTerm->term_id, 'light'),
        'label'       => esc_html__('Header Skin', 'qibla-framework'),
        'description' => esc_html__('The skin for the header to use.', 'qibla-framework'),
        'display'     => array($this, 'displayField'),
        'options'     => array(
            'transparent'  => esc_html__('Transparent', 'qibla-framework'),
            'light' => esc_html__('Light', 'qibla-framework'),
        ),
    )),

    /**
     * Sub Title
     *
     * @since 1.0.0
     */
    'qibla_tb_sub_title:text'    => $fieldFactory->termbox(array(
        'type'        => 'text',
        'name'        => 'qibla_tb_sub_title',
        'attrs'       => array(
            'value' => F\getTermMeta('_qibla_tb_sub_title', self::$currTerm->term_id),
            'class' => array('widefat'),
        ),
        'label'       => esc_html__('Sub Title', 'qibla-framework'),
        'description' => esc_html__('The sub-title for this listing item.', 'qibla-framework'),
        'display'     => array($this, 'displayField'),
    )),
));
