/**
 * Header Search
 *
 * The header search is working in desktop and mobile. There are some differences between them.
 * First of all, the desktop version has a search navigation,
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
 * but WITHOUT ANY WARRANTY; without even the implied warranty ofb
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

window.DL = window.DL || {};

(
    function (_, Modernizr, $, DL)
    {
        "use strict";

        var search = _.create(Object.prototype, {
            /**
             * Create Close Button
             *
             * @since 1.0.0
             */
            createCloseBtn: function ()
            {
                if (!this.submitEl || DL.Utils.Functions.classList(document.body).contains('is-winIE')) {
                    return null;
                }

                var closeBtn,
                    wrapper = document.createElement('div');

                DL.Utils.Functions.classList(wrapper).add('dlsearch-submit-wrapper');
                wrapper.appendChild(this.submitEl);

                // Create and append the new close button.
                closeBtn = document.createElement('i');
                closeBtn.setAttribute('aria-hidden', 'true');
                DL.Utils.Functions.classList(closeBtn).add('dlsearch__form-close', 'la', 'la-close');
                // Add the element to the document.
                wrapper.appendChild(closeBtn);

                this.form.appendChild(wrapper);

                return closeBtn;
            },

            /**
             * Hide Close Button
             *
             * @since 1.0.0
             */
            hideCloseBtn: function ()
            {
                if (this.closeBtn) {
                    this.closeBtn.style.display = 'none';
                }
            },

            /**
             * Show Close Button
             *
             * @since 1.0.0
             */
            showCloseBtn: function ()
            {
                if (this.closeBtn) {
                    // Inherit for vertical alignment.
                    this.closeBtn.style.display = 'inherit';
                }
            },

            /**
             * Show/Hide Header content
             */
            showHideHeaderElements: function (action)
            {
                var currEl,
                    // Show on input close, hide on input open.
                    opacity    = 'show' === action ? 1 : 0,
                    visibility = 'show' === action ? 'visible' : 'hidden';

                for (var i = 0; i !== this.search.parentElement.childElementCount; ++i) {
                    currEl = this.search.parentElement.children[i];
                    // Show or hide the element.
                    // Not hide the search form input.
                    if (!DL.Utils.Functions.classList(currEl).contains('dlsearch')) {
                        currEl.style.opacity    = opacity;
                        currEl.style.visibility = visibility;
                    }
                }
            },

            /**
             * Show/Hide navigation
             */
            toggleNavigation: function (force)
            {
                // Hide the nav but don't hide to keep the height.
                if (this.nav) {
                    // Show on input close, hide on input open.
                    // Pay attention on this, it's reverse.
                    var opacity = this.isOpen ? 1 : 0;

                    // Force opacity to a specific value.
                    if (force) {
                        opacity = parseFloat(force);
                    }

                    $(this.nav).animate({
                        opacity: opacity
                    }, {
                        queue: false,
                        duration: 100
                    });
                }

                return this;
            },

            /**
             * Input Blur
             */
            blur: function ()
            {
                this.inputSearch.blur();
            },

            /**
             * Input Focus
             */
            focus: function ()
            {
                this.inputSearch.focus();
            },

            /**
             * Initialize Toggler for Skin
             *
             * @todo Make as separated class. See the header-sticky and dispatch event there by changing skin in new class. So, on opening and closing this obj will dispatch another event.
             *
             * @since 1.0.0
             *
             * @returns {Function}
             */
            toggleSkin: function ()
            {
                var classes  = this.header.getAttribute('class'),
                    prevSkin = this.header.getAttribute('data-currentskin');

                if (!prevSkin) {
                    if (classes) {
                        classes = classes.split(' ');
                        _.forEach(classes, function (item)
                        {
                            if (-1 !== item.indexOf('--skin-')) {
                                // Plus --skin-.
                                prevSkin = item.substring(item.indexOf('--skin-') + 7);
                            }
                        });
                    }

                    this.header.setAttribute('data-currentskin', prevSkin);
                }

                if ('transparent' === prevSkin) {
                    DL.Utils.Functions.classList(this.header).toggle('dlheader--skin-' + prevSkin);
                    DL.Utils.Functions.classList(this.header).toggle('dlheader--skin-transparent-dark');
                }
            },

            /**
             * Open
             */
            open: function (evt)
            {
                evt.preventDefault();
                evt.stopPropagation();

                if (this.isOpen) {
                    return;
                }

                // Is Safari Mobile?
                // Needed to check specific functionality of Safari in iOS.
                var isSafariMobile = (DL.Utils.Functions.classList(document.body).contains('is-safari') && Modernizr.touchevents);

                // The width amount for the input search when opened.
                var inputSearchWidth = 0,
                    // Open the search input.
                    // We must calculate how large will be the search, so, get the logo image element and calculate the
                    // offsetLeft and width.
                    logoRect,
                    logo             = this.header.querySelector('.dlbrand');

                if (logo) {
                    logoRect = logo.getClientRects()[0];
                }

                // Mobile Form width.
                if (this.breakPoint >= window.innerWidth || this.isMobile) {
                    // On mobile, set the width of the input to the 100% of the viewport width.
                    // Less the width of the submit button.
                    inputSearchWidth = window.innerWidth - this.submitEl.offsetWidth;
                    // Hide temporary the header content.
                    // This doesn't hide the mobile navigation because it's outside of the .dlpage-wrapper.
                    this.showHideHeaderElements('hide');
                }

                // Now we have the client info about the logo, we must open the input.
                // To open correctly the input we must calculate the width of the logo if exists, plus the width of the submit icon.
                // This on desktop.
                if (this.breakPoint < window.innerWidth && !this.isMobile) {
                    this.toggleNavigation();

                    // If logo exists and have sizes, decrease the position and the width from the size of the input search.
                    if (logoRect) {
                        inputSearchWidth = this.form.getClientRects()[0].left - (logoRect.width + logoRect.left);
                    }
                }

                setTimeout(function ()
                {
                    $(this.inputSearch).stop().animate(
                        {
                            width: Math.round(inputSearchWidth),
                            opacity: 1,
                        },
                        {
                            queue: false,
                            duration: 257,
                            start: function ()
                            {
                                // Set the properly form style properties values.
                                // These group of properties prevent issue on elements that are within the flex-box container.
                                // For example, prevent the elements to shrink.
                                this.form.style.position = 'absolute';
                                this.form.style.right    = 0;
                                this.form.style.top      = 0;
                                this.form.style.bottom   = 0;
                                this.form.style.zIndex   = 9999;

                                // Toggle the skin.
                                this.toggleSkin();
                            }.bind(this),
                            complete: function ()
                            {
                                // Set the control variable to true when opening.
                                this.isOpen = true;
                                // Set the class to stylize the form after opened.
                                DL.Utils.Functions.classList(this.form).add('dlsearch__form--open');

                                // Make the input ready for user input.
                                if (!isSafariMobile) {
                                    this.focus();
                                }

                                // Show Close Btn
                                this.showCloseBtn();

                                // Block the document.
                                DL.Utils.Functions.classList(document.body).add('dldocument-blocked');
                            }.bind(this)
                        }
                    );
                }.bind(this), 257);

                // Make the input ready for user input.
                // On focus here to able to open the keyboard on iOS Devices
                if (isSafariMobile) {
                    this.focus();
                }

                return this;
            },

            /**
             * Close by Key
             */
            closeByKey: function (e)
            {
                // 27 is the esc key.
                if (document.activeElement === this.inputSearch && 27 === e.keyCode) {
                    this.blur();
                }

                return this;
            },

            /**
             * Close
             */
            close: function (e)
            {
                e.preventDefault();
                e.stopPropagation();

                if (!this.isOpen) {
                    return;
                }

                if (!_.isUndefined(e)) {
                    if (DL.Utils.Functions.classList(e.target).contains('dlsearch input[type="search"]') && 'keyup' !== e.type) {
                        return;
                    }
                }

                // Hide Close Btn.
                this.hideCloseBtn();

                // Reset the value of the input, no longer needed.
                this.inputSearch.value = '';
                // Remove the focus before doing anything else.
                this.inputSearch.blur();

                this.toggleSkin();

                $(this.inputSearch).stop(false, true).animate(
                    {
                        //Let iOS to able to open the keyboard.
                        width: 1,
                        opacity: 0,
                        padding: 0
                    },
                    {
                        queue: false,
                        duration: 257,
                        done: function ()
                        {
                            if (this.breakPoint <= window.innerWidth || this.isMobile) {
                                this.toggleNavigation(1);
                            }
                        }.bind(this),
                        complete: function ()
                        {
                            // Set the control variable to false when closing.
                            this.isOpen = false;
                            // Remove the open modifier.
                            DL.Utils.Functions.classList(this.form).remove('dlsearch__form--open');
                            this.form.zIndex = 1;

                            // Show the Header elements if small devices.
                            if (this.breakPoint >= window.innerWidth || this.isMobile) {
                                this.showHideHeaderElements('show');
                            }

                            // Clean all form inline styles.
                            this.form.removeAttribute('style');

                            // Remove the locked class.
                            DL.Utils.Functions.classList(document.body).remove('dldocument-blocked');
                        }.bind(this)
                    }
                );

                return this;
            },

            /**
             * Toggle
             */
            toggle: function (e)
            {
                e.stopImmediatePropagation();

                // Set toggling.
                this.isToggling = true;

                if (this.isOpen) {
                    this.close();
                } else {
                    this.open();
                }

                // Reset toggling.
                this.isToggling = false;

                return this;
            },

            /**
             * Destroy on resize
             */
            closeOnResize: function ()
            {
                var searchTimer;

                clearTimeout(searchTimer);

                searchTimer = setTimeout(function ()
                {
                    if (this.isOpen && !DL.Utils.Functions.classList(document.body).contains('is-mobile')) {
                        // Show the header Elements before close the input.
                        // This is because we can't know how many pixel the window will be resized.
                        // So may be that the elements header will not become visible.
                        this.showHideHeaderElements('show');
                        this.close();
                    }
                }.bind(this), 100);
            },

            /**
             * Submit by Key
             *
             * @param evt The event generated by the key up.
             */
            submitByKey: function (evt)
            {
                // 13 Enter
                if (13 === evt.keyCode) {
                    this.form.submit();
                }
            },

            /**
             * Set Listeners
             *
             * @since 1.3.0
             */
            setListeners: function ()
            {
                // On submit click.
                DL.Utils.Events.addListener(this.submitEl, 'click', this.open);
                DL.Utils.Events.addListener(this.closeBtn, 'click', this.close);
                DL.Utils.Events.addListener(this.inputSearch, 'keyup', this.closeByKey);
                DL.Utils.Events.addListener(this.inputSearch, 'blur', this.close);
                DL.Utils.Events.addListener(window, 'orientationchange', this.close);
                DL.Utils.Events.addListener(window, 'resize', this.closeOnResize);
                DL.Utils.Events.addListener(this.form, 'keyup', this.submitByKey);
            },

            /**
             * Initialize
             */
            init: function ()
            {
                // Set the element to close.
                this.isOpen     = false;
                // For when the element is toggling.
                this.isToggling = false;

                // Set the listeners.
                this.setListeners();

                return this;
            },

            /**
             * Construct
             *
             * @returns {mixed}
             */
            construct: function ()
            {
                _.bindAll(
                    this,
                    'createCloseBtn',
                    'hideCloseBtn',
                    'showCloseBtn',
                    'showHideHeaderElements',
                    'toggleNavigation',
                    'blur',
                    'focus',
                    'toggleSkin',
                    'open',
                    'closeByKey',
                    'close',
                    'toggle',
                    'setListeners',
                    'closeOnResize',
                    'init',
                    'submitByKey'
                );

                // Base Properties.
                this.breakPoint = 1024;

                // Is Mobile?
                this.isMobile = DL.Utils.Functions.classList(document.body).contains('is-mobile');

                // Looking for the search whitin the header.
                this.header = document.querySelector('.dlheader');
                if (!this.header) {
                    return;
                }

                // Looking for the search.
                this.search = this.header.querySelector('.dlsearch');
                if (!this.search) {
                    return;
                }

                // Looking for the form.
                this.form = this.search.querySelector('.dlsearch__form');
                if (!this.form) {
                    return;
                }

                // Looking for the input search.
                this.inputSearch = this.form.querySelector('.dlsearch input[type="search"]');
                if (!this.inputSearch) {
                    return;
                }

                // Retrieve the submit element.
                // This is a wrapper not the submit input.
                this.submitEl = this.form.querySelector('.dlsearch__form-submit');
                // Retrieve the navigation. Navigation is optional. In mobile version is on the side.
                this.nav      = this.header.querySelector('.dlnav-main');

                // Create and closing button.
                this.closeBtn = this.createCloseBtn();
                this.hideCloseBtn();

                return this;
            },
        });

        document.addEventListener('DOMContentLoaded', function ()
        {
            if (search.construct()) {
                search.init();
            }
        });

    }(_, window.Modernizr, window.jQuery, window.DL)
);
