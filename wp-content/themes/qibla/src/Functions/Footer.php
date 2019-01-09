<?php
namespace Qibla\Functions;

use Qibla\TemplateEngine\Engine as TEngine;

/**
 * Footer Functions
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
 * Footer
 *
 * @since 1.0.0
 */
function footer()
{
    $engine = new TEngine('footer', new \stdClass(), 'views/footer/footer.php');
    $engine->render();
}

/**
 * Show the colophon of the site
 *
 * @since 1.0.0
 */
function colophon()
{
    $engine = new TEngine('footer_colophon', new \stdClass(), 'views/footer/colophon.php');
    $engine->render();
}

/**
 * Copyright
 *
 * @since 1.0.0
 */
function copyright()
{
    // Initialize Data Object.
    $data = new \stdClass();

    $data->content = sprintf(
        esc_html__('Proudly by %1$s - Theme Name: %2$s', 'qibla'),
        '<a href="https://www.southemes.com">' . esc_html__('Southemes', 'qibla') . '</a>',
        '<a href="http://www.southemes.com/demos/qibla">' . esc_html__('Qibla', 'qibla') . '</a>'
    );

    $engine = new TEngine('copyright', $data, 'views/footer/copyright.php');
    $engine->render();
}
