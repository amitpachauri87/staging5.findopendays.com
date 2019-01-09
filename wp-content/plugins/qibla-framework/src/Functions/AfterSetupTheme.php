<?php
/**
 * AfterSetupTheme
 *
 * @package   QiblaFramework\Functions
 * @copyright Copyright (c) 2016, Guido Scialfa
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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

namespace QiblaFramework\Functions;

/**
 * Add Image Sizes
 *
 * @since  1.0.0
 *
 * @return void
 */
function addImageSizes()
{
    // The Generic Jumbo-tron Image Size.
    add_image_size('qibla-jumbotron', 1920, 1080, true);
    add_image_size('qibla-post-thumbnail-square', 250, 250, true);
    add_image_size('qibla-post-thumbnail-wide', 530, 255, true);
    add_image_size('qibla-cta', 1920, 560, true);
    add_image_size('qibla-testimonial-loop', 128, 128, true);

    // This image size is set even within the theme.
    if (! has_image_size('qibla-post-thumbnail-loop')) {
        add_image_size('qibla-post-thumbnail-loop', 346, 295, true);
    }
}

/**
 * Login Logo.
 *
 * @since  2.4.0
 */
function loginLogo()
{
    $logoUrl = wp_get_attachment_image_url(getThemeOption('logo', 'logo'), 'full');
    ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background:      transparent url(<?php echo esc_url($logoUrl); ?>) no-repeat scroll center center;
            background-size: contain;
            min-height:      50px;
            height:          auto;
            width:           auto;
        }
    </style>
<?php }

/**
 * Login logo url.
 *
 * @since  2.4.0
 *
 * @return string
 */
function loginLogoUrl()
{
    return esc_url(home_url());
}

/**
 * Login logo url title.
 *
 * @since  2.4.0
 *
 * @return string
 */
function loginLogoUrlTitle()
{
    return esc_html(get_bloginfo('name'));
}

