<?php

namespace Qibla\Functions\Woocommerce;

use Qibla\Functions as F;
use Qibla\TemplateEngine\Engine;

/**
 * WooCommerce Archive
 *
 * @since      1.1.0
 * @package    Qibla\Functions\Woocommerce
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    GNU General Public License, version 2
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

/**
 * Get Subcategory Thumbnail
 *
 * @since 1.1.0
 *
 * @param mixed $category The category from which retrieve the data.
 *
 * @return \stdClass The data retrieved
 */
function getSubcategoryThumbnailData($category)
{
    // Initialize the Data.
    $data = new \stdClass();

    $html5Support       = current_theme_supports('html5', 'caption');
    $smallThumbnailSize = apply_filters('subcategory_archive_thumbnail_size', 'shop_catalog');
    $data->ID           = intval(get_term_meta($category->term_id, 'thumbnail_id', true));

    $data->thumbnail = $data->ID ?
        wp_get_attachment_image($data->ID, $smallThumbnailSize) :
        '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="" />';

    $data->containerTag = ($html5Support ? 'figure' : 'div');

    return $data;
}

/**
 * Show subcategory thumbnails.
 *
 * @since 1.1.0
 *
 * @return void
 */
function subcategoryThumbnailTmpl()
{
    $data = call_user_func_array('Qibla\\Functions\\Woocommerce\\getSubcategoryThumbnailData', func_get_args());

    if ($data->thumbnail) {
        $engine = new Engine('woocommerce_subcategory_thumbnail', $data, '/views/archives/subcategoryThumbnail.php');
        $engine->render();
    }
}

/**
 * Get Subcategory Title Data
 *
 * @since 1.1.0
 *
 * @param mixed $category The category from which retrieve the data
 *
 * @return \stdClass The data
 */
function getSubcategoryTitleData($category)
{
    // Initialize Data.
    $data = new \stdClass();

    // Set the title.
    $data->title = $category->name;
    // Set the counter.
    $data->count = $category->count;

    return $data;
}

/**
 * Sub Category Title Template
 *
 * @since 1.1.0
 *
 * @return void
 */
function subcategoryTitleTmpl()
{
    $data = call_user_func_array('Qibla\\Functions\\Woocommerce\\getSubcategoryTitleData', func_get_args());

    if ($data->title) {
        $engine = new Engine('woocommerce_subcategory_title', $data, '/views/archives/subcategoryTitle.php');
        $engine->render();
    }
}

/**
 * Sub Categories Template
 *
 * @since 1.1.0
 *
 * @return void
 */
function subcategoriesTmpl()
{
    $engine = new Engine('woocommerce_subcategories', new \stdClass(), '/views/woocommerce/subcategories.php');
    $engine->render();
}

/**
 * Count And Ordering Wrap start
 *
 * @since 2.1.0
 */
function countAndOrderWrapStart()
{
    echo '<div class="' . F\sanitizeHtmlClass((F\getScopeClass('products', 'ordering'))) . '">';
}

/**
 * Count And Ordering Wrap end
 *
 * @since 2.1.0
 */
function countAndOrderWrapEnd()
{
    echo '</div>';
}
