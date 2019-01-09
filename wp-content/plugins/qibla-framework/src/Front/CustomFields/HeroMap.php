<?php
/**
 * HeroMap
 *
 * @since      2.4.0
 * @package    QiblaFramework\Front\CustomFields
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

namespace QiblaFramework\Front\CustomFields;

use QiblaFramework\TemplateEngine\Engine;
use QiblaFramework\TemplateEngine\TemplateInterface;

/**
 * Class HeroMap
 *
 * @since  2.4.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class HeroMap extends AbstractMeta implements TemplateInterface
{
    /**
     * Initialize Object
     *
     * @since 2.4.0
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        // Build the meta-keys array.
        $this->meta = array(
            'heromap_active'         => "_qibla_{$this->mbKey}_heromap_active",
            'heromap_search_disable' => "_qibla_{$this->mbKey}_heromap_search_disable",
            'heromap_post_type'      => "_qibla_{$this->mbKey}_heromap_filter_post_type",
            'heromap_locations'      => "_qibla_{$this->mbKey}_heromap_filter_locations",
            'heromap_categories'     => "_qibla_{$this->mbKey}_heromap_filter_categories",
            'heromap_min_height'     => "_qibla_{$this->mbKey}_heromap_min_height",
        );
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        // Initialize object.
        $this->init();

        // Initialize Data object.
        $data = new \stdClass();

        $data->active        = $this->getMeta('heromap_active');
        $data->searchDisable = $this->getMeta('heromap_search_disable');
        $data->postType      = $this->getMeta('heromap_post_type');
        $data->locations     = $this->getMeta('heromap_locations');
        $data->categories    = $this->getMeta('heromap_categories');
        $minHeight           = $this->getMeta('heromap_min_height');

        $data->height = 0 < $minHeight ? intval($minHeight) : null;

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        if ('on' === $data->active) {
            add_filter('disable_jumbotron_template', function () {
                return 'yes';
            }, 0);
        }

        $engine = new Engine('settings_socials_links', $data, 'views/customFields/heromap.php');
        $engine->render();
    }

    /**
     * Hero map filter
     *
     * @since 2.4.0
     */
    public static function heroMapFilter()
    {
        $instance = new static;
        $instance->tmpl($instance->getData());
    }
}
