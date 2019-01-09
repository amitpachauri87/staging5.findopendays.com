/**
 * wishlist.js
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
    function (_, $, DL, url, localized)
    {
        "use strict";

        /**
         * WishList Adder
         *
         * @since 2.0.0
         *
         * @type {Object}
         */
        DL.WishListAdder = {
            /**
             * Toggle Loader
             *
             * @since 2.0.0
             *
             * @return void
             */
            toggleLoader: function ()
            {
                DL.Utils.UI.toggleLoader(this.element);
            },

            /**
             * Added Feedback
             *
             * @since 2.0.0
             *
             * @return void
             */
            statusToggle: function ()
            {
                /**
                 * Dispatch Item Update status
                 *
                 * @since 2.0.0
                 */
                DL.Utils.Events.dispatchEvent('wishlist_item_status_update', this.element, null, {
                    dispatcher: this
                });

                this.action = ('remove' === this.action ? 'store' : 'remove');

                // Set the element as stored.
                DL.Utils.Functions.classList(this.element).toggle('is-stored');
            },

            /**
             * Add Running state
             *
             * @since 2.0.0
             */
            running: function ()
            {
                // This class will prevent that a multiple clicks during the ajax action will generate other
                // actions.
                DL.Utils.Functions.classList(this.element).add('is-running');
            },

            /**
             * Remove Element
             *
             * @since 2.0.0
             *
             * @param evt The event passed from the dispatcher
             *
             * @return void
             */
            removeOnStatusUpdate: function (evt)
            {
                var that = evt.detail.dispatcher;

                if (_.isUndefined(that)) {
                    return;
                }

                // Check if element is allowed to remove it self.
                var isAllowed = _.has(url('?', that.element.getAttribute('href')), 'hide-on-remove');

                if ('remove' === that.action && isAllowed) {
                    $(that.element).fadeOut(function ()
                    {
                        that.container.remove();
                    });
                }
            },

            /**
             * Perform the action
             *
             * @since 2.0.0
             *
             * @param data The data to send to the server
             */
            act: function (data)
            {
                this.statusToggle();

                $.ajax({
                    url: this.href,
                    data: data,
                    method: 'POST',
                    cache: false,
                    dataType: 'json',
                    /**
                     * Before Send
                     */
                    beforeSend: function ()
                    {
                        //this.toggleLoader();
                        this.running();
                    }.bind(this),
                    /**
                     * Complete
                     */
                    complete: function (xhr, status)
                    {
                        // Stop running.
                        DL.Utils.Functions.classList(this.element).remove('is-running');
                    }.bind(this),
                    /**
                     * Error
                     */
                    error: function (xhr, status, error)
                    {
                        this.statusToggle();

                        console.warn('Wishlist ' + error, status);
                    }.bind(this),
                    /**
                     * Success
                     */
                    success: function (data, status, xhr)
                    {
                    }.bind(this)
                });
            },

            /**
             * Prepare Data
             *
             * @since 2.0.0
             *
             * @returns {*} The data to send to the server
             */
            prepareData: function ()
            {
                if (!this.container && !this.element) {
                    return false;
                }

                // Retrieve the post ID from the element data-post-id attribute.
                var postID = this.element.getAttribute('data-post-id');

                return {
                    post_id: postID,
                    dlajax_action: 'wishlist',
                    crud_action: this.action,
                    wishlist_nonce: this.nonce
                };
            },

            /**
             * Add Item
             *
             * @since 2.0.0
             *
             * @param {Event} evt The event instance
             *
             * @returns void
             */
            add: function (evt)
            {
                evt.preventDefault();
                evt.stopPropagation();

                // Don't try to add anything if user isn't logged in.
                if (!localized.loggedin) {
                    return false;
                }

                var data = this.prepareData();

                if (data) {
                    this.act(data);
                }
            },

            /**
             * Init
             *
             * @since 2.0.0
             *
             * @returns {DL} this for chaining
             */
            init: function ()
            {
                DL.Utils.Events.addListener(this.element, 'click', this.add, {
                    capture: true
                });

                DL.Utils.Events.addListener(this.element, 'wishlist_item_status_update', this.removeOnStatusUpdate);

                // If not user isn't logged in, let's show the login form.
                // @note This script doesn't explicitly set the dependency to 'dl-login-register-modal' because
                // that script is loaded only when the user isn't logged in.
                if (!localized.loggedin && _.isFunction(DL.LoginRegisterFormFactory)) {
                    DL.LoginRegisterFormFactory(this.element).init();
                }

                return this;
            },

            /**
             * Construct
             *
             * @since 2.0.0
             *
             * @param {HTMLElement} element The element that will trigger the action
             *
             * @returns {DL} this for chaining
             */
            construct: function (element)
            {
                if (!element) {
                    return;
                }

                _.bindAll(
                    this,
                    'toggleLoader',
                    'statusToggle',
                    'act',
                    'prepareData',
                    'add',
                    'removeOnStatusUpdate',
                    'running',
                    'init'
                );

                var data = url('?', element.getAttribute('href'));

                this.href    = url('path', element.getAttribute('href'));
                this.nonce   = data.nonce;
                this.action  = data.action;
                this.element = element;

                // Retrieve the post ID from the container that has the attribute like 'post-{ID}'.
                this.container = $(this.element).closest('.dlarticle')[0];

                return this;
            },
        };

        /**
         * Factory
         *
         * @since 2.0.0
         *
         * @param {HTMLElement} element The element that will trigger the action
         *
         * @returns {*|DL} this for chaining
         */
        DL.WishListAdderFactory = function (element)
        {
            return Object.create(DL.WishListAdder).construct(element);
        };

        /**
         * Wishlist Collection Builder
         *
         * @since 2.0.0
         *
         * @return void
         */
        DL.WishlistCollectionBuilder = function ()
        {
            var elements = document.querySelectorAll('.dlwishlist-adder');
            elements && _.forEach(elements, function (element)
            {
                var wishlist = DL.WishListAdderFactory(element);
                wishlist && wishlist.init();
            });
        };

        window.addEventListener('load', function ()
        {
            DL.WishlistCollectionBuilder();
        });

    }(window._, window.jQuery, window.DL, window.url, window.dllocalized)
);
