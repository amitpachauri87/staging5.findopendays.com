<?php
/**
 * Walker Menu Edit Main
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

namespace QiblaFramework\Admin\Walker;

use QiblaFramework\Functions as F;
use QiblaFramework\Plugin;

/**
 * Class WalkerMenuEditMain
 *
 * @props  kucrut <https://github.com/kucrut/wp-menu-item-custom-fields>
 *
 * @since  1.5.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class MenuEditMainWalker extends \Walker_Nav_Menu_Edit
{
    /**
     * The Nav Slug
     *
     * @since  1.5.0
     *
     * @var string
     */
    protected static $taxonomySlug = 'nav_main';

    /**
     * Build Fields
     *
     * @since  1.5.0
     *
     * @return array A list of Fields instances
     */
    protected static function fieldBuilder($itemID)
    {
        // Sanitize.
        $itemID = intval($itemID);
        // Get all fields, but return only the one associated with the menu we are looking for.
        $fields = include Plugin::getPluginDirPath('/inc/menuEditFields.php');

        return $fields[static::$taxonomySlug];
    }

    /**
     * Is Valid Menu
     *
     * @since  1.5.0
     *
     * @param int $menuID The current menu we are filtering.
     *
     * @return bool True if is the $taxonomySlug menu, false otherwise
     */
    protected static function isValidMenu($menuID)
    {
        $valid     = false;
        $locations = get_nav_menu_locations();
        $menu      = wp_get_nav_menu_object($menuID);

        if (isset($locations[static::$taxonomySlug])) {
            // Get the location ID.
            $locationID = $locations[static::$taxonomySlug];

            // Check if the location have the current menu associated.
            if ($menu instanceof \WP_Term && $locationID === $menu->term_id) {
                $valid = true;
            }
        }

        return $valid;
    }

    // @codingStandardsIgnoreStart

    /**
     * @inheritdoc
     */
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        // Initialize the output.
        $item_output = '';

        parent::start_el($item_output, $item, $depth, $args, $id);

        // Get the markup of the fields. We'll add it to the output string.
        $fields       = $this->fieldBuilder($item->ID);
        $fieldsMarkup = array_reduce($fields, function ($output, $field) {
            return ($output . $field);
        }, '');

        // Append the custom fields.
        $output .= preg_replace('/(?=<(fieldset|p)[^>]+class="[^"]*field-move)/', $fieldsMarkup, $item_output);
    }

    // @codingStandardsIgnoreEnd

    /**
     * Edit Menu Main Filter
     *
     * @since  1.5.0
     *
     * @param string $className The walker class name to filter.
     * @param int    $menuID    The current menu id.
     *
     * @return string The filtered class name
     */
    public static function editMenuMainFilter($className, $menuID)
    {
        // Check if the location have the current menu associated.
        if (static::isValidMenu($menuID)) {
            // If so get the class as walker.
            $className = __CLASS__;
        }

        return $className;
    }

    /**
     * Store Extra Menu Fields
     *
     * @since  1.5.0
     *
     * @param int   $menuId       The menu ID.
     * @param int   $menuItemDbId The item ID.
     * @param array $args         Arguments of the menu item.
     *
     * @return void
     */
    public static function storeExtraMenuFields($menuId, $menuItemDbId, $args)
    {
        $fields = static::fieldBuilder($menuItemDbId);

        foreach ($fields as $fieldKey => $field) {
            // Get the value of the input.
            // Since the menu items are saved within the same request, we have all of the items values.
            $fieldKey = explode(':', $fieldKey);
            $fieldKey = $fieldKey[0];
            // @codingStandardsIgnoreLine
            $input = F\filterInput($_POST, "{$fieldKey}_{$menuItemDbId}", $field->getType()->getArg('filter'));

            // Sanitize the value.
            $input = $field->getType()->sanitize($input);

            if ($input) {
                update_post_meta($menuItemDbId, '_' . $fieldKey, $input);
            }
            unset($input);
        }
    }
}
