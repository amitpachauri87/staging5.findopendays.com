<?php

namespace QiblaFramework\Admin\Metabox;

use QiblaFramework\Form\Fieldsets\BaseFieldset;
use QiblaFramework\Form\Interfaces\Fields;
use QiblaFramework\ListingsContext\Types;
use QiblaFramework\Plugin;

/**
 * Class Event
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

/**
 * Class Listings
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Listings extends AbstractMetaboxFieldset
{
    /**
     * Field-sets & Formats
     *
     * @since  1.0.0
     *
     * @var array A list of key value pair
     */
    private $formatsList;

    /**
     * Listings Constructor
     *
     * @inheritdoc
     */
    public function __construct(array $args = array())
    {
        // Get the post to use to retrieve some data within the fields files.
        $post  = get_post() ?: new \WP_Post(new \stdClass());
        $types = new Types();

        parent::__construct(wp_parse_args(array(
            'id'       => 'listing_option',
            'title'    => esc_html__('Listing Option', 'qibla-framework'),
            'callback' => array($this, 'callBack'),
            'screen'   => $types->types(),
            'context'  => 'normal',
            'priority' => 'high',
        )), array(
            'dlui-metabox-tabs',
        ));

        parent::setFieldsets(apply_filters('qibla_listing_option_metabox_fieldsets', array(
            /**
             * Header
             *
             * @since 1.0.0
             */
            new BaseFieldset(include Plugin::getPluginDirPath('/inc/metaboxFields/headerFields.php'), array(
                'form'            => 'post',
                'id'              => 'listing_header',
                'name'            => 'header',
                'legend'          => esc_html__('Header', 'qibla-framework'),
                'container_class' => array('dl-metabox-fieldset'),
                'icon_class'      => 'la la-header',
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
             * Location Field-set
             *
             * @since 1.0.0
             */
            new BaseFieldset(include Plugin::getPluginDirPath('/inc/metaboxFields/listingsLocationsFields.php'), array(
                'form'            => 'post',
                'id'              => 'listing_map',
                'name'            => 'map',
                'legend'          => esc_html__('Location', 'qibla-framework'),
                'icon_class'      => 'la la-map-marker',
                'container_class' => array('dl-metabox-fieldset'),
            )),

            /**
             * Additional Info Field-set
             *
             * @since 1.0.0
             */
            new BaseFieldset(
                include Plugin::getPluginDirPath('/inc/metaboxFields/listingsAdditionalInfoFields.php'),
                array(
                    'form'            => 'post',
                    'id'              => 'listing_additional_info',
                    'name'            => 'additional_info',
                    'legend'          => esc_html__('Additional Info', 'qibla-framework'),
                    'container_class' => array('dl-metabox-fieldset'),
                    'icon_class'      => 'la la-info',
                )
            ),

            /**
             * Socials Field-set
             *
             * @since 1.0.0
             */
            new BaseFieldset(include Plugin::getPluginDirPath('/inc/metaboxFields/socialsUrlsFields.php'), array(
                'form'            => 'post',
                'id'              => 'listing_socials',
                'name'            => 'socials',
                'legend'          => esc_html__('Socials', 'qibla-framework'),
                'container_class' => array('dl-metabox-fieldset'),
                'icon_class'      => 'la la-users',
            )),

            /**
             * Gallery Field-set
             *
             * @since 1.0.0
             */
            new BaseFieldset(include Plugin::getPluginDirPath('/inc/metaboxFields/galleryFields.php'), array(
                'form'            => 'post',
                'id'              => 'listing_gallery',
                'name'            => 'gallery',
                'legend'          => esc_html__('Gallery', 'qibla-framework'),
                'container_class' => array('dl-metabox-fieldset'),
                'icon_class'      => 'la la-image',
            )),

            /**
             * Related Posts Field
             *
             * @since 1.0.0
             */
            new BaseFieldset(include Plugin::getPluginDirPath('/inc/metaboxFields/relatedPostsFields.php'), array(
                'form'            => 'post',
                'id'              => 'related_posts',
                'name'            => 'related_posts',
                'legend'          => esc_html__('Related Posts', 'qibla-framework'),
                'container_class' => array('dl-metabox-fieldset'),
                'icon_class'      => 'la la-columns',
            )),

            /**
             * Related Posts Field
             *
             * @since 1.0.0
             */
            new BaseFieldset(include Plugin::getPluginDirPath('/inc/metaboxFields/buttonFields.php'), array(
                'form'            => 'post',
                'id'              => 'listing_button',
                'name'            => 'listing_button',
                'legend'          => esc_html__('Button', 'qibla-framework'),
                'container_class' => array('dl-metabox-fieldset'),
                'icon_class'      => 'la la-external-link-square',
            )),
        ), $types->types()));
    }

    /**
     * Display Fields Callback
     *
     * @since  1.5.0
     *
     * @param Fields $field The current field in the form
     *
     * @return bool True if can be displayed, false otherwise¬
     */
    public function displayField(Fields $field)
    {
        // By default display to true.
        $display = true;
        // Get the field arguments.
        $fieldArgs = $field->getArgs();

        switch ($fieldArgs['name']) {
            case 'qibla_mb_hide_breadcrumb':
                // Single listings post doesn't have breadcrumbs.
                $display = false;
                break;
        }

        return $display;
    }
}
