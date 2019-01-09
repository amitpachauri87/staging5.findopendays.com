<?php
/**
 * LostPasswordFormController
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

use QiblaFramework\Exceptions\UserException;
use QiblaFramework\Request\AbstractRequestFormController;
use QiblaFramework\Request\Response;
use QiblaFramework\User\UserFactory;

/**
 * Class LostPasswordFormController
 *
 * @since   1.5.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\LoginRegister
 */
class LostPasswordFormController extends AbstractRequestFormController
{
    /**
     * Form key
     *
     * @since  1.5.0
     *
     * @var string The form prefix for inputs
     */
    protected static $formKey = 'qibla_lostpassword_form';

    /**
     * Handle
     *
     * @since  1.5.0
     *
     * @throws UserException When the user cannot be retrieved.
     *
     * @return Response The response instance after the controller handled the request
     */
    public function handle()
    {
        // Get user info.
        $user = UserFactory::create(
            $this->data[static::$formKey . '-username_email']
        );

        // User if exists.
        if ($user instanceof \WP_User) {
            $emailer  = new LostPasswordEmailer($user);
            $response = $emailer->sendMessage();
        } else {
            throw new UserException('Invalid user');
        }

        // Add the user instance to the response.
        $response->setData(array('user' => $user));

        return $response;
    }
}
