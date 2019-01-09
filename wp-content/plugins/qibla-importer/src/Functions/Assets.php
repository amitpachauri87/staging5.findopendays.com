<?php
namespace QiblaImporter\Functions;

use QiblaImporter\TemplateEngine\Engine as TEngine;

/**
 * Assets
 *
 * @since   1.0.0
 * @package QiblaFramework\Front
 *
 * Copyright (C) 2016 Guido Scialfa
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

defined('WPINC') || die;

/**
 * Svg Loader Template
 *
 * @since 1.0.0
 *
 * @return void
 */
function svgLoaderTmpl()
{
    $engine = new TEngine('svg_loader', new \stdClass(), '/assets/svg/loader.svg');
    $engine->render();
}
