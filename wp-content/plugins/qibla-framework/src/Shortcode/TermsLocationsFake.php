<?php
/**
 * TermsLocations
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

namespace QiblaFramework\Shortcode;

use QiblaFramework\Plugin;

/**
 * Class TermsLocations
 *
 * Don't use this class as shortcode. Use QiblaFramework\Shortcodes\Terms.
 * This is useful until we create a new Visual Composer field for manage taxonomies and terms relation.
 *
 * @since   1.6.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\Shortcode
 */
class TermsLocationsFake extends Terms
{
    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct();
        // Override the shortcode tag name.
        $this->tag = 'dl_term_locations';
    }

    /**
     * @inheritDoc
     */
    public function visualComposerMap()
    {
        $fields = include Plugin::getPluginDirPath('/inc/vcMapping/terms.php');

        $fields['name']        = esc_html_x('Qibla Locations Grid', 'shortcode-vc', 'qibla-framework');
        $fields['base']        = $this->tag;
        $fields['category']    = esc_html_x('Qibla', 'shortcode-vc', 'qibla-framework');
        $fields['description'] = esc_html_x('Locations Grid', 'shortcode-vc', 'qibla-framework');

        $fields['params'][0]['value'] = 'locations';
        $fields['params'][2]['value'] = \QiblaFramework\Functions\getTermsList(array(
            'taxonomy'   => 'locations',
            'hide_empty' => false,
        ), 'name', 'slug');

        return $fields;
    }
}
