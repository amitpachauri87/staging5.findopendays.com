<?php
namespace QiblaFramework\Widget;

use QiblaFramework\Plugin;
use QiblaFramework\Functions as F;

/**
 * Abstract Widget
 *
 * @since      1.0.0
 * @package    QiblaFramework\Widget
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
 * Class AbstractWidget
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class AbstractWidget extends \WP_Widget
{
    /**
     * Fields Path
     *
     * @since  1.0.0
     *
     * @var string The path where the fields are located
     */
    protected $fieldsPath;

    /**
     * Retrieve the fields
     *
     * @since  1.0.0
     *
     * @param array $instance The widget Instance to use within the included file.
     *
     * @return array The fields for the form.
     */
    protected function getFields($instance = array())
    {
        return include Plugin::getPluginDirPath($this->fieldsPath);
    }

    /**
     * Form
     *
     * @since  1.0.0
     *
     * {@inheritDoc}
     *
     * @param array $instance
     */
    public function form($instance)
    {
        foreach ($this->getFields($instance) as $field) {
            // @codingStandardsIgnoreLine
            echo F\ksesPost($field);
        }
    }

    /**
     * Update
     *
     * @since  1.0.0
     *
     * {@inheritDoc}
     */
    public function update($new, $old)
    {
        $data = array_merge($old, $new);

        foreach ($this->getFields() as $field) {
            $type = $field->getType();
            $key  = $field->getArg('key');

            $data[$key] = $type->sanitize($data[$key]);
        }

        return $data;
    }
}
