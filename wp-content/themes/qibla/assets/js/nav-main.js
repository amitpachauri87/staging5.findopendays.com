/**
 * Navigation Main
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

;(
    function (_, ClassList)
    {
        "use strict";

        // Get the main nav.
        var mainNav = document.querySelector('.dlnav-main');
        if (!mainNav) {
            return;
        }

        var mainNavListItems = mainNav.querySelector('.dlnav-main__list-items');
        if (!mainNavListItems) {
            return;
        }

        _.forEach(mainNavListItems.querySelectorAll('.menu-item'), function (item)
        {
            item.addEventListener('mouseenter', function (e)
            {
                if (mainNav.classList.contains('dlnav-main--mobile')) {
                    return;
                }

                var target     = e.target,
                    // See the nav-main.scss about the position of the first sub-menu, that have a rule that override the
                    // position calculated by the script. More info there.
                    subMenu    = target.querySelector('.sub-menu'),
                    isSubMenu  = target.parentElement.classList.contains('sub-menu'),
                    subMenuPos = 0;

                // Check if sub-menu exists.
                if (!subMenu) {
                    return;
                }

                // Is sub menu when the current target ('li') is inside a ul with the class sub-menu. Depth >= 2.
                if (isSubMenu) {
                    var mainItem = target,
                        depth    = parseInt(mainNavListItems.getAttribute('data-depth'));
                    // We can't know how deeply we are within the menu, so starting from the current position,
                    // get up until the item menu at level 1 is reached, then get the sub-menu position using
                    // the offset of the mainItem, because of the sub-menus and menu item position respectively
                    // absolute and relative, the offsetLeft value of the sub-menu is 0.
                    while (mainItem = mainItem.parentElement) {
                        if (!mainItem.classList.contains('sub-menu')) {
                            var mainItemRect = mainItem.getBoundingClientRect();
                            // target.offsetWidth because of we need to add the current sub menu element.
                            subMenuPos       = mainItemRect.left + mainItem.offsetWidth + target.offsetWidth + subMenu.offsetWidth;
                            break; // We have the main item, the one at level 1.
                        }
                    }

                    if (subMenu) {
                        subMenu.classList.remove('fadeOutDown');
                        (new ClassList(subMenu)).add('animated', 'fadeInUp');
                    }
                } else {
                    subMenu.classList.remove('fadeOutDownSubMenu');
                    (new ClassList(subMenu)).add('animated', 'fadeInUpSubMenu');

                    var targetRect = target.getBoundingClientRect();
                    // Sub-menu position.
                    subMenuPos     = targetRect.left + target.offsetWidth + subMenu.offsetWidth;
                }

                if (Math.round(subMenuPos) >= window.innerWidth) {
                    subMenu.style.right = isSubMenu ? '100%' : '0';
                    subMenu.style.left  = isSubMenu ? 'auto' : 'auto';
                } else {
                }
            });

            item.addEventListener('mouseleave', function (e)
            {
                if (mainNav.classList.contains('dlnav-main--mobile')) {
                    return;
                }

                var target    = e.target,
                    subMenu   = target.querySelector('.sub-menu'),
                    isSubMenu = target.parentElement.classList.contains('sub-menu');

                if (!subMenu) {
                    return;
                }

                if (isSubMenu) {
                    if (subMenu) {
                        subMenu.classList.remove('fadeInUp');
                        subMenu.classList.add('fadeOutDown');
                    }
                } else {
                    subMenu.classList.remove('fadeInUpSubMenu');
                    subMenu.classList.add('fadeOutDownSubMenu');
                }
            });
        });
    }(_, window.ClassList)
);