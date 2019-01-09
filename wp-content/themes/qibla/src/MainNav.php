<?php
namespace Qibla;

use Qibla\Functions as F;
use Qibla\TemplateEngine\Engine as TEngine;
use Qibla\Woocommerce\CartCounterTemplate;

/**
 * Main Nav
 *
 * @since      1.0.0
 * @package    Qibla
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    GNU General Public License, version 2
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
 * Class MainNav
 *
 * @since   1.0.0
 *
 * @package Qibla
 */
class MainNav
{
    /**
     * Arguments
     *
     * @since  1.0.0
     *
     * @var array The arguments for the navigation
     */
    public $args;

    /**
     * Nav Location
     *
     * @since  1.0.0
     *
     * @var string The location where the nav will appear
     */
    protected $location;

    /**
     * Constructor
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        /**
         * Main Nav Depth
         *
         * @since 1.0.0
         *
         * @param int $depth The number of depth allowed on this menu.
         */
        $depth = apply_filters('qibla_nav_main_depth', 3);

        $this->location = 'nav_main';
        $this->args     = array(
            'theme_location' => $this->location,
            'menu_id'        => F\getScopeClass('nav-main', 'list-items', '', 'id'),
            'menu_class'     => F\getScopeClass('nav-main', 'list-items'),
            'container'      => '',
            'items_wrap'     => '<ul id="%1$s" class="%2$s" data-depth="' . $depth . '">%3$s</ul>',
            'fallback_cb'    => apply_filters('qibla_nav_main_fallback', function () {
                echo '<div><a style="float: right; margin: .63em 0" id="dlnav-main--no-nav" class="dlbtn dlbtn--tiny" href="' .
                     esc_url(admin_url('/nav-menus.php')) . '">' . esc_html__('Create a Menu', 'qibla') . '</a>';
            }),
            // Since WP 4.7.0.
            'item_spacing'   => 'discard',
            'depth'          => $depth,
            'walker'         => apply_filters('qibla_nav_main_walker', ''),
        );
    }

    /**
     * Show Main Nav
     *
     * @since  1.0.0
     *
     * @uses   has_nav_menu()
     *
     * @return void
     */
    public function display()
    {
//        if (! has_nav_menu($this->location)) {
//            return;
//        }

        // Initialize Data.
        $data = new \stdClass();

        // Add the navigation Arguments.
        $data->navArgs = $this->args;

        /**
         * Main Content Anchor
         *
         * Filter the element anchor that represent the main document content
         *
         * @since 1.0.0
         */
        $data->mainHref = apply_filters('qibla_main_content_href', '#upxmain');

        if (F\isWooCommerceActive() && 'yes' === apply_filters('qibla_show_cart_on_main_nav', 'no')) {
            add_filter('wp_nav_menu_items', array($this, 'cartCounter'), 20, 2);
        }

        $engine = new TEngine('the_nav_main', $data, 'views/header/navMain.php');
        $engine->render();
    }

    /**
     * Cart Counter
     *
     * @since  1.0.0
     *
     * @param string $items The HTML list content for the menu items.
     * @param object $args  An object containing wp_nav_menu() arguments.
     *
     * @return string The filtered $items argument
     */
    public function cartCounter($items, $args)
    {
        if ($this->location !== $args->theme_location || ! F\isWooCommerceActive()) {
            return $items;
        }

        ob_start();
        // Rendere the cart counter view.
        $cartCounterTmpl = new CartCounterTemplate();
        $cartCounterTmpl->tmpl($cartCounterTmpl->getData());

        $items .= '<li class="menu-item">' . ob_get_clean() . '</li>';

        // Remove after done. May be the nav is used within some menu widget or something else.
        remove_filter('wp_nav_menu_items', array($this, 'cartCounter'), 20, 2);

        return $items;
    }
}
