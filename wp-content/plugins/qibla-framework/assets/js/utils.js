/**
 * utils
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
    function (_, $, DL, ClassList)
    {
        "use strict";

        DL.Utils = {
            /**
             * Functions
             *
             * @since 1.5.0
             *
             * @type {object}
             */
            Functions: {
                /**
                 * Deparam
                 *
                 * From query string to object.
                 * The inverse of the $.param() function.
                 *
                 * @since 1.5.0
                 *
                 * @link https://coderwall.com/p/quv2zq/deparam-function-in-javascript-opposite-of-param
                 *
                 * @param {string} querystring The query string to convert to object
                 *
                 * @returns {{}} The object create by the query string
                 */
                deparam: function (querystring)
                {
                    // Remove any preceding url and split.
                    querystring = querystring.substring(querystring.indexOf('?') + 1).split('&');
                    var params  = {}, pair, d = decodeURIComponent, i;

                    // March and parse.
                    for (i = querystring.length; i > 0;) {
                        pair               = querystring[--i].split('=');
                        params[d(pair[0])] = d(pair[1]);
                    }

                    return params;
                },

                /**
                 * Class List Utils
                 *
                 * Used to BC with IE
                 *
                 * @since 1.5.1
                 *
                 * @param {HTMLElement} el The element for which create the classList object.
                 * @returns {ClassList} The classList object
                 */
                classList: function (el)
                {
                    return new ClassList(el);
                },

                /**
                 * Unique Array
                 *
                 * @since 2.3.0
                 *
                 * @param arrArg
                 */
                uniqueArray: function (arrArg) {
                    return arrArg.filter(function (elem, pos, arr) {
                        return arr.indexOf(elem) === pos;
                    });
                },

                /**
                 * Equals Array
                 *
                 * @since 2.3.0
                 *
                 * @param arr1
                 * @param arr2
                 */
                isEqualsArray: function (arr1, arr2) {
                    // Attach the .equals method to Array's prototype to call it on any array
                    Array.prototype.equals = function (array) {
                        // If the other array is a falsy value, return
                        if (!array) {
                            return false;
                        }

                        // Compare lengths - can save a lot of time
                        if (this.length !== array.length) {
                            return false;
                        }

                        for (var i = 0, l = this.length; i < l; i++) {
                            // Check if we have nested arrays
                            if (this[i] instanceof Array && array[i] instanceof Array) {
                                // recurse into the nested arrays
                                if (!this[i].equals(array[i])) {
                                    return false;
                                }
                            }
                            else if (this[i] !== array[i]) {
                                // Warning - two different object instances will never be equal: {x:20} != {x:20}
                                return false;
                            }
                        }
                        return true;
                    };

                    Object.defineProperty(Array.prototype, "equals", {enumerable: false});

                    if (Array.isArray(arr1) && Array.isArray(arr2)) {
                        return arr1.equals(arr2);
                    }
                }
            },

            /**
             * String
             *
             * @since 1.5.1
             *
             * @type {object}
             */
            String: {
                /**
                 * Capitalize
                 *
                 * @since 1.5.1
                 * @link http://alvinalexander.com/javascript/how-to-capitalize-each-word-javascript-string
                 *
                 * @param str The string to capitalize.
                 *
                 * @return string The capitalized string
                 */
                capitalize: function (str)
                {
                    return str.replace(/\w\S*/g, function (txt)
                    {
                        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                    });
                },

                /**
                 * Variablize String
                 *
                 * Create a camelized variable representation by a string
                 *
                 * @since 1.5.1
                 *
                 * @param string string The string to make as variable
                 *
                 * @returns {string} The variable representation of the string
                 */
                variablizeString: function (string)
                {
                    return (this.capitalize(string.replace(/[-_]+/g, ' '))).replace(/(\s)+/g, '');
                },

                /**
                 * Slugify
                 *
                 * @link https://gist.github.com/mathewbyrne/1280286
                 *
                 * @since 2.0.0
                 *
                 * @param {string} string The string to slugify.
                 *
                 * @returns {string} The slugified string
                 */
                toSlug: function (string)
                {
                    return string.toString().toLowerCase()

//                                 .replace(/\s+/g, '-')
//                                 .replace(/[^\w\-]+/g, '')
//                                 .replace(/\-\-+/g, '-')
//                                 .replace(/^-+/, '')
//                                 .replace(/-+$/, '')

                    // Cyrillic support @since 2.4.0.
                                 .replace(/\s+/g, '-')
                                 .replace(/\-\-+/g, '-')
                                 .replace(/^-+/, '')
                                 .replace(/-+$/, '')
                    .replace(/[^\w\s\u0400-\u04FF-]/g, '');
                },

                /**
                 * From Slug
                 *
                 * @since 2.0.0
                 *
                 * @param string The string to transform to slug.
                 *
                 * @returns {string} The slugified string
                 */
                fromSlug: function (string)
                {
                    return string.toString().replace('/\-\_/g', ' ');
                }
            },

            /**
             * Events
             *
             * @since 1.5.0
             *
             * @type {object}
             */
            Events: {
                /**
                 * Add Listener
                 *
                 * @since 1.5.0
                 *
                 * @throws Error if object is not valid or callback is not a function.
                 *
                 * @param obj
                 * @param event
                 * @param callback
                 * @param options
                 * @param extra
                 *
                 * @returns this for chaining
                 */
                addListener: function (obj, event, callback, options, extra)
                {
                    if (!obj || _.isArray(obj)) {
                        throw 'Invalid Object on addListener.';
                    }

                    if (!_.isFunction(callback)) {
                        throw 'Invalid callback on addListener.';
                    }

                    // Make it as an array, allow us to use multiple event but define the callback once.
                    event = _.isArray(event) ? event : [event];

                    // Through the event list.
                    _.forEach(event, function (evt)
                    {
                        // Set the event listener.
                        obj.addEventListener(evt, function (e)
                        {
                            callback.call(this, e, extra);
                        }.bind(this), options);
                    });

                    return this;
                },

                /**
                 * Dispatch Event
                 *
                 * Cross-browser version of the Event class with callback feature.
                 *
                 * Note:
                 * The event callback function need to call the callback by it self.
                 *
                 * @since 1.5.1
                 *
                 * @throws Error if object is not valid or callback is not a function.
                 *
                 * @param {string} event The event name.
                 * @param {*} obj The object on which the event must be dispatched.
                 * @param {Function} callback The callback to execute on event callback function. Optional.
                 * @param {*} detail Custom details. Optional.
                 */
                dispatchEvent: function (event, obj, callback, detail)
                {
                    if (!obj || _.isArray(obj)) {
                        throw 'Invalid Object on addListener.';
                    }

                    // Throw error only if callback is passed.
                    if (callback && !_.isFunction(callback)) {
                        throw 'Invalid callback on addListener.';
                    }

                    // Add custom details if passed.
                    detail = _.extend({
                        callback: callback
                    }, detail);

                    var theEvent = new CustomEvent(event, {
                        detail: detail
                    });

                    obj.dispatchEvent(theEvent);
                }
            },

            /**
             * UI
             *
             * @since 1.5.0
             *
             * @type {object}
             */
            UI: {
                /**
                 * Toggle Loader
                 *
                 * @since 1.0.0
                 *
                 * @param {object} element The element in which append the loader
                 * @param {Function} inCallback The callback to call after the loader become visible.
                 * @param {Function} outCallback The callback to call after the loader become hidden.
                 */
                toggleLoader: function (element, inCallback, outCallback)
                {
                    // Get the loader.
                    var loader = document.querySelector('.ajax-loader');
                    if (!loader) {
                        return;
                    }

                    var isIE11  = !!window.MSInputMethodContext && !!document.documentMode;
                    var display = window.getComputedStyle(element, null).getPropertyValue('display');

                    // Ie 11 fix loader.
                    if (isIE11 && ('flex' === display || '-ms-flexbox' === display)) {
                        loader.style.display = 'block';
                        $(element).stop(true, true).fadeOut();
                        if (!element.parentNode.lastElementChild.classList.contains('ajax-loader')) {
                            element.parentNode.appendChild(loader);
                        }
                    }

                    if ('block' === loader.style.display) {
                        $(loader).stop(true, true).fadeOut(function ()
                        {
                            $(element).stop(true, true).fadeIn();
                            if (!isIE11) {
                                // Prevent to lost the loader when the listing Element is removed.
                                document.body.appendChild(loader);
                            }

                            // Callback to call when loader showing.
                            if (_.isFunction(inCallback)) {
                                inCallback();
                            }
                        }.bind(this));
                    } else {
                        if (!isIE11 && !element.parentNode.lastElementChild.classList.contains('ajax-loader')) {
                            element.parentNode.appendChild(loader);
                        }

                        $(element).stop(true, true).fadeOut(function ()
                        {
                            loader.style.display = 'block';

                            // Callback to call when loader will be hidden.
                            if (_.isFunction(outCallback)) {
                                outCallback();
                            }
                        });
                    }
                },
            }
        };
    }(_, window.jQuery, window.DL, window.ClassList)
);
