<?php
/**
 * TaskFactory
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

namespace QiblaFramework\Task;

use QiblaFramework\ValueObject\QiblaString;

/**
 * Class TaskFactory
 *
 * @since  1.7.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class TaskFactory
{
    /**
     * Task Class Name
     *
     * @since 1.7.0
     *
     * @var string The task class name
     */
    private $name;

    /**
     * NameSpace
     *
     * @since 1.7.0
     *
     * @var string The namespace for the task
     */
    private static $namespace = 'QiblaFramework\\Task\\';

    /**
     * Build Name
     *
     * Build the name of the class from the slug.
     *
     * @since 1.7.0
     *
     * @param string $taskName The name of the task
     *
     * @return string The fully qualified class name
     */
    private function buildName($taskName)
    {
        $slug = new QiblaString($taskName);
        $slug = $slug->fromLabelToSlug()
                     ->snakeCaseToCamel();
        $slug = ucfirst($slug->val());

        return self::$namespace . "Task{$slug}";
    }

    /**
     * TaskFactory constructor
     *
     * @since 1.7.0
     *
     * @param string $name The name of the task
     */
    public function __construct($name)
    {
        $this->name = $this->buildName($name);
    }

    /**
     * Create the task
     *
     * @since 1.7.0
     *
     * @throws \InvalidArgumentException If the class task doesn't exits.
     *
     * @return TaskInterface An instance of the Task class
     */
    public function create()
    {
        // Since it's dynamically, we need to be sure the class implements the TaskInterface.
        $interface = class_implements($this->name, 'QiblaFramework\\Task\\TaskInterface');

        if (! class_exists($this->name) || ! in_array('QiblaFramework\Task\TaskInterface', $interface, true)) {
            throw new \InvalidArgumentException(sprintf('Task %s does not exists', $this->name));
        }

        return new $this->name;
    }
}
