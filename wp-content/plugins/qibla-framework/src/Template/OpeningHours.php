<?php
/**
 * OpeningHours
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

namespace QiblaFramework\Template;

use QiblaFramework\TemplateEngine\Engine;

/**
 * Class OpeningHours
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class OpeningHours implements TemplateInterface
{
    /**
     * Get Data.
     *
     * @since 1.0.0
     *
     * @return \stdClass
     */
    public function getData()
    {
        // Initialized Data.
        $data = new \stdClass();
        // Get meta.
        $opening = \QiblaFramework\Functions\getPostMeta('_qibla_mb_opening_hours');

        // Initialized.
        $data->isActivePlugin = false;
        $data->openingId      = 0;
        $data->openingTitle   = esc_html__('Opening Hours', 'qibla-framework');

        if (isset($opening) && 2 === strpos($opening, '::')) {
            $opening              = explode('::', $opening);
            $data->isActivePlugin = self::checkDependencies();
            $data->openingId      = $opening[1];
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new Engine('qibla_opening_hours', $data, '/views/customFields/listings/openingHours.php');
        $engine->render();
    }

    /**
     * Check Dependencies
     *
     * @since 1.0.0
     *
     * @return bool True if check pass, false otherwise
     */
    public static function checkDependencies()
    {
        if (! function_exists('is_plugin_active')) {
            require_once untrailingslashit(ABSPATH) . '/wp-admin/includes/plugin.php';
        }

        return is_plugin_active('wp-opening-hours/wp-opening-hours.php') && class_exists('OpeningHours\OpeningHours');
    }

    /**
     * Get Opening Hours Lists
     *
     * @since 1.0.0
     *
     * @return array The lists
     */
    public static function getOpeningHoursList()
    {
        $lists = array();
        $posts = get_posts(array(
            'post_type'      => 'op-set',
            'posts_per_page' => -1,
            'cache_results'  => false,
            'no_found_rows'  => true,
        ));

        if (! $posts || ! self::checkDependencies()) {
            return array('' => esc_html__('No opening hours created.', 'qibla-framework'));
        }

        foreach ($posts as $post) {
            setup_postdata($post);
            $id                         = intval($post->ID);
            $lists[esc_attr("id::$id")] = esc_html($post->post_title . " - id:" . intval($post->ID));
        }
        wp_reset_postdata();

        return $lists;
    }

    /**
     * @inheritdoc
     */
    public static function template($object = null)
    {
        $instance = new self;
        $instance->tmpl($instance->getData());
    }
}
