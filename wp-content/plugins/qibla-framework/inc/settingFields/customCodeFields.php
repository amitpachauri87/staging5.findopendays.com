<?php

use QiblaFramework\Functions as F;
use QiblaFramework\Form\Factories\FieldFactory;

/**
 * Settings Custom Code Fields
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
 * Filter Custom Code Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
return apply_filters('qibla_opt_inc_custom_code_fields', array(
    /**
     * Custom Javascript
     *
     * @since 1.0.0
     */
    'qibla_opt-custom_code-javascript:codearea' => $fieldFactory->table(array(
        'type'        => 'codearea',
        'name'        => 'qibla_opt-custom_code-javascript',
        'value'       => F\getThemeOption('custom_code', 'javascript', true),
        'label'       => esc_html__('Custom Javascript', 'qibla-framework'),
        'description' => esc_html__(
            'Custom javascript will enqueued in footer. Press F11 for fullscreen.',
            'qibla-framework'
        ),
        'args'        => array(
            'type' => 'text/javascript',
        ),
    )),
));
