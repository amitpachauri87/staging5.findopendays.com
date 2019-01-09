/**
 * search
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

;(
    function (_, $, DL)
    {
        "use strict";

        function toggleOverlay(element)
        {
            DL.Utils.Functions.classList(element).toggle('dljumbotron--overlay-darken');
        }

        var jumbotron = document.querySelector('.dljumbotron');
        if (!jumbotron) {
            return;
        }

        $('.dlsearch select').on('select2:open', function ()
        {
            toggleOverlay(jumbotron);
        });

        $('.dlsearch select').on('select2:close', function ()
        {
            toggleOverlay(jumbotron);
        });

        // Darken the jumbotron on search focus.
        _.forEach(
            jumbotron.querySelectorAll('.dlsearch input'),
            function (input)
            {
                DL.Utils.Events.addListener(input, 'focus', function ()
                {
                    toggleOverlay(jumbotron);
                });
            }
        );
        _.forEach(
            jumbotron.querySelectorAll('.dlsearch input'),
            function (input)
            {
                DL.Utils.Events.addListener(input, 'blur', function ()
                {
                    toggleOverlay(jumbotron);
                });
            }
        );
    }(window._, window.jQuery, window.DL)
);
