/**
 * login-register.js
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

(
    function (_, $, DL, localized)
    {
        "use strict";

        /**
         * Login Register Form
         *
         * This object is a wrapper for DL.Modal.LoginRegisterForm
         *
         * @since 1.5.0
         */
        DL.LoginRegisterForm = {
            /**
             * Get The Modal
             *
             * @since 1.5.0
             *
             * @param {object} evt The event object
             *
             * @return void
             */
            requestModal: function (evt)
            {
                evt.preventDefault();
                evt.stopImmediatePropagation();

                DL.Modal.construct('script', {
                    url: localized.site_url + '/index.php',
                    data: {
                        dlajax_action: 'modal_login_register_request',
                    },
                    beforeSend: null,
                    complete: null
                }, evt);
            },

            /**
             * Build The form
             *
             * @since 1.5.0
             *
             * @return void
             */
            buildForm: function ()
            {
                if (!DL.Modal.LoginRegisterForm.isInUse) {
                    DL.Modal.LoginRegisterForm.init();
                }
            },

            /**
             * Add Listeners
             *
             * @since 1.5.0
             *
             * @return void
             */
            addListeners: function ()
            {
                var addListener = DL.Utils.Events.addListener.bind(this);

                // Event to get and show the modal form.
                addListener(this.modalElement, 'click', this.requestModal);
                addListener(this.modalElement, 'preload-modal', this.requestModal, {once: true});
                addListener(window, 'dl_modal_opened', this.buildForm);
                addListener(window, 'dl_modal_closed', function ()
                {
                    // Remember to remove the listener or every time the modal is inserted into the document
                    // a new event handler will be registered.
                    window.removeEventListener('dl_modal_opened', this.buildForm);
                }.bind(this));
            },

            /**
             * Modal login action
             *
             * @since 2.2.1
             */
            modalLoginAction: function () {
                if (window.location.search && document.referrer.indexOf('action=resetpass')) {
                    if (!window.location.search.search('=')) {
                        return;
                    }
                    var action = window.location.search.substring(1).split("=");
                    if ('action' === action[0] && 'modal-login' === action[1]) {
                        DL.Modal.construct('html');
                        DL.Modal.LoginRegisterForm.init();
                        DL.Modal.autoOpen();
                    }
                    // Remove Modal on click.
                    DL.Utils.Events.addListener(document.querySelector('.dlmodal-close, .dlmodal-overlay'), 'click', function (evt) {
                        var modal = document.getElementById('dlmodal');
                        if (evt.target.classList.contains('dlmodal-close') ||
                            evt.target.classList.contains('dlmodal-overlay') ||
                            evt.target.classList.contains('la-times')
                        ) {
                            modal.remove();
                        }
                    });
                }
            },

            /**
             * Initialize
             *
             * @since 1.5.0
             *
             * @return void
             */
            init: function ()
            {
                // Don't set any event is user isn't logged in.
                if (!localized.loggedin) {
                    this.addListeners();
                }
            },

            /**
             * Construct
             *
             * @since 1.5.0
             */
            construct: function (element)
            {
                _.bindAll(
                    this,
                    'requestModal',
                    'addListeners'
                );

                this.modalElement = element;
                // Don't build if the main selector is missed.
                if (!this.modalElement) {
                    return false;
                }

                // The form will be build after the modal has been retrieved.
                this.form = null;

                return this;
            }
        };

        /**
         * Login Register Factory
         *
         * @since 2.0.0
         *
         * @constructor
         *
         * @param HTMLElement element The element to use as trigger
         *
         * @returns DL.LoginRegisterForm instance
         */
        DL.LoginRegisterFormFactory = function (element)
        {
            return Object.create(DL.LoginRegisterForm).construct(element);
        };

        window.addEventListener('load', function ()
        {
            setTimeout(function ()
            {
                // Action modal-login
                DL.LoginRegisterForm.modalLoginAction();

                var loginRegisterForm = DL.LoginRegisterFormFactory(
                    document.querySelector('.is-login-register-toggler')
                );

                if (loginRegisterForm) {
                    loginRegisterForm.init();
                    // Lazy load, so we can cache the modal for a much speed opening.
                    DL.Utils.Events.dispatchEvent('preload-modal', loginRegisterForm.modalElement);
                }
            }, 0);
        });

    }(_, window.jQuery, window.DL, window.dllocalized)
);
