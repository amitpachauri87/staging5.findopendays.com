<?php
use Qibla\Functions as F;

/**
 * Content Image
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
 * Before Attachment Image
 *
 * @since 1.0.0
 */
do_action('qibla_before_attachment_image_loop'); ?>

<article id="post-<?php the_ID() ?>" <?php post_class(F\getScopeClass('article')) ?>>

    <?php
    /**
     * Before Attachment Image Loop Header
     *
     * @since 1.0.0
     */
    do_action('qibla_before_attachment_image_loop_header'); ?>

    <?php if ('yes' === apply_filters('qibla_show_attachment_image_header', 'yes')) : ?>

        <header <?php F\scopeClass('article', 'header') ?>>

            <?php
            /**
             * Attachment Header
             *
             * @since 1.0.0
             */
            do_action('qibla_attachment_header'); ?>

        </header>
        <!-- .header-entry -->

    <?php endif; ?>

    <?php
    /**
     * Before the attachment image loop entry
     *
     * @since 1.0.0
     */
    do_action('qibla_before_attachment_image_loop_entry_content'); ?>

    <?php echo F\getAttachmentFigureImage(get_the_ID(), 'post-thumbnail', 'article', wp_get_attachment_caption()) ?>

    <div <?php F\scopeClass('article', 'content') ?>>
        <?php the_content() ?>
    </div>

    <?php
    /**
     * After the attachment image loop entry
     *
     * @since 1.0.0
     */
    do_action('qibla_after_attachment_image_loop_entry_content'); ?>

</article>

<?php
/**
 * After Attachment Image
 *
 * @since 1.0.0
 */
do_action('qibla_after_attachment_image_loop'); ?>
