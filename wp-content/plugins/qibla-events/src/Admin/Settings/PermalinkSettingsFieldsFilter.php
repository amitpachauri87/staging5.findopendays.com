<?php
/**
 * PermalinkSettingsFieldsFilter
 *
 * @since      1.0.0
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

namespace AppMapEvents\Admin\Settings;

use AppMapEvents\FilterInterface;
use AppMapEvents\Plugin;
use QiblaFramework\Admin\PermalinkSettings;

/**
 * Class PermalinkSettingsFieldsFilter
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class PermalinkSettingsFieldsFilter implements FilterInterface
{
    /**
     * List
     *
     * @since 1.0.0
     *
     * @var array The list to add
     */
    private $list;

    /**
     * Settings
     *
     * @since 1.0.0
     *
     * @var array The settings for the permalinks
     */
    private $settings;

    /**
     * Object
     *
     * @since 1.0.0
     *
     * @var \QiblaFramework\Admin\PermalinkSettings The original reference to the class
     */
    private $obj;

    /**
     * The list to filter
     *
     * @since 1.0.0
     *
     * @var array The list to filter
     */
    private $toFilter;

    /**
     * PermalinkSettingsFieldsFilter constructor
     *
     * @since 1.0.0
     *
     * @param \QiblaFramework\Admin\PermalinkSettings $obj      The original reference to the class.
     * @param  array                                  $settings The settings for the permalinks.
     * @param  array                                  $toFilter The list to filter.
     */
    public function __construct(PermalinkSettings $obj, $settings, $toFilter)
    {
        $this->settings = $settings;
        $this->obj      = $obj;
        $this->toFilter = $toFilter;
        $this->list     = include Plugin::getPluginDirPath('/inc/settingFields/permalinkSettingsFields.php');
    }

    /**
     * @inheritdoc
     */
    public function filter()
    {
        $list = array_merge($this->toFilter, $this->list);

        // Return only current list.
        return array_merge($list);
    }

    /**
     * Filter Helper
     *
     * @since 1.0.0
     *
     * @param $list
     * @param $obj
     * @param $settings
     *
     * @return array The filtered list.
     */
    public static function filterFilter($list, $obj, $settings)
    {
        $instance = new self($obj, $settings, $list);

        return $instance->filter();
    }
}
