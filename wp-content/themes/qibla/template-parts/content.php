<?php
use Qibla\Functions as F;

/**
 * Content Single
 *
 * This template part should be used only for single post type pages.
 * If you need a template part for the main content, consider to include template-parts/loop.php
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
 * Before Post
 *
 * @since 1.0.0
 */
do_action('qibla_before_single_loop'); ?>

<article id="post-<?php the_ID() ?>"
    <?php post_class(F\getScopeClass('article', '', get_post_type())) ?>>
    <?php get_template_part('template-parts/post-formats/format', get_post_format()) ?>
</article>

<?php
/**
 * After Post
 *
 * @since 1.0.0
 */
do_action('qibla_after_single_loop'); ?>
