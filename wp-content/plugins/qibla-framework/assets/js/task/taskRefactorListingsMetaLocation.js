/**
 * taskRefactorListingsMetaLocation
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
    function (_, $, DL, localized, ajaxUrl)
    {
        "use strict";

        /**
         * Task Refactor Listings Meta Location
         *
         * @since 1.7.0
         */
        var Task = {
            /**
             * Success
             *
             * When server get back with success data.
             *
             * @since 1.7.0
             *
             * @param data The data returned by the server.
             */
            success: function (data)
            {
                var response;

                try {
                    response = data.data;

                    // Remember to update the task arguments.
                    this.offset += this.number;

                    // Remember to update the failed list.
                    if ('failed' in response.data) {
                        _.union(this.failed, response.data.failed);
                    }

                    // If should keep going.
                    // Consider the absence of 'completed' property a sign to stop to do everything.
                    // Preventing unknown issues.
                    this.completed = response.data.completed;
                } catch (e) {
                    this.completed = true;
                    response       = e;
                }

                if (this.completed) {
                    DL.Utils.Events.dispatchEvent(this.name + '_completed', window, null, {response: response});
                    return;
                }

                // Continue.
                this.exec();
            },

            /**
             * Error
             *
             * @since 1.7.0
             *
             * @param jqXHR THe HXR instance.
             */
            error: function (jqXHR)
            {
                var response   = {};
                this.completed = true;

                try {
                    if (!_.isUndefined(jqXHR.responseJSON)) {
                        response = jqXHR.responseJSON.data;
                        // Remember to update the failed list.
                        if ('failed' in response.data) {
                            _.union(this.failed, response.data.failed);
                        }
                    }
                } catch (e) {
                    response = e;
                }

                // Dispatch the error event.
                DL.Utils.Events.dispatchEvent(this.name + '_error', window, null, {response: response});
            },

            /**
             * Exec
             *
             * Execute the task
             *
             * @since 1.7.0
             */
            exec: function ()
            {
                $.ajax(ajaxUrl, {
                    cache: false,
                    contentType: 'application/x-www-form-urlencoded; charset=' + localized.charset,
                    context: this.form,
                    data: this.args(),
                    dataType: 'json',
                    global: false,
                    method: 'POST',
                    timeout: 300000,
                    success: this.success,
                    error: this.error
                });
            },

            /**
             * Arguments
             *
             * The arguments for the task to pass to the server.
             *
             * @since 1.7.0
             *
             * @returns {{dlajax_action: string, request_task_nonce: *, task_name: string, task_args: {number: Number, offset: Number}}}
             */
            args: function ()
            {
                return {
                    dlajax_action: 'request_task',
                    request_task_nonce: this.nonce,
                    task_name: this.name,
                    task_args: {
                        number: parseInt(this.number),
                        offset: parseInt(this.offset),
                    }
                };
            },

            /**
             * Init
             *
             * @since 1.7.0
             */
            init: function ()
            {
            },

            /**
             * Construct
             *
             * @since 1.7.0
             *
             * @param {string} nonce The nonce to use on server to validate the request.
             *
             * @returns {Task} This for concatenation
             */
            construct: function (nonce)
            {
                _.bindAll(
                    this,
                    'init',
                    'exec',
                    'args',
                    'success',
                    'error'
                );

                // Task name to perform.
                this.name      = 'refactor_listings_meta_location';
                // The nonce for the request.
                this.nonce     = nonce;
                // Number to posts to process per request.
                this.number    = 10;
                // Number of posts from which start.
                this.offset    = 0;
                // True when the task finish to perform his internal tasks.
                this.completed = false;
                // Container for the failed posts.
                this.failed    = [];

                return this;
            },
        };

        /**
         * Task Form
         *
         * A wrapper for the task to used by a form trigger
         *
         * @since 1.7.0
         */
        var TaskForm = {
            /**
             * Toggle Loader
             *
             * @since 1.7.0
             */
            toggleLoader: function ()
            {
                DL.Utils.UI.toggleLoader(this.form);
            },

            /**
             * Show Message
             *
             * @since 1.7.0
             *
             * @param {Object} failed  The list of the post that are not updated.
             * @param {string} message The message to show.
             */
            message: function (failed, message)
            {
                message && this.form.insertAdjacentHTML('beforeBegin', '<h3>' + message + '</h3>');

                if (!_.isUndefined(failed) && !_.isEmpty(failed)) {
                    var list = document.createElement('ul');

                    _.forEach(failed, function (item)
                    {
                        var el       = document.createElement('li');
                        el.innerHTML = '<a href="' + item.permalink + '">' + item.title + '</a>';

                        list.appendChild(el);
                    });

                    this.form.appendChild(list);
                }

                this.form.querySelector('#run_task').remove();
                this.toggleLoader();
            },

            /**
             * Exec
             *
             * @since 1.7.0
             *
             * @param evt The submit event
             */
            exec: function (evt)
            {
                evt.preventDefault();
                evt.stopPropagation();

                this.toggleLoader();

                // Exec the task.
                this.task.exec();
            },

            /**
             * Init
             *
             * @since 1.7.0
             */
            init: function ()
            {
                DL.Utils.Events.addListener(this.form, 'submit', this.exec);
                DL.Utils.Events.addListener(
                    window,
                    ['refactor_listings_meta_location_completed', 'refactor_listings_meta_location_error'],
                    function (evt)
                    {
                        var failed,
                            message;

                        try {
                            failed  = evt.detail.response.data.failed;
                            message = evt.detail.response.message;
                        } catch (e) {
                            failed  = [];
                            message = 'Unknown error.';
                        }

                        this.message(failed, message);
                    }.bind(this)
                );
            },

            /**
             * Construct
             *
             * @since 1.7.0
             *
             * @param {HTMLElement} form THe form element.
             * @param {Object} task The task to wrap.
             *
             * @returns {TaskForm} The instance of the object for concatenation
             */
            construct: function (form, task)
            {
                _.bindAll(
                    this,
                    'init',
                    'exec',
                    'toggleLoader'
                );

                this.task = task;
                this.form = form;

                return this;
            },
        };

        /**
         * Task Factory
         *
         * @constructor
         *
         * @since 1.7.0
         *
         * @param {string} nonce The nonce to use on server to validate the request.
         * @param {object} options The options to pass to the Task.
         *
         * @returns {*|Task} For concatenation
         */
        var TaskFactory = function (nonce, options)
        {
            return Object.create(Task).construct(nonce, options);
        };

        /**
         * Task Form Factory
         *
         * @constructor
         *
         * @param {HTMLElement} form The form to use as trigger
         *
         * @returns {*|TaskForm} For concatenation
         */
        var TaskFormFactory = function (form)
        {
            return Object.create(TaskForm).construct(
                form,
                TaskFactory(form.querySelector('#request_task_nonce').value)
            );
        };

        window.addEventListener('load', function ()
        {
            var form = document.querySelector('#task_refactor_listings_meta_location');
            if (!form) {
                return;
            }

            var task = TaskFormFactory(form);
            task && task.init();
        });

    }(window._, window.jQuery, window.DL, window.dllocalized, window.ajaxurl)
);
