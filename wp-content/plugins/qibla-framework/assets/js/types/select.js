/**
 * Select
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
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

window.DL = window.DL || {};

(function (_, Modernizr, $, DL)
{
    "use strict";

    window.addEventListener('load', function ()
    {
        // Retrieve the elements.
        var selects = document.querySelectorAll('.dlselect2');

        if (!selects.length) {
            return;
        }

        _.forEach(selects, function (s)
        {
            // The tagging attr.
            var tagging = s.getAttribute('data-tagging');
            // The width of the select based on class attribute.
            var width   = s.classList.contains('dlselect2--wide') ? '100%' : '25%';

            var options = {
                theme: s.getAttribute('data-selecttheme'),
                width: width,
                allowClear: false
            };

            if ('yes' === tagging) {
                options.tags = true;
                options.minimumResultsForSearch = '';
            } else {
                options.minimumResultsForSearch = 20;
            }
            // Get the select2 instance.
            var $select = $(s).select2(options);

            // Fired on change.
            $select.change(function (e)
            {
                DL.Utils.Events.dispatchEvent('select2change', e.target);
            });
        });
    });
})(_, window.Modernizr, window.jQuery, window.DL);
