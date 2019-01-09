<?php

use Qibla\Functions as F;

/**
 * 404 Template
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

get_header('404'); ?>

<div <?php F\scopeID('content') ?> <?php F\scopeClass('wrapper') ?>>

    <?php
    /**
     * Before 404 content
     *
     * @since 1.0.0
     */
    do_action('qibla_before_404_content'); ?>

    <div <?php F\scopeClass('container') ?>>

        <?php
        /**
         * Before 404
         *
         * @since 1.0.0
         */
        do_action('qibla_before_404'); ?>

        <main <?php F\scopeID('main') ?> <?php F\scopeClass() ?>>
            <header <?php F\scopeClass('', 'header') ?>>
                <?php
                /**
                 * Header
                 *
                 * @since 1.0.0
                 */
                do_action('qibla_404_header'); ?>
            </header>

            <?php
            /**
             * 404 Main Content
             *
             * @since 2.0.0
             */
            do_action('qibla_404_content'); ?>
        </main>

        <?php
        /**
         * After 404
         *
         * @since 1.0.0
         */
        do_action('qibla_after_404'); ?>

    </div>

    <?php
    /**
     * After 404 content
     *
     * @since 1.0.0
     */
    do_action('qibla_after_404_content'); ?>

</div>

<?php get_footer('404'); ?>
