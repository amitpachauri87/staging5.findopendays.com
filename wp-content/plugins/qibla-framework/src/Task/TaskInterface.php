<?php
/**
 * Task
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

/**
 * Interface Task
 *
 * @since  1.7.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
interface TaskInterface
{
    /**
     * Can
     *
     * If the task can be executed
     *
     * @since 1.7.0
     *
     * @return bool True if the task must be executed, false otherwise
     */
    public function can();

    /**
     * Setup Task
     *
     * @since 1.7.0
     *
     * @param array $args The arguments to set to be consumed by the task
     *
     * @return TaskInterface The instance of the class for concatenation
     */
    public function setup(array $args);

    /**
     * Execute the task
     *
     * @since 1.7.0
     *
     * @return TaskInterface The instance of the class for concatenation
     */
    public function exec();

    /**
     * Response
     *
     * @since 1.7.0
     *
     * @return mixed Whatever the task want to returns
     */
    public function response();

    /**
     * Is the task completed
     *
     * @since 1.7.0
     *
     * @return bool True if the task has finished or false otherwise
     */
    public function completed();
}
