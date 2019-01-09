<?php
/**
 * Filter Email Recipient By Listing Author
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

namespace QiblaWcListings\Email;

/**
 * Class FilterEmailRecipientByListingAuthor
 *
 * @since  1.2.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class FilterEmailRecipientByListingAuthor
{
    /**
     * User
     *
     * @since 1.2.0
     *
     * @var \WP_User The user of which retrieve the email
     */
    private $user;

    /**
     * FilterEmailRecipientByListingAuthor constructor
     *
     * @since 1.2.0
     *
     * @param \WP_User $user The user from which retrieve the email.
     */
    public function __construct(\WP_User $user)
    {
        $this->user = $user;
    }

    /**
     * Check if user is allowed
     *
     * @since 1.2.0
     *
     * @return bool True if allowed, false otherwise
     */
    private function isUserAllowed()
    {
        return $this->user->has_cap('manage_listings');
    }

    /**
     * Filter
     *
     * @since 1.2.0
     *
     * @param string $recipient The email recipient to filter
     *
     * @return FilterEmailRecipientByListingAuthor The instance of the class for chaining
     */
    public function filter(&$recipient)
    {
        if ($this->isUserAllowed()) {
            // Get the user email.
            $userEmail = $this->user->user_email;

            // Add the new recipient only if not exists in the current one.
            if (is_array($recipient) && ! in_array($userEmail, $recipient, true)) {
                $recipient[] = $userEmail;
            } elseif (is_string($recipient) && false === strpos($recipient, $userEmail)) {
                $recipient = rtrim($recipient, ',') . ",{$userEmail}";
            }
        }

        return $this;
    }
}
