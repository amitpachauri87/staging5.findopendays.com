/**
 * parallax.js
 *
 * @since      1.4.0
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

// Just to avoid issues with other elements.
dlDataParallax = window.dlDataParallax || {};

;(
    function (_, jarallax, dlparallaxlocalized, dlDataParallax)
    {
        "use strict";

        if (typeof jarallax === 'undefined') {
            return;
        }

        // Get the jarallax function.
        var Jarallax = jarallax.constructor;

        // Set the videoImage to prevent script to create it automatically from the video.
        VideoWorker.prototype.videoImage = dlDataParallax.imgSrc;
        VideoWorker.prototype.parseURL   = function (url)
        {
            // parse youtube ID
            function getYoutubeID(ytUrl)
            {
                var regExp = /.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/;
                var match  = ytUrl.match(regExp);
                return match && match[1].length === 11 ? match[1] : false;
            }

            // parse vimeo ID
            function getVimeoID(vmUrl)
            {
                var regExp = /https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/;
                var match  = vmUrl.match(regExp);
                return match && match[3] ? match[3] : false;
            }

            // parse local string
            function getLocalVideos(locUrl)
            {
                var videoFormats = locUrl.split(/,(?=mp4\:|webm\:|ogv\:|ogg\:)/);
                var result       = {};
                var ready        = 0;

                if (typeof videoFormats[0] !== 'undefined') {
                    result.mp4 = videoFormats[0];
                    ready      = 1;
                }

                return ready ? result : false;
            }

            var Youtube = getYoutubeID(url);
            var Vimeo   = getVimeoID(url);
            var Local   = getLocalVideos(url);

            if (Youtube) {
                this.type = 'youtube';
                return Youtube;
            } else if (Vimeo) {
                this.type = 'vimeo';
                return Vimeo;
            } else if (Local) {
                this.type = 'local';
                return Local;
            }

            return false;
        };

        // init video
        Jarallax.prototype.initImg = function ()
        {
            var _this         = this;
            var defaultResult = (function (_this)
            {
                // get image src
                if (_this.image.src === null) {
                    _this.image.src = _this.css(_this.$item, 'background-image')
                                           .replace(/^url\(['"]?/g, '')
                                           .replace(/['"]?\)$/g, '');
                }
                return !(!_this.image.src || _this.image.src === 'none');
            }(_this));

            if (!_this.options.videoSrc) {
                _this.options.videoSrc = _this.$item.getAttribute('data-jarallax-video') || false;
            }

            if (_this.options.videoSrc) {
                var video = new VideoWorker(_this.options.videoSrc, {
                    startTime: _this.options.videoStartTime || 0,
                    endTime: _this.options.videoEndTime || 0
                });

                if (video.isValid()) {
                    _this.image.useImgTag = true;

                    video.on('ready', function ()
                    {
                        var oldOnScroll = _this.onScroll;
                        _this.onScroll  = function ()
                        {
                            oldOnScroll.apply(_this);
                            if (_this.isVisible()) {
                                video.play();
                            } else {
                                video.pause();
                            }
                        };
                    });

                    video.on('started', function ()
                    {
                        _this.image.$default_item = _this.image.$item;
                        _this.image.$item         = _this.$video;

                        // set video width and height
                        _this.image.width = _this.options.imgWidth = _this.video.videoWidth || 1280;
                        _this.image.height = _this.options.imgHeight = _this.video.videoHeight || 720;
                        _this.coverImage();
                        _this.clipContainer();
                        _this.onScroll();

                        /**
                         * Remove the cover when needed
                         *
                         * @since 1.4.0
                         */
                        function removeCover()
                        {
                            /**
                             * Remove Image
                             *
                             * @since 1.6.0
                             */
                            function removeImage()
                            {
                                jQuery(_this.image.$default_item).fadeOut();
                                clearInterval(timeupdater);
                            }

                            if ('local' !== video.type && _this.video.player && _this.video.player.getCurrentTime) {
                                if (_this.video.player.getCurrentTime() > 1) {
                                    removeImage();
                                }
                            } else if ('local' === video.type) {
                                removeImage();
                            }
                        }

                        var timeupdater = setInterval(removeCover, 100);
                    });

                    _this.video = video;

                    if (video.type !== 'local') {
                        video.getImageURL(function (url)
                        {
                            _this.image.src = url;
                            _this.init();
                        });
                    }
                }

                // prevent default image loading when not local video
                if (video.type !== 'local') {
                    return false;
                }

                // set empty image on local video if not defined
                else if (!defaultResult) {
                    _this.image.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
                    return true;
                }
            }

            return defaultResult;
        };

        document.addEventListener('DOMContentLoaded', function ()
        {
            if (!dlparallaxlocalized.isParallaxEnabled) {
                return;
            }

            // Jumbotron Parallax
            var jumbotron = document.querySelector('.dljumbotron--use-parallax');
            // The Parallax.
            if (jumbotron && _.isObject(dlDataParallax)) {
                try {
                    // Initialize the parallax.
                    jarallax(jumbotron, dlDataParallax);
                } catch (e) {
                    ('dev' === dllocalized.env) && console.warn(e);
                }
            }

            // General Parallax.
            var elements = document.querySelectorAll('.use-parallax');
            elements.length && jarallax(elements, dlparallaxlocalized.parallaxOptions);
        });
    }(_, jarallax, dlparallaxlocalized, dlDataParallax)
);
