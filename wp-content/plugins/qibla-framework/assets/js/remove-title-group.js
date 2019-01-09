/**
 * Remove title group JavaScript
 *
 * @since      1.0.0
 * @author     alfio piccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfio piccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfio piccione <alfio.piccione@gmail.com>
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
    function (_, $, dllocalized) {

        /**
         * Select2 remove item
         */
        function removeTitleGroup()
        {
            var selTermGroup = document.getElementById('qibla_tb_taxonomy_term_groups_title');
            if (!selTermGroup) {
                return;
            }

            // Button.
            var button = document.createElement('a');
            button.setAttribute('id', 'remove_title_group_trigger');
            button.setAttribute('class', 'button button-primary');
            button.setAttribute('href', 'javascript:;');
            button.innerHTML = dllocalized.removeTitleGroup;
            selTermGroup.insertAdjacentElement('afterend', button);

            // Input
            var input = document.createElement('input');
            input.setAttribute('id', 'remove_title_group');
            input.setAttribute('name', 'remove_title_group');
            input.setAttribute('type', 'hidden');
            selTermGroup.insertAdjacentElement('afterend', input);

            var btn = document.getElementById('remove_title_group_trigger');
            var optionsValue = '';
            btn.addEventListener('click', function (ev) {
                ev.preventDefault();
                ev.stopImmediatePropagation();
                var currentSelected = document.querySelector('.select2-selection__rendered');
                var options = document.getElementById('qibla_tb_taxonomy_term_groups_title');

                // Remove option.
                _.forEach(options, function (option, index) {
                    if (option && 'none' !== currentSelected.getAttribute('title') &&
                        option.getAttribute('value') === currentSelected.getAttribute('title')
                    ) {
                        var title = options[index].getAttribute('value');
                        // Check yes or not remove.
                        if (confirm('Do you want to delete the title "' + title + '" from the list?')) {
                            optionsValue += title + ',';
                            options[index].remove();
                        }
                    }
                });

                var input = document.getElementById('remove_title_group');
                input.setAttribute('value', optionsValue);
            });

        }

        document.addEventListener('DOMContentLoaded', function (ev) {
            removeTitleGroup();
        })

    }(window._, window.jQuery, window.dllocalized)
);