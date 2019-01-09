<?php
/**
 * DirectorRequestFormRegister
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
use QiblaFramework\Exceptions\UserException;
use QiblaFramework\Request\AbstractDirectorRequestForm;
use QiblaFramework\Request\Response;
use QiblaFramework\User\User;

/**
 * Class DirectorRequestFormRegister
 *
 * @since   1.5.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\LoginRegister\Register
 */
class DirectorRequestFormRegister extends AbstractDirectorRequestForm
{
    /**
     * Assign user to a blog
     *
     * @since  1.5.0
     *
     * @param \WP_User $user The user to assign to the current blog.
     *
     * @throws UserException In case the user cannot be assigned to the current blog.
     *
     * @return void
     */
    protected function assignUserToBlog(\WP_User $user)
    {
        /**
         * Filter the role of the user
         *
         * @since 1.5.0
         *
         * @param string $role The default role to use for the current blog.
         * @param string $user The current user to assign to the blog.
         */
        $role = apply_filters('qibla_fw_user_assign_role_in_blog', get_option('default_role'), $user);

        $response = add_user_to_blog(get_current_blog_id(), $user->ID, $role);

        if (is_wp_error($response)) {
            throw new UserException('User has been created but cannot be set to the blog.', 412);
        }
    }

    /**
     * @inheritDoc
     */
    public function director()
    {
        // Validate the form fields.
        $validationResponse = $this->validate();

        // Check for invalid fields before dispatch to the controller.
        if (! empty($validationResponse['invalid'])) :
            $response = new Response(400, esc_html__('Incorrect username or email.', 'qibla-framework'));
        elseif (! empty($validationResponse['valid'])) :
            $this->injectDataIntoController($validationResponse['valid']);
            // Dispatch to the controller.
            $response = $this->dispatchToController();

            // Everything went well.
            // 201 means that user has been created correctly.
            if (201 === $response->getCode()) :
                // Get the response data.
                $responseData = $response->getData();

                // Retrieve the newly user.
                $user = (isset($responseData['user']) && $responseData['user'] instanceof \WP_User) ?
                    $responseData['user'] :
                    false;

                if ($user) {
                    // Set to the current blog if multi-site.
                    if (is_multisite()) {
                        $this->assignUserToBlog($user);
                    }

                    // Since we use our form even within the WooCommerce template, let's assign the 'customer'
                    // role to the newly created user like WooCommerce does.
                    if (F\isWooCommerceActive()) {
                        $user->add_role('customer');
                    }

                    // Perform the login.
                    User::programmaticallyLogin($responseData['user'], array(
                        'remember' => true,
                    ));
                }
            endif;
            // 202 means that user has been created correctly but not password reset.
            if (202 === $response->getCode()) :
                // Get the response data.
                $responseData = $response->getData();
                // Retrieve the newly user.
                $user = (isset($responseData['user']) && $responseData['user'] instanceof \WP_User) ?
                    $responseData['user'] :
                    false;
                if ($user) {
                    // Set to the current blog if multi-site.
                    if (is_multisite()) {
                        $this->assignUserToBlog($user);
                    }

                    // Since we use our form even within the WooCommerce template, let's assign the 'customer'
                    // role to the newly created user like WooCommerce does.
                    if (F\isWooCommerceActive()) {
                        $user->add_role('customer');
                    }
                }
            endif;
        endif;

        // Set the validation response.
        $response->setData(array(
            'validation_data' => $validationResponse,
        ));

        return $response;
    }
}
