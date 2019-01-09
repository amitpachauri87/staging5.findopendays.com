<?php
/**
 * RequestFormAjaxLogin
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

namespace QiblaFramework\LoginRegister\Login;

use QiblaFramework\Form\Interfaces\Forms;
use QiblaFramework\Form\Validate;
use QiblaFramework\Functions as F;
use QiblaFramework\Request\AbstractRequestFormAjax;
use QiblaFramework\Request\Redirect;
use QiblaFramework\Request\ResponseAjax;

/**
 * Class RequestFormAjaxLogin
 *
 * @since   1.5.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\LoginRegister\Login
 */
class RequestFormLoginAjax extends AbstractRequestFormAjax
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
        parent::__construct($form, 'login_request');
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

        F\setHeaders();

        try {
            // Director the request.
            $director = new DirectorRequestFormLogin($this->form, new Validate(), new LoginFormController());
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
            $response = new ResponseAjax(
                403,
                esc_html__('Ops! Something went wrong during login. Please try in a few minutes.', 'qibla-framework')
            );
        }//end try

        $response->sendAjaxResponse();
    }
}
