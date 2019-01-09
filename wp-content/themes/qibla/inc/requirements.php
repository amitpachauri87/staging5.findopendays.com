<?php
/**
 * Requirements
 *
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    GNU General Public License, version 2
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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

global $wp_version;

return array(
    'php'       => array(
        'current'  => PHP_VERSION,
        'required' => '5.3',
        'compare'  => '<',
        'type'     => 'error',
        'message'  => sprintf(
        /* Translators: The %s is the php version of the user. */
            esc_html__(
                'Qibla theme require at least php version 5.3 yours is %s. Restoring the previous theme.',
                'qibla'
            ),
            PHP_VERSION
        ),
    ),
    'wordpress' => array(
        'current'  => $wp_version,
        'required' => '4.6',
        'compare'  => '<',
        'type'     => 'error',
        'message'  => sprintf(
        /* Translators: The  current WordPress version installed. */
            esc_html__(
                'Qibla theme require at least WordPress version 4.6 yours is %s. The theme may not work properly.',
                'qibla'
            ),
            $wp_version
        ),
    ),
);
