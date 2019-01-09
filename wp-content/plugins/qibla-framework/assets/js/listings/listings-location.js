/**
 * Single Listings Location
 *
 * Even if this file is defined to work with location there are some other logic
 * that move elements with the google map.
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
    function (_, Backbone, dllocalized)
    {
        "use strict";

        document.addEventListener('DOMContentLoaded', function ()
        {
            var ListingsLocation = {

                constructor: function ()
                {
                    /**
                     * Location Element
                     *
                     * @since 1.0.0
                     */
                    this.location = document.querySelector('.dllisting-location');

                    if (!this.location) {
                        throw "No location found. Aborting";
                    }

                    /**
                     * The Button element
                     *
                     * @since 2.5.1
                     */
                    this.button = this.location.parentElement.querySelector('.dllisting-ticket-wrapper');

                    /**
                     * The Socials element
                     *
                     * @since 1.0.0
                     */
                    this.socials = this.location.parentElement.querySelector('.dlsocials-links');

                    /**
                     * The Share and Wish element
                     *
                     * @since 2.0.0
                     */
                    this.actions = this.location.parentElement.querySelector('.dlactions');

                    /**
                     * Sidebar
                     *
                     * @since 1.0.0
                     */
                    this.sidebar = document.querySelector('#dlsidebar-listings');

                    /**
                     * Booking Price
                     *
                     * @since 1.1.0
                     */
                    this.booking = document.querySelector('#dlbookings-booking');

                    /**
                     * Article Content
                     *
                     * @since 1.0.0
                     */
                    this.articleContent = document.querySelector('.dlarticle__content');
                },

                /**
                 * Initialize
                 *
                 * @since 1.0.0
                 */
                initialize: function ()
                {
                    _.bindAll(this, 'render');

                    window.addEventListener('resize', this.render);

                    this.render();
                },

                /**
                 * Render
                 *
                 * @since 1.0.0
                 */
                render: function ()
                {
                    try {
                        var articleHeader  = document.querySelector('.dlarticle__header');

                        // If button ticket add margin top to the sidebar.
                        if (this.button) {
                            var sidebarMarginTop = parseInt(articleHeader.offsetHeight / 2 - this.button.offsetHeight / 2);
                            this.sidebar.style.top = 0;
                            this.sidebar.style.marginTop = sidebarMarginTop + 'px';
                        }

                        // Get the current viewport width.
                        var viewPortWidth = Math.round(window.innerWidth),
                            parentNode,
                            beforeEl;

                        if (!viewPortWidth) {
                            return;
                        }

                        // Related width the css.
                        if (1024 <= viewPortWidth) {
                            parentNode = this.sidebar;
                            beforeEl   = parentNode.firstElementChild;

                            // If the element is positioned correctly don't do anything.
                            if (parentNode === this.location.parentElement) {
                                return;
                            }
                        } else if (1024 > viewPortWidth) {
                            beforeEl   = this.articleContent;
                            parentNode = beforeEl.parentNode;
                        } else {
                            return;
                        }

                        if (parentNode) {
                            var elementsList = [this.button, this.booking, this.location, this.actions, this.socials];
                            _.forEach(elementsList, function (el)
                            {
                                if (!el) {
                                    return;
                                }
                                // May be empty, let's append the element and return.
                                if (!beforeEl) {
                                    parentNode.appendChild(el);
                                    return;
                                }

                                // Insert the element.
                                parentNode.insertBefore(el, beforeEl);

                                // Clean the Html of the sidebar in case of empty.
                                if (parentNode.classList.contains('dlarticle') && !this.sidebar.firstElementChild) {
                                    this.sidebar.innerHTML = '';
                                }
                            }.bind(this));
                        }
                    } catch (e) {
                        ('dev' === dllocalized.env) && console.warn(e);
                    }
                }
            };

            // Allowed only within single listings.
            if (document.body.classList.contains('dl-is-singular-listings')) {
                try {
                    // Let's do it.
                    ListingsLocation.constructor();
                    ListingsLocation.initialize();
                } catch (e) {
                    if ('dev' === dllocalized.env) {
                        console.warn(e);
                    }
                }
            }
        });
    }(_, Backbone, dllocalized)
);
