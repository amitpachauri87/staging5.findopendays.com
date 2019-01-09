/**
 * qibla select2 Visual Composer Type
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
    function (window, _, $)
    {
        "use strict";

        var types = document.querySelectorAll('.wpb_el_type_qibla_select2 select');
        if (!types.length) {
            return;
        }

        // Remove the [] from the multiple select or visual composer will not fire the atts class methods.
        // See below.
        _.forEach(types, function (el)
        {
            var nameAttr = el.getAttribute('name');
            if (el.getAttribute('multiple')) {
                nameAttr = nameAttr.replace('[]', '');
                el.setAttribute('name', nameAttr);
            }
        });

        // Create the select2 object.
        $('.wpb_el_type_qibla_select2 select').select2({
            tags: true
        }).on("select2:select", function (evt)
        {
            var element  = evt.params.data.element,
                $element = $(element);

            $element.detach();

            $(this).append($element);
            $(this).trigger("change");
        });

        window.vc.atts.qibla_select2 = {
            /**
             * Used to save multiple values in single string for saving/parsing/opening
             * @param param
             * @returns {string}
             */
            parse: function (param)
            {
                var newValue = '',
                    el       = this.content()[0].querySelector('select[name=' + param.param_name + ']');

                if (el) {
                    newValue = $(el).val();
                }

                return newValue;
            },
            /**
             * Used in shortcode saving
             * Default: '' empty (unchecked)
             * Can be overwritten by 'std'
             * @param param
             * @returns {string}
             */
            defaults: function (param)
            {
                return '';
            }
        };

    }(window, window._, window.jQuery)
);
