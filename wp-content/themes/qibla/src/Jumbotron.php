<?php

namespace Qibla;

use Qibla\Archive\Title as ArchiveTitle;
use Qibla\Functions as F;

/**
 * Jumbo-tron Template
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    Qibla
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    GNU General Public License, version 2
 *
 * Copyright (C) 2017 Guido Scialfa <dev@guidoscialfa.com>
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
 * Class Jumbotron
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package Qibla
 */
class Jumbotron
{
    /**
     * Context
     *
     * The context may be post, term, user etc...
     *
     * @since  1.1.0
     *
     * @var string The context of the custom meta
     */
    protected $context;

    /**
     * Meta-box Key
     *
     * The meta-box key is a string that define the context of the meta-box.
     * mb stay for meta-box, tb stay for term-box.
     *
     * @since  1.1.0
     *
     * @var string The meta-box key
     */
    protected $mbKey;

    /**
     * Meta
     *
     * The meta array containing all of the meta-keys for the current custom box
     *
     * @since  1.1.0
     *
     * @var array An array containing all meta-keys for the current box
     */
    protected $meta;

    /**
     * ID
     *
     * The current queried id of the current queried object
     *
     * @since  1.1.0
     *
     * @var int The id of the current queried object
     */
    protected $id;

    /**
     * Taxonomy
     *
     * @since  1.1.0
     *
     * The taxonomy related to the current term if exists.
     */
    protected $taxonomy;

    /**
     * Get Background Image
     *
     * @since  1.2.0
     *
     * @return \stdClass The background image data
     */
    private function getBackgroundImage()
    {
        static $data = null;

        if (null === $data) {
            $background = get_custom_header();

            $data = (object)array(
                'backgroundId'   => attachment_url_to_postid($background->url),
                'backgroundSize' => array($background->width, $background->height),
                'backgroundSrc'  => $background->url,
            );
        }

        return $data;
    }

    /**
     * Initialize Object
     *
     * @since  1.1.0
     *
     * @return void
     */
    public function init()
    {
        // Get the current queried object.
        $currObj = get_queried_object();

        // Set the work stuffs for Taxonomies.
        if (is_tax() || is_category() || is_tag()) {
            $this->context = 'term';
            $this->mbKey   = 'tb';
            $this->id      = $currObj->term_id;
            // The current term may have multiple relationships.
            $this->taxonomy = is_array($currObj->taxonomy) ? $currObj->taxonomy[0] : $currObj->taxonomy;
        } elseif (is_singular() || F\isShop() || is_home()) {
            $this->context = 'post';
            $this->mbKey   = 'mb';

            if (F\isShop()) {
                $this->id = intval(get_option('woocommerce_shop_page_id'));
            } elseif (is_home()) {
                $this->id = intval(get_option('page_for_posts'));
            } else {
                $this->id = $currObj->ID;
            }
        }
    }

    /**
     * Get the Title
     *
     * Get the title based on context
     *
     * @since  1.1.0
     *
     * @return string The title of the current context
     */
    protected function getContextTitle()
    {
        // Get the archive title by default.
        $title = new ArchiveTitle();
        $title = $title->title();

        if (is_front_page() && is_home()) {
            // When the page is the index.php use static title.
            $title = esc_html__('Posts', 'qibla');
        } else {
            switch ($this->context) {
                case 'term':
                    $title = get_term_field('name', $this->id, $this->taxonomy);
                    break;
                case 'post':
                    $title = get_the_title($this->id);
                    break;
            }

            if (is_wp_error($title)) {
                $title = '';
            }
        }

        return $title;
    }

    /**
     * Get Attribute Class
     *
     * @since  1.2.0
     *
     * @return array The classes for the jumbotron markup
     */
    protected function getAttributeClass()
    {
        // Get the background.
        $background = $this->getBackgroundImage();

        // Set the jumbo-tron classes.
        $class = array('dljumbotron');

        // Don't allow within the single post post type.
        if (! is_single() && ! empty($background->backgroundSrc)) {
            $class[] = 'dljumbotron--has-background-image';
            $class[] = 'dljumbotron--overlay';
        }
        if (F\isHeaderVideoEligible()) {
            $class[] = 'dljumbotron--has-background-video';
            $class[] = 'dljumbotron--overlay';
        }

        $class = array_unique($class);

        return $class;
    }

    /**
     * Jumbo-tron
     *
     * @since  1.0.0
     *
     * @return \stdClass The data for view
     */
    public function getJumbotronData()
    {
        // Initialize Data Object.
        $data = new \stdClass();

        $this->init();

        // Set the correct title.
        $data->title = $this->getContextTitle();

        $data->subtitle    = '';
        $data->description = '';

        if (is_search()) {
            // Set the archive description as subtitle if it's search archive.
            $data->subtitle = F\getTheArchiveDescription();
        }

        // Get the classes for class attribute.
        $data->class = $this->getAttributeClass();

        return $data;
    }

    /**
     * Jumbo-tron Template Loader
     *
     * @since  1.0.0
     *
     * @return void
     */
    public static function jumbotronTmpl()
    {
        // Is the current page allowed to show the jumbo-tron?
        if (! F\isJumbotronAllowed()) {
            return;
        }

        $instance = new static;
        $instance->init();

        $data = $instance->getJumbotronData();

        if ($data->title) {
            $engine = new \Qibla\TemplateEngine\Engine('dljumbotron', $data, '/views/jumbotron.php');
            $engine->render();
        }
    }

    /**
     * Custom Css
     *
     * @since  1.2.0
     *
     * @return void
     */
    public static function customCss()
    {
        // Don't allow within the single post post type.
        if (! F\isJumbotronAllowed() || is_single()) {
            return;
        }

        $instance = new static;

        // Get the background image data.
        $background = $instance->getBackgroundImage();

        /**
         * Filter custom header background
         *
         * @since 1.2.0
         *
         * @param \stdClass $background The custom header background data.
         */
        $background = apply_filters('qibla_custom_header_background_data', $background);

        // Background Style.
        if (! empty($background->backgroundSrc)) {
            $data['style'] = array(
                'background'          => ' url(' . esc_url($background->backgroundSrc) . ') no-repeat center center',
                'background-size'     => 'cover',
                'background-position' => 'center',
                'background-repeat'   => 'no-repeat',
            );
            // Build the style.
            $style = '';
            if (! empty($data['style']) && is_array($data['style'])) {
                $style = F\implodeAssoc(';', ':', $data['style']);
            }

            if ($style) {
                // @todo Need cssTidy
                echo '<style type="text/css">.dljumbotron{' . $style . '}</style>';
            }
        }
    }
}
