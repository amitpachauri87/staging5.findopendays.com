<?php
/**
 * Class Front-end Settings Listings
 *
 * @since      1.0.0
 * @package    QiblaFramework\Front\Settings
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa
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

namespace QiblaFramework\Front\Settings;

use QiblaFramework\Functions as F;
use QiblaFramework\Front\CustomFields;

/**
 * Class Listings
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Logo
{
    /**
     * Logo Style
     *
     * @since  1.0.0
     *
     * @param \stdClass $data The data to filter.
     *
     * @return \stdClass The filtered data.
     */
    public function logoStyle(\stdClass $data)
    {
        // Overwrite the logo style based on header meta.
        $meta = new CustomFields\Header();
        $meta->init();
        $skin = $meta->getMeta('header_skin');
        // Additional image arguments.
        $argsImage = array(
            'class' => 'dlbrand__logo',
        );

        switch ($skin) {
            case 'transparent':
                // Right know we have only two skin: transparent and light.
                // dark logo is for transparent header.
                $optSlug = 'dark';
                break;

            default:
                $optSlug = 'logo';
                break;
        }

        // Try to retrieve the theme custom logo.
        $data->logo = F\getThemeOption('logo', $optSlug);
        // Fallback to the main theme mod, and if is the case fallback to the theme option $skin and at least
        // fallback to the theme option main logo.
        if (! $data->logo) {
            if (! $data->logo && ! $skin) {
                $data->logo = get_theme_mod('custom_logo');
            }

            $data->logo = $data->logo ?: F\getThemeOption('logo', 'logo');
        }

        // Add retina version for the logo if set.
        $retina = F\getThemeOption('logo', "{$optSlug}_retina");
        if ($retina) {
            $argsImage['srcset']              = wp_get_attachment_image_url($retina, 'full') . ' 2x';
            $argsImage['data-originalretina'] = esc_url(wp_get_attachment_image_url(F\getThemeOption(
                'logo',
                'logo_retina'
            ), 'full')) . ' 2x';
        }

        // Set the original logo to allow us to use it in various context.
        $argsImage['data-original'] = esc_url(wp_get_attachment_image_url(F\getThemeOption('logo', 'logo'), 'full'));
        // Remember to allow 'data-original' as attribute for the img tag.
        add_filter('qibla_kses_image_allowed_attrs', array($this, 'imgTagAllowExtraAttributes'));

        if ($data->logo) {
            $data->logo = wp_get_attachment_image(intval($data->logo), 'full', false, $argsImage);
        }

        return $data;
    }

    /**
     * Allow Img tag Attributes
     *
     * @since  1.6.0
     *
     * @param array $list The list of the allowed tag attribute's.
     *
     * @return array The filtered list
     */
    public function imgTagAllowExtraAttributes(array $list = array())
    {
        // Remember to remove before return.
        remove_filter('qibla_kses_image_allowed_attrs', array($this, 'imgTagAllowExtraAttributes'), 10);

        return array_merge($list, array(
            'data-original'       => true,
            'data-originalretina' => true,
        ));
    }

    /**
     * Filter Logo Filter
     *
     * @since 1.6.0
     *
     * @param \stdClass $data
     *
     * @return mixed
     */
    public static function filterLogoFilter(\stdClass $data)
    {
        $instance = new static;

        return $instance->logoStyle($data);
    }
}
