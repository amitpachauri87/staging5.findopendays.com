<?php
/**
 * RequestFormRegisterAjax
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

namespace QiblaFramework\LoginRegister\Register;

use QiblaFramework\Functions as F;
use QiblaFramework\Form\Interfaces\Forms;
use QiblaFramework\Form\Validate;
use QiblaFramework\Request\AbstractRequestFormAjax;
use QiblaFramework\Request\Redirect;
use QiblaFramework\Request\ResponseAjax;

/**
 * Class RequestFormRegisterAjax
 *
 * @since   1.5.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\LoginRegister\Register
 */
class RequestFormRegisterAjax extends AbstractRequestFormAjax
{
    /**
     * RequestFormAjaxLogin constructor
     *
     * @since 1.5.0
     *
     * @param Forms $form The forms to handle with the submission
     */
    public function __construct(Forms $form)
    {
        parent::__construct($form, 'register_request');
    }

    /**
     * @inheritDoc
     */
    public function isValidRequest()
    {
        return parent::isValidRequest() && get_option('users_can_register');
    }

    /**
     * @inheritDoc
     */
    public function handleRequest()
    {
        // Parse the request POST data.
        $this->parsePOSTRequest();

        if (! $this->isValidRequest()) {
            return;
        }

        // Set the header for ajax request.
        F\setHeaders();

        try {
            // Director the request.
            $director = new DirectorRequestFormRegister($this->form, new Validate(), new RegisterFormController());
            $response = $director->director();

            // Rebuild the default Response into an Ajax instance.
            $response = new ResponseAjax(
                $response->getCode(),
                $response->getMessage(),
                $response->getData()
            );

            // Set the redirect.
            $response->setData(array(
                'redirect_to' => Redirect::getRedirect(),
            ));
        } catch (\Exception $e) {
            // Set the message. If the exception has one, or use a default message.
            $message = $e->getMessage() ?:
                esc_html__(
                    'Ops! Something went wrong. Please try in a few minutes or contact our support.',
                    'qibla-framework'
                );

            $response = new ResponseAjax(403, $message);
        }//end try

        $response->sendAjaxResponse();
    }
}
