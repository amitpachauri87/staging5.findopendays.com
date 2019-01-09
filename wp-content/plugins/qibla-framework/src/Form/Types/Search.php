<?php
/**
 * Search
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

namespace QiblaFramework\Form\Types;

/**
 * Class Search
 *
 * @since  1.7.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class Search extends Text
{
    /**
     * @inheritDoc
     */
    public function __construct(array $args)
    {
        // Force Type.
        $args['type'] = 'search';

        parent::__construct($args);
    }

    /**
     * @inheritDoc
     */
    public function getHtml()
    {
        $output = parent::getHtml();

        /**
         * Output Filter
         *
         * @since 2.0.0
         *
         * @param string                                $output The output of the input type.
         * @param \QiblaFramework\Form\Interfaces\Types $this   The instance of the type.
         */
        $output = apply_filters('qibla_fw_type_search_output', $output, $this);

        return $output;
    }
}