<?php
/**
 * AjaxRequest
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
use QiblaFramework\Form\Interfaces\Forms;
use QiblaFramework\Form\Validate;
use QiblaFramework\Request\AbstractRequestFormAjax;
use QiblaFramework\Request\ResponseAjax;
use AppMapEvents\Package\PackageFormBuilder;

/**
 * Class AjaxRequest
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package AppMapEvents\Front\ListingForm
 */
class RequestAjax extends AbstractRequestFormAjax
{
    /**
     * Package post
     *
     * @since  1.0.0
     * @access protected
     *
     * @var \WP_Post The post package instance
     */
    protected $packagePost;

    /**
     * RequestAjax constructor
     *
     * @since 1.0.0
     *
     * @param Forms  $form              The instance of the form.
     * @param string $requestActionName The name of the action to perform with the submit values.
     *
     * @inheritdoc
     */
    public function __construct(Forms $form, $requestName, \WP_Post $packagePost)
    {
        parent::__construct($form, $requestName);
        $this->packagePost = $packagePost;
    }

    /**
     * @inheritdoc
     */
    public function isValidRequest()
    {
        $isValid = parent::isValidRequest();
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
        // Parse the request POST data.
        $this->parsePOSTRequest();

        // Check for the request.
        if (! $this->isValidRequest()) {
            return null;
        }

        Fw\setHeaders();

        $director = new DirectorAjax(
            $this->form,
            new Validate(),
            new Controller($this->form, $this->actionName),
            $this->packagePost,
            new Template()
        );
        // Delegate to the director the form validation and other stuffs.
        $response = $director->director();

        // Convert to an ajax response.
        $response = new ResponseAjax($response->getCode(), $response->getMessage(), $response->getData());
        // Send back the response.
        $response->sendAjaxResponse();
    }

    /**
     * Handle Filter
     *
     * @since  1.0.0
     * @access public static
     *
     * @return void
     */
    public static function handleFilter()
    {
        // @codingStandardsIgnoreLine
        $package     = Fw\filterInput($_POST, PackageFormBuilder::FORM_PACKAGE_KEY, FILTER_SANITIZE_STRING);
        $packageType = Fw\filterInput($_POST, PackageFormBuilder::FORM_PACKAGE_POST_TYPE, FILTER_SANITIZE_STRING);

        // Retrieve the package by the POST request, needed to build the form to send to the constructor.
        if ($package && 'event_package' === $packageType) {
            $package = Fw\getPostByName($package, 'event_package');
        }

        // Retrieve the action to perform.
        // @codingStandardsIgnoreStart
        $action = Fw\filterInput(
            $_POST,
            PackageFormBuilder::FORM_PACKAGE_ACTION_KEY,
            FILTER_SANITIZE_STRING
        ) ?: 'create';
        // @codingStandardsIgnoreEnd

        if ($package instanceof \WP_Post && 'event_package' === $packageType) {
            // Get the form package Post.
            $form = PackageFormBuilder::getFormHelper($package, null, $action);

            $instance = new static($form, $action, $package);
            $instance->handleRequest();
        }
    }
}
