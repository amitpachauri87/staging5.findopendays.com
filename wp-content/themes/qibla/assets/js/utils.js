/**
 * Utils Scripts
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    GNU General Public License, version 2
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
    function (_, $, force)
    {
        "use strict";

        // Remove the dlu-no-js class.
        var htmlEl = document.getElementsByTagName('html')[0];
        htmlEl.classList.remove('dlu-no-js');
        htmlEl.classList.add('dl-js');

        /**
         * Header fadeIn
         *
         * @since 2.0.1
         */
        function HeaderfadeIn()
        {
            var header = document.getElementById('dlheader');

            if (!header) {
                return;
            }

            // Header Fade In.
            setTimeout(function () {
                header.classList.add('fadeIn');
                header.classList.add('animated');
                header.classList.add('unscreen-reader-text');
            }, 200);
        }

        window.addEventListener('load', function ()
        {
            // Header fadeIn
            HeaderfadeIn();
            // Set the video header to the viewport width.
            var headerBgVideo = document.querySelector('#wp-custom-header-video');
            if (headerBgVideo) {
                headerBgVideo.style.maxWidth = parseInt(window.outerWidth) + 'px';
            }

            var anchors = document.querySelectorAll('a[href^="#"]');
            // Smooth Scrolling Fragments.
            anchors.length && _.forEach(anchors, function (element)
            {
                element.addEventListener('click', function (evt)
                {
                    var frag = evt.target.href.substr(evt.target.href.indexOf('#'));

                    if ('#' === frag) {
                        // Don't do anything with '#' fragments.
                        // This kind of links generally are used in third party plugins or to prevent empty
                        // href attribute.
                        return;
                    }

                    // Workaround for when the element is hidden.
                    // Refer to https://github.com/gravmatt/force-js/issues/2
                    if (!$(frag).is(':visible')) {
                        return;
                    }

                    evt.preventDefault();
                    force.jump(frag, {
                        easing: 'easeInOutQuad',
                        duration: 1500
                    });
                });
            });
        });
    }(_, window.jQuery, window.force)
);