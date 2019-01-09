<?php
namespace QiblaFramework\Admin\Settings;

use QiblaFramework\Functions as F;

/**
 * Class Settings Store
 *
 * @since      1.0.0
 * @package    QiblaFramework\Admin\Settings
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
 * Class Store
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Store
{
    /**
     * Options Prefix
     *
     * @since  1.0.0
     *
     * @var string The prefix of the options name
     */
    private $optionsPrefix;

    /**
     * Options Name
     *
     * @since  1.0.0
     *
     * @var string The name of the options group to store
     */
    private $optionsName;

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param string $optionsName   The name of the options.
     * @param string $optionsPrefix The prefix for the options.
     */
    public function __construct($optionsName, $optionsPrefix)
    {
        $this->optionsPrefix = $optionsPrefix;
        $this->optionsName   = strtolower($optionsName);
    }

    /**
     * Clean the Options Keys
     *
     * Clean the option key string to don't add the option name as keys for value, instead keep only the
     * single option name.
     *
     * @param array $options The array of the options to clean.
     *
     * @return array The cleaned array
     */
    private function cleanOptionsKeys(array $options)
    {
        $opt = array();
        foreach ($options as $key => $value) {
            $key       = trim(str_replace("{$this->optionsPrefix}{$this->optionsName}", '', $key), '-');
            $opt[$key] = $value;
        }

        return $opt;
    }

    /**
     * Store Options
     *
     * @since  1.0.0
     *
     * @param array $data The data to store in database.
     *
     * @return bool True on success false on failure.
     */
    public function store(array $data)
    {
        if (! current_user_can('edit_theme_options')) {
            wp_die('Cheatin&#8217; Uh?');
        }

        if (! $this->optionsName) {
            return false;
        }

        // Clean the valid keys.
        $optCleaned = $this->cleanOptionsKeys($data);

        switch ($this->optionsName) :
            case 'blog':
                if (in_array('posts_per_page', array_keys($optCleaned), true)) {
                    // Check if the new value is different than the one stored in database.
                    // update_option will return false not only if the data has problem to be stored within the db
                    // but also when the value to update is the same of the one within the db.
                    if (F\getThemeOption('blog', 'posts_per_page') === $optCleaned['posts_per_page']) {
                        break;
                    }

                    update_option('posts_per_page', $optCleaned['posts_per_page']);
                    unset($optCleaned['posts_per_page']);
                }
                break;
            case 'logo':
                if (isset($optCleaned['icon'])) {
                    // Don't unset the site_icon, we need the data of the option to able to export it.
                    update_option('site_icon', $optCleaned['icon']);
                }

                foreach (array('logo', 'logo_dark') as $item) {
                    if (isset($optCleaned[$item])) {
                        // Retrieve the logo ID value.
                        $logoID = intval($optCleaned[$item]);
                        // Keep the option within the array so we can use both theme mod if se or fallback
                        // to option. The brand logo is important.
                        set_theme_mod('custom_' . $item, $logoID);
                    }
                }
                break;
        endswitch;

        // Store the options.
        $updated = update_option($this->optionsPrefix . $this->optionsName, $optCleaned, true);

        // Update the transient for typography.
        if ($updated && 'typography' === $this->optionsName) {
            F\getGoogleFontsUrl(true);
        }

        return $updated;
    }
}
