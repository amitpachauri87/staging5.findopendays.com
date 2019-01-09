<?php
/**
 * RequestPostDeleteAjax
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

namespace QiblaListings\Crud\Request;

use QiblaFramework\Functions as Fw;
use QiblaFramework\Request\AbstractRequestAjax;
use QiblaFramework\Request\Nonce;
use QiblaListings\Crud\PostDeleteInterface;

/**
 * Class RequestPostDeleteAjax
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class RequestPostDeleteAjax extends AbstractRequestAjax
{
    /**
     * @inheritdoc
     */
    public function isValidRequest()
    {
        $nonce = new Nonce('_nonce');

        // @codingStandardsIgnoreLine
        $request = Fw\filterInput($_POST, 'dlajax_action', FILTER_SANITIZE_STRING);
        $isValid = 'post_request_delete' === $request;
        $isValid = $isValid && $nonce->verify();

        // Non logged in users are evil.
        if ($isValid && ! is_user_logged_in()) {
            die;
        }

        return $isValid;
    }

    /**
     * Build the Delete Instance Handler
     *
     * @since  1.0.0
     *
     * @return PostDeleteInterface The instance of the class
     */
    public function deleteInstanceBuilder()
    {
        // @codingStandardsIgnoreStart
        $postID = intval(Fw\filterInput($_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT));
        if (! $postID) {
            throw new \InvalidArgumentException('No post provided when trying to delete it.');
        }
        // @codingStandardsIgnoreEnd
        $post = get_post($postID);

        // Let's create it by the post type, but aware of the "listings" post type.
        // Convert it to singular before build the class name instance.
        $className = $post->post_type;

        // The hack.
        if ('listings' === $className) {
            $className = 'listing';
        }

        // Build the class name string based on PSR standard.
        $className = str_replace(array('-', '_'), ' ', $className);
        $className = ucwords($className);
        $className = str_replace(' ', '', $className);
        $className = "QiblaListings\\Crud\\{$className}Delete";

        return new $className($post);
    }

    /**
     * @inheritdoc
     */
    public function handleRequest()
    {
        // Check for the request.
        if (! $this->isValidRequest()) {
            return;
        }

        Fw\setHeaders();

        // Build the instance of the class that will handle the deletion.
        $deleteInstanceHandler = $this->deleteInstanceBuilder();
        // Build the Director.
        $director = new DirectorRequestPostDeleteAjax(
            new RequestPostDeleteController($deleteInstanceHandler)
        );

        $response = $director->director();
        $response->sendAjaxResponse();
    }

    /**
     * Handle Filter
     *
     * @since  1.0.0
     */
    public static function handleFilter()
    {
        $instance = new static;
        $instance->handleRequest();
    }
}
