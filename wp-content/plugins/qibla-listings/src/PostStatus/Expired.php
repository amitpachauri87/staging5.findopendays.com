<?php
/**
 * Expired Status
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

namespace QiblaListings\PostStatus;

use QiblaFramework\PostStatus\AbstractPostStatus;

/**
 * Class Expired
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class Expired extends AbstractPostStatus
{
    /**
     * Expired constructor
     *
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct(
            'expired',
            esc_html_x('Expired', 'post-status', 'qibla-listings'),
            array(
                'public'              => false,
                'internal'            => true,
                'private'             => false,
                'exclude_from_search' => true,
                'label_count'         => _n_noop(
                /* Translators: %s are the number of posts marked with that status */
                    'Expired <span class="count">(%s)</span>',
                    'Expired <span class="count">(%s)</span>'
                ),
            )
        );
    }
}
