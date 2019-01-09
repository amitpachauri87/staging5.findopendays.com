<?php
use Qibla\Functions as F;

/**
 * Standard Post Format
 *
 * @since   1.0.0
 * @package Qibla\template
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

/**
 * Before Single Loop Header
 *
 * @since 1.0.0
 */
do_action('qibla_before_single_loop_header'); ?>

<?php if ('yes' === apply_filters('qibla_show_single_header', 'yes')) : ?>

    <header <?php F\scopeClass('article', 'header') ?>>

        <?php
        /**
         * Single Header
         *
         * @since 1.0.0
         */
        do_action('qibla_single_header'); ?>

    </header>

<?php endif; ?>

<?php
/**
 * Before the single loop entry
 *
 * @since 1.0.0
 */
do_action('qibla_before_single_loop_entry_content'); ?>

<?php if (get_the_content()) : ?>
    <div <?php F\scopeClass('article', 'content') ?>>
        <?php the_content(); ?>
    </div>
<?php endif; ?>

<?php
/**
 * After the single loop entry
 *
 * @since 1.0.0
 */
do_action('qibla_after_single_loop_entry_content'); ?>
