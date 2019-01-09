<?php
/**
 * title
 *
 * @since      1.0.0
 * @package    ${NAMESPACE}
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

use QiblaFramework\Functions as F;

?>

<<?php echo tag_escape($data->titleTag); ?> class="<?php echo F\getScopeClass('article', 'title') ?>">

<?php if (isset($data->icon)) : ?>
    <i class="dlarticle__icon <?php echo esc_attr(F\sanitizeHtmlClass($data->icon['icon_html_class'])) ?>"></i>
<?php endif;

/**
 * Before Post Title
 *
 * @since 1.2.0
 *
 * @param \stdClass $data The data for the current template.
 */
do_action('qibla_before_post_title', $data);

// The post title.
echo F\ksesPost($data->title);

/**
 * After Post Title
 *
 * @since 1.2.0
 *
 * @param \stdClass $data The data for the current template.
 */
do_action('qibla_after_post_title', $data); ?>

</<?php echo tag_escape($data->titleTag) ?>>
