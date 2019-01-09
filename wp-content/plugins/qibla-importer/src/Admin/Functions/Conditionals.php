<?php
namespace QiblaImporter\Admin\Functions;

/**
 * Conditional Admin Functions
 *
 * @package QiblaImporter\Admin\Functions
 * @author  guido scialfa <dev@guidoscialfa.com>
 *
 * @license GPL 2
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
 * Is Importer page?
 *
 * @since 1.0.0
 *
 * @return bool True if it is the current importer page within admin. False otherwise.
 */
function isImporterPage()
{
    return is_admin() && in_array(
            get_current_screen()->id,
            array('toplevel_page_qb-importer', 'qibla_page_qb-importer'),
            true
        );
}
