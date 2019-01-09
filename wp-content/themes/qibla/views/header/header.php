<?php
use Qibla\Functions as F;

/**
 * Header View
 *
 * @since   1.0.0
 *
 * @license GNU General Public License, version 2
 *
 *    This program is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU General Public License
 *    as published by the Free Software Foundation; either version 2
 *    of the License, or (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
?>

<header <?php F\scopeID('header') ?> <?php F\scopeClass('header') ?>>

    <div <?php F\scopeClass('container', '', 'flex') ?>>
        <?php
        /**
         * Header Content
         *
         * @since 1.0.0
         */
        do_action('qibla_header_content'); ?>
    </div>

</header>
