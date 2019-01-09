<?php
/**
 * Director Request Task
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

namespace QiblaFramework\Task\Request;

use QiblaFramework\Request\AbstractDirectorRequest;
use QiblaFramework\Task\TaskFactory;

/**
 * Class DirectorRequestTask
 *
 * @since  1.7.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class DirectorRequestTask extends AbstractDirectorRequest
{
    /**
     * Task Name
     *
     * @since 1.7.0
     *
     * @var string The name of the task
     */
    private $name;

    /**
     * Arguments
     *
     * @since 1.7.0
     *
     * @var array The arguments to pass to the task
     */
    private $args;

    /**
     * DirectorRequestTask constructor
     *
     * @since 1.7.0
     *
     * @param RequestTaskController $controller The controller where dispatch the request.
     * @param string                $name       The task name.
     * @param array                 $args       The arguments to pass to the task name.
     */
    public function __construct(RequestTaskController $controller, $name, $args)
    {
        if (! $name) {
            throw new \InvalidArgumentException('Empty task name. Cannot direct.');
        }

        $this->name       = $name;
        $this->args       = $args;
        $this->controller = $controller;
    }

    /**
     * @inheritDoc
     *
     * @since 1.7.0
     */
    public function director()
    {
        $task = new TaskFactory($this->name);
        $task = $task->create();

        $this->injectDataIntoController(array(
            'task' => $task,
            'args' => $this->args,
        ));

        return $this->dispatchToController();
    }
}
