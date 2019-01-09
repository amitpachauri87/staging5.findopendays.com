<?php
/**
 * User
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

namespace QiblaFramework\User;

use QiblaFramework\Exceptions\UserException;
use QiblaFramework\Request\Response;

/**
 * Class User
 *
 * @since   1.5.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\User
 */
class User
{
    /**
     * Insert User
     *
     * @since  1.5.0
     *
     * @see    register_new_user()
     *
     * @param array $userData The data needed to create a new user.
     *
     * @throws \InvalidArgumentException If the user Data is an empty array.
     * @throws UserException If the user cannot be created.
     *
     * @return Response A response instance with a message of successfully created user
     */
    public static function insertUser(array $userData)
    {
        if (empty($userData)) {
            throw new \InvalidArgumentException('User data must not be empty');
        }

        $response = wp_insert_user($userData);

        if (is_wp_error($response)) {
            throw new UserException($response->get_error_message());
        }

        // Set up the Password change nag.
        update_user_option($response, 'default_password_nag', true, true);

        /**
         * Fires after a new user registration has been recorded.
         *
         * @since  1.5.1
         *
         * @param int $response ID of the newly registered user.
         *
         * @hooked wp_send_new_user_notifications
         */
        do_action('register_new_user', $response);

        // Send confimation mail.
        $response = self::confirmResponse($response);

        // Build the response. Add the user ID as additional data.
        // We may need to elaborate the new user after has been inserted.
//        $response = new Response(
//            201,
//            esc_html__('User created Successfully.', 'qibla-framework'),
//            array(
//                // Create the newly user instance.
//                'user' => UserFactory::create($response),
//            )
//        );

        return $response;
    }

    /**
     * Send Confirm Mail
     *
     * @since 2.2.1
     *
     * @param $userID
     *
     * @return bool|Response
     */
    private static function confirmResponse($userID)
    {
        if (! $userID || ! intval($userID)) {
            return false;
        }

        if ($userID) {
            $response = new Response(
                202,
                esc_html__(
                    'We sent you an email to your address with a link to confirm your account.',
                    'qibla-framework'
                )
            );
        } else {
            $response = new Response(
                406,
                esc_html__(
                    'Sorry something went wrong, please try again later or contact the administrator.',
                    'qibla-framework'
                )
            );
        }

        return $response;
    }

    /**
     * login Message Filter
     *
     * @param $message
     *
     * @since 2.2.1
     *
     * @return string
     */
    public static function loginMessageFilter($message)
    {
        // @codingStandardsIgnoreLine
        $action = \QiblaFramework\Functions\filterInput($_REQUEST, 'action', FILTER_DEFAULT);
        switch ($action) {
            case 'resetpass':
                return sprintf(
                    '<p class="message reset-pass">%s <a href="%s">%s</a></p>',
                    esc_html__('Your password has been reset.', 'qibla-framework'),
                    esc_url(home_url('/?action=modal-login')),
                    esc_html__('Login.', 'qibla-framework')
                );
                break;
            default:
                return $message;
                break;
        }
    }

    /**
     * Modal Login Action.
     *
     * @since 2.2.1
     */
    public static function modalLoginAction()
    {
        // @codingStandardsIgnoreLine
        $action  = \QiblaFramework\Functions\filterInput($_REQUEST, 'action', FILTER_DEFAULT);
        $referer = wp_get_referer();
        $path    = parse_url($referer);
        if (! is_user_logged_in() && isset($path['query']) && 'action=resetpass' === $path['query']) {
            switch ($action) {
                case 'modal-login':
                    $modal = new \QiblaFramework\Modal\ModalTemplate(
                        'QiblaFramework\\LoginRegister\\LoginRegisterFormTemplate::loginRegisterFormHelper',
                        array(
                            'class_container'   => \QiblaFramework\Functions\getScopeClass(
                                'modal',
                                '',
                                'login-register'
                            ),
                            'context'           => 'html',
                            'show_close_button' => true,
                        )
                    );

                    $modal->tmpl($modal->getData());
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Log In the user
     *
     * @since  1.5.0
     *
     * @uses   wp_signon() To sign in the user
     *
     * @param \WP_User $user The user to login
     * @param array    $args The arguments to pass to the sign-on function
     *
     * @return Response|\WP_Error|\WP_User
     */
    public static function userLogin(\WP_User $user, array $args)
    {
        if (empty($args)) {
            throw new \InvalidArgumentException('User data must not be empty');
        }

        // User found, let's define if want to user secure login.
        if (is_ssl()) {
            $secureCookie = true;
        } else {
            $secureCookie = false;
        }

        // Sign in the user.
        $response = wp_signon(array(
            'user_login'    => $user->user_login,
            'user_password' => $args['user_password'],
            'remember'      => $args['remember'],
        ), $secureCookie);

        // Set the appropriated response.
        if (is_wp_error($response)) {
            // Create a response for this WP_Error because the error means the user or password
            // are not valid.
            $response = new Response(
                400,
                esc_html__('Invalid username or password.', 'qibla-framework')
            );
        } else {
            $response = new Response(
                200,
                esc_html__('User logged in successfully.', 'qibla-framework')
            );
        }

        return $response;
    }

    /**
     * Login Programmatically
     *
     * @since  1.5.0
     *
     * @uses   wp_set_auth_cookie() To login the user.
     *
     * @param \WP_User $user The user to login
     * @param array    $args The additional arguments needed for the internal function.
     */
    public static function programmaticallyLogin(\WP_User $user, array $args = array())
    {
        // To remember the login or not.
        $remember = isset($args['remember']) ? $args['remember'] : false;

        // Login.
        wp_set_auth_cookie($user->ID, $remember);

        /**
         * Wp Login
         *
         * @since 2.0.0
         *
         * @param string   $user_login The user login.
         * @param \WP_User $user       The user instance.
         */
        do_action('wp_login', $user->user_login, $user);
    }

    /**
     * User Logout Redirect
     *
     * @since 1.7.0
     *
     * @param string $url The logout url.
     *
     * @return string The filtered logout url
     */
    public static function userLogoutRedirect($url)
    {
        $args                = array('action' => 'logout');
        $args['redirect_to'] = home_url('/');

        $url = add_query_arg($args, site_url('wp-login.php', 'login'));
        $url = wp_nonce_url($url, 'log-out');

        return $url;
    }

    /**
     * Is Listings Manager
     *
     * @since 2.0.0
     *
     * @return bool True if current user has `manage_listings` capability, false otherwise.
     */
    public static function isListingsManager()
    {
        return current_user_can('manage_listings');
    }
}
