<?php
/**
 * TaskRefactorListingsMetaLocationTriggerTemplate
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

use QiblaFramework\TemplateEngine\Engine;
use QiblaFramework\TemplateEngine\TemplateInterface;
use QiblaFramework\Plugin;

/**
 * Class TaskRefactorListingsMetaLocationTriggerTemplate
 *
 * @since  1.7.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class TaskRefactorListingsMetaLocationTriggerTemplate implements TemplateInterface
{
    /**
     * Template Slug
     *
     * @since 1.7.0
     *
     * @var string The template slug name
     */
    private static $slug = 'task_refactor_listings_meta_location_trigger';

    /**
     * View Path
     *
     * @since 1.7.0
     *
     * @var string The view path
     */
    private static $view = '/views/task/taskRefactorListingsMetaLocationTrigger.php';

    /**
     * @inheritDoc
     *
     * @since 1.7.0
     */
    public function getData()
    {
        return new \stdClass();
    }

    /**
     * @inheritDoc
     *
     * @since 1.7.0
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new Engine(self::$slug, $data, self::$view);
        $engine->render();

        wp_enqueue_script(
            'task-refactor-listings-meta-location',
            Plugin::getPluginDirUrl('/assets/js/task/taskRefactorListingsMetaLocation.js'),
            array('underscore', 'jquery'),
            '1.0.0',
            true
        );
    }
}
