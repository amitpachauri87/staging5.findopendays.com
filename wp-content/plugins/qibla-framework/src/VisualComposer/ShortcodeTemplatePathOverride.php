<?php
/**
 * ShortcodeTemplatePathOverride
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2017, Guido Scialfa
 * @license   GNU General Public License, version 2
 *
 * Copyright (C) 2017 Guido Scialfa <dev@guidoscialfa.com>
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

namespace QiblaFramework\VisualComposer;

use QiblaFramework\Plugin;

/**
 * Class ShortcodeTemplatePathOverride
 *
 * @since   1.6.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\VisualComposer
 */
class ShortcodeTemplatePathOverride
{
    /**
     * Shortcodes File Path
     *
     * @since 1.6.0
     *
     * @var string The Vc shortcodes file path
     */
    private static $shortcodesFilePath = '/views/shortcodes/vc/';

    /**
     * The Shortcodes Names
     *
     * @since 1.6.0
     *
     * @var array The list of the shortcode names for which override the template
     */
    private static $shortcodeNameList = array(
        'vc_column',
        'vc_column_inner',
    );

    /**
     * Convert Shortcode File Name
     *
     * Convert shortcode file name from snake case to camelcase.
     *
     * @since 1.6.0
     *
     * @param string $templatePath Visual Composer template path for the shortcode.
     *
     * @return string The converted file name
     */
    private function convertShortcodeFileName($templatePath)
    {
        $fileName = str_replace('vc', '', basename($templatePath, '.php'));
        $fileName = 'vc' . str_replace(' ', '', ucwords(str_replace(array('_', '-'), ' ', $fileName))) . '.php';

        return $fileName;
    }

    /**
     * Set Hooks
     *
     * @since 1.6.0
     *
     * @return void
     */
    private function setHooks()
    {
        foreach (self::$shortcodeNameList as $shortcodeName) {
            add_filter("vc_shortcode_set_template_{$shortcodeName}", array($this, 'overrideTemplatePath'), 20);
        }
    }

    /**
     * Initialize
     *
     * @since 1.6.0
     *
     * @return void
     */
    public function init()
    {
        $this->setHooks();
    }

    /**
     * Override Template Path
     *
     * @since 1.6.0
     *
     * @param string $templatePath The template path to override.
     *
     * @return string The new template path
     */
    public function overrideTemplatePath($templatePath)
    {
        $newTemplatePath = Plugin::getPluginDirPath(
            untrailingslashit(self::$shortcodesFilePath) . '/' . $this->convertShortcodeFileName($templatePath)
        );

        $templatePath = $newTemplatePath ?: $templatePath;

        return $templatePath;
    }
}
