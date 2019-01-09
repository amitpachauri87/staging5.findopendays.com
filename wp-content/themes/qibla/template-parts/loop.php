<?php
use Qibla\Functions as F;

/**
 * Main Loop
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

/**
 * Before Post
 *
 * @since 1.0.0
 */
do_action('qibla_before_main_loop'); ?>

<article id="post-<?php the_ID() ?>" <?php post_class(F\getScopeClass('article', '', array('overlay', 'card'))) ?>>

    <div <?php F\scopeClass('article-card-box') ?>>
        <?php
        /**
         * Before Loop Header
         *
         * @since 1.0.0
         */
        do_action('qibla_before_loop_header'); ?>

        <header <?php F\scopeClass('article', 'header') ?>>

            <a <?php F\scopeClass('article', 'link') ?> href="<?php echo esc_url(get_permalink()) ?>">
                <?php
                /**
                 * Loop Header
                 *
                 * @since 1.0.0
                 */
                do_action('qibla_loop_header'); ?>
            </a>

        </header>

        <?php
        /**
         * Loop Entry Content
         *
         * @since 1.0.0
         */
        do_action('qibla_loop_entry_content'); ?>
    </div>
</article>

<?php
/**
 * After Post
 *
 * @since 1.0.0
 */
do_action('qibla_after_loop'); ?>
