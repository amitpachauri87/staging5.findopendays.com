<?php
use Qibla\Functions as F;

/**
 * Single Template
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

get_header('single'); ?>

<div <?php F\scopeID('content') ?> <?php F\scopeClass('wrapper') ?>>

    <?php
    /**
     * Before single content
     *
     * @since 1.0.0
     */
    do_action('qibla_before_single_content'); ?>

    <div <?php F\scopeClass('container', '', 'flex') ?>>

        <?php
        /**
         * Before single
         *
         * @since 1.0.0
         */
        do_action('qibla_before_single'); ?>

        <?php while (have_posts()) :
            the_post(); ?>
            <main <?php F\scopeID('main') ?> <?php F\scopeClass('post', '', 'post') ?>>
                <?php get_template_part('template-parts/content', get_post_type()); ?>
            </main>
        <?php endwhile; ?>

        <?php
        /**
         * After single
         *
         * @since 1.0.0
         */
        do_action('qibla_after_single'); ?>

    </div>

    <?php
    /**
     * After single content
     *
     * @since 1.0.0
     */
    do_action('qibla_after_single_content'); ?>

</div>

<?php get_footer('single') ?>
