<?php
/**
 * Font awesome Solid
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
 * Class Font awesome Solid
 *
 * @since  2.4.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class Fontawesomesolid extends AbstractIconsSet
{
    public function __construct()
    {
        $this->version = '5.0.12';
        $this->prefix  = 'fas';
        $this->list    = array(
            'fa-address-book'                        => 'f2b9',
            'fa-address-card'                        => 'f2bb',
            'fa-adjust'                              => 'f042',
            'fa-align-center'                        => 'f037',
            'fa-align-justify'                       => 'f039',
            'fa-align-left'                          => 'f036',
            'fa-align-right'                         => 'f038',
            'fa-allergies'                           => 'f461',
            'fa-ambulance'                           => 'f0f9',
            'fa-american-sign-language-interpreting' => 'f2a3',
            'fa-anchor'                              => 'f13d',
            'fa-angle-double-down'                   => 'f103',
            'fa-angle-double-left'                   => 'f100',
            'fa-angle-double-right'                  => 'f101',
            'fa-angle-double-up'                     => 'f102',
            'fa-angle-down'                          => 'f107',
            'fa-angle-left'                          => 'f104',
            'fa-angle-right'                         => 'f105',
            'fa-angle-up'                            => 'f106',
            'fa-archive'                             => 'f187',
            'fa-arrow-alt-circle-down'               => 'f358',
            'fa-arrow-alt-circle-left'               => 'f359',
            'fa-arrow-alt-circle-right'              => 'f35a',
            'fa-arrow-alt-circle-up'                 => 'f35b',
            'fa-arrow-circle-down'                   => 'f0ab',
            'fa-arrow-circle-left'                   => 'f0a8',
            'fa-arrow-circle-right'                  => 'f0a9',
            'fa-arrow-circle-up'                     => 'f0aa',
            'fa-arrow-down'                          => 'f063',
            'fa-arrow-left'                          => 'f060',
            'fa-arrow-right'                         => 'f061',
            'fa-arrow-up'                            => 'f062',
            'fa-arrows-alt'                          => 'f0b2',
            'fa-arrows-alt-h'                        => 'f337',
            'fa-arrows-alt-v'                        => 'f338',
            'fa-assistive-listening-systems'         => 'f2a2',
            'fa-asterisk'                            => 'f069',
            'fa-at'                                  => 'f1fa',
            'fa-audio-description'                   => 'f29e',
            'fa-backward'                            => 'f04a',
            'fa-balance-scale'                       => 'f24e',
            'fa-ban'                                 => 'f05e',
            'fa-band-aid'                            => 'f462',
            'fa-barcode'                             => 'f02a',
            'fa-bars'                                => 'f0c9',
            'fa-baseball-ball'                       => 'f433',
            'fa-basketball-ball'                     => 'f434',
            'fa-bath'                                => 'f2cd',
            'fa-battery-empty'                       => 'f244',
            'fa-battery-full'                        => 'f240',
            'fa-battery-half'                        => 'f242',
            'fa-battery-quarter'                     => 'f243',
            'fa-battery-three-quarters'              => 'f241',
            'fa-bed'                                 => 'f236',
            'fa-beer'                                => 'f0fc',
            'fa-bell'                                => 'f0f3',
            'fa-bell-slash'                          => 'f1f6',
            'fa-bicycle'                             => 'f206',
            'fa-binoculars'                          => 'f1e5',
            'fa-birthday-cake'                       => 'f1fd',
            'fa-blind'                               => 'f29d',
            'fa-bold'                                => 'f032',
            'fa-bolt'                                => 'f0e7',
            'fa-bomb'                                => 'f1e2',
            'fa-book'                                => 'f02d',
            'fa-bookmark'                            => 'f02e',
            'fa-bowling-ball'                        => 'f436',
            'fa-box'                                 => 'f466',
            'fa-box-open'                            => 'f49e',
            'fa-boxes'                               => 'f468',
            'fa-braille'                             => 'f2a1',
            'fa-briefcase'                           => 'f0b1',
            'fa-briefcase-medical'                   => 'f469',
            'fa-bug'                                 => 'f188',
            'fa-building'                            => 'f1ad',
            'fa-bullhorn'                            => 'f0a1',
            'fa-bullseye'                            => 'f140',
            'fa-burn'                                => 'f46a',
            'fa-bus'                                 => 'f207',
            'fa-calculator'                          => 'f1ec',
            'fa-calendar'                            => 'f133',
            'fa-calendar-alt'                        => 'f073',
            'fa-calendar-check'                      => 'f274',
            'fa-calendar-minus'                      => 'f272',
            'fa-calendar-plus'                       => 'f271',
            'fa-calendar-times'                      => 'f273',
            'fa-camera'                              => 'f030',
            'fa-camera-retro'                        => 'f083',
            'fa-capsules'                            => 'f46b',
            'fa-car'                                 => 'f1b9',
            'fa-caret-down'                          => 'f0d7',
            'fa-caret-left'                          => 'f0d9',
            'fa-caret-right'                         => 'f0da',
            'fa-caret-square-down'                   => 'f150',
            'fa-caret-square-left'                   => 'f191',
            'fa-caret-square-right'                  => 'f152',
            'fa-caret-square-up'                     => 'f151',
            'fa-caret-up'                            => 'f0d8',
            'fa-cart-arrow-down'                     => 'f218',
            'fa-cart-plus'                           => 'f217',
            'fa-certificate'                         => 'f0a3',
            'fa-chart-area'                          => 'f1fe',
            'fa-chart-bar'                           => 'f080',
            'fa-chart-line'                          => 'f201',
            'fa-chart-pie'                           => 'f200',
            'fa-check'                               => 'f00c',
            'fa-check-circle'                        => 'f058',
            'fa-check-square'                        => 'f14a',
            'fa-chess'                               => 'f439',
            'fa-chess-bishop'                        => 'f43a',
            'fa-chess-board'                         => 'f43c',
            'fa-chess-king'                          => 'f43f',
            'fa-chess-knight'                        => 'f441',
            'fa-chess-pawn'                          => 'f443',
            'fa-chess-queen'                         => 'f445',
            'fa-chess-rook'                          => 'f447',
            'fa-chevron-circle-down'                 => 'f13a',
            'fa-chevron-circle-left'                 => 'f137',
            'fa-chevron-circle-right'                => 'f138',
            'fa-chevron-circle-up'                   => 'f139',
            'fa-chevron-down'                        => 'f078',
            'fa-chevron-left'                        => 'f053',
            'fa-chevron-right'                       => 'f054',
            'fa-chevron-up'                          => 'f077',
            'fa-child'                               => 'f1ae',
            'fa-circle'                              => 'f111',
            'fa-circle-notch'                        => 'f1ce',
            'fa-clipboard'                           => 'f328',
            'fa-clipboard-check'                     => 'f46c',
            'fa-clipboard-list'                      => 'f46d',
            'fa-clock'                               => 'f017',
            'fa-clone'                               => 'f24d',
            'fa-closed-captioning'                   => 'f20a',
            'fa-cloud'                               => 'f0c2',
            'fa-cloud-download-alt'                  => 'f381',
            'fa-cloud-upload-alt'                    => 'f382',
            'fa-code'                                => 'f121',
            'fa-code-branch'                         => 'f126',
            'fa-coffee'                              => 'f0f4',
            'fa-cog'                                 => 'f013',
            'fa-cogs'                                => 'f085',
            'fa-columns'                             => 'f0db',
            'fa-comment'                             => 'f075',
            'fa-comment-alt'                         => 'f27a',
            'fa-comment-dots'                        => 'f4ad',
            'fa-comment-slash'                       => 'f4b3',
            'fa-comments'                            => 'f086',
            'fa-compass'                             => 'f14e',
            'fa-compress'                            => 'f066',
            'fa-copy'                                => 'f0c5',
            'fa-copyright'                           => 'f1f9',
            'fa-couch'                               => 'f4b8',
            'fa-credit-card'                         => 'f09d',
            'fa-crop'                                => 'f125',
            'fa-crosshairs'                          => 'f05b',
            'fa-cube'                                => 'f1b2',
            'fa-cubes'                               => 'f1b3',
            'fa-cut'                                 => 'f0c4',
            'fa-database'                            => 'f1c0',
            'fa-deaf'                                => 'f2a4',
            'fa-desktop'                             => 'f108',
            'fa-diagnoses'                           => 'f470',
            'fa-dna'                                 => 'f471',
            'fa-dollar-sign'                         => 'f155',
            'fa-dolly'                               => 'f472',
            'fa-dolly-flatbed'                       => 'f474',
            'fa-donate'                              => 'f4b9',
            'fa-dot-circle'                          => 'f192',
            'fa-dove'                                => 'f4ba',
            'fa-download'                            => 'f019',
            'fa-edit'                                => 'f044',
            'fa-eject'                               => 'f052',
            'fa-ellipsis-h'                          => 'f141',
            'fa-ellipsis-v'                          => 'f142',
            'fa-envelope'                            => 'f0e0',
            'fa-envelope-open'                       => 'f2b6',
            'fa-envelope-square'                     => 'f199',
            'fa-eraser'                              => 'f12d',
            'fa-euro-sign'                           => 'f153',
            'fa-exchange-alt'                        => 'f362',
            'fa-exclamation'                         => 'f12a',
            'fa-exclamation-circle'                  => 'f06a',
            'fa-exclamation-triangle'                => 'f071',
            'fa-expand'                              => 'f065',
            'fa-expand-arrows-alt'                   => 'f31e',
            'fa-external-link-alt'                   => 'f35d',
            'fa-external-link-square-alt'            => 'f360',
            'fa-eye'                                 => 'f06e',
            'fa-eye-dropper'                         => 'f1fb',
            'fa-eye-slash'                           => 'f070',
            'fa-fast-backward'                       => 'f049',
            'fa-fast-forward'                        => 'f050',
            'fa-fax'                                 => 'f1ac',
            'fa-female'                              => 'f182',
            'fa-fighter-jet'                         => 'f0fb',
            'fa-file'                                => 'f15b',
            'fa-file-alt'                            => 'f15c',
            'fa-file-archive'                        => 'f1c6',
            'fa-file-audio'                          => 'f1c7',
            'fa-file-code'                           => 'f1c9',
            'fa-file-excel'                          => 'f1c3',
            'fa-file-image'                          => 'f1c5',
            'fa-file-medical'                        => 'f477',
            'fa-file-medical-alt'                    => 'f478',
            'fa-file-pdf'                            => 'f1c1',
            'fa-file-powerpoint'                     => 'f1c4',
            'fa-file-video'                          => 'f1c8',
            'fa-file-word'                           => 'f1c2',
            'fa-film'                                => 'f008',
            'fa-filter'                              => 'f0b0',
            'fa-fire'                                => 'f06d',
            'fa-fire-extinguisher'                   => 'f134',
            'fa-first-aid'                           => 'f479',
            'fa-flag'                                => 'f024',
            'fa-flag-checkered'                      => 'f11e',
            'fa-flask'                               => 'f0c3',
            'fa-folder'                              => 'f07b',
            'fa-folder-open'                         => 'f07c',
            'fa-font'                                => 'f031',
            'fa-football-ball'                       => 'f44e',
            'fa-forward'                             => 'f04e',
            'fa-frown'                               => 'f119',
            'fa-futbol'                              => 'f1e3',
            'fa-gamepad'                             => 'f11b',
            'fa-gavel'                               => 'f0e3',
            'fa-gem'                                 => 'f3a5',
            'fa-genderless'                          => 'f22d',
            'fa-gift'                                => 'f06b',
            'fa-glass-martini'                       => 'f000',
            'fa-globe'                               => 'f0ac',
            'fa-golf-ball'                           => 'f450',
            'fa-graduation-cap'                      => 'f19d',
            'fa-h-square'                            => 'f0fd',
            'fa-hand-holding'                        => 'f4bd',
            'fa-hand-holding-heart'                  => 'f4be',
            'fa-hand-holding-usd'                    => 'f4c0',
            'fa-hand-lizard'                         => 'f258',
            'fa-hand-paper'                          => 'f256',
            'fa-hand-peace'                          => 'f25b',
            'fa-hand-point-down'                     => 'f0a7',
            'fa-hand-point-left'                     => 'f0a5',
            'fa-hand-point-right'                    => 'f0a4',
            'fa-hand-point-up'                       => 'f0a6',
            'fa-hand-pointer'                        => 'f25a',
            'fa-hand-rock'                           => 'f255',
            'fa-hand-scissors'                       => 'f257',
            'fa-hand-spock'                          => 'f259',
            'fa-hands'                               => 'f4c2',
            'fa-hands-helping'                       => 'f4c4',
            'fa-handshake'                           => 'f2b5',
            'fa-hashtag'                             => 'f292',
            'fa-hdd'                                 => 'f0a0',
            'fa-heading'                             => 'f1dc',
            'fa-headphones'                          => 'f025',
            'fa-heart'                               => 'f004',
            'fa-heartbeat'                           => 'f21e',
            'fa-history'                             => 'f1da',
            'fa-hockey-puck'                         => 'f453',
            'fa-home'                                => 'f015',
            'fa-hospital'                            => 'f0f8',
            'fa-hospital-alt'                        => 'f47d',
            'fa-hospital-symbol'                     => 'f47e',
            'fa-hourglass'                           => 'f254',
            'fa-hourglass-end'                       => 'f253',
            'fa-hourglass-half'                      => 'f252',
            'fa-hourglass-start'                     => 'f251',
            'fa-i-cursor'                            => 'f246',
            'fa-id-badge'                            => 'f2c1',
            'fa-id-card'                             => 'f2c2',
            'fa-id-card-alt'                         => 'f47f',
            'fa-image'                               => 'f03e',
            'fa-images'                              => 'f302',
            'fa-inbox'                               => 'f01c',
            'fa-indent'                              => 'f03c',
            'fa-industry'                            => 'f275',
            'fa-info'                                => 'f129',
            'fa-info-circle'                         => 'f05a',
            'fa-italic'                              => 'f033',
            'fa-joget'                               => 'f3b7',
            'fa-key'                                 => 'f084',
            'fa-keyboard'                            => 'f11c',
            'fa-language'                            => 'f1ab',
            'fa-laptop'                              => 'f109',
            'fa-leaf'                                => 'f06c',
            'fa-lemon'                               => 'f094',
            'fa-level-down-alt'                      => 'f3be',
            'fa-level-up-alt'                        => 'f3bf',
            'fa-life-ring'                           => 'f1cd',
            'fa-lightbulb'                           => 'f0eb',
            'fa-link'                                => 'f0c1',
            'fa-lira-sign'                           => 'f195',
            'fa-list'                                => 'f03a',
            'fa-list-alt'                            => 'f022',
            'fa-list-ol'                             => 'f0cb',
            'fa-list-ul'                             => 'f0ca',
            'fa-location-arrow'                      => 'f124',
            'fa-lock'                                => 'f023',
            'fa-lock-open'                           => 'f3c1',
            'fa-long-arrow-alt-down'                 => 'f309',
            'fa-long-arrow-alt-left'                 => 'f30a',
            'fa-long-arrow-alt-right'                => 'f30b',
            'fa-long-arrow-alt-up'                   => 'f30c',
            'fa-low-vision'                          => 'f2a8',
            'fa-magic'                               => 'f0d0',
            'fa-magnet'                              => 'f076',
            'fa-male'                                => 'f183',
            'fa-map'                                 => 'f279',
            'fa-map-marker'                          => 'f041',
            'fa-map-marker-alt'                      => 'f3c5',
            'fa-map-pin'                             => 'f276',
            'fa-map-signs'                           => 'f277',
            'fa-mars'                                => 'f222',
            'fa-mars-double'                         => 'f227',
            'fa-mars-stroke'                         => 'f229',
            'fa-mars-stroke-h'                       => 'f22b',
            'fa-mars-stroke-v'                       => 'f22a',
            'fa-medkit'                              => 'f0fa',
            'fa-meh'                                 => 'f11a',
            'fa-mercury'                             => 'f223',
            'fa-microchip'                           => 'f2db',
            'fa-microphone'                          => 'f130',
            'fa-microphone-slash'                    => 'f131',
            'fa-minus'                               => 'f068',
            'fa-minus-circle'                        => 'f056',
            'fa-minus-square'                        => 'f146',
            'fa-mobile'                              => 'f10b',
            'fa-mobile-alt'                          => 'f3cd',
            'fa-money-bill-alt'                      => 'f3d1',
            'fa-moon'                                => 'f186',
            'fa-motorcycle'                          => 'f21c',
            'fa-mouse-pointer'                       => 'f245',
            'fa-music'                               => 'f001',
            'fa-neuter'                              => 'f22c',
            'fa-newspaper'                           => 'f1ea',
            'fa-notes-medical'                       => 'f481',
            'fa-object-group'                        => 'f247',
            'fa-object-ungroup'                      => 'f248',
            'fa-outdent'                             => 'f03b',
            'fa-paint-brush'                         => 'f1fc',
            'fa-pallet'                              => 'f482',
            'fa-paper-plane'                         => 'f1d8',
            'fa-paperclip'                           => 'f0c6',
            'fa-parachute-box'                       => 'f4cd',
            'fa-paragraph'                           => 'f1dd',
            'fa-paste'                               => 'f0ea',
            'fa-pause'                               => 'f04c',
            'fa-pause-circle'                        => 'f28b',
            'fa-paw'                                 => 'f1b0',
            'fa-pen-square'                          => 'f14b',
            'fa-pencil-alt'                          => 'f303',
            'fa-people-carry'                        => 'f4ce',
            'fa-percent'                             => 'f295',
            'fa-phone'                               => 'f095',
            'fa-phone-slash'                         => 'f3dd',
            'fa-phone-square'                        => 'f098',
            'fa-phone-volume'                        => 'f2a0',
            'fa-piggy-bank'                          => 'f4d3',
            'fa-pills'                               => 'f484',
            'fa-plane'                               => 'f072',
            'fa-play'                                => 'f04b',
            'fa-play-circle'                         => 'f144',
            'fa-plug'                                => 'f1e6',
            'fa-plus'                                => 'f067',
            'fa-plus-circle'                         => 'f055',
            'fa-plus-square'                         => 'f0fe',
            'fa-podcast'                             => 'f2ce',
            'fa-poo'                                 => 'f2fe',
            'fa-portrait'                            => 'f3e0',
            'fa-pound-sign'                          => 'f154',
            'fa-power-off'                           => 'f011',
            'fa-prescription-bottle'                 => 'f485',
            'fa-prescription-bottle-alt'             => 'f486',
            'fa-print'                               => 'f02f',
            'fa-procedures'                          => 'f487',
            'fa-puzzle-piece'                        => 'f12e',
            'fa-qrcode'                              => 'f029',
            'fa-question'                            => 'f128',
            'fa-question-circle'                     => 'f059',
            'fa-quidditch'                           => 'f458',
            'fa-quote-left'                          => 'f10d',
            'fa-quote-right'                         => 'f10e',
            'fa-random'                              => 'f074',
            'fa-recycle'                             => 'f1b8',
            'fa-redo'                                => 'f01e',
            'fa-redo-alt'                            => 'f2f9',
            'fa-registered'                          => 'f25d',
            'fa-reply'                               => 'f3e5',
            'fa-reply-all'                           => 'f122',
            'fa-retweet'                             => 'f079',
            'fa-ribbon'                              => 'f4d6',
            'fa-road'                                => 'f018',
            'fa-rocket'                              => 'f135',
            'fa-rss'                                 => 'f09e',
            'fa-rss-square'                          => 'f143',
            'fa-ruble-sign'                          => 'f158',
            'fa-rupee-sign'                          => 'f156',
            'fa-save'                                => 'f0c7',
            'fa-search'                              => 'f002',
            'fa-search-minus'                        => 'f010',
            'fa-search-plus'                         => 'f00e',
            'fa-seedling'                            => 'f4d8',
            'fa-server'                              => 'f233',
            'fa-share'                               => 'f064',
            'fa-share-alt'                           => 'f1e0',
            'fa-share-alt-square'                    => 'f1e1',
            'fa-share-square'                        => 'f14d',
            'fa-shekel-sign'                         => 'f20b',
            'fa-shield-alt'                          => 'f3ed',
            'fa-ship'                                => 'f21a',
            'fa-shipping-fast'                       => 'f48b',
            'fa-shopping-bag'                        => 'f290',
            'fa-shopping-basket'                     => 'f291',
            'fa-shopping-cart'                       => 'f07a',
            'fa-shower'                              => 'f2cc',
            'fa-sign'                                => 'f4d9',
            'fa-sign-in-alt'                         => 'f2f6',
            'fa-sign-language'                       => 'f2a7',
            'fa-sign-out-alt'                        => 'f2f5',
            'fa-signal'                              => 'f012',
            'fa-sitemap'                             => 'f0e8',
            'fa-sliders-h'                           => 'f1de',
            'fa-smile'                               => 'f118',
            'fa-smoking'                             => 'f48d',
            'fa-snowflake'                           => 'f2dc',
            'fa-sort'                                => 'f0dc',
            'fa-sort-alpha-down'                     => 'f15d',
            'fa-sort-alpha-up'                       => 'f15e',
            'fa-sort-amount-down'                    => 'f160',
            'fa-sort-amount-up'                      => 'f161',
            'fa-sort-down'                           => 'f0dd',
            'fa-sort-numeric-down'                   => 'f162',
            'fa-sort-numeric-up'                     => 'f163',
            'fa-sort-up'                             => 'f0de',
            'fa-space-shuttle'                       => 'f197',
            'fa-spinner'                             => 'f110',
            'fa-square'                              => 'f0c8',
            'fa-square-full'                         => 'f45c',
            'fa-star'                                => 'f005',
            'fa-star-half'                           => 'f089',
            'fa-step-backward'                       => 'f048',
            'fa-step-forward'                        => 'f051',
            'fa-stethoscope'                         => 'f0f1',
            'fa-sticky-note'                         => 'f249',
            'fa-stop'                                => 'f04d',
            'fa-stop-circle'                         => 'f28d',
            'fa-stopwatch'                           => 'f2f2',
            'fa-street-view'                         => 'f21d',
            'fa-strikethrough'                       => 'f0cc',
            'fa-subscript'                           => 'f12c',
            'fa-subway'                              => 'f239',
            'fa-suitcase'                            => 'f0f2',
            'fa-sun'                                 => 'f185',
            'fa-superscript'                         => 'f12b',
            'fa-sync'                                => 'f021',
            'fa-sync-alt'                            => 'f2f1',
            'fa-syringe'                             => 'f48e',
            'fa-table'                               => 'f0ce',
            'fa-table-tennis'                        => 'f45d',
            'fa-tablet'                              => 'f10a',
            'fa-tablet-alt'                          => 'f3fa',
            'fa-tablets'                             => 'f490',
            'fa-tachometer-alt'                      => 'f3fd',
            'fa-tag'                                 => 'f02b',
            'fa-tags'                                => 'f02c',
            'fa-tape'                                => 'f4db',
            'fa-tasks'                               => 'f0ae',
            'fa-taxi'                                => 'f1ba',
            'fa-terminal'                            => 'f120',
            'fa-text-height'                         => 'f034',
            'fa-text-width'                          => 'f035',
            'fa-th'                                  => 'f00a',
            'fa-th-large'                            => 'f009',
            'fa-th-list'                             => 'f00b',
            'fa-thermometer'                         => 'f491',
            'fa-thermometer-empty'                   => 'f2cb',
            'fa-thermometer-full'                    => 'f2c7',
            'fa-thermometer-half'                    => 'f2c9',
            'fa-thermometer-quarter'                 => 'f2ca',
            'fa-thermometer-three-quarters'          => 'f2c8',
            'fa-thumbs-down'                         => 'f165',
            'fa-thumbs-up'                           => 'f164',
            'fa-thumbtack'                           => 'f08d',
            'fa-ticket-alt'                          => 'f3ff',
            'fa-times'                               => 'f00d',
            'fa-times-circle'                        => 'f057',
            'fa-tint'                                => 'f043',
            'fa-toggle-off'                          => 'f204',
            'fa-toggle-on'                           => 'f205',
            'fa-trademark'                           => 'f25c',
            'fa-train'                               => 'f238',
            'fa-transgender'                         => 'f224',
            'fa-transgender-alt'                     => 'f225',
            'fa-trash'                               => 'f1f8',
            'fa-trash-alt'                           => 'f2ed',
            'fa-tree'                                => 'f1bb',
            'fa-trophy'                              => 'f091',
            'fa-truck'                               => 'f0d1',
            'fa-truck-loading'                       => 'f4de',
            'fa-truck-moving'                        => 'f4df',
            'fa-tty'                                 => 'f1e4',
            'fa-tv'                                  => 'f26c',
            'fa-umbrella'                            => 'f0e9',
            'fa-underline'                           => 'f0cd',
            'fa-undo'                                => 'f0e2',
            'fa-undo-alt'                            => 'f2ea',
            'fa-universal-access'                    => 'f29a',
            'fa-university'                          => 'f19c',
            'fa-unlink'                              => 'f127',
            'fa-unlock'                              => 'f09c',
            'fa-unlock-alt'                          => 'f13e',
            'fa-upload'                              => 'f093',
            'fa-user'                                => 'f007',
            'fa-user-alt'                            => 'f406',
            'fa-user-alt-slash'                      => 'f4fa',
            'fa-user-astronaut'                      => 'f4fb',
            'fa-user-check'                          => 'f4fc',
            'fa-user-circle'                         => 'f2bd',
            'fa-user-clock'                          => 'f4fd',
            'fa-user-cog'                            => 'f4fe',
            'fa-user-edit'                           => 'f4ff',
            'fa-user-friends'                        => 'f500',
            'fa-user-graduate'                       => 'f501',
            'fa-user-lock'                           => 'f502',
            'fa-user-md'                             => 'f0f0',
            'fa-user-minus'                          => 'f503',
            'fa-user-ninja'                          => 'f504',
            'fa-user-plus'                           => 'f234',
            'fa-user-secret'                         => 'f21b',
            'fa-user-shield'                         => 'f505',
            'fa-user-slash'                          => 'f506',
            'fa-user-tag'                            => 'f507',
            'fa-user-tie'                            => 'f508',
            'fa-user-times'                          => 'f235',
            'fa-users'                               => 'f0c0',
            'fa-users-cog'                           => 'f509',
            'fa-utensil-spoon'                       => 'f2e5',
            'fa-utensils'                            => 'f2e7',
            'fa-venus'                               => 'f221',
            'fa-venus-double'                        => 'f226',
            'fa-venus-mars'                          => 'f228',
            'fa-vial'                                => 'f492',
            'fa-vials'                               => 'f493',
            'fa-video'                               => 'f03d',
            'fa-video-slash'                         => 'f4e2',
            'fa-volleyball-ball'                     => 'f45f',
            'fa-volume-down'                         => 'f027',
            'fa-volume-off'                          => 'f026',
            'fa-volume-up'                           => 'f028',
            'fa-warehouse'                           => 'f494',
            'fa-weight'                              => 'f496',
            'fa-wheelchair'                          => 'f193',
            'fa-wifi'                                => 'f1eb',
            'fa-window-close'                        => 'f410',
            'fa-window-maximize'                     => 'f2d0',
            'fa-window-minimize'                     => 'f2d1',
            'fa-window-restore'                      => 'f2d2',
            'fa-wine-glass'                          => 'f4e3',
            'fa-won-sign'                            => 'f159',
            'fa-wrench'                              => 'f0ad',
            'fa-x-ray'                               => 'f497',
            'fa-yen-sign'                            => 'f157',
        );

        parent::__construct();
    }
}
