<?php
namespace QiblaWcListings\TemplateEngine;

use QiblaWcListings\Plugin;

/**
 * Template Engine
 *
 * @since      1.0.0
 * @package    QiblaWcListings\TemplateEngine
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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

defined('WPINC') || die;

/**
 * Class Engine
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class Engine
{
    /**
     * Name
     *
     * @since  1.0.0
     *
     * @var string The name of the current template instance.
     */
    protected $name;

    /**
     * Data
     *
     * @since  1.0.0
     *
     * @var \stdClass The data object
     */
    protected $data;

    /**
     * Template Path
     *
     * @since  1.0.0
     *
     * @var string The template path
     */
    protected $templatePath;

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param string    $name         The name of the current template instance.
     * @param \stdClass $data         The data object containing the data for the view template.
     * @param string    $templatePath The path of the template to use.
     */
    public function __construct($name, \stdClass $data, $templatePath)
    {
        $this->name         = sanitize_key($name);
        $this->data         = $data;
        $this->templatePath = $templatePath;
    }

    /**
     * Render
     *
     * @todo   Use a function to include the file to prevent to use $this and pass the $data value.
     *
     * @since  1.0.0
     *
     * @throws \Exception In case the template path is incorrect or cannot be located.
     *
     * @return string The template filename if one is located.
     */
    public function render()
    {
        // Sanitize template path and remove the path separator.
        // locate_template build the path in this way {STYLESHEET|TEMPLATE}PATH . '/' . $template_name.
        $this->templatePath = ltrim(preg_replace('[^a-zA-Z0-9\-\_]', '', $this->templatePath), '/');

        if (! $this->templatePath) {
            throw new \Exception(esc_html__('Qibla Template Engine, wrong path format', 'qibla-woocommerce-listings'));
        }

        /**
         * Filter Data
         *
         * @since 1.0.0
         *
         * @param \stdClass $data The template data.
         * @param string    $name The name of the current template instance.
         */
        $data = apply_filters('qibla_wc_listings_template_engine_data', $this->data, $this->name);

        /**
         * Filter Specific Data
         *
         * @since 1.0.0
         *
         * @param \stdClass $data The template data.
         */
        $data = apply_filters("qibla_wc_listings_template_engine_data_{$this->name}", $this->data);

        // If data is empty, no other actions is needed.
        if (! $data) {
            return '';
        }

        // Retrieve the theme file path from child or parent.
        $viewPath = get_theme_file_path($this->templatePath);

        // May be the template is not found within the child / theme, so we try to locate the view
        // within the plugin directory.
        if (! file_exists($viewPath)) {
            $viewPath = Plugin::getPluginDirPath($this->templatePath);
        }

        // Empty string or bool depend by the conditional above.
        if (! file_exists($viewPath)) {
            throw new \Exception(sprintf(
                esc_html__('Qibla Template Engine: No way to locate the template %s.'),
                $viewPath
            ));
        }

        // Include the template.
        // Don't use include_once because some templates/views may need to be included multiple times.
        include $viewPath;

        return $viewPath;
    }
}
