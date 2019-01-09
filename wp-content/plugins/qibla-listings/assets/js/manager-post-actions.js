/**
 * manager-post-actions.php
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
    function (_, $, dllocalized, DL)
    {
        "use strict";

        var Actions = {
            /**
             * The callback list
             *
             * @since 1.0.0
             *
             * @type {Object}
             */
            callBackList: {
                edit: function ()
                {
                },
                delete: function (evt)
                {
                    evt.preventDefault();
                    evt.stopImmediatePropagation();

                    var target = evt.target;
                    if ('I' === evt.target.tagName && 'A' === evt.target.parentNode.tagName) {
                        // This is a workaround for IE10 because not support pointer-events to the elements.
                        // Dropping support to IE10 this may be removed.
                        target = evt.target.parentNode;
                    }

                    // Get the data obj from the url.
                    var data = DL.Utils.Functions.deparam(decodeURI(target.getAttribute('href')));

                    // Make the call.
                    $.ajax(dllocalized.site_url + '/index.php', {
                        method: 'POST',
                        data: data,
                        beforeSend: function (xhr)
                        {
                            DL.Utils.UI.toggleLoader(target);
                        },
                        complete: function ()
                        {
                            DL.Utils.UI.toggleLoader(target);
                        },
                        success: function (data, textStatus, jqXHR)
                        {
                            // Get the parent
                            var row = $(target).parents('.dlmanager-posts-post')[0];
                            $(row).fadeOut(function ()
                            {
                                this.remove();
                            });
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            console.error('Manager Posts - Action Error: ' + errorThrown);
                        }
                    });
                }
            },

            /**
             * Add Listener
             *
             * @since 1.0.0
             *
             * @param obj
             * @param event
             * @param callback
             * @param options
             * @param extra
             *
             * @returns {Object} this for chaining
             */
            addListener: function (obj, event, callback, options, extra)
            {
                if (!obj || _.isArray(obj)) {
                    throw 'Invalid Object on addListener.';
                }

                if (!_.isFunction(callback)) {
                    throw 'Invalid callback on addListener.';
                }

                // Set the event listener.
                obj.addEventListener(event, function (e)
                {
                    callback.call(this, e, extra);
                }.bind(this), options);

                return this;
            },

            /**
             * Add Listeners
             *
             * @since 1.0.0
             *
             * @return this for chaining
             */
            addListeners: function ()
            {
                _.forEach(this.callBackList, function (callback, name)
                {
                    // Try to get the element.
                    var actionEl = document.querySelectorAll('.dlpost-action--' + name);
                    if (actionEl) {
                        _.forEach(actionEl, function (item)
                        {
                            this.addListener(item, 'click', callback.bind(this));
                        }.bind(this));
                    }
                }.bind(this));

                return this;
            },

            /**
             * Construct
             *
             * @since 1.0.0
             *
             * @return this for chaining
             */
            construct: function ()
            {
                _.bindAll(
                    this,
                    'addListeners',
                    'init',
                    'addListener'
                );
            },

            /**
             * Initialize
             *
             * @since 1.0.0
             *
             * @return this for chaining
             */
            init: function ()
            {
                this.addListeners();

                return this;
            }
        };

        window.addEventListener('load', function ()
        {
            Actions.construct();
            Actions.init();
        });
    }(_, window.jQuery, window.dllocalized, window.DL)
);
