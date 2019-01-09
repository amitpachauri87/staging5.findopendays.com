<?php
namespace QiblaFramework\Admin\Page;

use QiblaFramework\Plugin;
use QiblaFramework\TemplateEngine\Engine as TEngine;

/**
 * Class Locations
 *
 * @since      1.0.0
 * @package    QiblaFramework\Admin\Page
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

/**
 * Class Settings
 *
 * @since   1.0.0
 * @package QiblaFramework\Admin\Page
 */
class Settings extends AbstractMenuPage
{
    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        parent::__construct(
            esc_html__('Theme Options', 'qibla-framework'),
            esc_html__('Theme Options', 'qibla-framework'),
            'qibla-options',
            'dashicons-admin-customizer',
            'edit_theme_options',
            array($this, 'callback'),
            null,
            array(),
            'qibla'
        );
    }

    /**
     * Content Page Callback
     *
     * @since  1.0.0
     */
    public function callback()
    {
        // Initialize Data.
        $data = new \stdClass();

        $data->title = esc_html__('Theme Options', 'qibla-framework');

        $engine = new TEngine('admin_theme_option', $data, '/views/settings/adminSettingsPage.php');
        $engine->render();
    }
}
