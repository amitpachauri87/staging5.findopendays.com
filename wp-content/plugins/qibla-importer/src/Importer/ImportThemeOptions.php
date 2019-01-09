<?php
namespace QiblaImporter\Importer;

use QiblaFramework\Admin\Settings\Import as DwImporter;

/**
 * Import Theme Option
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
 * Class ImportThemeOptions
 *
 * @since   1.0.0
 *
 * @package QiblaImporter\Importer
 */
class ImportThemeOptions extends AbstractImporter
{
    /**
     * Importer
     *
     * @since  1.0.0
     * @access protected
     *
     * @var \QiblaFramework\Admin\Settings\Import The importer instance
     */
    protected $importer;

    /**
     * ImportThemeOptions constructor.
     *
     * @since  1.0.0
     * @access public
     *
     * @param DwImporter $importer The instance of the theme options importer.
     */
    public function __construct(DwImporter $importer)
    {
        $this->importer = $importer;
    }

    /**
     * Import Options
     *
     * @since  1.0.0
     * @access public
     *
     * @throws \InvalidArgumentException If the data is empty or not valid.
     * @throws \Exception                Whatever exception the DwImporter::importer throw.
     *
     * @param array $data The list of the data to store.
     *
     * @return null
     */
    public function import(array $data)
    {
        if (! $data) {
            throw new \InvalidArgumentException(
                esc_html__('Cannot import Theme Option. Data is empty.', 'qibla-importer')
            );
        }

        $this->importer->import($data);
    }
}
