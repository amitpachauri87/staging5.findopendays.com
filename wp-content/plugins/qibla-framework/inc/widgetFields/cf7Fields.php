<?php
use QiblaFramework\Functions as F;
use QiblaFramework\Form\Factories\FieldFactory;

/**
 * Contact Form 7 Fields
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

// @todo Make this as iife on 7.x only.
$cf7WidgetPosts = get_posts(array('post_type' => $this->postType, 'numberposts' => -1, 'post_status' => 'publish'));
$options        = array();
foreach ($cf7WidgetPosts as $post) {
    $options[$post->post_name] = esc_html($post->post_title);
}

/**
 * Filter Contact Form 7 Widget Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the widget fields.
 */
return apply_filters('qibla_widget_cf7_fields', array(
    /**
     * The Contact Form 7 Title
     *
     * @since 1.0.0
     */
    'dlwidget-cf7-title:text'     => $fieldFactory->base(array(
        'key'       => 'dlwidget_cf7_title',
        'name'      => $this->get_field_name('dlwidget_cf7_title'),
        'id'        => $this->get_field_id('dlwidget_cf7_title'),
        'type'      => 'text',
        'container' => 'p',
        'label'     => esc_html__('Contact Form Title', 'qibla-framework'),
        'attrs'     => array(
            'class' => 'widefat',
            'value' => isset($instance['dlwidget_cf7_title']) ? $instance['dlwidget_cf7_title'] : '',
        ),
    )),

    /**
     * The Contact Form 7 Fields
     *
     * @since 1.0.0
     */
    'dlwidget-cf7-options:select' => $fieldFactory->base(array(
        'key'          => 'dlwidget_cf7_options',
        'name'         => $this->get_field_name('dlwidget_cf7_options'),
        'id'           => $this->get_field_id('dlwidget_cf7_options'),
        'type'         => 'select',
        'container'    => 'p',
        'label'        => esc_html__('Select the Contact Form', 'qibla-framework'),
        'value'        => isset($instance['dlwidget_cf7_options']) ? $instance['dlwidget_cf7_options'] : '',
        'exclude_none' => true,
        'options'      => $options,
        'attrs'        => array(
            'class' => 'widefat',
        ),
    )),
));
