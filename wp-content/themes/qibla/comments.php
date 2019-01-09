<?php
use Qibla\Functions as F;

/**
 * Comments Template
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

<section id="comments" <?php F\scopeClass('comments') ?>>

    <?php if (! post_password_required()) :

        /**
         * Before comments
         *
         * @since 1.0.0
         */
        do_action('qibla_before_comments');

        if (have_comments()) :
            /**
             * Before comments list
             *
             * @since 1.0.0
             */
            do_action('qibla_before_comments_list'); ?>

            <ol <?php F\scopeClass('comments', 'list') ?>>
                <?php wp_list_comments(array(
                    'max_depth'   => get_option('thread_comments') ? intval(get_option('thread_comments_depth')) : 1,
                    'avatar_size' => 64,
                )); ?>
            </ol>

            <?php
            /**
             * After comments list
             *
             * @since 1.0.0
             */
            do_action('qibla_after_comments_list');

        endif;

        if (comments_open() && ! have_comments()) : ?>
            <p <?php F\scopeClass('comments', 'nocomments') ?>>
                <?php esc_html_e('There are no comments yet.', 'qibla'); ?>
            </p>
        <?php elseif (! comments_open() && 'post' === get_post_type()) : ?>
            <p <?php F\scopeClass('comments', 'nocomments') ?>>
                <?php esc_html_e('Comments are closed.', 'qibla'); ?>
            </p>
        <?php endif;

        if (comments_open()) :
            // Enqueue comment reply script.
            if (get_option('thread_comments')) {
                wp_enqueue_script('comment-reply');
            }

            comment_form();
        endif; ?>

        <?php
        /**
         * After comments
         *
         * @since 1.0.0
         */
        do_action('qibla_after_comments');

    endif; ?>

</section>
