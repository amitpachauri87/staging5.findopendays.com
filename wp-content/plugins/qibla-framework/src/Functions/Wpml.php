<?php
/**
 * Wpml
 *
 * @since      2.4.0
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

namespace QiblaFramework\Functions;

/**
 * Set current lang
 *
 * @since  2.1.0
 *
 * @return null or current lang.
 */
function setCurrentLang()
{
    $currentLang = null;
    if (isWpMlActive() && defined('ICL_LANGUAGE_CODE')) {
        global $sitepress;
        $default = $sitepress->get_default_language();

        $currentLang = ICL_LANGUAGE_CODE !== $default ? ICL_LANGUAGE_CODE : null;
    }

    return $currentLang;
}

/**
 * Get formt lang in url
 *
 * @since  2.4.0
 *
 * @return string
 */
function getLangFormatLangInUrl()
{
    $format = '';

    if (isWpMlActive() && defined('ICL_LANGUAGE_CODE')) {
        global $sitepress;
        $default = $sitepress->get_default_language();

        $currentLang = ICL_LANGUAGE_CODE !== $default ? ICL_LANGUAGE_CODE : '';

        if ('' !== $currentLang) {
            $urlFormat = $sitepress->convert_url(get_permalink(), $currentLang);
            $url       = parse_url($urlFormat);

            if (isset($url['query'])) {
                if (false !== strpos($url['query'], 'lang')) {
                    $format = 'lang=' . $currentLang;
                }
            } else {
                $format = $currentLang;
            }
        }
    }

    return $format;
}
