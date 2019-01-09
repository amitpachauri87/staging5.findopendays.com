<?php
/**
 * DirectorRequestFormLostPassword
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

use QiblaFramework\Request\AbstractDirectorRequestForm;
use QiblaFramework\Request\Response;

/**
 * Class DirectorRequestFormLostPassword
 *
 * @since   1.5.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\LoginRegister
 */
class DirectorRequestFormLostPassword extends AbstractDirectorRequestForm
{
    /**
     * @inheritDoc
     */
    public function director()
    {
        // Validate the form fields.
        $validationResponse = $this->validate();

        // Check for invalid fields before dispatch to the controller.
        if (! empty($validationResponse['invalid'])) {
            $response = new Response(400, esc_html__('Incorrect username or email.', 'qibla-framework'));
        } elseif (! empty($validationResponse['valid'])) {
            $this->injectDataIntoController($validationResponse['valid']);
            // Dispatch to the controller.
            $response = $this->dispatchToController();
        }

        // Set the validation response.
        $response->setData(array(
            'validation_data' => $validationResponse,
        ));

        return $response;
    }
}
