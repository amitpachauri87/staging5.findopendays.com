<?php
/**
 * Font awesome Regular
 *
 * @since      2.4.0
 * @package    QiblaFramework\IconsSet
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

namespace QiblaFramework\IconsSet;

/**
 * Class Font awesome Regular
 *
 * @since  2.4.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class Fontawesomeregular extends AbstractIconsSet
{
    public function __construct()
    {
        $this->version = '5.0.12';
        $this->prefix  = 'far';
        $this->list    = array(
            'fa-address-book'           => 'f2b9',
            'fa-address-card'           => 'f2bb',
            'fa-arrow-alt-circle-down'  => 'f358',
            'fa-arrow-alt-circle-left'  => 'f359',
            'fa-arrow-alt-circle-right' => 'f35a',
            'fa-arrow-alt-circle-up'    => 'f35b',
            'fa-bell'                   => 'f0f3',
            'fa-bell-slash'             => 'f1f6',
            'fa-bookmark'               => 'f02e',
            'fa-building'               => 'f1ad',
            'fa-calendar'               => 'f133',
            'fa-calendar-alt'           => 'f073',
            'fa-calendar-check'         => 'f274',
            'fa-calendar-minus'         => 'f272',
            'fa-calendar-plus'          => 'f271',
            'fa-calendar-times'         => 'f273',
            'fa-caret-square-down'      => 'f150',
            'fa-caret-square-left'      => 'f191',
            'fa-caret-square-right'     => 'f152',
            'fa-caret-square-up'        => 'f151',
            'fa-caret-up'               => 'f0d8',
            'fa-chart-bar'              => 'f080',
            'fa-check-circle'           => 'f058',
            'fa-check-square'           => 'f14a',
            'fa-circle'                 => 'f111',
            'fa-clipboard'              => 'f328',
            'fa-clock'                  => 'f017',
            'fa-clone'                  => 'f24d',
            'fa-closed-captioning'      => 'f20a',
            'fa-comment'                => 'f075',
            'fa-comment-alt'            => 'f27a',
            'fa-comment-dots'           => 'f4ad',
            'fa-comments'               => 'f086',
            'fa-compass'                => 'f14e',
            'fa-copy'                   => 'f0c5',
            'fa-copyright'              => 'f1f9',
            'fa-credit-card'            => 'f09d',
            'fa-dot-circle'             => 'f192',
            'fa-edit'                   => 'f044',
            'fa-envelope'               => 'f0e0',
            'fa-envelope-open'          => 'f2b6',
            'fa-eye'                    => 'f06e',
            'fa-eye-slash'              => 'f070',
            'fa-file'                   => 'f15b',
            'fa-file-alt'               => 'f15c',
            'fa-file-archive'           => 'f1c6',
            'fa-file-audio'             => 'f1c7',
            'fa-file-code'              => 'f1c9',
            'fa-file-excel'             => 'f1c3',
            'fa-file-image'             => 'f1c5',
            'fa-file-pdf'               => 'f1c1',
            'fa-file-powerpoint'        => 'f1c4',
            'fa-file-video'             => 'f1c8',
            'fa-file-word'              => 'f1c2',
            'fa-flag'                   => 'f024',
            'fa-folder'                 => 'f07b',
            'fa-folder-open'            => 'f07c',
            'fa-frown'                  => 'f119',
            'fa-futbol'                 => 'f1e3',
            'fa-gem'                    => 'f3a5',
            'fa-hand-lizard'            => 'f258',
            'fa-hand-paper'             => 'f256',
            'fa-hand-peace'             => 'f25b',
            'fa-hand-point-down'        => 'f0a7',
            'fa-hand-point-left'        => 'f0a5',
            'fa-hand-point-right'       => 'f0a4',
            'fa-hand-point-up'          => 'f0a6',
            'fa-hand-pointer'           => 'f25a',
            'fa-hand-rock'              => 'f255',
            'fa-hand-scissors'          => 'f257',
            'fa-hand-spock'             => 'f259',
            'fa-handshake'              => 'f2b5',
            'fa-hdd'                    => 'f0a0',
            'fa-heart'                  => 'f004',
            'fa-hospital'               => 'f0f8',
            'fa-hourglass'              => 'f254',
            'fa-id-badge'               => 'f2c1',
            'fa-id-card'                => 'f2c2',
            'fa-image'                  => 'f03e',
            'fa-images'                 => 'f302',
            'fa-keyboard'               => 'f11c',
            'fa-lemon'                  => 'f094',
            'fa-life-ring'              => 'f1cd',
            'fa-lightbulb'              => 'f0eb',
            'fa-list-alt'               => 'f022',
            'fa-map'                    => 'f279',
            'fa-meh'                    => 'f11a',
            'fa-minus-square'           => 'f146',
            'fa-money-bill-alt'         => 'f3d1',
            'fa-moon'                   => 'f186',
            'fa-newspaper'              => 'f1ea',
            'fa-object-group'           => 'f247',
            'fa-object-ungroup'         => 'f248',
            'fa-paper-plane'            => 'f1d8',
            'fa-pause-circle'           => 'f28b',
            'fa-play-circle'            => 'f144',
            'fa-plus-square'            => 'f0fe',
            'fa-question-circle'        => 'f059',
            'fa-registered'             => 'f25d',
            'fa-save'                   => 'f0c7',
            'fa-share-square'           => 'f14d',
            'fa-smile'                  => 'f118',
            'fa-snowflake'              => 'f2dc',
            'fa-square'                 => 'f0c8',
            'fa-star'                   => 'f005',
            'fa-star-half'              => 'f089',
            'fa-sticky-note'            => 'f249',
            'fa-stop-circle'            => 'f28d',
            'fa-sun'                    => 'f185',
            'fa-thumbs-down'            => 'f165',
            'fa-thumbs-up'              => 'f164',
            'fa-times-circle'           => 'f057',
            'fa-trash-alt'              => 'f2ed',
            'fa-user'                   => 'f007',
            'fa-user-circle'            => 'f2bd',
            'fa-window-close'           => 'f410',
            'fa-window-maximize'        => 'f2d0',
            'fa-window-minimize'        => 'f2d1',
            'fa-window-restore'         => 'f2d2',
        );

        parent::__construct();
    }
}
