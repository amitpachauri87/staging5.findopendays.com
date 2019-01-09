<?php
/**
 * MultiDates
 *
 * @since      1.0.0
 *
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

namespace AppMapEvents\Form\Types;

/**
 * Class MultiDate
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class MultiDates extends \QiblaFramework\Form\Abstracts\Type
{
    /**
     * Constructor
     *
     * @since  1.0.0
     *
     * @param array $args The arguments for this type.
     */
    public function __construct($args)
    {
        $args = wp_parse_args($args, array(
            'filter'         => FILTER_SANITIZE_STRING,
            'filter_options' => array(
                'flags' => FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_ENCODE_AMP,
            ),
            'attrs'          => array(
                'data-format' => 'yy/mm/dd'
            ),
        ));

        // Force input type.
        $args['type'] = 'text';

        // Able to use the jQuery multidatepicker widget.
        $args['attrs'] = array_merge($args['attrs'], array(
            'data-type'   => 'multidatespicker',
        ));


        parent::__construct($args);
    }

    /**
     * Sanitize
     *
     * @since  1.0.0
     *
     * @param string $value The value to sanitize.
     *
     * @return string The sanitized value of this type. Empty string if the value is not correct.
     */
    public function sanitize($value)
    {
        $value = str_replace(', ', ',', $value);
        $value = sanitize_text_field($value);

        return $this->applyPattern($value);
    }

    /**
     * Get Html
     *
     * @since  1.0.0
     *
     * @return string The html version of this type
     */
    public function getHtml()
    {
        // Script
        if (wp_script_is('multidatespicker-type', 'registered')) {
            wp_enqueue_script('jquery-ui-datepicker', array('jquery'));
            wp_enqueue_script('multidatespicker-type', array('jquery-ui-datepicker'));
        }

        // Lang
        if (wp_script_is('datepicker-lang', 'registered')) {
            wp_enqueue_script('datepicker-lang', array('jquery', 'jquery-ui-datepicker'));
        }

        // Register ad Enqueue jquery-ui
        $protocol = is_ssl() ? 'https://' : 'http://';
        wp_register_style('jquery-ui', $protocol . 'ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');

        // Styles
        if (wp_style_is('multidatespicker-style', 'registered')) {
            wp_enqueue_style('qibla-form-types');
            wp_enqueue_style('jquery-ui');
            if (is_admin()) {
                wp_enqueue_style('multidatespicker-style', array('jquery-ui'));
            }
        }

        $output = sprintf(
            '<input type="%s" name="%s" id="%s"%s />',
            sanitize_key($this->getArg('type')),
            esc_attr($this->getArg('name')),
            esc_attr($this->getArg('id')),
            $this->getAttrs()
        );

        /**
         * Output Filter
         *
         * @since 1.0.0
         *
         * @param string $output The output of the input type.
         * @param object         The instance of the type.
         */
        $output = apply_filters('qibla_fw_type_multidate_output', $output, $this);

        return $output;
    }

}