<?php
/**
 * Ajax Request After Submit
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

namespace AppMapEvents\Front\ListingForm;

use QiblaFramework\Functions as Fw;
use QiblaFramework\Request\AbstractRequestAjax;
use QiblaFramework\Request\ResponseAjax;
use AppMapEvents\Package\PackageFormBuilder;

/**
 * Class RequestAjaxAfterSubmit
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class RequestAjaxAfterSubmit extends AbstractRequestAjax
{
    /**
     * @inheritdoc
     */
    public function isValidRequest()
    {
        // @codingStandardsIgnoreStart
        $action = Fw\filterInput($_POST, 'dlajax_action', FILTER_SANITIZE_STRING);
        // @codingStandardsIgnoreEnd
        $isValid = 'post_listing_form_submit_tasks' === $action;

        // Non logged in users are evil.
        if ($isValid && ! is_user_logged_in()) {
            die;
        }

        return $isValid;
    }

    /**
     * Handle Request
     *
     * @since  1.0.0
     * @access public
     *
     * @uses   wp_send_json() To send back the response
     *
     * @return null
     */
    public function handleRequest()
    {
        // Check for the request.
        if (! $this->isValidRequest()) {
            return null;
        }

        Fw\setHeaders();

        // @codingStandardsIgnoreStart
        $packagePost = Fw\filterInput($_POST, PackageFormBuilder::FORM_PACKAGE_KEY, FILTER_SANITIZE_STRING);
        $packagePost = Fw\getPostByName($packagePost, 'event_package');
        $newPost     = Fw\filterInput($_POST, 'qibla_newly_post', FILTER_SANITIZE_NUMBER_INT);
        $packageLang = Fw\filterInput($_POST, 'qibla_listing_form_lang', FILTER_SANITIZE_STRING);
        // @codingStandardsIgnoreEnd

        if(! $packagePost instanceof \WP_Post) {
            return null;
        }

        // Perform the tasks.
        $tasks = new AfterSubmitTasks($packagePost, get_post($newPost), $packageLang);
        $tasks->setPostExpireDate();
        $tasks->addProductToCart();
        $tasks->setPackageRelatedPost();

        $response = $tasks->getResponse();

        // Convert the response to a Response Ajax.
        $response = new ResponseAjax($response->getCode(), $response->getMessage(), $response->getData());
        $response->sendAjaxResponse();
    }

    /**
     * Handle Filter
     *
     * @since  1.0.0
     * @access public static
     */
    public static function handleFilter()
    {
        $instance = new static;
        $instance->handleRequest();
    }
}
