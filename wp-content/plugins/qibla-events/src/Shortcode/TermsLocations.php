<?php
/**
 * Short-code TermsLocations
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

/**
 * Class TermsLocations
 *
 * @since  1.1.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class TermsLocations extends \QiblaFramework\Shortcode\Terms
{
    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct();
        // Override the shortcode tag name.
        $this->tag = 'dl_ev_term_locations';
    }

    /**
     * @inheritDoc
     */
    public function visualComposerMap()
    {
        $fields = include Plugin::getPluginDirPath('/inc/vcMapping/terms.php');

        $fields['name']        = esc_html_x('Qibla Event Locations Grid', 'shortcode-vc', 'qibla-events');
        $fields['base']        = $this->tag;
        $fields['category']    = esc_html_x('Qibla', 'shortcode-vc', 'qibla-events');
        $fields['description'] = esc_html_x('Event Locations', 'shortcode-vc', 'qibla-events');

        $fields['params'][0]['value'] = 'event_locations';
        $fields['params'][2]['value'] = \QiblaFramework\Functions\getTermsList(array(
            'taxonomy'   => 'event_locations',
            'hide_empty' => false,
        ), 'name', 'slug');

        return $fields;
    }
}
