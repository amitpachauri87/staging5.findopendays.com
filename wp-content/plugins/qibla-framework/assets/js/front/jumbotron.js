/**
 * Jumbotron
 *
 * The header search is working in desktop and mobile. There are some differences between them.
 * First of all, the desktop version has a search navigation,
 *
 * @since 1.3.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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

window.DL = window.DL || {};

;(
    function (_, Modernizr, DL)
    {
        "use strict";

        var jumbotron = document.querySelector('.dljumbotron');
        if (!jumbotron) {
            return;
        }

        // Set the proper height for the jumbotron when the search is within the container and the input get focus.
        // Since we use the height relative vh size, on mobile the jumbo-tron shrink his height and go
        // below the header, this make the UI ugly.
        if (Modernizr.touchevents &&
            // And the search input is within the jumbo-tron.
            jumbotron.querySelector('.dlsearch input[type="search"]')
        ) {
            // Check for the header's modifier.
            var header             = document.querySelector('.dlheader'),
                newJumbotronHeight = Math.floor(window.innerHeight);

            // If the header style is not transparent, we need to remove the height from it.
            // Needed because only the transparent style is absolute positioned.
            // Other styles increase the offset of the jumbo-tron.
            if (header && !DL.Utils.Functions.classList(header).contains('dlheader--skin-transparent')) {
                newJumbotronHeight -= header.offsetHeight;
            }

            // Override the Css height value.
            jumbotron.style.height = newJumbotronHeight + 'px';
        }
    }(_, window.Modernizr, window.DL)
);
