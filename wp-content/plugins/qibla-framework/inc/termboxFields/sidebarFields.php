<?php
use QiblaFramework\Functions as F;
use QiblaFramework\Form\Factories\FieldFactory;

/**
 * Sidebar Term-box Fields
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
 * Filter Sidebar Terms Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the term-box fields.
 */
return apply_filters('qibla_tb_inc_sidebar_fields', array(
    /**
     * Sidebar Position
     *
     * @since 1.0.0
     */
    'qibla_tb_sidebar_position:radio' => $fieldFactory->termbox(array(
        'type'        => 'radio',
        'name'        => 'qibla_tb_sidebar_position',
        'value'       => F\getTermMeta('_qibla_tb_sidebar_position', self::$currTerm->term_id, 'right'),
        'options'     => array(
            'none'    => esc_html__('No Sidebar', 'qibla-framework'),
            'default' => esc_html__('Default', 'qibla-framework'),
            'left'    => esc_html__('Left', 'qibla-framework'),
            'right'   => esc_html__('Right', 'qibla-framework'),
        ),
        'label'       => esc_html__('Sidebar Position', 'qibla-framework'),
        'description' => esc_html__('Select the position of the sidebar.', 'qibla-framework'),
        'display'     => array($this, 'displayField'),
    )),
));
