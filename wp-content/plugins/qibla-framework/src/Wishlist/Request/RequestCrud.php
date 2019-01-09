<?php
/**
 * RequestItem
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @package   dreamlist-framework
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

namespace QiblaFramework\Wishlist\Request;

use \QiblaFramework\Functions as F;
use QiblaFramework\Request\AbstractRequestAjax;
use QiblaFramework\Request\Nonce;
use QiblaFramework\Request\Response;
use QiblaFramework\Request\ResponseAjax;

/**
 * Class RequestItem
 *
 * @since   2.0.0
 * @package QiblaFramework\Wishlist\Request
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class RequestCrud extends AbstractRequestAjax
{
    /**
     * @inheritDoc
     */
    public function isValidRequest()
    {
        // @codingStandardsIgnoreLine
        $valid = F\filterInput($_POST, 'dlajax_action', FILTER_SANITIZE_STRING);
        $nonce = new Nonce('wishlist');

        return $valid and ('wishlist' === $valid) and $nonce->verify() && is_user_logged_in();
    }

    /**
     * @inheritDoc
     */
    public function handleRequest()
    {
        if (! $this->isValidRequest()) {
            return;
        }

        F\setHeaders();

        // Retrieve the data.
        // @codingStandardsIgnoreStart
        $post   = get_post(intval(F\filterInput($_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT)));
        $action = F\filterInput($_POST, 'crud_action', FILTER_SANITIZE_STRING);
        // @codingStandardsIgnoreEnd

        // Return silently.
        if (! $post || ! $action) {
            return;
        }

        try {
            $user     = wp_get_current_user();
            $director = new DirectorRequestCrud(new RequestCrudController(), $user, get_post($post), $action);
            $response = $director->director();
        } catch (\Exception $e) {
            $response = new Response($e->getCode(), $e->getMessage());
        }

        $response = new ResponseAjax(
            $response->getCode(),
            $response->getMessage(),
            $response->getData()
        );

        $response->sendAjaxResponse();
    }

    /**
     * Helper
     *
     * @since 2.0.0
     */
    public static function handleFilter()
    {
        $instance = new self;
        $instance->handleRequest();
    }
}
