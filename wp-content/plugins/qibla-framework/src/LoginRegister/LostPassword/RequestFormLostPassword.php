<?php
/**
 * RequestFormLostPassword
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

namespace QiblaFramework\LoginRegister\LostPassword;

use QiblaFramework\Form\Interfaces\Forms;
use QiblaFramework\Form\Validate;
use QiblaFramework\Request\AbstractRequestForm;
use QiblaFramework\Request\Redirect;
use QiblaFramework\Request\Response;

/**
 * Class RequestFormLostPassword
 *
 * @since   1.5.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\LoginRegister
 */
class RequestFormLostPassword extends AbstractRequestForm
{
    /**
     * RequestFormLogin constructor
     *
     * @since 1.5.0
     */
    public function __construct(Forms &$form)
    {
        parent::__construct($form, 'lostpassword_request');
    }

    /**
     * @inheritDoc
     */
    public function handleRequest()
    {
        if (! $this->isValidRequest()) {
            return false;
        }

        try {
            $director = new DirectorRequestFormLostPassword(
                $this->form,
                new Validate(),
                new LostPasswordFormController()
            );
            $response = $director->director();
        } catch (\Exception $e) {
            $response = new Response(
                500,
                esc_html__(
                    'Ops! Something went wrong during password reset. Please contact our support or try in a few minutes.',
                    'qibla-framework'
                )
            );
        }//end try

        // Set the appropriate alert based on the response of the director method.
        self::setAlertResponse('qibla_lostpassword_before_form', $response);

        return $response;
    }
}
