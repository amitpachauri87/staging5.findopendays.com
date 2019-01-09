<?php
namespace QiblaImporter;

use QiblaImporter\Functions as F;

/**
 * Demo
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
 * Class Demo
 *
 * @since   1.0.0
 *
 * @package QiblaImporter
 */
class Demo
{
    /**
     * Demo Data
     *
     * @since  1.0.0
     * @access protected
     *
     * @var Object a list of properties of the demo
     */
    protected $demoData;

    /**
     * Demo file Directory
     *
     * @since 1.0.0
     *
     * @var string The demos file directory path
     */
    protected $path;

    /**
     * Set
     *
     * Set the demo data.
     *
     * @since  1.0.0
     * @access private
     *
     * @throws \InvalidArgumentException In case of the demo name is not valid or the content of the demo file is not a
     *                                   valid json.
     *
     * @return void
     */
    private function setDemoData($slug)
    {
        $slug = sanitize_key($slug);

        if (! $slug) {
            throw new \InvalidArgumentException(esc_html__('Invalid demo name.', 'qibla-importer'));
        }

        // Set the demo path.
        $demoPath = untrailingslashit($this->path) . '/' . $slug;

        // Decode details.
        $this->demoData = F\getJsonContent($demoPath . '/details.json');
        // Set the screen-shot url.
        $this->demoData->screenshot = Plugin::switchPluginDirPath(
            untrailingslashit($demoPath) . '/screenshot.png',
            'dir>url'
        );

        // Set the properly url action.
        foreach ($this->demoData->actions as &$action) {
            $action->url = wp_nonce_url(
                add_query_arg(
                    urlencode_deep((array)$action->queryArgs),
                    home_url('/wp-admin/admin.php?page=qb-importer')
                ),
                'qbimport_action',
                'qbimport_nonce'
            );
        }
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct($slug)
    {
        $this->path = Plugin::getPluginDirPath('/assets/exports/demos/');

        $this->setDemoData($slug);
    }

    /**
     * Get Demo Path Dir
     *
     * @since 1.0.0
     *
     * @param string $name The name of the demo.
     *
     * @return string The path where the demos are located
     */
    public function getDemoPath($name)
    {
        return untrailingslashit($this->path) . '/' . sanitize_key($name);
    }

    /**
     * Get Demo Data
     *
     * @since  1.0.0
     * @access public
     *
     * @return Object The demo data object
     */
    public function getDemoData()
    {
        return $this->demoData;
    }

    /**
     * Get Property
     *
     * @since  1.0.0
     * @access public
     *
     * @param string $name The name of the property
     *
     * @throws \InvalidArgumentException In case the property doesn't exists.
     *
     * @return mixed The property value
     */
    public function __get($name)
    {
        $name = preg_replace('/[^a-zA-Z0-9_\-]/', '', $name);

        if (! isset($this->demoData->$name)) {
            throw new \InvalidArgumentException(
                sprintf(
                    esc_html__('Property %1$s does not exists for %2$s.', 'qibla-importer'),
                    $name,
                    $this->slug
                )
            );
        }

        return $this->demoData->$name;
    }
}
