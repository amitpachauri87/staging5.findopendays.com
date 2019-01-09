<?php
/**
 * Password
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
 * Class Password
 *
 * @since   1.5.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\Form\Types
 */
class Password extends Text
{
    /**
     * @inheritDoc
     */
    public function __construct(array $args)
    {
        // Explicitly set the type.
        $args['type'] = 'password';

        parent::__construct($args);
    }

    /**
     * @inheritDoc
     */
    public function sanitize($value)
    {
        return $this->getValue();
    }

    /**
     * @inheritDoc
     */
    public function escape()
    {
        $this->getValue();
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
        $output = apply_filters('qibla_fw_type_password_output', $output, $this);

        return $output;
    }
}
