<?php
/**
 * Request Task
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

use QiblaFramework\Functions as F;
use QiblaFramework\Request\AbstractRequestAjax;
use QiblaFramework\Request\Nonce;
use QiblaFramework\Request\ResponseAjax;

/**
 * Class RequestTask
 *
 * @since  1.7.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class RequestTask extends AbstractRequestAjax
{
    /**
     * Task action name
     *
     * @since 1.7.0
     *
     * @var string The name of the action
     */
    private static $actionName = 'request_task';

    /**
     * Nonce Key
     *
     * @since 1.7.0
     *
     * @var string The nonce key used to verify the nonce
     */
    private static $nonceKey = 'request_task';

    /**
     * Task Name
     *
     * @since 1.7.0
     *
     * @var string The key of which value is the task name
     */
    private static $taskKeyName = 'task_name';

    /**
     * Task Arguments Key
     *
     * @since 1.7.0
     *
     * @var string The key from which retrieve the task arguments
     */
    private static $taskArgsKey = 'task_args';

    /**
     * Task slug to name
     *
     * @since 1.7.0
     *
     * @return string The name of the task in a readable format.
     */
    private function taskSlugToName()
    {
        // @codingStandardsIgnoreLine
        $taskName = F\filterInput($_POST, self::$taskKeyName, FILTER_SANITIZE_STRING);

        return ucwords(str_replace(array('-', '_'), ' ', $taskName));
    }

    /**
     * Invalid Response
     *
     * @since 1.7.0
     *
     * @param \Exception $e The Throwable instance.
     *
     * @return ResponseAjax The ajax response instance
     */
    private function invalidResponse(\Exception $e)
    {
        return new ResponseAjax(403, sprintf(
        // Translators: $1 is the name of the task requested. $2 Is the message of the exception.
            esc_html__('Invalid request for task %1$s. %2$s'),
            $this->taskSlugToName(),
            $e->getMessage()
        ), array(
            // Used to stop the execution by the caller.
            'completed' => true,
        ));
    }

    /**
     * @inheritDoc
     *
     * @since 1.7.0
     */
    public function isValidRequest()
    {
        // @codingStandardsIgnoreLine
        $action = F\filterInput($_POST, self::$actionKey, FILTER_SANITIZE_STRING);
        $nonce  = new Nonce(self::$nonceKey);

        return self::$actionName === $action &&
               $nonce->verify();
    }

    /**
     * @inheritDoc
     *
     * @since 1.7.0
     */
    public function handleRequest()
    {
        if (! $this->isValidRequest()) {
            return;
        }

        try {
            // @codingStandardsIgnoreStart
            $slug = sanitize_key(F\filterInput($_POST, self::$taskKeyName, FILTER_SANITIZE_STRING));
            $args = F\filterInput($_POST, self::$taskArgsKey, FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY) ?: array();
            // @codingStandardsIgnoreEnd
            $director = new DirectorRequestTask(new RequestTaskController(), $slug, $args);

            // Direct the request.
            $response = $director->director();
        } catch (\Exception $e) {
            $response = $this->invalidResponse($e);
        }

        $response->sendAjaxResponse();
    }

    /**
     * Handle Request
     *
     * @since 1.7.0
     *
     * @return void
     */
    public static function handleRequestFilter()
    {
        $instance = new self;
        $instance->handleRequest();
    }
}
