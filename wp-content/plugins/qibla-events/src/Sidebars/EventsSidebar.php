<?php
/**
 * EventsSidebar.php
 *
 * @since      1.0.0
 * @package    AppMapEvents\Sidebars
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

namespace AppMapEvents\Sidebars;

use AppMapEvents\TemplateEngine\Engine;
use AppMapEvents\TemplateEngine\TemplateInterface;
use QiblaFramework\ListingsContext\Context;
use QiblaFramework\Functions as F;
use QiblaFramework\ListingsContext\Types;

/**
 * Class EventsSidebar
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class EventsSidebar implements TemplateInterface
{
    /**
     * Get Data
     *
     * @inheritDoc
     */
    public function getData()
    {
        // Initialize data class.
        $data = new \stdClass();

        $data->postType = get_post_type();

        return $data;
    }

    /**
     * Template
     *
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        $context = new Context(F\getWpQuery(), new Types());
        $path    = null;

        if ($context::isSingleListings() && is_singular('events')) {
            $path = '/views/sidebarEvents.php';
        } elseif ($context->isListingsArchive() && 'events' === $context->listingsArchiveType()) {
            $path = '/views/sidebarArchiveListings.php';
        }

        if ($path) {
            $engine = new Engine('events_sidebar', $data, $path);
            $engine->render();
        } else {
            // Remove action hook.
            remove_action('qibla_load_template_sidebar', 'AppMapEvents\\Sidebars\\EventsSidebar::sidebarEventsFilter');
            remove_action('qibla_load_template_archive_listings_sidebar', 'AppMapEvents\\Sidebars\\EventsSidebar::sidebarEventsFilter');
        }
    }

    /**
     * Sidebar Filter
     *
     * @since 1.6.1
     *
     * @return void
     */

    public static function sidebarEventsFilter()
    {
        $instance = new self;
        $instance->tmpl($instance->getData());
    }
}
