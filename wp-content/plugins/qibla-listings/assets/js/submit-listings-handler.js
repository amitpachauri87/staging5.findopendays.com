/**
 * submit-listings-handler
 *
 * @since      1.0.0
 * @version    1.0.0
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
    function (dllocalized, $, DL)
    {
        "use strict";

        window.addEventListener('load', function ()
        {
            // Set the form.
            var form = document.querySelector('#qibla_listing_form');

            if (form) {
                var dlform = DL.Form.getForm(form, {
                    /**
                     * Custom AjaxAction
                     *
                     * @since 1.0.0
                     *
                     * @type {String} The name of the action that the server must perform
                     */
                    ajaxAction: form.querySelector('#qibla_listing_form_action').value,

                    /**
                     * Force Update
                     *
                     * For when even if there are no files to upload, something changed on media and we need to
                     * perform the after submit tasks.
                     *
                     * @since 1.0.0
                     *
                     * @type {Boolean} True to force update, false otherwise. Default to false.
                     */
                    forceMediaUpdate: false,

                    /**
                     * On Init Callback
                     *
                     * @since 1.0.0
                     */
                    onInit: function ()
                    {
                        if (!_.isUndefined(this.dropZone)) {
                            _.forEach(this.dropZone.dropzoneCollection, function (dropzone)
                            {
                                dropzone.on('removedfile', function (element)
                                {
                                    // Update the prefilled media ID's when a file is removed from dropzone.
                                    // The edit form need to show the media attached to the post. But we need to allow
                                    // users to remove them if they want.
                                    // To do that there are hidden fields with ID's comma separated that will be send
                                    // to the server to perform the sync action.
                                    // The ones that remain on submit, are the ones that we want to keep.
                                    var dzPrefilledDataRef = dropzone.options.dzPrefilledDataRef;
                                    if (!_.isUndefined(dzPrefilledDataRef)) {
                                        var el     = this.form.querySelector('#' + dzPrefilledDataRef),
                                            values = el.value.split(',').map(function (val)
                                            {
                                                return parseInt(val);
                                            });

                                        var existsIndex = values.indexOf(element.id);
                                        if (-1 !== existsIndex) {
                                            values.splice(existsIndex, 1);
                                            el.value = values.join(',');
                                            // This is to inform the script that there has been changes to sync.
                                            // Useful because not always a user request to upload images.
                                            this.forceMediaUpdate = true;
                                        }

                                    }
                                }.bind(this));
                            }.bind(this));
                        }
                    },

                    /**
                     * Success Callback
                     *
                     * @since 1.0.0
                     *
                     * @param {Object} success The data object
                     */
                    ajaxSuccessCb: function (success)
                    {
                        var response                   = success.data,
                            _self                      = this,
                            count                      = this.dropZone.dropzoneCollection.length,
                            performDropzoneUpload      = false;

                        // Check if there are files to upload.
                        for (var c = 0; c < count; ++c) {
                            // There are valid dropzone files to upload?
                            if (this.dropZone.dropzoneCollection[c].getQueuedFiles().length) {
                                performDropzoneUpload = true;
                                break;
                            }
                        }

                        if (performDropzoneUpload || this.forceMediaUpdate) {
                            // When we need to sync the media but there are no files to upload.
                            if (this.forceMediaUpdate && !performDropzoneUpload) {
                                $.ajax(dllocalized.site_url + '/index.php', {
                                    method: 'post',
                                    data: _.extend({
                                        dlajax_action: 'store_media_file',
                                        dlajax_subaction: 'sync_media_ids',
                                        data_file: JSON.stringify({
                                            post: {
                                                action: response.data.action,
                                                ID: response.data.postID
                                            }
                                        })
                                    }, dlform.getFormHiddenFields()),

                                    /**
                                     * Success
                                     *
                                     * @since 1.0.0
                                     *
                                     * @param success XHR data success
                                     */
                                    success: function (success)
                                    {
                                        _self.toggleLoader();
                                        _self.showAlert(
                                            response.message,
                                            'success',
                                            ['la', 'la-check']
                                        );
                                    }
                                });
                            } else {
                                // Create the event details to pass to the Fields Type.
                                var eventDetail = {
                                    dropzoneCb: [
                                        {
                                            name: 'sending',

                                            /**
                                             * Append Extra Form Data to the Dropzone
                                             *
                                             * @since 1.0.0
                                             *
                                             * @type {Function}
                                             *
                                             * @param file
                                             * @param xhr
                                             * @param formData
                                             *
                                             * @return void
                                             */
                                            cb: function (file, xhr, formData)
                                            {
                                                // Set the fields to send via form.
                                                var fields = _.extend({
                                                    dlajax_action: 'store_media_file',
                                                    // Use snake-case to be compatible with array key structure.
                                                    // Don't change it.
                                                    data_file: JSON.stringify({
                                                        post: {
                                                            action: response.data.action,
                                                            ID: response.data.postID
                                                        }
                                                    })
                                                }, dlform.getFormHiddenFields());

                                                _.forEach(fields, function (value, key)
                                                {
                                                    // Append any data passed by the event a part of the request.
                                                    // Used for example to pass nonce fields and extra hidden data.
                                                    formData.append(key, value);
                                                });
                                            }
                                        }
                                    ]
                                };

                                // Perform different actions for different context.
                                // On edit context we don't need to perform the after submit tasks.
                                // Just let the user about the server response.
                                if ('edit' !== response.data.action) {
                                    eventDetail.dropzoneCb.push({
                                        name: 'queuecomplete',

                                        /**
                                         * After Submit Tasks
                                         *
                                         * @since 1.0.0
                                         *
                                         * @type {Function}
                                         *
                                         * @return void
                                         */
                                        cb: function ()
                                        {
                                            // Tell the server that everything is ok and he can perform the
                                            // after submit tasks.
                                            $.ajax(dllocalized.site_url + '/index.php', {
                                                method: 'post',
                                                data: _.extend({
                                                    dlajax_action: 'post_listing_form_submit_tasks',
                                                    action: response.data.action,
                                                    qibla_newly_post: response.data.postID
                                                }, dlform.getFormHiddenFields()),
                                                success: function (success)
                                                {
                                                    // If everything is went well, redirect the user to the next page.
                                                    var location = success.data.data.location;
                                                    if (!_.isUndefined(location)) {
                                                        window.location = location;
                                                    }
                                                }
                                            });
                                        }
                                    });
                                } else {
                                    eventDetail.dropzoneCb.push({
                                        name: 'queuecomplete',
                                        cb: function ()
                                        {
                                            _self.toggleLoader();
                                            _self.showAlert(
                                                response.message,
                                                'success',
                                                ['la', 'la-check']
                                            );
                                        }
                                    });
                                }

                                // Dispatch file upload.
                                this.dispatch('ajax-form-submit-files', eventDetail);
                            }
                        } else {
                            switch (response.data.action) {
                                default:
                                    this.toggleLoader();
                                    this.showAlert(response.message, 'success', ['la', 'la-check']);
                                    break;
                            }
                        }
                    }
                });

                dlform.init();
            }
        });

    }(window.dllocalized, window.jQuery, window.DL)
);
