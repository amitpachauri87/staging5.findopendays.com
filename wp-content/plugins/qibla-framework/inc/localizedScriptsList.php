<?php

use QiblaFramework\Functions as F;
use QiblaFramework\Plugin;
use QiblaFramework\Parallax;
use QiblaFramework\Geo\LatLngFactory;
use QiblaFramework\Request\Nonce;
use QiblaFramework\ListingsContext\Context;

/**
 * Localized Scripts List
 *
 * @since     1.0.0
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2016, Guido Scialfa
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
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
 * Json Map Style
 *
 * Map styles are located in assets/json/maps-styles
 *
 * @since 1.0.0
 *
 * @param string $name The name of the json map file.
 */
$mapStyle     = apply_filters('qibla_map_style_slug', '');
$mapStylePath = $mapStyle ?
    Plugin::getPluginDirPath('/assets/json/maps-styles/' . sanitize_key($mapStyle) . '.json') :
    '';

// Try to retrieve the center of the map by a submit data.
// Default to lost serie location.
try {
    $mapCenter = LatLngFactory::createFromPostRequest(new Nonce('geocoded'));
} catch (\Exception $e) {
    $mapCenter = new QiblaFramework\Geo\LatLng(21.4010244, -157.9784046);

    /**
     * Filter Default Map Location
     *
     * @since 2.0.0
     *
     * @param \QiblaFramework\Geo\LatLng $mapCenter The latLng center of the map.
     */
    $mapCenter = apply_filters('qibla_fw_default_map_location', $mapCenter);
}
// Set the parallax settings to localize data.
$parallaxSettings = new Parallax\Settings();

$list = array(
    'localized' => array(
        array(
            'handle' => is_admin() ? 'admin' : 'front',
            'name'   => 'dllocalized',
            array(
                'lang'                  => get_bloginfo('language'),
                'date_format'           => get_option('date_format'),
                'time_format'           => get_option('time_format'),
                'env'                   => defined('QB_ENV') ? QB_ENV : 'prod',
                'site_url'              => esc_url(site_url()),
                'usersCanRegister'      => intval(get_option('users_can_register')),
                'charset'               => get_option('blog_charset') ?: 'UTF-8',
                'loggedin'              => is_user_logged_in(),
                'searchIconPlaceholder' => esc_html__('Search icon name', 'qibla-framework'),
                'removeTitleGroup'      => esc_html__('Remove current title', 'qibla-framework'),
            ),
        ),
        array(
            'handle' => is_admin() ? 'admin' : 'front',
            'name'   => 'dlparallaxlocalized',
            array(
                'isParallaxEnabled' => $parallaxSettings->isEnabled(),
                'parallaxOptions'   => $parallaxSettings->getOptions(),
            ),
        ),
        array(
            'handle' => 'front',
            'name'   => 'dlgooglemap',
            array(
                'zoom'              => intval(F\getThemeOption('google_map', 'zoom', true)),
                'style'             => (file_exists($mapStylePath) ? file_get_contents($mapStylePath) : ''),
                'center'            => $mapCenter->asAssoc(),
                'travel'            => F\getThemeOption('tours', 'travel-mode', true),
                'travelZeroResults' => esc_html__('There is no route with this mode', 'qibla-framework'),
            ),
            function () {
                return (F\isListingsArchive() ||
                        Context::isSingleListings() ||
                        wp_script_is('dlmap-listings', 'enqueued'));
            },
        ),
        array(
            'handle' => is_admin() ? 'admin' : 'front',
            'name'   => 'dlformlocalized',
            array(
                'missedFile'    => esc_html__(
                    'Missed file. Some file that need to be send with the form is not set.',
                    'qibla-framework'
                ),
                'rejectedFiles' => esc_html__(
                    'Ops! There are some file rejected. Please check them an try again.',
                    'qibla-framework'
                ),
                'unknownError'  => esc_html__(
                    'Ops! An unknow error occurred. Please contact our support or try in a few minutes.',
                    'qibla-framework'
                ),
            ),
        ),
        array(
            'handle' => is_admin() ? 'admin' : 'front',
            'name'   => 'dlmodallocalized',
            array(
                'closeBtn'          => esc_html__('Close Modal', 'qibla-framework'),
                'signupLabel'       => sprintf(
                    esc_html__('Not a member? %s', 'qibla-framework'),
                    '<span href="#" class="u-highlight-text">' .
                    esc_html__('Sign up', 'qibla-framework') .
                    '</span>'
                ),
                'signinLabel'       => sprintf('<label style="text-align:left;" for="qibla_register_form-terms">By creating an account you agree to our <span style="color:e3520d;text-decoration:underline;" class="pseudolink" onclick=location="https://odays.co/terms-and-conditions/">terms and conditions</span> and <span style="color:e3520d;text-decoration:underline;" class="pseudolink" onclick=location="https://odays.co/privacy/">privacy policy</span></label>'.
                    esc_html__('Returned User? %s', 'qibla-framework'),
                    '<span href="#" class="u-highlight-text">' .
                    esc_html__('Sign In', 'qibla-framework') .
                    '</span>'
                ),
                'lostPasswordLabel' => sprintf(
                    '<span href="#" class="u-highlight-text">%s</span>',
                    esc_html__('Forgot Password?', 'qibla-framework')
                ),
                'goBackLabel'       => esc_html__('Back to login', 'qibla-framework'),
            ),
        ),
        array(
            'handle' => 'front',
            'name'   => 'dlreview',
            array(
                'formLabels' => array(
                    'textAreaLabel' => esc_html__('Your Reply', 'qibla-framework'),
                    'replyTitle'    => esc_html__('Reply to %s', 'qibla-framework'),
                    'submitLabel'   => esc_html__('Send Reply', 'qibla-framework'),
                ),
            ),
        ),
    ),
);

/**
 * Filter Localized Scripts
 *
 * @since 1.0.0
 *
 * @param array $list The list of the localized scripts data
 */
return apply_filters('qibla_fw_localized_scripts_list', $list);
