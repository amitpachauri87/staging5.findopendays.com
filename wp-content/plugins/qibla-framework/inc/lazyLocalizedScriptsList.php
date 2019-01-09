<?php
/**
 * Lazy Localized Scripts
 *
 * @since     2.0.0
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2017, Guido Scialfa
 * @license   GNU General Public License, version 2
 *
 * Copyright (C) 2017 Guido Scialfa <dev@guidoscialfa.com>
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
use \QiblaFramework\Front\Settings;

$context = new \QiblaFramework\ListingsContext\Context(
    F\getWpQuery(),
    new \QiblaFramework\ListingsContext\Types()
);

$list = array(
    'localized' => array(
        array(
            'handle' => 'front',
            'name'   => 'dllistings',
            array(
                'listingsAjaxUrl' => home_url('?post_type=' . urlencode($context->context())),
                'mapVisible'      => Settings\Listings::showMapOnArchive(),
            ),
        ),
    ),
);

/**
 * Filter Localized Scripts
 *
 * @since 2.0.0
 *
 * @param array $list The list of the localized scripts data.
 */
return apply_filters('qibla_fw_lazy_localized_scripts_list', $list);
