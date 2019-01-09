<?php
namespace QiblaFramework\Front\Settings;

use QiblaFramework\Functions as F;

/**
 * Class Front-end Settings Page404
 *
 * @since      1.0.0
 * @package    QiblaFramework\Front\Settings
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa
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

/**
 * Class Page404
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Page404
{
    /**
     * Header Content
     *
     * @since  1.0.0
     *
     * @param \stdClass $data The data to filter.
     *
     * @return \stdClass The filtered data
     */
    public function header(\stdClass $data)
    {
        // Retrieve the data by theme option.
        $options = F\getThemeOption('page_404', '', true);

        if (! $options) {
            return $data;
        }

        // Set the title and subtitle.
        $data->title    = $options['title'];
        $data->subtitle = $options['subtitle'];

        return $data;
    }

    /**
     * Background
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function background()
    {
        // Try to retrieve the option, if exists show the background, if not return.
        $option = intval(F\getThemeOption('page_404', 'background_image', true));
        if (! $option) {
            return;
        }

        // Try to retrieve the media, if not, return.
        $mediaUrl = wp_get_attachment_image_url($option, 'full');
        if (! $mediaUrl) {
            return;
        }

        $background = array(
            'background'      => 'url(' . esc_url($mediaUrl) . ') no-repeat center center',
            'background-size' => 'cover',
        );

        // @todo Need cssTidy
        echo '<style type="text/css">.error404 #dlpage-wrapper > .dlwrapper{' .
             esc_html(F\implodeAssoc(';', ':', $background)) .
             '}</style>';
    }
}
