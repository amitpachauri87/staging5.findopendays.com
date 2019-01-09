<?php
namespace QiblaImporter\Importer;

/**
 * Import Settings
 *
 * @copyright Copyright (c) 2016, Guido Scialfa
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
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

defined('WPINC') || die;

/**
 * Class ImportSettings
 *
 * @since   1.0.0
 *
 * @package QiblaImporter\Importer
 */
class ImportSettings extends AbstractImporter
{
    /**
     * Import
     *
     * @todo  return some data in case one or more settings cannot be saved.
     *
     * @since 1.0.0
     *
     * @throw \InvalidArgumentException If the data is empty or not valid.
     * @throws \Exception               When one or more options cannot be saved.
     *
     * @param array $data The list of the data to store.
     *
     * @return void
     */
    public function import(array $data)
    {
        global $wpdb;

        // List of the update response.
        $optList = array();

        // Data empty, nothing to do.
        if (! $data) {
            throw new \InvalidArgumentException('Cannot Import settings, data empty.');
        }

        // Loop through the options.
        foreach ($data as $key => $value) {
            $key = sanitize_key($key);

            $row = $wpdb->get_row(
                $wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $key)
            );

            // Do not create options.
            if (null !== $row) {
                if (! update_option($key, $value)) {
                    $optList[$key] = false;
                }
            }
        }

//        if (! empty($optList)) {
//            throw new \Exception(
//                sprintf(
//                    esc_html__(
//                        '%1$sImported%2$s The Following options: %3$s are not imported correctly. You should set them manually.',
//                        'qibla-importer'
//                    ),
//                    '<h5>',
//                    '</h5>',
//                    implode(',', array_keys($optList))
//                )
//            );
//        }
    }
}
