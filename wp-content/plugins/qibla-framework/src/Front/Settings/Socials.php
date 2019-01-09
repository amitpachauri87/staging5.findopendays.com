<?php
namespace QiblaFramework\Front\Settings;

use QiblaFramework\Functions as F;
use QiblaFramework\Plugin;
use QiblaFramework\TemplateEngine as T;

/**
 * Class Front-end Settings Socials
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

/**
 * Class Socials
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Socials
{
    /**
     * Get Socials List Names
     *
     * Retrieve the list of the registered socials links names.
     *
     * @since  1.0.0
     *
     * @return array The list of the registered socials links names.
     */
    protected function getSocialsListNames()
    {
        $list    = array();
        $socials = include Plugin::getPluginDirPath('/inc/settingFields/socialsFields.php');

        if (! $socials) {
            return array();
        }

        // Store the socials option names into the list.
        foreach ($socials as $key => $field) {
            $key    = str_replace('qibla_opt-', '', $field->getArg('name'));
            $parts  = explode('-', $key);
            $list[] = str_replace('-', '_', sanitize_key(end($parts)));
        }

        return $list;
    }

    /**
     * Socials Links
     *
     * @since  1.0.0
     *
     * @return \stdClass The data instance.
     */
    public function getSocialsLinks()
    {
        // Retrieve the list of the socials links registered.
        $list = $this->getSocialsListNames();
        // Initialize data object.
        $data = new \stdClass();

        if (! empty($list)) {
            // Build the socials links data list.
            foreach ($list as $social) {
                $href = F\getThemeOption('socials', $social);
                if ($href) {
                    $data->linksList[$social] = (object)array(
                        'href'  => esc_url($href),
                        'class' => F\getScopeClass('socials-links__link', '', $social),
                        'label' => $social,
                    );
                }
            }
        }

        return $data;
    }

    /**
     * Socials Links Template
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function socialsLinksTmpl()
    {
        $data = $this->getSocialsLinks();

        if (empty($data->linksList)) {
            return;
        }

        $engine = new T\Engine('settings_socials_links', $data, '/views/socialsLinks.php');
        $engine->render();
    }
}
