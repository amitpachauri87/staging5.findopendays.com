<?php
/**
 * openingHours
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

// In plugin don't active, return.
if (! $data->isActivePlugin) {
    return;
}

if (isset($data) && 0 !== $data->openingId) {
    echo sprintf(
        '<div class="dlsidebar__widget">%s</div>',
        do_shortcode('[op-is-open set_id="' . esc_attr($data->openingId) . '"
        open_text="' . esc_html__('We\'re currently open.', 'qibla-framework') . '"
        closed_text="' . esc_html__('We\'re currently closed.', 'qibla-framework') . '"
        next_format="' . esc_html__('We\'re open again on %2$s (%1$s) from %3$s to %4$s', 'qibla-framework') . '"
        today_format="' . esc_html__('Opening Hours today: %1$s', 'qibla-framework') . '"]')
    );

    echo sprintf(
        '<div class="dlsidebar__widget"><h4 class="dlsidebar__widget__title">%s</h4> %s</div>',
        esc_html($data->openingTitle),
        do_shortcode(
            '[op-overview 
            set_id="' . $data->openingId . '" 
            show_closed_days="true" 
            show_description="true" 
            template="table"]'
        )
    );
}