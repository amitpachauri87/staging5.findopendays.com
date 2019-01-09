<?php
/**
 * Class Meta-box Pages
 *
 * @since      1.0.0
 * @package    QiblaFramework\Admin\Metabox
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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

namespace QiblaFramework\Admin\Metabox;

use QiblaFramework\Form\Interfaces\Fields;
use QiblaFramework\Form\Interfaces\Fieldsets;
use QiblaFramework\Form\Fieldsets\BaseFieldset;
use QiblaFramework\Plugin;
use QiblaFramework\Slider\RevolutionSlider;

/**
 * Class Page
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Page extends AbstractMetaboxFieldset
{
    /**
     * Page Constructor
     *
     * @inheritdoc
     */
    public function __construct(array $args = array())
    {
        $imageSizes = array('full' => 'Full');
        foreach (get_intermediate_image_sizes() as $image) {
            $imageSizes[$image] = ucwords(str_replace(array('_', '-'), ' ', $image));
        }

        parent::__construct(wp_parse_args($args, array(
            'id'       => 'page',
            'title'    => esc_html__('Page Options', 'qibla-framework'),
            'callback' => array($this, 'callBack'),
            'screen'   => array('page', 'post'),
            'context'  => 'normal',
            'priority' => 'high',
        )), array(
            'dlui-metabox-tabs',
        ));

        parent::setFieldsets(array(
            /**
             * Header
             *
             * @since 1.0.0
             */
            new BaseFieldset(include Plugin::getPluginDirPath('/inc/metaboxFields/headerFields.php'), array(
                'form'            => 'post',
                'id'              => 'page_options_header',
                'name'            => 'page_options_header',
                'legend'          => esc_html__('Header', 'qibla-framework'),
                'container_class' => array('dl-metabox-fieldset'),
                'icon_class'      => 'la la-header',
                'display'         => array($this, 'display'),
            )),

            /**
             * Jumbo-tron
             *
             * @since 1.0.0
             */
            new BaseFieldset(include Plugin::getPluginDirPath('/inc/metaboxFields/jumbotronFields.php'), array(
                'form'            => 'post',
                'id'              => 'page_options_jumbotron',
                'name'            => 'page_options_jumbotron',
                'legend'          => esc_html__('Hero Image', 'qibla-framework'),
                'container_class' => array('dl-metabox-fieldset'),
                'icon_class'      => 'la la-picture-o',
                'display'         => array($this, 'display'),
            )),

            /**
             * Hero-maps
             *
             * @since 2.4.0
             */
            new BaseFieldset(include Plugin::getPluginDirPath('/inc/metaboxFields/heroMapFields.php'), array(
                'form'            => 'post',
                'id'              => 'page_options_heromap',
                'name'            => 'page_options_heromap',
                'legend'          => esc_html__('Hero Map', 'qibla-framework'),
                'container_class' => array('dl-metabox-fieldset'),
                'icon_class'      => 'la la-map-o',
                'display'         => array($this, 'display'),
            )),

            /**
             * Slider
             *
             * @since 1.6.0
             */
            new BaseFieldset(include Plugin::getPluginDirPath('/inc/metaboxFields/jumbotronSliderFields.php'), array(
                'form'            => 'post',
                'id'              => 'page_options_jumbotron_slider',
                'name'            => 'page_options_jumbotron_slider',
                'legend'          => esc_html__('Hero Slider', 'qibla-framework'),
                'container_class' => array('dl-metabox-fieldset'),
                'icon_class'      => 'la la-picture-o',
                'display'         => array($this, 'display'),
            )),
        ));
    }

    /**
     * Display Fields Callback
     *
     * @since  1.0.0
     *
     * @param Fields $field The current field in the form
     *
     * @return bool True if can be displayed, false otherwise¬
     */
    public function displayField(Fields $field)
    {
        // Get the font page ID.
        $frontPageID = intval(get_option('page_on_front'));
        // By default display to true.
        $display = true;
        // Get the field arguments.
        $fieldArgs = $field->getArgs();

        if (! $frontPageID) {
            return $display;
        }

        switch ($fieldArgs['name']) {
            case 'qibla_mb_jumbotron_disable':
                /**
                 * Filter jumbo-tron disable in front page.
                 *
                 * @since 2.4.0
                 */
                $display = 'yes' === apply_filters(
                    'qibla_jumbotron_disable_remove_in_front',
                    $frontPageID,
                    get_the_ID(),
                    'no'
                ) ? false : $display;
                break;
            case 'qibla_mb_hide_breadcrumb':
                $display = (function_exists('breadcrumb_trail') || function_exists('yoast_breadcrumb'));
                break;
        }

        return $display;
    }

    /**
     * Display
     *
     * Conditional function that check if a field set can be showed or not.
     *
     * @since 1.6.0
     *
     * @param Fieldsets $fieldSet An instance of Fieldsets to check against.
     *
     * @return bool True to show, false otherwise
     */
    public function display(Fieldsets $fieldSet)
    {
        $args = $fieldSet->getArgs();

        switch ($args['name']) {
            case 'page_options_jumbotron_slider':
                $display = RevolutionSlider::sliderIsActive();
                break;

            default:
                $display = true;
                break;
        }

        return $display;
    }
}
