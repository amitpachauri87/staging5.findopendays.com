<?php
use Qibla\Functions as F;

/**
 * Author Template
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

$author = get_queried_object();

if (! is_object($author) && ! is_a($author, 'WP_User')) {
    return;
}

if (! username_exists($author->nickname)) {
    return;
}

// Get author Bio.
$bio = get_user_meta($author->ID, 'description', true);

// Get author Gravatar.
$avatar = get_avatar(
    $author->ID,
    64,
    get_option('avatar_default', 'mystery'),
    sprintf(
        esc_html__('%s Author Avatar', 'qibla'),
        trim(($author->first_name ? $author->first_name . ' ' . $author->last_name : $author->display_name))
    )
);

/**
 * Filter the Author name
 *
 * @since 1.0.0
 *
 * @param string $author_display_name The author name
 * @param object $author              The WP_User object for the current author
 * @param int    $authorID            The Id of the current author
 */
$author_name = apply_filters('qibla_author_page_author_name', $author->display_name, $author, $author->ID);

get_header('author'); ?>

<div <?php F\scopeID('content') ?> <?php F\scopeClass('wrapper') ?>>

    <?php
    /**
     * Before author content
     *
     * @since 1.0.0
     */
    do_action('qibla_before_author_content'); ?>

    <div <?php F\scopeClass('container', '', 'flex') ?>>

        <?php
        /**
         * Before author
         *
         * @since 1.0.0
         */
        do_action('qibla_before_author'); ?>

        <main <?php F\scopeID('main') ?> <?php F\getScopeClass() ?>>

            <?php if ('yes' === apply_filters('qibla_show_author_page_header', 'yes')) : ?>
                <header <?php F\getScopeClass('', 'header') ?>>
                    <h1 <?php F\getScopeClass('', 'title') ?>>
                        <?php echo F\ksesImage($avatar) ?>
                        <?php echo sanitize_user($author_name) ?>
                    </h1>
                    <p <?php F\getScopeClass('', 'description') ?>>
                        <?php echo F\ksesPost($bio) ?>
                    </p>
                </header>
            <?php endif; ?>

            <?php
            /**
             * Before the author loop
             *
             * @since 1.0.0
             */
            do_action('qibla_before_author_posts_loop'); ?>

            <?php
            // @todo Move in a section with a title outside of the main content?
            if (have_posts()) : ?>
                <div <?php F\scopeClass('grid', '', 'masonry') ?>>
                    <?php
                    while (have_posts()) :
                        the_post();
                        get_template_part('template-parts/loop', get_post_format());
                    endwhile;
                    ?>
                </div>
                <?php
            else :
                get_template_part('template-parts/content', 'none');
            endif;

            /**
             * After the author loop
             *
             * @since 1.0.0
             */
            do_action('qibla_after_author_posts_loop');
            ?>
        </main>

        <?php
        /**
         * After author
         *
         * @since 1.0.0
         */
        do_action('qibla_after_author'); ?>

    </div>

    <?php
    /**
     * After author content
     *
     * @since 1.0.0
     */
    do_action('qibla_after_author_content'); ?>

</div>

<?php get_footer('author') ?>
