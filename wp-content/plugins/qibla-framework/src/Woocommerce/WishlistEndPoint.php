<?php
/**
 * Wishlist End Point
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

namespace QiblaFramework\Woocommerce;

use QiblaFramework\EndPoint\AbstractEndPoint;
use QiblaFramework\Exceptions\UserException;
use QiblaFramework\ListingsContext\Types;
use QiblaFramework\Template\LoopTemplate;
use QiblaFramework\TemplateEngine\Engine;
use QiblaFramework\Wishlist\WishList;

/**
 * Class WishlistEndPoint
 *
 * @since   2.0.0
 * @package QiblaFramework\Woocommerce
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class WishlistEndPoint extends AbstractEndPoint
{
    /**
     * Slug ID
     *
     * @since  2.0.0
     *
     * @var string The input slug id.
     */
    private static $slugID = 'woocommerce_myaccount_my_favorites';

    /**
     * Slug
     *
     * @since  2.0.0
     *
     * @var string The slug.
     */
    private static $defaultSlug = 'my-favorites';

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct(array(
            'slug'    => $this->slug(),
            'label'   => esc_html_x('My Favorites', 'endpoint', 'qibla-framework'),
            'context' => 'woocommerce_account',
        ), EP_ROOT | EP_PAGES);
    }

    /**
     * @inheritDoc
     */
    public function register()
    {
        // Assign the callback.
        $endPointSlug = sanitize_key($this->getEndPoint());
        add_action('woocommerce_account_' . $endPointSlug . '_endpoint', array($this, 'callback'));
        add_filter('woocommerce_account_settings', array($this, 'editSlug'));

        return true;
    }

    /**
     * Endpint Slug
     *
     * @since  2.0.0
     *
     * @return string The saved slug.
     */
    public static function slug()
    {
        $slug = get_option(self::$slugID, self::$defaultSlug);

        if (! $slug) {
            $slug = self::$defaultSlug;
        }

        return sanitize_title_with_dashes($slug);
    }

    /**
     * Edit Slug
     *
     * @since  2.0.0
     *
     * @param $settings array The my account endpoint settings.
     *
     * @return array          The new array containing the new input.
     */
    public function editSlug($settings)
    {
        // Initialized.
        $newSettings = array();

        foreach ((array)$settings as $section) {
            if (isset($section['id']) && 'woocommerce_myaccount_edit_account_endpoint' === $section['id']) {
                // My Favorites section.
                $newSettings[] = array(
                    'title'    => esc_html_x('My Favorites', 'my-account', 'qibla-framework'),
                    'desc'     => esc_html_x(
                        'Endpoint for the page "my account &rarr; my favorites"',
                        'my-account',
                        'qibla-framework'
                    ),
                    'id'       => self::$slugID,
                    'type'     => 'text',
                    'default'  => self::$defaultSlug,
                    'desc_tip' => true,
                );
            }

            $newSettings[] = $section;
        }

        return $newSettings;
    }

    /**
     * @inheritDoc
     */
    public function callback($endPointSlug)
    {
        if (! is_user_logged_in()) {
            throw new UserException('User not logged in.');
        }

        $wishlist = new WishList(wp_get_current_user());
        $list     = $wishlist->read();

        // Empty List show the markup and return.
        if (! $list) {
            $engine = new Engine('wishlist_empty', (object)array(), '/views/wishlist/empty.php');
            $engine->render();

            return;
        }

        $types = new Types();

        $posts = new LoopTemplate(new \WP_Query(array(
            'post_type'      => $types->types(),
            'posts_per_page' => -1,
            'post__in'       => wp_list_pluck($list, 'ID'),
        )), '/views/loop/listings.php');

        add_filter('qibla_fw_template_engine_data_wishlist_cta', function (\stdClass $data) {
            if (isset($data->queryArgs)) {
                $data->queryArgs = add_query_arg(array(
                    'hide-on-remove' => 1,
                ), $data->queryArgs);
            }

            return $data;
        });

        $posts->tmpl($posts->getData());
    }
}
