<?php
/**
 * RequestCrudController
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

use QiblaFramework\Request\AbstractRequestController;
use QiblaFramework\Request\Response;
use QiblaFramework\Wishlist\WishList;

/**
 * Class RequestCrudController
 *
 * @since   2.0.0
 * @package QiblaFramework\Wishlist\Request
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class RequestCrudController extends AbstractRequestController
{
    /**
     * @inheritDoc
     */
    public function handle()
    {
        if (! isset($this->data['action'])) {
            throw new \RuntimeException('Missed action in wishlist controller. Aborting');
        }

        if (! is_string($this->data['action'])) {
            throw new \DomainException('Wrong action value in wishlist controller. Must be a string.');
        }

        // Response to send back to the client.
        $response = null;
        // Create the instance to work with.
        $wishlist = new WishList($this->data['user']);

        switch ($this->data['action']) :
            case 'store':
                // Item all-ready exists.
                if ($wishlist->hasItem($this->data['post'])) {
                    $response = new Response(200, esc_html__('Item allready exists.', 'qibla-framework'));
                    break;
                }

                // Store the item.
                if ($wishlist->storeItem($this->data['post'])) {
                    $response = new Response(201, esc_html__('Wishlist Updated.', 'qibla-framework'));
                }
                break;

            case 'delete':
                // Delete the entire collection.
                if ($wishlist->delete()) {
                    $response = new Response(200, esc_html__('Wishlist emptied correctly.', 'qibla-framework'));
                }
                break;

            case 'remove':
                if ($wishlist->removeItem($this->data['post'])) {
                    $response = new Response(200, esc_html__('Item removed from wishlist.', 'qibla-framework'));
                }
                break;

            default:
                $response = new Response(405, esc_html__('Unknown action.', 'qibla-framework'));
                break;
        endswitch;

        // Return some info in case the request cannot be full-filled.
        if (! $response) {
            $response = new Response(
                406,
                esc_html__('Unknow error occured during processing wishlist action'),
                $this->data
            );
        }

        return $response;
    }
}
