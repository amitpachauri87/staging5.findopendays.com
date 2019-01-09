<?php
namespace QiblaFramework\Admin\Settings;

use \Leafo\ScssPhp;
use QiblaFramework\Functions as F;
use QiblaFramework\Plugin;
use QiblaFramework\Scss;

/**
 * Handler
 *
 * @since      1.0.0
 * @package    QiblaFramework\Admin\Settings
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
 * Class HandlerSettings
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class HandlerCss
{
    /**
     * Allowed Context
     *
     * The list of the pages where the script is allowed to perform the creation of the css files.
     * The name of the context is also the name of the file.
     *
     * @since  1.0.0
     *
     * @var array The list of the allowed context
     */
    private static $allowedContext = array(
        'typography',
        'colors',
        'importexport',
    );

    /**
     * Retrieve the file list
     *
     * @since  1.0.0
     *
     * Retrieve the file lists where the style optioins are stored
     *
     * @return array The list of the files, path included
     */
    private function getFileList()
    {
        $list = array();

        foreach (self::$allowedContext as $item) {
            $list[$item] = Plugin::getPluginDirPath('/src/Admin/Settings/Scss/' . $item . '.php');
        }

        return $list;
    }

    /**
     * Create File if not exists
     *
     * @since 1.5.1
     *
     * @param string $filePath The path of the file to check.
     *
     * @return void
     */
    private function createFileIfNotExists($filePath)
    {
        if (! file_exists($filePath)) {
            // Open the file in write mode.
            $f = @fopen($filePath, 'a');
            fclose($f);
        }
    }

    /**
     * Get Custom Code
     *
     * @since  1.0.0
     *
     * @param string $filePath The absolute path of the file from which read the rules
     *
     * @return string The content of the file.
     */
    private function getCustomCode($filePath)
    {
        // Be sure the file path is a good file path.
        $filePath = realpath($filePath);

        if (! $filePath) {
            return '';
        }

        ob_start();

        // Include the file.
        // The css custom file is a php file not a css.
        // This is why we use an include here.
        include $filePath;

        // And retrieve the content.
        $customCode = ob_get_clean();

        return $customCode;
    }

    /**
     * Handle
     *
     * Process, compile and store the newly styles.
     *
     * @since  1.0.0
     * @since  1.5.1 Introduce \UnexpectedValueException exception
     *
     * @throws \UnexpectedValueException If the filtered base path is an empty string.
     *
     * @param string $context The context of the handler.
     *
     * @return void
     */
    public function handle($context)
    {
        // Prevent users that cannot customize site to perform this operation.
        if (! current_user_can('customize')) {
            wp_die('Cheatin&#8217; Uh?');
        }

        // If the current context is not allowed, simply return silently,
        // we don't need to perform the same tasks in all situations.
        if (! in_array($context, self::$allowedContext, true)) {
            return;
        }

        // Temporary WorkAround.
        // Need to find a better way to keep the execution time within the range.
        // @see issue #102.
        @set_time_limit(0);
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', -1);

        // We can remove the import-export context.
        // No longer needed because we had passed the checks.
        unset(self::$allowedContext[array_search('importexport', self::$allowedContext, true)]);

        // Retrieve the files list.
        $list = $this->getFileList();

        /**
         * Filter Scss Files List
         *
         * @since 1.0.0
         *
         * @param array  $list    The list of the scss files to include in compiler.
         * @param string $context The context of the handler. Generally is the setting slug.
         */
        $list = apply_filters('qibla_settings_handler_css_files_list', $list);

        // Set the compiler instance.
        $compiler = new Scss\Compiler(new ScssPhp\Compiler(), $list);

        // Retrieve the Store instance.
        $store = new Scss\Store();

        /**
         * Base Directory Scss Path
         *
         * @since 1.5.1
         *
         * @param string $baseDir The path of the main scss file to build.
         */
        $baseDir = apply_filters('qibla_fw_settings_handler_scss_base_dir_path', get_template_directory());
        $baseDir = untrailingslashit(F\sanitizePath($baseDir));

        if ('' === $baseDir) {
            throw new \UnexpectedValueException('Settings Handle Scss: Base Dir Path cannot be empty.');
        }

        /**
         * Dynamic Css's Output Base Dir
         *
         * @since 1.5.1
         *
         * @param string $outputDir The base path of the dynamic files. Default to get_template_directory.
         *                          Unless the 'qibla_fw_settings_handler_scss_base_dir_path' is applied.
         */
        $outputDir = apply_filters('qibla_fw_settings_css_output_file_path', $baseDir);
        $outputDir = untrailingslashit(F\sanitizePath($outputDir));

        if ('' === $outputDir) {
            throw new \UnexpectedValueException('Settings Handle Css: Output Dir Path cannot be empty.');
        }

        // Set the scss files path.
        $compiler->getCompiler()->setImportPaths($baseDir . '/assets/scss/');

        /**
         * Filter Dynamic Css
         *
         * @since 1.5.1
         *
         * @param string $mainContent The main content of the dynamic file.
         */
        $import = apply_filters('qibla_fw_settings_dynamic_css', ' @import "main.scss"; ');
        // Write file.
        $this->createFileIfNotExists($outputDir . '/assets/css/dynamic.min.css');
        $store->store($compiler->compile($import), $outputDir . '/assets/css/dynamic.min.css');

        // DropZone.
        $import = ' @import "dropzone.scss"; ';
        $this->createFileIfNotExists($outputDir . '/assets/css/dropzone.min.css');
        $store->store($compiler->compile($import), $outputDir . '/assets/css/dropzone.min.css');
    }
}
