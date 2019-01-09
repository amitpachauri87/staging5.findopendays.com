<?php
/**
 * Listings Location Meta Box
 *
 * @since      1.0.0
 * @package    QiblaFramework\Front\CustomFields
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

namespace QiblaFramework\Front\CustomFields;

use QiblaFramework\Functions as F;
use QiblaFramework\Listings\ListingLocation;
use QiblaFramework\Listings\ListingsPost;
use QiblaFramework\Listings\PlainObject;
use QiblaFramework\Template\OpeningHours;
use QiblaFramework\TemplateEngine\TemplateInterface;

/**
 * Class Front-end ListingsLocation
 *
 * @since  1.6.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingsLocation extends AbstractMeta implements TemplateInterface
{
    /**
     * Initialize Object
     *
     * @since  1.6.0
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        // Build the meta-keys array.
        $this->meta = array(
            'map_location' => "_qibla_{$this->mbKey}_map_location",
        );
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        // Initialize data object.
        $data     = new \stdClass();
        $post     = get_post($this->id);
        $listings = new ListingsPost($post);
        $location = new ListingLocation($post);

        // Initialize properties to avoid issue in views and js.
        $data->mapOptions       = array();
        $data->locationInfoList = array();

        // Create and Add the location properties if a valid location is provided.
        if ($location->isValidLocation()) :
            // Parse the zoom option as int or google map will throw an error.
            $data->mapOptions['zoom']   = intval(F\getThemeOption('google_map', 'zoom', true));
            $data->mapOptions['center'] = array(
                'lat' => $location->latitude(),
                'lng' => $location->longitude(),
            );

            // Set the data for the Marker Icon.
            $obj                      = new PlainObject($listings);
            $data->mapOptions['item'] = $obj->object();

            // Json Encode the map Options.
            $data->mapOptions = wp_json_encode($data->mapOptions);

            // Set address.
            $data->locationInfoList['address'] = array(
                'label'           => esc_html__('Address', 'qibla-framework'),
                'icon_html_class' => array('la', 'la-map-signs'),
                'data'            => sprintf(
                    '<a class="dllisting-meta__link" href="https://www.google.com/maps/place/%1$s">%2$s</a>',
                    urlencode($location->address()),
                    $location->address()
                ),
            );
        endif;

        // Retrieve the Business Phone number.
        $meta                                     = F\getPostMeta('_qibla_mb_business_phone');
        $data->locationInfoList['business_phone'] = $meta ? array(
            'label'           => esc_html__('Business Phone', 'qibla-framework'),
            'icon_html_class' => array('la', 'la-phone'),
            'data'            => sprintf('<a href="tel:%1$s">%1$s</a>', $meta),
        ) : null;

        // Retrieve the site url.
        $url = F\getPostMeta('_qibla_mb_site_url');
        if ($url) {
            $urlHost                            = wp_parse_url($url);
            $urlHost                            = $urlHost ? $urlHost['host'] : $url;
            $urlHost                            = str_replace(array('http://', 'https://', 'www.'), '', $urlHost);
            $data->locationInfoList['site_url'] = F\getPostMeta('_qibla_mb_site_url') ? array(
                'label'           => esc_html__('Site Url', 'qibla-framework'),
                'icon_html_class' => array('la', 'la-link'),
                'data'            => sprintf(
                    '<a href="%1$s">%2$s</a>',
                    esc_url($url),
                    esc_attr($urlHost)
                ),
            ) : null;
        }

        if (! OpeningHours::checkDependencies()) {
            // Retrieve the open hours.
            $data->locationInfoList['open_hours'] = F\getPostMeta('_qibla_mb_open_hours') ? array(
                'label'           => esc_html__('Open Hours', 'qibla-framework'),
                'icon_html_class' => array('la', 'la-clock-o'),
                'data'            => F\getPostMeta('_qibla_mb_open_hours'),
            ) : null;
        }

        // Clean the location info list data.
        foreach ($data->locationInfoList as $key => $item) {
            if (null === $item) {
                unset($data->locationInfoList[$key]);
            }
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        /**
         * Add template only in singular post type.
         * - Filter singular post type for view template.
         *
         * @since 2.3.0
         */
        if (is_singular(apply_filters('qibla_listings_location_tmpl', 'listings'))) {
            if ($this->loadTemplate('listing_location', $data, 'views/customFields/listings/listingsLocation.php')) {
                // Enqueue the Google Map Script.
                if (wp_script_is('dllistings-google-map', 'registered')) {
                    wp_enqueue_script('dllistings-google-map');
                }
            }
        }
    }

    /**
     * Listings Location Filter
     *
     * @since 1.6.0
     *
     * @return void
     */
    public static function listingsLocationFilter()
    {
        $instance = new self;

        $instance->init();
        $instance->tmpl($instance->getData());
    }
}
