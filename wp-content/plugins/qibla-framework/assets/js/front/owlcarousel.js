/**
 * Dl Owl Carousel
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
 * along with this program; if not, write to the Free Softwares
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

;(
    function (window, _, $) {
        'use strict';

        window.addEventListener('load', function (e) {
            // Get the elements.
            var carousels = document.querySelectorAll('.owl-carousel');
            if (!carousels.length) {
                return;
            }

            _.forEach(carousels, function (el) {

                var items = el.getAttribute('data-items');
                var dots = el.getAttribute('data-dots');
                var nav = el.getAttribute('data-nav');
                var margin = el.getAttribute('data-margin');
                var loop = el.getAttribute('data-loop');

                if (null === loop) {
                    loop = true;
                } else {
                    loop = '1' === loop;
                }

                if (null === nav) {
                    nav = false;
                } else {
                    nav = '1' === nav;
                }

                if (null === dots) {
                    dots = true;
                } else {
                    dots = '1' === dots;
                }

                items = items ? parseInt(items) : 1;

                $(el).owlCarousel({
                    dots: dots,
                    nav: nav,
                    items: items,
                    autoplay: true,
                    autoplayHoverPause: true,
                    loop: loop,
                    navText: [],
                    margin: margin ? parseInt(margin) : 50,
                    responsive:{
                        0:{
                            items: 1,
                            nav: false,
                            dots: false
                        },
                        560:{
                            items : 1 !== items ? 2 : 1
                        },
                        860:{
                            items : 1 !== items ? 3 : 1
                        },
                        1200:{
                            items: 1 !== items ? 4 : 1
                        },
                        1400:{
                            items: items
                        }
                    }
                });
            });
        });
    }(window, _, jQuery)
);
