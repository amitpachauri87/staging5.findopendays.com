<?php
/**
 * Logo
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2017, Guido Scialfa
 * @license   GNU General Public License, version 2
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

namespace Qibla;

use Qibla\Functions as F;
use Qibla\TemplateEngine\Engine as TEngine;
use Qibla\TemplateEngine\TemplateInterface;

/**
 * Class Logo
 *
 * @since   1.6.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package Qibla
 */
class Logo implements TemplateInterface
{
    /**
     * Retrieve Custom Logo
     *
     * @since 1.6.0
     *
     * @uses  current_theme_support() To check if theme support 'custom-logo'
     * @uses  get_theme_mod() To retrieve the value of the custom logo.
     * @uses  wp_get_attachment_image() To retrieve the image of the logo.
     *
     * @return string The attachment image markup
     */
    private function customLogo()
    {
        $logo = '';

        if (current_theme_supports('custom-logo')) {
            // Custom logo ID.
            $id = intval(get_theme_mod('custom_logo'));

            if ($id) {
                // Base logo image markup.
                $logo = wp_get_attachment_image($id, 'full', false, array(
                    'class' => F\getScopeClass('brand', 'logo'),
                    'alt'   => F\getAttachmentImageAlt($id),
                ));
            }
        }

        return $logo;
    }

    /**
     * Get Site Info
     *
     * @since 1.6.0
     *
     * @return object The data for the site. Name and Description
     */
    private function siteInfo()
    {
        return (object)array(
            'name'        => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
        );
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        // Initialize the data.
        $data = new \stdClass();

        // Get the Site info.
        $data->site = $this->siteInfo();
        // Get Logo markup.
        $data->logo = $this->customLogo();

        /**
         * Logo Data Hook
         *
         * @since 1.6.0
         *
         * @param \stdClass   $data The data for the the view.
         * @param \Qibla\Logo $this The instance of the class.
         */
        do_action('qibla_logo_data', $data, $this);

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new TEngine('the_brand', $data, 'views/brand.php');
        $engine->render();
    }

    /**
     * Logo Filter
     *
     * @since 1.6.0
     */
    public static function logoFilter()
    {
        $instance = new static;

        $instance->tmpl($instance->getData());
    }
}
