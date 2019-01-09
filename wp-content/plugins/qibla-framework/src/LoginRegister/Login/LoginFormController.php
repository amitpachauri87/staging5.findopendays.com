<?php
/**
 * LoginController
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

use QiblaFramework\Request\AbstractRequestFormController;
use QiblaFramework\Request\Response;
use QiblaFramework\User\User;
use QiblaFramework\User\UserFactory;

/**
 * Class LoginController
 *
 * @since   1.5.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\LoginRegister
 */
class LoginFormController extends AbstractRequestFormController
{
    /**
     * Form key
     *
     * @since  1.5.0
     *
     * @var string The form prefix for inputs
     */
    protected static $formKey = 'qibla_login_form';

    /**
     * Handle
     *
     * @since  1.5.0
     *
     * @return Response The response instance after the controller handled the request
     */
    public function handle()
    {
        // Get user info.
        $user     = $this->data[static::$formKey . '-username_email'];
        $pass     = $this->data[static::$formKey . '-password'];
        $remember = $this->data[static::$formKey . '-remember'];

        $user = UserFactory::create($user);

        // Login user if exists.
        if ($user instanceof \WP_User) {
            $response = User::userLogin($user, array(
                'user_password' => $pass,
                'remember'      => $remember,
            ));
        } else {
            $response = new Response(
                404,
                esc_html__('Invalid User. Please try again.', 'qibla-framework')
            );
        }
        return $response;
    }
}
