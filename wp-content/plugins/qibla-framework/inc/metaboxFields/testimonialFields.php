<?php
use \QiblaFramework\Functions as F;
use \QiblaFramework\Form\Factories\FieldFactory;

/**
 * Testimonial Meta-box Fields
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
 * Filter Testimonial Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the testimonial meta-box fields.
 */
return apply_filters('qibla_mb_inc_testimonial_fields', array(
    /**
     * Rating
     *
     * @since 1.0.0
     */
    'qibla_mb_testominial_rating:number' => $fieldFactory->table(array(
        'name'        => 'qibla_mb_testimonial_rating',
        'type'        => 'number',
        'label'       => esc_html__('Rating', 'qibla-framework'),
        'description' => esc_html__('Add the rating value for the testimonial', 'qibla-framework'),
        'filter'      => FILTER_SANITIZE_NUMBER_FLOAT,
        'attrs'       => array(
            'min'   => 1,
            'max'   => 5,
            'value' => F\getPostMeta('_qibla_mb_testimonial_rating', 3),
            'step'  => 1,
            'class' => array(
                'widefat',
            ),
        ),
    )),
));
