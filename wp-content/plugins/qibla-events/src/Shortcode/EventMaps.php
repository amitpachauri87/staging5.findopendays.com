<?php
/**
 * EventMaps.php
 *
 * @since      1.1.0
 * @package    AppMapEvents\Shortcode
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

namespace AppMapEvents\Shortcode;

use QiblaFramework\Plugin;
use QiblaFramework\Shortcode\Maps;

/**
 * Class EventMaps
 *
 * @since  1.1.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class EventMaps extends Maps
{
    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct();
        // Override the shortcode tag name.
        $this->tag = 'dl_ev_maps';
    }

    /**
     * @inheritDoc
     */
    public function visualComposerMap()
    {
        $fields = include Plugin::getPluginDirPath('/inc/vcMapping/maps.php');

        $fields['name']        = esc_html_x('Event Map', 'shortcode-vc', 'qibla-framework');
        $fields['base']        = $this->tag;
        $fields['category']    = esc_html_x('Qibla', 'shortcode-vc', 'qibla-framework');

        $fields['params'][0]['value'] = 'events';
        $fields['params']['categories']['value'] = array_merge(array(
            esc_attr__('All', 'qibla-events') => '',
        ), array_flip(\QiblaFramework\Functions\getTermsList(array(
            'taxonomy'   => 'event_categories',
            'hide_empty' => false,
        ))));
        $fields['params']['locations']['value'] = array_merge(array(
            esc_attr__('All', 'qibla-events') => '',
        ), array_flip(\QiblaFramework\Functions\getTermsList(array(
            'taxonomy'   => 'event_locations',
            'hide_empty' => false,
        ))));

        return $fields;
    }
}
