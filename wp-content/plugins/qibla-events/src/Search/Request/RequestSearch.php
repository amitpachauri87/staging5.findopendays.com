<?php
/**
 * RequestSearch
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

namespace AppMapEvents\Search\Request;

use QiblaFramework\Functions as F;
use QiblaFramework\Request\Nonce;
use QiblaFramework\Utils\Json\Decoder;

/**
 * Class RequestSearch
 *
 * @since  1.1.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class RequestSearch
{
    /**
     * @inheritDoc
     */
    public function isValidRequest()
    {
        $nonce         = new Nonce('dlsearch_input');
        $taxonomyNonce = new Nonce('dlsearch_taxonomy');

        // @codingStandardsIgnoreLine
        $taxonomyFilter = F\filterInput($_POST, 'qibla_taxonomy_filter_taxonomy', FILTER_SANITIZE_STRING);

        // We don't care about the value right now,
        // just want to know if the value is set and verify the nonce if so.
        // @codingStandardsIgnoreLine
        if (isset($_POST["qibla_{$taxonomyFilter}_filter"])) {
            return $nonce->verify() && $taxonomyNonce->verify();
        }

        return $nonce->verify();
    }

    /**
     * @inheritDoc
     */
    public function handleRequest()
    {
        if (! $this->isValidRequest()) {
            return;
        }

        $director = new DirectorRequestSearch(new RequestSearchController(), $this->context());
        $director->director();
    }

    /**
     * Handle Request Filter
     *
     * @since 1.7.0
     */
    public static function handleRequestFilter()
    {
        $instance = new self;
        $instance->handleRequest();
    }

    /**
     * Retrieve context
     *
     * @since 1.1.0
     *
     * @return string The context of the search
     */
    private function context()
    {
        // @codingStandardsIgnoreLine
        $context = F\filterInput($_POST, 'dlautocomplete_context');
        $context = $context ? wp_unslash($context) : 's';

        // Build the json.
        if (F\isJSON($context)) {
            $json    = new Decoder($context);
            $context = $json->decode();
        }

        return $context;
    }
}
