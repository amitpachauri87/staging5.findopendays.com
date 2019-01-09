<?php
namespace QiblaFramework\Scss;

/**
 * Compiler
 *
 * @since      1.0.0
 * @package    QiblaFramework\Scss
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

use QiblaFramework\Plugin;

/**
 * Class Compiler
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Compiler
{
    /**
     * The Compiler ScssPhp instance
     *
     * @since  1.0.0
     *
     * @var \Leafo\ScssPhp\Compiler The compiler instance
     */
    protected $compiler;

    /**
     * The files List
     *
     * @since  1.0.0
     *
     * @var array A list of files to compile
     */
    protected $files;

    /**
     * FileSystem
     *
     * @since  1.0.0
     *
     * @var \WP_Filesystem_Direct The instance of the filesystem to manage file directly.
     */
    protected $fs;

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param \Leafo\ScssPhp\Compiler $scssCompiler The compiler for convert scss to css.
     * @param array                   $files        The files to compile. Optional.
     */
    public function __construct(\Leafo\ScssPhp\Compiler $scssCompiler, array $files = array())
    {
        global $wp_filesystem;

        // To be sure that the variable is an instance of a filesystem class.
        // Generally the 'direct'.
        if (! $wp_filesystem) {
            WP_Filesystem();
        }

        // Clone instead of get a reference.
        $this->fs = clone $wp_filesystem;

        $this->compiler = $scssCompiler;
        $this->files    = $files;
    }

    /**
     * Get Compiler
     *
     * @since  1.0.0
     *
     * @return \Leafo\ScssPhp\Compiler The compiler instance
     */
    public function getCompiler()
    {
        return $this->compiler;
    }

    /**
     * Compile the Scss
     *
     * @since  1.0.0
     *
     * @param string $data      The data to compile. This data is additional to the files.
     * @param string $formatter The formatter class name. Optional. Default to Compressed.
     *
     * @return string The compiled scss to css
     */
    public function compile($data, $formatter = '\\Leafo\\ScssPhp\\Formatter\\Compressed')
    {
        // Initialize the content.
        $content = '';

        // If there are files to process, ok.
        if (! empty($this->files)) {
            ob_start();

            // Retrieve the styles by the files.
            foreach ($this->files as $file) {
                if (! $file || ! $this->fs->exists($file)) {
                    continue;
                }

                // Retrieve the content data.
                include $file;
            }

            $content = ob_get_clean();
        }

        if ($data) {
            $content .= $data;
        }

        // If there is no content to process, Don't perform additional actions.
        if (! $content) {
            return '';
        }

        // Set the Formatter. Default to Compressed.
        $this->compiler->setFormatter($formatter);

        // Finally compile the scss.
        return $this->compiler->compile($content);
    }
}
