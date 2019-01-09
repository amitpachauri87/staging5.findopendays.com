<?php
namespace QiblaImporter\Admin\Page;

use QiblaImporter\Demo;
use QiblaImporter\DemoList;
use QiblaImporter\Plugin;
use QiblaImporter\TemplateEngine\Engine as TEngine;

/**
 * Menu Page Import
 *
 * @copyright Copyright (c) 2016, Guido Scialfa
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
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
 * Class Import
 *
 * @since   1.0.0
 *
 * @package QiblaImporter\Admin\Page
 */
class Import extends AbstractMenuPage
{
    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct(
            esc_html__('Demo Importer', 'qibla-importer'),
            esc_html__('Demo Importer', 'qibla-importer'),
            'qb-importer',
            Plugin::getPluginDirUrl('/assets/img/logo-menu-page.png'),
            'manage_options',
            array($this, 'callback'),
            100,
            array(),
            class_exists('\\QiblaFramework\\Plugin') ? 'qibla' : null
        );
    }

    /**
     * Import Page Callback
     *
     * @since  1.0.0
     * @access public
     *
     * @return void
     */
    public function callback()
    {
        // Initialize Data.
        $data = new \stdClass();

        // Set the page title.
        $data->pageTitle = esc_html__('Qibla Demo Imports', 'qibla-importer');
        // Set the description.
        $data->pageDescription = esc_html__('Starting from scratch is heavy, we know that. Click on "import" button and wait until the download is completed. Don\'t close this page during the download! You can browse your dashboard by opening a new tab instead.', 'qibla-importer');

        // Get the instance of the demo Class.
        $demo = new DemoList();
        // Get the demos List.
        $data->demoList = $demo->getAll();
        // Get the demo path.
        $data->demoPath = untrailingslashit($demo->getPath());

        // View.
        $engine = new TEngine('admin_page_import', $data, '/views/importPage.php');
        $engine->render();
    }
}
