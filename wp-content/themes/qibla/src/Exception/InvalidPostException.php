<?php

namespace Qibla\Exception;

/**
 * InvalidPostException
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    Qibla\Exceptions
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    GNU General Public License, version 2
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

/**
 * Class InvalidPostException
 *
 * @since   1.2.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package Qibla\Exceptions
 */
class InvalidPostException extends PostException
{
    /**
     * Get Caller
     *
     * Retrieve the caller that throw the exception.
     * Because it is not possible to override the getMessage method.
     *
     * @since  1.2.0
     *
     * @return string The caller name
     */
    protected function getCaller()
    {
        $trace       = debug_backtrace();
        $callerTrace = $trace[2];
        $caller      = '';

        if (isset($callerTrace['class'])) {
            $caller .= $callerTrace['class'];
        }

        if (isset($callerTrace['function'])) {
            // Trim for namespaces.
            $caller = rtrim($caller, '\\') . '\\' . $callerTrace['function'];
        }

        return $caller;
    }

    /**
     * InvalidPostException constructor
     *
     * @since 1.2.0
     */
    public function __construct()
    {
        parent::__construct(
            sprintf(
            /* Translators: 1 is the name of the function that throw the exception */
                esc_html__('%s cannot retrieve post.', 'qibla-framework'), $this->getCaller()
            )
        );
    }
}
