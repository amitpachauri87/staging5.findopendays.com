<?php
/**
 * LostPasswordEmailer
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

use QiblaFramework\Functions as Fw;
use QiblaFramework\Request\Response;

/**
 * Class LostPasswordEmailer
 *
 * @since   1.5.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\LoginRegister\LostPassword
 */
class LostPasswordEmailer
{
    protected $user;

    protected $subject;

    protected $message;

    protected function sanitizeUserEmail()
    {
        return sanitize_user_field('email', $this->user->user_email, $this->user->ID, 'raw');
    }

    protected function sanitizeSubject()
    {
        return wp_specialchars_decode(sanitize_text_field($this->subject));
    }

    protected function sanitizeMessage()
    {
        return Fw\ksesPost($this->message);
    }

    protected function setSubject($subject)
    {
        if (is_multisite()) {
            $blogname = get_network()->site_name;
        } else {
            // The blogname option is escaped with esc_html on the way into the database
            // in sanitize_option we want to reverse this for the plain text arena of emails.
            $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        }

        $this->subject = $this->subject ?: sprintf(
        /* Translators: Password reset email subject. 1: Site name. */
            esc_html__('[%s] Password Reset', 'qibla-framework'),
            $blogname
        );
    }

    public function __construct(\WP_User $user, $subject = '', $message = '')
    {
        $this->user    = $user;
        $this->message = $message;

        $this->setSubject($subject);
    }

    public function sendMessage()
    {
        // Redefining user_login ensures we return the right case in the email.
        $key = get_password_reset_key($this->user);

        if (is_wp_error($key)) {
            throw new \LogicException($key->get_error_message());
        }

        $message = esc_html__('Someone has requested a password reset for the following account:', 'qibla-framework') .
                   "\r\n\r\n";
        $message .= network_home_url('/') . "\r\n\r\n";
        $message .= sprintf(esc_html__('Username: %s', 'qibla-framework'), $this->user->user_login) . "\r\n\r\n";
        $message .= esc_html__(
                        'If this was a mistake, just ignore this email and nothing will happen.',
                        'qibla-framework'
                    ) . "\r\n\r\n";
        $message .= esc_html__('To reset your password, visit the following address:', 'qibla-framework') . "\r\n\r\n";
        // Set the link.
        $message .= sprintf(
            "<%s>\r\n",
            network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($this->user->user_login), 'login')
        );

        // Send the email.
        if ($message && ! wp_mail($this->sanitizeUserEmail(), $this->sanitizeSubject(), $message)) {
            throw new \LogicException(
                'The email could not be sent. Possible reason: your host may have disabled the mail() function.'
            );
        } else {
            $response = new Response(
                200,
                esc_html__('The new password has been sent to your email.', 'qibla-framework')
            );
        }

        return $response;
    }
}
