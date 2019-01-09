<?php
/**
 * Dispatcher
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @package   dreamlist-framework
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

namespace QiblaFramework\ListingsContext;

use QiblaFramework\Functions as F;

/**
 * Class Dispatcher
 *
 * @since   2.0.0
 * @package QiblaFramework\Template
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class TemplateIncludeFilter
{
    /**
     * Context
     *
     * @since 2.0.0
     *
     * @var Context The instance used to check against the context
     */
    private $context;

    /**
     * Template
     *
     * @since 2.0.0
     *
     * @var string The template string to filter
     */
    private $template;

    /**
     * Templates
     *
     * @since 2.0.0
     *
     * @var array List of the template to override
     */
    private static $templates = array(
        'archive'  => '/views/archive/listings.php',
        'singular' => '/views/singular/listings.php',
        'search'   => 'search.php',
    );

    /**
     * TemplateIncludeFilter constructor
     *
     * @since 2.0.0
     *
     * @throws \InvalidArgumentException If the template is not a valid string
     *
     * @param Context $context  The instance used to check against the context.
     * @param string  $template The template string to filter.
     */
    public function __construct(Context $context, $template)
    {
        if (! is_string($template)) {
            throw new \InvalidArgumentException('Template is not a valid string.');
        }

        $this->context  = $context;
        $this->template = $template;
    }

    /**
     * Filter Template
     *
     * @since 2.0.0
     *
     * @return string The template string
     */
    public function filter()
    {
        $template = $this->template;

        /**
         * Circuit Template
         *
         * Allow plugins and theme to prevent to circuit the template.
         * This way is still possible to use the WordPress template part feature.
         *
         * @since 2.0.0
         *
         * @param string $template The template string
         */
        $circuit = apply_filters('qibla_listings_template_circuit', true);

        // @codingStandardsIgnoreLine
        $action = F\filterInput($_POST, 'post_type', FILTER_SANITIZE_STRING);

        // Is an archive.
        if ($this->context->isListingsArchive() and $circuit) {
            $template = F\pathFromThemeFallbackToPlugins(self::$templates['archive']);
        }

        // Is a singular post type listings.
        if (Context::isSingleListings() and $circuit) {
            $template = F\pathFromThemeFallbackToPlugins(self::$templates['singular']);
        }

        // Is search post.
        if (is_search() && ! $action) {
            $template = F\pathFromThemeFallbackToPlugins(self::$templates['search']);
        }

        return $template;
    }


    /**
     * Filter Template Helper
     *
     * @since 2.0.0
     *
     * @param string $template The template to filter.
     *
     * @return string The filtered template
     */
    public static function templateIncludeFilterFilter($template)
    {
        $instance = new self(
            new Context(F\getWpQuery(), new Types()),
            $template
        );

        return $instance->filter();
    }
}