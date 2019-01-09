<?php
namespace QiblaImporter\Importer;

use WXRImporter\WPImporterLogger;

/**
 * Import Demo
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

class ImportDemo extends AbstractImporter
{
    /**
     * Importer
     *
     * @since  1.0.0
     * @access protected
     *
     * @var \WP_Importer
     */
    protected $importer;

    /**
     * ImportDemo constructor.
     *
     * @since 1.0.0
     *
     * @param \WP_Importer $importer
     */
    public function __construct(\WP_Importer $importer)
    {
        $this->importer = $importer;
    }

    /**
     * Import
     *
     * @since 1.0.0
     *
     * @throws \InvalidArgumentException In case the file doesn't exists within the data array.
     * @throws \Exception                If the \WXRImporter return a \WP_Error.
     *
     * @param array $data The list of the data to store.
     *
     * @return void
     */
    public function import(array $data)
    {
        if (! isset($data['file'])) {
            throw new \InvalidArgumentException(
                sprintf(
                    esc_html__('%s invalid data format', 'qibla-importer'),
                    __METHOD__
                )
            );
        }

        // Import Demo data.
        $result = $this->importer->import($data['file']);

        if (is_wp_error($result)) {
            throw new \Exception($result->get_message());
        }
    }

    /**
     * Set Logger
     *
     * @since  1.0.0
     * @access public
     *
     * @param \WXRImporter\WPImporterLogger $logger
     */
    public function setLogger(WPImporterLogger $logger)
    {
        $this->importer->set_logger($logger);
    }
}
