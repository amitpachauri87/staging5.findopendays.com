<?php
/**
 * Thumbnail View
 *
 * @since      2.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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

/**
 * Before Thumbnail
 *
 * @since 2.0.0
 *
 * @param \stdClass $data The data passed to the view.
 */
do_action('qibla_fw_before_thumbnail', $data); ?>

    <figure <?php F\scopeClass('thumbnail') ?>>
        <?php

        /**
         * Before Thumbnail Image
         *
         * @since 2.0.0
         *
         * @param \stdClass $data The data passed to the view.
         */
        do_action('qibla_fw_before_thumbnail_image', $data);

        // @codingStandardsIgnoreLine
        echo F\ksesImage($data->image);

        /**
         * After Thumbnail Image
         *
         * @since 2.0.0
         *
         * @param \stdClass $data The data passed to the view.
         */
        do_action('qibla_fw_after_thumbnail_image', $data);
        ?>
    </figure>

<?php
/**
 * After Thumbnail
 *
 * @since 2.0.0
 *
 * @param \stdClass $data The data passed to the view.
 */
do_action('qibla_fw_after_thumbnail', $data);
