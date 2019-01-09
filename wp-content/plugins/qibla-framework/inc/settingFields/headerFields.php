<?php
/**
 * Settings Header Fields
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

use QiblaFramework\Functions as F;
use QiblaFramework\Form\Factories\FieldFactory;

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

/**
 * Filter Header Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
return apply_filters('qibla_opt_inc_header_fields', array(
    /**
     * Sticky Nav
     *
     * @since 1.0.0
     */
    'qibla_opt-header-sticky_header:checkbox' => $fieldFactory->table(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_opt-header-sticky_header',
        'label'       => esc_html_x('Sticky Header', 'settings', 'qibla-framework'),
        'description' => esc_html_x('Set the header as sticky.', 'settings', 'qibla-framework'),
        'value'       => F\getThemeOption('header', 'sticky_header', true),
    )),
));
