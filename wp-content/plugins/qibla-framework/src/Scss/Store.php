<?php
namespace QiblaFramework\Scss;

/**
 * Store
 *
 * @since     1.0.0
 * @package   QiblaFramework\Scss
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2016, Guido Scialfa
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
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
 * Class Store
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Store
{
    /**
     * The data to store
     *
     * @since  1.0.0
     *
     * @var string The css rules to store
     */
    protected $data;

    /**
     * FileSystem
     *
     * @since  1.0.0
     *
     * @var \WP_Filesystem_Direct The instance of the filesystem to manage file directly
     */
    protected $fs;

    /**
     * Store
     *
     * Store the styles rules and properties into file.
     *
     * @since  1.0.0
     *
     * @throws \Exception In case data is empty or file doesn't exists
     *
     * @param string $data The data to store within the file.
     * @param string $file The path of the file in which store the data.
     *
     * @return bool False upon failure, true otherwise.
     */
    public function store($data, $file)
    {
        if ('' === $data || ! $this->fs->exists($file)) {
            throw new \Exception('No way to store the data. Data is empty or file does not exists.');
        }

        // Save the file.
        return $this->fs->put_contents($file, $data, FS_CHMOD_FILE);
    }

    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        global $wp_filesystem;

        // To be sure that the variable is an instance of a filesystem class.
        // Generally the 'direct'.
        if (! $wp_filesystem) {
            WP_Filesystem();
        }

        // Clone instead of get a reference.
        $this->fs = clone $wp_filesystem;
    }
}
