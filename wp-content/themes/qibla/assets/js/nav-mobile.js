/**
 * Navigation Mobile
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
    function (_, $, ClassList)
    {
        "use strict";

        window.addEventListener('load', function ()
        {
            // No main nav, nothing to do.
            var mainNav = document.querySelector('.dlnav-main');
            if (!mainNav) {
                return;
            }

            /**
             *
             * Slide Out
             *
             * @type {Object}
             */
            var slideOut = Object.create(Object.prototype, {

                /**
                 * The instance of the Slideout
                 *
                 * @since 1.0.0
                 */
                instance: {
                    value: null,
                    writable: true
                },

                /**
                 * The Hamburger Menu Toggler
                 *
                 * @since 1.0.0
                 */
                hambToggler: {
                    value: null,
                    writable: true
                },

                /**
                 * Overlay
                 *
                 * @since 1.0.0
                 */
                overlay: {
                    value: null,
                    writable: true
                },

                /**
                 * The Hamburger Menu Element
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                insertHamburgerMenu: {
                    value: function ()
                    {
                        if (this.hambToggler) {
                            this.hambToggler.style.display = 'block';
                            return;
                        }

                        // @todo Use the _.template.
                        this.hambToggler = document.createElement('button');
                        this.hambToggler.setAttribute('id', 'hamburger');
                        this.hambToggler.setAttribute('aria-label', 'Menu');
                        this.hambToggler.setAttribute('aria-controls', 'dlmain-nav');
                        (new ClassList(this.hambToggler)).add('hamburger', 'hamburger--slider');
                        this.hambToggler.innerHTML = '<span class="hamburger-box"><span class="hamburger-inner"></span></span>';

                        var brandEl = document.querySelector('.dlbrand');

                        brandEl.parentElement.insertBefore(this.hambToggler, brandEl);
                    }
                },

                /**
                 * The Overlay Element
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                insertOverlay: {
                    value: function ()
                    {
                        if (null === this.overlay) {
                            var overlay = document.createElement('div');
                            overlay.classList.add('dloverlay');
                            overlay.style.position        = 'fixed';
                            overlay.style.top             = 0;
                            overlay.style.left            = 0;
                            overlay.style.right           = 0;
                            overlay.style.bottom          = 0;
                            overlay.style.height          = '100vh';
                            overlay.style.backgroundColor = 'rgba(0,0,0,.7)';
                            overlay.style.zIndex          = 9998;
                            // Hidden by default.
                            overlay.style.display         = 'none';

                            // Append the element.
                            document.querySelector('.dlheader').appendChild(overlay);
                            // Set the property.
                            this.overlay = overlay;
                        }
                    }
                },

                /**
                 * Activate the Hamburger
                 *
                 * @since 1.0.0
                 */
                activateHamburger: {
                    value: function ()
                    {
                        this.hambToggler.classList.add('is-active');
                    }
                },

                /**
                 * Deactivate Hamburger
                 *
                 * @since 1.0.0
                 */
                deactivateHamburger: {
                    value: function ()
                    {
                        this.hambToggler.classList.remove('is-active');
                    }
                },

                /**
                 * Show Overlay
                 *
                 * @since 1.0.0
                 */
                showOverlay: {
                    value: function ()
                    {
                        this.overlay.style.display = 'block';
                    }
                },

                /**
                 * Hide Overlay
                 *
                 * @since 1.0.0
                 */
                hideOverlay: {
                    value: function ()
                    {
                        this.overlay.style.display = 'none';
                    }
                },

                /**
                 * Toggle The Mobile Menu
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                toggle: {
                    value: function ()
                    {
                        if (null !== this.instance) {
                            if (!this.instance.classList.contains('dlnav-main--open')) {
                                this.open();
                            } else {
                                this.close();
                            }
                        }
                    }
                },

                /**
                 * Open the Menu
                 *
                 * @since 1.0.0
                 */
                open: {
                    value: function ()
                    {
                        // Add Classes
                        (new ClassList(this.instance)).add('dlnav-main--open', 'animated');
                        // Change the animated class.
                        this.instance.classList.remove('slideOutLeft');
                        this.instance.classList.add('slideInLeft');

                        // Add overlay.
                        this.showOverlay();

                        // Set the body
                        document.body.style.overflowY = 'hidden';
                        document.body.classList.add('dlu-nav-mobile-open');

                        this.activateHamburger();
                    }
                },

                /**
                 * Close the Menu
                 *
                 * @since 1.0.0
                 */
                close: {
                    value: function ()
                    {
                        // Add Classes
                        this.instance.classList.remove('dlnav-main--open');
                        // Change the animated class.
                        this.instance.classList.remove('slideInLeft');
                        this.instance.classList.add('slideOutLeft');

                        this.hideOverlay();

                        // Unset the body.
                        document.body.style.position  = 'initial';
                        document.body.style.overflowY = 'scroll';
                        document.body.classList.remove('dlu-nav-mobile-open');

                        this.deactivateHamburger();
                    }
                },

                /**
                 * Destroy the Slideout
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                destroy: {
                    value: function ()
                    {
                        if (null !== this.instance) {
                            this.close();

                            // Remove elements
                            this.hambToggler.remove();
                            this.overlay.remove();

                            // Reset the properties.
                            this.instance    = null;
                            this.overlay     = null;
                            this.hambToggler = null;
                        }
                    }
                },

                /**
                 * Initialize
                 *
                 * @since 1.0.0
                 */
                init: {
                    value: function (panel, menu)
                    {
                        if (null === this.instance) {
                            this.instance = document.querySelector('.dlnav-main--mobile');
                            if (!this.instance) {
                                throw 'Mobile Nav: No mobile nav element found. Aborting';
                            }

                            // Add Elements.
                            this.insertHamburgerMenu();
                            this.insertOverlay();

                            // Set events.
                            this.hambToggler.addEventListener('click', this.toggle.bind(this));
                            this.overlay.addEventListener('click', this.close.bind(this));
                        }
                    }
                }
            });

            /**
             * Mobile Navigation
             *
             * @type {Object}
             */
            var mobileNav = Object.create(Object.prototype, {

                /**
                 * The is Mobile
                 *
                 * @since 2.1.0
                 *
                 * @type {bool} The is Mobile
                 */
                isMobile: {
                    value: document.body.classList.contains('is-mobile')
                },

                /**
                 * The page wrapper
                 *
                 * @since 1.0.0
                 *
                 * @type {HtmlElement} The page wrapper
                 */
                wrapper: {
                    value: document.getElementById('dlpage-wrapper')
                },

                /**
                 * The page header
                 *
                 * @since 1.0.0
                 *
                 * @type {HTMLElement} The header of the page
                 */
                header: {
                    value: document.getElementById('dlheader')
                },

                /**
                 * Navigation
                 *
                 * @since 1.0.0
                 *
                 * @type {HtmlElement} THe main nav of the page
                 */
                nav: {
                    value: document.getElementById('dlnav-main')
                },

                /**
                 * The newly mobile navigation
                 *
                 * It's a copy of the main nav
                 *
                 * @since 1.0.0
                 *
                 * @type {HTMLElement} The mobile navigation
                 */
                navMobile: {
                    value: null,
                    writable: true
                },

                /**
                 * Set Elements
                 *
                 * Set some elements to position and stylize them accordingly to the mobile menu
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                setElements: {
                    value: function ()
                    {
                        this.header.classList.add('dlheader--mobile');
                        // Hide the main Nav.
                        this.nav.style.display = 'none';
                    }
                },

                /**
                 * Reset Elements
                 *
                 * Reset the elements to their original state.
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                resetElements: {
                    value: function ()
                    {
                        this.header.classList.remove('dlheader--mobile');
                        this.nav.removeAttribute('style');
                    }
                },

                filterClick: {
                    value: function (e)
                    {
                        if ('A' === e.target.tagName) {
                            // prevent the default behavior only for menu items that have childrens.
                            if (e.target.parentElement.classList.contains('menu-item-has-children')) {
                                e.preventDefault();
                                e.stopPropagation();

                                var submenu = e.target.parentElement.querySelector('.sub-menu');

                                // Close only the sub-menus at the first depth. We can have more tha two depth,
                                // so keep the ability to open nested sub-menus.
                                // a.parentElement = 'li', a.parentElement.parentElement = 'ul:menu-depth-1'.
                                var parentElement = e.target.parentElement.parentElement.getAttribute('id');
                                if ('dlnav-main__list-items' === parentElement) {
                                    $(e.target.parentElement.parentElement.querySelectorAll('.sub-menu'))
                                        .stop(true, true)
                                        .slideUp();
                                }

                                // And toggle the current one.
                                $(submenu).stop().slideToggle();
                                return false;
                            }
                        }
                    }
                },

                /**
                 * Construct the new mobile menu
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                construct: {
                    value: function ()
                    {
                        if (!this.wrapper || !this.header || !this.nav) {
                            throw 'Header not found in nav-mobile.js during construct';
                        }

                        if (!this.navMobile) {
                            this.navMobile = document.createElement('div');

                            // Copy the navigation classes into the navMobile.
                            _.forEach(this.nav.classList, function (e)
                            {
                                this.navMobile.classList.add(e);
                            }.bind(this));

                            this.navMobile.classList.add('dlnav-main--mobile');
                            this.navMobile.innerHTML = this.nav.innerHTML;

                            // Insert the newly element into the document.
                            document.body.insertBefore(this.navMobile, this.wrapper);

                            // As of the sub-menu uses the screen-reader-text, we must hide the sub-menu elements
                            // to avoid issues on first click.
                            _.forEach(this.navMobile.querySelectorAll('.menu-item'), function (el)
                            {
                                var submenu = el.querySelector('.sub-menu');
                                if (submenu) {
                                    submenu.style.display = 'none';
                                }
                            });

                            // Set Events for links.
                            _.forEach(this.navMobile.querySelectorAll('a'), function (el)
                            {
                                el.addEventListener('click', this.filterClick.bind(this));
                            }.bind(this));
                        }

                        this.setElements();
                    }
                },

                destroy: {
                    value: function ()
                    {
                        this.resetElements();
                    }
                }
            });

            try {
                // Create and set the mobile nav on load if the viewport is the correct width.
                if (1024 >= window.innerWidth || mobileNav.isMobile) {
                    mobileNav.construct();
                    slideOut.init(mobileNav.wrapper, mobileNav.navMobile);
                }

                // Set the event listener.
                // Prevent to execute multiple unnecessary time the same code.
                window.addEventListener('resize', function ()
                {
                    clearTimeout(navMobileTimeout);

                    var navMobileTimeout = setTimeout(function ()
                    {
                        if (1024 >= window.innerWidth || mobileNav.isMobile) {
                            mobileNav.construct();
                            slideOut.init(mobileNav.wrapper, mobileNav.navMobile);
                        } else {
                            mobileNav.destroy();
                            slideOut.destroy();
                        }
                    }, 300);
                });
            } catch (e) {
                console.warn(e);
            }
        });
    }(_, window.jQuery, window.ClassList)
);