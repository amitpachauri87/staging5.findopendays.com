<?php
/**
 * CustomContent
 *
 * @since      2.3.0
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
use QiblaFramework\Functions as F;

/**
 * Class CustomContent
 *
 * @since  2.3.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class CustomContent extends AbstractMeta implements TemplateInterface
{
    /**
     * Initialize Object
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        // Build the meta-keys array.
        $this->meta = array(
            'content' => "_qibla_{$this->mbKey}_custom_code",
        );
    }

    /**
     * Get Data
     *
     * @inheritDoc
     */
    public function getData()
    {
        // Initialize Data object.
        $data = new \stdClass();

        $data->content = F\getPostMeta($this->meta['content']);

        return $data;
    }

    /**
     * Template
     *
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        if (! \QiblaFramework\Functions\isWcBookingListingsActive()) {
            $engine = new Engine(
                'qibla_listings_sidebar_custom_code',
                $data,
                '/views/customFields/listings/customContent.php'
            );
            $engine->render();
        }
    }

    /**
     * Social Links Filter
     *
     * @since 1.5.0
     *
     * @return void
     */
    public static function customContentFilter()
    {
        $instance = new static;

        $instance->init();
        $instance->tmpl($instance->getData());
    }
}
