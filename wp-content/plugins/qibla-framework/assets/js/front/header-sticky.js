/**
 * sticky-header.js
 *
 * @since
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
    function (_, $, DL)
    {
        "use strict";

        var StickyHeader = {

            /**
             * Is header in Sticky
             *
             * @since 1.6.0
             *
             * @type {boolean} To know if the header is set as sticky or not
             */
            isSticky: false,

            /**
             * Get Options
             *
             * @since 1.6.0
             *
             * @param   {string} opt The option name.
             * @returns {boolean}    False if option not exist. The option otherwise.
             */
            option: function (opt)
            {
                return (typeof this.options[opt] !== 'undefined' ? this.options[opt] : false);
            },

            /**
             * Set in Sticky
             *
             * @since 1.6.0
             */
            sticky: function ()
            {
                this.isSticky = true;

                DL.Utils.Functions.classList(this.option('el')).remove(
                    this.option('selectorBase') + '--skin-' + this.option('skin').original
                );
                DL.Utils.Functions.classList(this.option('el')).add(
                    this.option('selectorBase') + '--is-sticky',
                    this.option('selectorBase') + '--skin-' + this.option('skin').toUse
                );

                this.option('el').setAttribute('data-currentskin', this.option('skin').toUse);

                this.toggleLogoStyle();
            },

            /**
             * Un Sticky
             *
             * @since 1.6.0
             */
            unSticky: function ()
            {
                this.isSticky = false;

                DL.Utils.Functions.classList(this.option('el')).remove(
                    this.option('selectorBase') + '--is-sticky',
                    this.option('selectorBase') + '--skin-' + this.option('skin').toUse
                );
                DL.Utils.Functions.classList(this.option('el')).add(
                    this.option('selectorBase') + '--skin-' + this.option('skin').original
                );

                this.option('el').setAttribute('data-currentskin', this.option('skin').original);

                this.toggleLogoStyle();
            },

            /**
             * Toggle Logo Style
             *
             * @since 1.6.0
             */
            toggleLogoStyle: function ()
            {
                var logo = this.option('logo');
                if (!logo) {
                    return;
                }

                var dataLogo   = logo.getAttribute('data-original'),
                    dataLogo2x = logo.getAttribute('data-originalretina'),
                    srcLogo    = logo.getAttribute('src'),
                    srcLogo2x  = logo.getAttribute('srcset');

                logo.setAttribute('src', dataLogo);
                logo.setAttribute('data-original', srcLogo);

                logo.setAttribute('srcset', dataLogo2x);
                logo.setAttribute('data-originalretina', srcLogo2x);

            },
            
            /**
             * Sticky Toggler
             *
             * @since 1.6.0
             *
             * @returns {function(this:StickyHeader)}
             */
            stickyToggler: function ()
            {
                var scrollTimer = null,
                    wasNegative = false,
                    //  Use 10px of play.
                    play        = 10;

                return function (evt)
                {
                    // Set the scrollValue.
                    var scrollAmount = parseInt(window.scrollY);

                    // Here we check if the scrolling was negative and if so, we prevent to
                    // set the header as sticky.
                    // This may happen on iOS and macOs devices where the scrolling can go negative.
                    wasNegative = (0 > scrollAmount);

                    if ('scroll' === evt.type) {
                        // Set immediately if we are scrolling from the top of the page.
                        // Then we set the timeout. This allow us to execute the task responsively
                        // keeping the performances when status changes are not necessary.
                        if (play < scrollAmount && false === this.isSticky && false === wasNegative) {
                            this.sticky();
                            return;
                        }

                        // Clear the previous timeout.
                        clearTimeout(scrollTimer);
                        // Set the action to the next event loop.
                        scrollTimer = setTimeout(function ()
                        {
                            if (play >= scrollAmount && this.isSticky) {
                                this.unSticky();
                                scrollTimer = null;
                            }
                        }.bind(this), 0);
                    }
                }.bind(this);
            },

            /**
             * Init
             *
             * @returns {StickyHeader} For chaining
             */
            init: function ()
            {
                if (this.options.el) {
                    // Add the event listener.
                    DL.Utils.Events.addListener(window, 'scroll', this.stickyToggler());
                }

                // Show as sticky on init if needed.
                if (0 < window.scrollY) {
                    this.option('el').style.top = '-100%';

                    setTimeout(function ()
                    {
                        this.sticky();

                        $(this.option('el')).animate({
                            top: 0
                        }, 750);
                    }.bind(this), 0);
                }

                return this;
            },

            /**
             * Construct
             *
             * @since 1.6.0
             *
             * @param {object} options The options for the object.
             * @returns {StickyHeader} For chaining
             */
            construct: function (options)
            {
                _.bindAll(
                    this,
                    'init',
                    'unSticky',
                    'sticky',
                    'stickyToggler',
                    'toggleLogoStyle'
                );

                // Set the options.
                this.options = _.extend({
                    el: null,
                    selectorBase: '',
                    skin: ''
                }, options);

                // Build the correct skinSlug based on
                this.options.skin = (function (el, skin)
                {
                    var classes = el.getAttribute('class'),
                        _skin   = skin;

                    if (classes) {
                        classes = classes.split(' ');
                        _.forEach(classes, function (item)
                        {
                            if (-1 !== item.indexOf('--skin-')) {
                                // Plus --skin-.
                                _skin = item.substring(item.indexOf('--skin-') + 7);
                            }
                        });
                    }

                    return {
                        original: _skin,
                        toUse: skin,
                    };
                })(this.options.el, this.options.skin);

                return this;
            }
        };

        window.addEventListener('load', function ()
        {
           setTimeout(function(){
               var stickyHeader = Object.create(StickyHeader),
                   element      = document.querySelector('#dlheader');

               element && stickyHeader.construct({
                   el: element,
                   logo: document.querySelector('.dlbrand__logo'),
                   selectorBase: 'dlheader',
                   skin: 'light'
               }).init();
           }, 0);
        });
    }(_, window.jQuery, window.DL)
);
