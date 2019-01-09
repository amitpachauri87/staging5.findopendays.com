<?php
/**
 * WalkerMainNav
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

namespace QiblaFramework\Front\Walker;

use QiblaFramework\Functions as F;

/**
 * Class WalkerMainNav
 *
 * @since   1.5.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class NavMainWalker extends \Walker_Nav_Menu
{
    /**
     * User Menu Item
     *
     * Used to known which is the correct element during sub-menu composition.
     *
     * @since  1.5.0
     *
     * @var \WP_Post The post that have the flag for the user logged in menu.
     */
    private $userMenuItem;

    /**
     * Set Hooks
     *
     * @since  1.5.0
     *
     * @return void
     */
    public function setHooks()
    {
        add_filter('nav_menu_css_class', array($this, 'filterMenuClasses'), 20, 4);
    }

    /**
     * Filter Menu Classes
     *
     * @since  1.5.0
x     *
     * @param array    $classes The classes to filter.
     * @param \WP_Post $item    The current menu item.
     * @param array    $args    The arguments of the menu.
     * @param int      $depth   The current menu item depth.
     *
     * @return array The filtered classes
     */
    public function filterMenuClasses($classes, $item, $args, $depth)
    {
        // Get the ID of the item in order to retrieve the style.
        $meta = \QiblaFramework\Functions\getPostMeta('_qibla_nb_item_style', 'none', $item->ID);

        if ('button' === $meta) {
            $classes[] = 'menu-item--btn';
        }

        if ($this->userMenuItem instanceof \WP_Post && ! is_user_logged_in()) {
            $classes[] = 'is-login-register-toggler';

            if ($this->has_children) {
                $index = array_search('menu-item-has-children', $item->classes, true);
                if (false !== $index) {
                    unset($classes[$index]);
                }
            }
        }

        return $classes;
    }

    /**
     * Is User menu Item
     *
     * Check if the menu item have the flag set to be used as user logged in menu.
     *
     * @since 1.5.0
     *
     * @param \WP_Post $item  The menu item to check for.
     * @param int      $depth The current depth level.
     *
     * @return bool
     */
    protected function isUserMenuItem($item, $depth)
    {
        // Check if the current item have the user container flag to 'on'.
        $meta = F\stringToBool(F\getPostMeta('_qibla_nb_item_user_loggedinout', 'off', $item->ID));

        return (0 === $depth && $meta);
    }

    /**
     * @inheritDoc
     */
    // @codingStandardsIgnoreLine
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        // Store the menu item that have the flag set to be used as user logged menu item container.
        if ($this->isUserMenuItem($item, $depth)) {
            $this->userMenuItem = $item;
        }

        // If the user menu item has been set.
        // Avoid to show extra sub menu items not necessary.
        // To be clear, this will remove the items that user non logged in are not allowed to see.
        if ($this->userMenuItem instanceof \WP_Post) {
            if (! is_user_logged_in() && $this->userMenuItem->ID === intval($item->menu_item_parent)) {
                return;
            }
        }

        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ($depth) ? str_repeat($t, $depth) : '';

        $classes   = empty($item->classes) ? array() : (array)$item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        /**
         * Filters the arguments for a single nav menu item.
         *
         * @since WP4.4.0
         *
         * @param \stdClass $args  An object of wp_nav_menu() arguments.
         * @param \WP_Post  $item  Menu item data object.
         * @param int       $depth Depth of menu item. Used for padding.
         */
        $args = apply_filters('nav_menu_item_args', $args, $item, $depth);

        // Add the properly classes to the item.
        $classes = $this->filterMenuClasses($classes, $item, $args, $depth);

        /**
         * Filters the CSS class(es) applied to a menu item's list item element.
         *
         * @since WP3.0.0
         * @since WP4.1.0 The `$depth` parameter was added.
         *
         * @param array     $classes The CSS classes that are applied to the menu item's `<li>` element.
         * @param \WP_Post  $item    The current menu item.
         * @param \stdClass $args    An object of wp_nav_menu() arguments.
         * @param int       $depth   Depth of menu item. Used for padding.
         */
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        /**
         * Filters the ID applied to a menu item's list item element.
         *
         * @since WP3.0.1
         * @since WP4.1.0 The `$depth` parameter was added.
         *
         * @param string    $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param \WP_Post  $item    The current menu item.
         * @param \stdClass $args    An object of wp_nav_menu() arguments.
         * @param int       $depth   Depth of menu item. Used for padding.
         */
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        if ($this->userMenuItem instanceof \WP_Post) {
            // Show the user avatar if logged in and the current item is the one that is set as user logged in menu.
            if (is_user_logged_in() && 0 === $depth && $item->ID === $this->userMenuItem->ID) {
                $output .= get_avatar(wp_get_current_user()->ID, 32);
            }
        }

        $atts           = array();
        $atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = ! empty($item->target) ? $item->target : '';
        $atts['rel']    = ! empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = ! empty($item->url) ? $item->url : '';

        /**
         * Filters the HTML attributes applied to a menu item's anchor element.
         *
         * @since WP3.6.0
         * @since WP4.1.0 The `$depth` parameter was added.
         *
         * @param array     $atts   {
         *                          The HTML attributes applied to the menu item's `<a>` element,
         *                          empty strings are ignored.
         *
         * @type string     $title  Title attribute.
         * @type string     $target Target attribute.
         * @type string     $rel    The rel attribute.
         * @type string     $href   The href attribute.
         * }
         *
         * @param \WP_Post  $item   The current menu item.
         * @param \stdClass $args   An object of wp_nav_menu() arguments.
         * @param int       $depth  Depth of menu item. Used for padding.
         */
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (! empty($value)) {
                $value      = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        if ($this->userMenuItem instanceof \WP_Post) {
            if (is_user_logged_in() && 0 === $depth && $item->ID === $this->userMenuItem->ID) {
                $item->title = sprintf(
                // Translators: %s is the user name.
                    esc_html__('Hello, %s', 'qibla-framework'),
                    wp_get_current_user()->display_name
                );
            }
        }

        // This filter is documented in wp-includes/post-template.php.
        $title = apply_filters('the_title', $item->title, $item->ID);

        /**
         * Filters a menu item's title.
         *
         * @since WP4.4.0
         *
         * @param string    $title The menu item's title.
         * @param \WP_Post  $item  The current menu item.
         * @param \stdClass $args  An object of wp_nav_menu() arguments.
         * @param int       $depth Depth of menu item. Used for padding.
         */
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        /**
         * Filters a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @since WP3.0.0
         *
         * @param string    $item_output The menu item's starting HTML output.
         * @param \WP_Post  $item        Menu item data object.
         * @param int       $depth       Depth of menu item. Used for padding.
         * @param \stdClass $args        An object of wp_nav_menu() arguments.
         */
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    /**
     * @inheritDoc
     */
    // @codingStandardsIgnoreLine
    public function end_el(&$output, $item, $depth = 0, $args = array())
    {
        // @see the start_el().
        if ($this->userMenuItem instanceof \WP_Post) {
            if (! is_user_logged_in() && $this->userMenuItem->ID === intval($item->menu_item_parent)) {
                return;
            }
        }

        parent::end_el($output, $item, $depth, $args);
    }

    /**
     * @inheritDoc
     */
    // @codingStandardsIgnoreLine
    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        // Current user not allowed to view this sub menu?
        if ($this->userMenuItem instanceof \WP_Post && ! is_user_logged_in()) {
            return;
        }

        parent::start_lvl($output, $depth, $args);
    }

    /**
     * @inheritDoc
     */
    // @codingStandardsIgnoreLine
    public function end_lvl(&$output, $depth = 0, $args = array())
    {
        // @see start_lvl().
        if ($this->userMenuItem instanceof \WP_Post) {
            if (! is_user_logged_in()) {
                $this->userMenuItem = null;

                return;
            } else {
                // Show logout url.
                $output .= '<li class="menu-item"><a href="' . wp_logout_url(home_url('/')) . '">' .
                           esc_html__('Sign out', 'qibla-framework') .
                           '</a></li>';

                // Unset to prevent other submenu have the url appended.
                $this->userMenuItem = null;
            }
        }

        parent::end_lvl($output, $depth, $args);
    }

    /**
     * Walker Filter
     *
     * @since  1.6.1
     *
     * @return NavMainWalker instance of the class
     */
    public static function walkerFilter()
    {
        return new static;
    }
}
