/**
 * Select2
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

window.DL = window.DL || {};

;(
    function (Modernizr, $, DL) {
        "use strict";

        /**
         * Init Select2
         */
        function initSelect2()
        {
            var $select = $('select');

            var theme = $select.attr('data-selecttheme');
            if (!theme) {
                theme = 'qibla'
            }

            var dropDwParent = document.getElementById('dlform_filter');
            if (!dropDwParent) {
                dropDwParent = document.body;
            }

            $select.select2({
                theme: theme,
                width: '100%',
                //minimumResultsForSearch: 50,
                dropdownParent: $(dropDwParent),
                templateResult: templateResult
            });

            $select.change(function (e) {
                DL.Utils.Events.dispatchEvent('select2change', e.target);
            });
        }

        /**
         * Select Overlay
         *
         * @since 2.3.0
         */
        function overlay()
        {
            var $select = $('select');

            var list = document.querySelector('.dllistings-list');
            var toolbar = document.querySelector('.dllistings-toolbar');
            var map = document.querySelector('.dlgoogle-map');
            if (!list) {
                return;
            }

            var sel = false;

            // Open
            $select.on('select2:open', function () {
                sel = $(this).attr('id');
                if (toolbar) {
                    toolbar.style.transition = 'opacity 275ms ease-in-out';
                    toolbar.style.opacity = '.3';
                }
                if (map) {
                    map.style.transition = 'opacity 275ms ease-in-out';
                    map.style.opacity = '.3';
                }
                if (list) {
                    list.classList.add('overlay');
                    list.style.transition = 'opacity 275ms ease-in-out';
                    list.style.opacity = '.3';
                }
            });

            // Close
            $select.on('select2:close', function () {
                if ($(this).attr('id') === sel) {
                    if (toolbar) {
                        toolbar.style.opacity = '1';
                    }
                    if (map) {
                        map.style.opacity = '1';
                    }
                    if (list) {
                        list.classList.remove('overlay');
                        list.style.opacity = '1';
                    }
                }
            });
        }

        /**
         * Template Result
         *
         * @since 2.2.2
         *
         * @param state
         */
        function templateResult(state)
        {
            if (!state.id) {
                return state.text;
            }

            var textState = state.text,
                markup = state.element.getAttribute('data-markup');

            if (null !== markup) {
                textState = whitHeading(markup, state);
            }

            return textState;
        }

        /**
         * Whit Heading
         *
         * @since 2.2.2
         *
         * @param markup
         * @param state
         * @returns {*|jQuery|HTMLElement}
         */
        function whitHeading(markup, state)
        {
            return $('<strong class="select2-results__option-heading">' + markup + '</strong>' +
                     '<span class="select2-results__option-text">' + state.text + '</span>');
        }

        window.addEventListener('load', function () {
            initSelect2();
            overlay();
        });

        //
        // WorkAround for WooCommerce country field.
        // The field select2 is reinitilized and the style of the theme is missed.
        // @see https://github.com/woocommerce/woocommerce/issues/14647.
        $(document.body).bind('country_to_state_changed', function () {
            setTimeout(function () {
                initSelect2();
            }, 0);
        });
    }(window.Modernizr, window.jQuery, window.DL)
);