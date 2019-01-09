<?php
use Qibla\Functions as F;

/**
 * Index Template
 *
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

get_header(); ?>

<div <?php F\scopeID('content') ?> <?php F\scopeClass('wrapper') ?>>

    <?php
    /**
     * Before archive content
     *
     * @since 1.0.0
     */
    do_action('qibla_before_archive_content'); ?>

    <div <?php F\scopeClass('container', '', 'flex') ?>>

        <?php
        /**
         * Before archive
         *
         * @since 1.0.0
         */
        do_action('qibla_before_archive'); ?>

        <main <?php F\scopeID('main') ?> <?php F\scopeClass() ?>>

            <?php
            if (have_posts()) :
                /**
                 * Before the archive loop
                 *
                 * @since 1.0.0
                 */
                do_action('qibla_before_archive_posts_loop'); ?>

                <div <?php F\scopeClass('grid', '', 'masonry') ?>>
                    <?php
                    while (have_posts()) :
                        the_post();
                        get_template_part('template-parts/loop', F\getLoopContext());
                    endwhile;
                    ?>
                </div>

                <?php
                /**
                 * After the archive loop
                 *
                 * @since 1.0.0
                 */
                do_action('qibla_after_archive_posts_loop');
            else :
                get_template_part('template-parts/content', 'none');
            endif;
            ?>

        </main>

        <?php
        /**
         * After archive
         *
         * @since 1.0.0
         */
        do_action('qibla_after_archive'); ?>

    </div>

    <?php
    /**
     * After archive content
     *
     * @since 1.0.0
     */
    do_action('qibla_after_archive_content'); ?>

</div>

<?php get_footer() ?>
