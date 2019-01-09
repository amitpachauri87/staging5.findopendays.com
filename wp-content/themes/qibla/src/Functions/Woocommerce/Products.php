<?php

namespace Qibla\Functions\Woocommerce;

use Qibla\Debug;
use Qibla\Exception\InvalidPostException;
use Qibla\Functions as F;
use Qibla\TemplateEngine\Engine as TEngine;

/**
 * Product Functions
 *
 * @package Qibla\Functions\Woocommerce
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

/**
 * Get the product thumbnail for the loop.
 *
 * A wrapper for thePostThumbnailTmpl in order to pass the 'shop_catalog' image size.
 *
 * @uses  thePostThumbnailTmpl()
 *
 * @since 1.1.0
 *
 * @return void
 */
function wcTemplateLoopProductThumbnail()
{
    F\thePostThumbnailTmpl(null, 'shop_catalog');
}

/**
 * Remove Headings From Tabs
 *
 * Remove the headings within tabs on single product page.
 *
 * @since 1.1.0
 *
 * @param string $heading The heading to filter
 *
 * @return string
 */
function removeSingleHeadingTabs($heading)
{
    $heading = '';

    return $heading;
}
