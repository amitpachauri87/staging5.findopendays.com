<?php
/**
 * ReviewerFilter
 *
 * @since      2.4.0
 * @package    QiblaFramework\Reviewer
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

namespace QiblaFramework\Reviewer;

use QiblaFramework\FilterInterface;
use QiblaFramework\Functions as Ffw;

/**
 * Class ReviewerFilter
 *
 * @since  2.4.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class ReviewerFilter implements FilterInterface
{
    /**
     * Data
     *
     * @since 2.4.0
     *
     * @var object The data to filter
     */
    private $data;

    /**
     * Template ID
     *
     * @since 2.4.0
     *
     * @var string the template id
     */
    private static $templateID = '';

    /**
     * ReviewFilter constructor.
     *
     * @since 2.4.0
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;

        $type             = get_post_type();
        self::$templateID = Ffw\getThemeOption('listings', $type . '_reviewer_template_id', true);
    }

    /**
     * Check Dependencies
     *
     * @since 1.0.0
     *
     * @return bool True if check pass, false otherwise
     */
    private function checkDependencies()
    {
        if (! function_exists('is_plugin_active')) {
            require_once untrailingslashit(ABSPATH) . '/wp-admin/includes/plugin.php';
        }

        return is_plugin_active('reviewer/reviewer.php') && class_exists('RWP_API') && '' !== self::$templateID;
    }

    /**
     * @inheritdoc
     */
    public function filter()
    {
        if (! $this->checkDependencies()) {
            return $this->data;
        }

        // Disable comments template.
        return 'yes';
    }

    /**
     * @param $data
     *
     * @return mixed|string
     */
    public static function filterFilter($data)
    {
        $instance = new self($data);

        return $instance->filter();
    }
}
