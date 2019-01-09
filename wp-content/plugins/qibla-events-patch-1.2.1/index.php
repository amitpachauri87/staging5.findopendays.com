<?php
/**
 * Qibla Events Patch
 *
 * @wordpress-plugin
 * Plugin Name: Qibla Events Patch #QEV121
 * Plugin URI: http://www.southemes.com
 * Description: Apply Qibla Events Patch #QEV121 (updates the type of data used for sorting events)
 * Version: 1.0.0
 * Author: App&Map <luca@appandmap.com>
 * Author URI: http://appandmap.com/en/
 * License: GPL2
 *
 * Copyright (C) 2018 App&Map <luca@appandmap.com>
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

if (! function_exists('is_plugin_active')) {
    require_once untrailingslashit(ABSPATH) . '/wp-admin/includes/plugin.php';
}

if(! is_plugin_active('qibla-framework/index.php') && ! is_plugin_active('qibla-events/index.php')) {
    return;
}

// Fix meta data order by.
add_action('init', function () {
    // Initialized.
    $startDate = '';

    $args = array(
        'post_type'              => 'events',
        'post_status'            => 'publish',
        'posts_per_page'         => -1,
        'cache_results'          => false,
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
        'no_found_rows'          => true,
        'fields'                 => 'ids',
    );

    $query = new \WP_Query($args);

    $update = false;

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Check if meta exists.
            if (metadata_exists('post', get_the_ID(), '_qibla_mb_event_dates_start_for_orderby')) {
                add_action('admin_notices', function () { ?>
                    <div class="error notice is-dismissible">
                        <p>You have already applied the #QEV121 patch. disable the plugin</p>
                    </div>

                <?php });
                wp_reset_query();
                return;
            }

            $multiDates = \QiblaFramework\Functions\getPostMeta('_qibla_mb_event_dates_multidatespicker') ?: null;
            if ($multiDates) {
                $multiDates = explode(',', $multiDates);

                $dates = array();
                foreach ($multiDates as $date) {
                    $date    = new \DateTime($date);
                    $dates[] = (string)$date->format('Y-m-d');
                }

                $startDate = reset($dates);
            }

            // Date for order.
            $eventTimeStar = \QiblaFramework\Functions\getPostMeta('_qibla_mb_event_start_time_timepicker', '');
            $date          = \AppMapEvents\Functions\setDateTimeFromTimeAndDate(intval($eventTimeStar), $startDate);
            $sortDate      = $date instanceof \DateTime ? $date->format('YmdHi') : '';
            update_post_meta(get_the_ID(), '_qibla_mb_event_dates_start_for_orderby', $sortDate);

            $update = true;
        }
    }
    wp_reset_query();

    if($update) {
        add_action( 'admin_notices', function (){ ?>
            <div class="updated notice is-dismissible">
                <p>The data has been updated, you can deactivate the plugin</p>
            </div>

        <?php });
    }
});
