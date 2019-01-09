<?php
/**
 * LoginFormController
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

use QiblaFramework\Request\AbstractRequestFormController;
use QiblaFramework\Request\Response;
use QiblaFramework\User\User;

/**
 * Class LoginFormController
 *
 * @since   1.5.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\LoginRegister\Register
 */
class RegisterFormController extends AbstractRequestFormController
{
    /**
     * Form key
     *
     * @since  1.5.0
     *
     * @var string The form prefix for inputs
     */
    protected static $formKey = 'qibla_register_form';

    /**
     * User Name RegExp
     *
     * User name regExp used to build the user first and last name
     *
     * @since  1.5.0
     *
     * @var string The pattern for the RegExp
     */
    protected static $userNameRegExp = '/[-_\s\.\,]+/';

    /**
     * Build User Data
     *
     * @since  1.5.0
     *
     * @return array The user data
     */
    protected function buildUserData($args)
    {
        // Initialize data.
        $data = array();

        // Create the user First and last name.
        $userName = preg_split(static::$userNameRegExp, $args['userName']);
        if (! empty($userName)) {
            $data['first_name'] = ucfirst($userName[0]);
            $data['last_name']  = isset($userName[1]) ? ucfirst($userName[1]) : '';
        }

        // Build the user_login.
        $data['user_login'] = sanitize_user($args['userName'], true);

        // Set the email.
        // Here we can trust the email, but just in case, sanitize it.
        $data['user_email'] = sanitize_email($args['userEmail']);

        // Don't show the admin bar.
        $data['show_admin_bar_front'] = false;

        /**
         * Filter the role of the user
         *
         * @since 1.5.0
         *
         * @param string $role The default role.
         * @param array  $data The data used to insert the user.
         */
        $role = apply_filters('qibla_fw_new_user_role', get_option('default_role'), $data);

        // Set the role.
        $data['role'] = $role;

        // Generate the password.
        $data['user_pass'] = wp_generate_password();

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function handle()
    {
        // Get the user data.
        $userName  = $this->data[static::$formKey . '-username'];
        $userEmail = $this->data[static::$formKey . '-email'];

        // User Name exists?
        if (username_exists($userName)) :
            $response = new Response(
                400,
                sprintf(
                /* Translators: %1 is the name of the user. */
                    esc_html__('User with name %s already exists. Please choose another one.', 'qibla-framework'),
                    $userName
                )
            );
        // An user with the same Email all ready exists?
        elseif (email_exists($userEmail)) :
            $response = new Response(
                400,
                esc_html__('You all ready have an account with this email. Try to login.', 'qibla-framework')
            );
        // User doesn't exists, let's create one.
        else :
            // Build user data.
            $userData = $this->buildUserData(compact('userName', 'userEmail'));
            $response = User::insertUser($userData);
        endif;

        return $response;
    }
}
