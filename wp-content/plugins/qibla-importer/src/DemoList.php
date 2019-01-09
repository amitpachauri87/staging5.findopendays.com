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
 * Class DemoList
 *
 * @since   1.0.0
 *
 * @package QiblaImporter
 */
class DemoList
{
    /**
     * Demo file Directory
     *
     * @since 1.0.0
     *
     * @var string The demos file directory path
     */
    protected $path;

    /**
     * Get Demo list
     *
     * @since  1.0.0
     * @access protected
     *
     * @throws \Exception in case the path is not a dir.
     *
     * @return array The list of the demo slugs
     */
    protected function getDemoList()
    {
        $filesystem = F\getFileSystem();

        if (! $filesystem->is_dir($this->path)) {
            throw new \Exception(
                sprintf(esc_html__('%s not a directory. Aborting.', 'qibla-importer'), $this->path)
            );
        }

        return array_keys($filesystem->dirlist($this->path, false));
    }

    /**
     * Set
     *
     * Set the demo data.
     *
     * @since  1.0.0
     * @access protected
     *
     * @throws \Exception In case of the demo name is not valid or the content of the demo file is not a valid json.
     *
     * @return Demo The demo instance
     */
    protected function getDemo($slug)
    {
        return new Demo($slug);
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->path = Plugin::getPluginDirPath('/assets/exports/demos/');
    }

    /**
     * Get the Path
     *
     * Retrieve the path where the Demos are stored
     *
     * @since  1.0.0
     * @access public
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
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
     * Get all Demo data
     *
     * @since  1.0.0
     * @access public
     *
     * @return array The list of the demo containing details
     */
    public function getAll()
    {
        // Get the Demo list.
        $list = $this->getDemoList();
        // Initialize the container for the demo details.
        $data = array();

        foreach ($list as $value) {
            // Sanitize the demo name.
            $value = sanitize_key($value);
            // Get the demo details data.
            $data[$value] = $this->getDemo($value);
        }

        return $data;
    }
}
