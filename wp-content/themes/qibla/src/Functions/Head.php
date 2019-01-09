<?php
namespace Qibla\Functions;

/**
 * Head Functions
 *
 * @license GNU General Public License, version 2
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

/**
 * Meta Device
 *
 * @since 1.0.0
 *
 * @return void Echo the correct meta tag content for viewport based on device.
 */
function metaDevice()
{
    if (wp_is_mobile()) {
        echo '<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1" />';
    } else {
        echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    }
}
