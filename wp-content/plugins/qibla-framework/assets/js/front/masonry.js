/**
 * Masonry JavaScript
 *
 * @since      1.0.0
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

;(
    function (dllocalized)
    {
        "use strict";

        /**
         * Resize
         *
         * Resize the elements within the Grid
         *
         * @param {HTMLElement} grid  The grid containing the articles
         * @param {HTMLElement} items The list items from which retrieve the min height.
         */
        function resize(grid, items)
        {
            var min = 9999;
            // Calculate the min height for all elements within a grid.
            [].forEach.call(items, function (el)
            {
                if (!el) {
                    return;
                }

                try {
                    // May throw an error if the image is not defined.
                    var child = el.querySelector('.dlthumbnail').querySelector('.dlthumbnail__image');

                    if (!child) {
                        return;
                    }

                    // First time the min will get the child.offsetHeight value.
                    min = (
                        min < child.offsetHeight ? min : child.offsetHeight
                    );
                } catch (e) {
                    ('dev' === dllocalized.env) && console.warn(e);
                }
            });

            // Set the height for every thumbnail image container.
            // Hide the overflow.
            [].forEach.call(items, function (el)
            {
                try {
                    el = el.querySelector('.dlthumbnail');

                    // Get the ref to the imgEl.
                    var imgEl = el.querySelector('.dlthumbnail__image');

                    if (!el) {
                        return;
                    }

                    // Instead of using the min height we want to keep the own height element in small devices.
                    // See for more info about the small device breakpoint the theme css grid files.
                    if (window.innerWidth < 768) {
                        min = parseInt(
                            window.getComputedStyle(imgEl).getPropertyValue('height')
                        );
                    }

                    el.style.height   = min + 'px';
                    el.style.overflow = 'hidden';

                    // Set the image as background and hide the img element.
                    // This is a temporary hack I hope to solve the issue with masonry, grid and image sizes that add
                    // additional white spaces to the bottom or top of the images because of the fluid grid.
                    el.style.background     = 'url(' + imgEl.src + ') no-repeat center center';
                    el.style.backgroundSize = 'cover';
                    imgEl.style.opacity     = 0;

                } catch (e) {
//                    if ('dev' === dllocalized.env) {
//                        console.warn(e);
//                    }
                }
            });
        }

        /**
         * Re Grid
         *
         * @param {HTMLElement} grid The grid element containing all articles
         *
         * @return void
         */
        function reGrid(grid)
        {
            [].forEach.call(grid, function (el)
            {
                resize(el, el.querySelectorAll('.dlarticle'));
            });
        }

        // Init.
        window.addEventListener('load', function ()
        {
            // Do nothing if the masonry doesn't exists.
            if (!document.querySelectorAll('.dlgrid--masonry').length) {
                return;
            }

            var grid = document.querySelectorAll('.dlu-same-height');

            if (!grid) {
                return false;
            }

            reGrid(grid);
            window.addEventListener('resize', function ()
            {
                reGrid(grid);
            });

            var msnry = new Masonry('.dlgrid--masonry', {
                itemSelector: '.dlarticle',
//                percentPosition: true,
            });
        });
    }(dllocalized)
);
