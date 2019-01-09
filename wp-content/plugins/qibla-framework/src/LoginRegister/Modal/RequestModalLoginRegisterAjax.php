<?php
/**
 * Request Modal for Contact Form via Ajax
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

namespace QiblaFramework\LoginRegister\Modal;

use QiblaFramework\Functions as F;
use QiblaFramework\Request\AbstractRequestAjax;
use QiblaFramework\Request\ResponseAjax;

/**
 * Class RequestModalContactFormAjax
 *
 * @since  1.5.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class RequestModalLoginRegisterAjax extends AbstractRequestAjax
{
    /**
     * @inheritDoc
     */
    public function isValidRequest()
    {
        // @codingStandardsIgnoreLine
        $action  = F\filterInput($_POST, self::$actionKey, FILTER_SANITIZE_STRING);
        $isValid = 'modal_login_register_request' === $action;

        return $isValid;
    }

    /**
     * @inheritDoc
     */
    public function handleRequest()
    {
        if (! $this->isValidRequest()) {
            return;
        }

        try {
            $director = new DirectorRequestModalLoginRegister(
                new RequestModalLoginRegisterController()
            );
            $response = $director->director();
            $response = new ResponseAjax(
                $response->getCode(),
                $response->getMessage(),
                $response->getData()
            );
        } catch (\Exception $e) {
            $response = new ResponseAjax(500, '');
        }

        // Send the response.
        $response->sendAjaxResponse();
    }

    /**
     * Handle Request Filter
     *
     * @since 1.5.0
     *
     * @return void
     */
    public static function handleFilter()
    {
        $instance = new static;
        $instance->handleRequest();
    }
}
