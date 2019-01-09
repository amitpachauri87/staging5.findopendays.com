/**
 * Importer
 *
 * Version: 1.0.0
 * Author: Guido Scialfa <dev@guidoscialfa.com>
 * Author URI: http://www.guidoscialfa.com
 * License: GPL2
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
;(function (_, $)
{
    window.addEventListener('load', function ()
    {
        // Retrieve the demo actions.
        var demoActions = document.querySelectorAll('.demo-actions .action-import');
        if (!demoActions.length) {
            return;
        }

        _.forEach(demoActions, function (actions)
        {
            actions.addEventListener('click', function (evt)
            {
                evt.preventDefault();
                evt.stopImmediatePropagation();

                // Retrieve the url from the element.
                var actionUrl = this.getAttribute('href');
                if (!actionUrl) {
                    return;
                }

                // Prevent to able to click multiple time when the import running.
                if (this.classList.contains('importing')) {
                    return;
                }

                // Set the importing flag.
                this.classList.add('importing');
                this.style.opacity = '0.3';

                // Initialize Variables.
                var self          = this,
                    demoContainer = document.querySelector('#' + self.getAttribute('data-slugreferer')),
                    elBox         = demoContainer.querySelector('.demo-screenshot'),
                    evtSource     = new EventSource(actionUrl),
                    progressArea  = elBox.querySelector('.qbimpprogress'),
                    ajaxLoader    = demoContainer.querySelector('.ajax-loader');

                // Create the progress area if not exists.
                if (!progressArea) {
                    progressArea = document.createElement('p');
                    progressArea.classList.add('qbimpprogress');
                    // Add the progress area.
                    elBox.append(progressArea);
                } else {
                    // Else clean the inner html.
                    progressArea.innerHTML = '';
                    progressArea.classList.remove('qbimpprogress--success', 'qbimpprogress--failed', 'la', 'la-check');
                }

                // Show the ajax loader.
                if (!ajaxLoader) {
                    // Get the ajax loader and put within the elBox.
                    ajaxLoader = document.querySelector('.ajax-loader');
                    demoContainer.querySelector('.demo-actions').append(ajaxLoader);
                } else {
                    ajaxLoader.style.display = 'inline-block';
                }
                /**
                 * On Message Event
                 *
                 * @since 1.0.0
                 *
                 * @param {{object}} message The message from the server
                 */
                evtSource.onmessage = function (message)
                {
                    var data = JSON.parse(message.data);
                    switch (data.action) {
                        case 'complete':
                            // On complete close the event source connection.
                            evtSource.close();

                            if (!data.error) {
                                progressArea.classList.add('qbimpprogress--success');
                                progressArea.innerHTML = '<span><span class="la la-check"></span><span class="qbimpprogress__message">' + data.success + '</span></span>';
                            } else {
                                progressArea.innerHTML = data.error;
                                if ('dev' === qbimplocalized.env) {
                                    // Show the errors on console if any.
                                    console.log(data.error);
                                }
                            }

                            // Remove the importing flag.
                            self.classList.remove('importing');

                            self.style.opacity       = '1';
                            ajaxLoader.style.display = 'none';
                            break;
                        default:
                            if ('dev' === qbimplocalized.env) {
                                console.log(data);
                            }
                            break;
                    }
                };

                /**
                 * Server Log
                 *
                 * Server send some log during import
                 *
                 * @since 1.0.0
                 *
                 * @param {{Object}} The message log
                 */
                evtSource.addEventListener('log', function (message)
                {
                    // Get the Object from data.
                    var data = JSON.parse(message.data);

                    if (-1 !== _.indexOf(['info', 'notice'], data.level)) {
                        progressArea.innerHTML = data.message;
                    }

                    // Log data in console only if the enviroment is set to 'dev'.
                    if ('dev' === qbimplocalized.env) {
                        console.log(data.message);
                    }
                });
            });
        });
    });

}(_, jQuery));