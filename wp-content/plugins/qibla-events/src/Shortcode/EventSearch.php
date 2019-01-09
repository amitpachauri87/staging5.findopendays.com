<?php
/**
 * Short-code Event Search
 *
 * @since      1.1.0
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

use QiblaFramework\ListingsContext\Types;
use AppMapEvents\Plugin;
use AppMapEvents\Search as EvSearch;

/**
 * Class EventSearch
 *
 * @since  1.1.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class EventSearch extends AbstractShortcode implements \QiblaFramework\Shortcode\ShortcodeVisualComposerInterface
{
    /**
     * Construct
     *
     * 'nav' => [
     *     'static',
     *     'autocomplete'
     * ]
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        $this->tag      = 'dl_ev_search';
        $this->defaults = array(
            'id'          => 'search_events_form',
            'search_type' => 'dates',
        );
    }

    /**
     * Build Data
     *
     * @since  1.0.0
     *
     * @param array  $atts    The short-code attributes.
     * @param string $content The content within the short-code.
     *
     * @return \stdClass The data instance or null otherwise.
     * @throws \Exception
     */
    public function buildData(array $atts, $content = '')
    {
        $factory = new EvSearch\SearchFactory(
            $atts['search_type'],
            // Unique type of listings in the search bar.
            array('events'),
            $atts['id'],
            'post'
        );

        try {
            return (object)array(
                'form' => $factory->create(),
            );
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Callback
     *
     * @since  1.0.0
     *
     * @param array  $atts    The short-code's attributes.
     * @param string $content The content within the short-code.
     *
     * @return string The short-code markup
     * @throws \Exception
     */
    public function callback($atts = array(), $content = '')
    {
        // Parse the attributes.
        $atts = $this->parseAttrsArgs($atts);
        // Build Data.
        $data = $this->buildData($atts, $content);

        ob_start();
        if ($data instanceof \stdClass) {
            $data->form->tmpl($data->form->getData());
        }

        return ob_get_clean();
    }

    /**
     * @inheritDoc
     */
    public function visualComposerMap()
    {
        return include Plugin::getPluginDirPath('/inc/vcMapping/search.php');
    }
}
