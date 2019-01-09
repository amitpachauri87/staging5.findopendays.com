<?php
namespace QiblaImporter\Importer;

use QiblaImporter\Functions as F;
use QiblaImporter\Demo;

/**
 * Controller
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

class Controller
{
    /**
     * Load Requirement Libs
     *
     * @since  1.0.0
     * @access private
     *
     * @return void
     */
    private function loadRequirements()
    {
        require_once untrailingslashit(ABSPATH) . '/wp-admin/includes/image.php';
        require_once untrailingslashit(ABSPATH) . '/wp-admin/includes/media.php';
    }

    /**
     * Importer php padding
     *
     * Set some ini variables to prevent issue with large files.
     *
     * @since  1.0.0
     * @access private
     *
     * @throws \Exception In case one of the variables cannot be set.
     *
     * @return void
     */
    private function setImporterPadding()
    {
        // Time to run the import!
//        if (false === set_time_limit(0)) {
//            throw new \Exception(esc_html__('Cannot set time limit to 0. Aborting.', 'qibla-importer'));
//        }
//        if (false === ini_set('max_execution_time', 300)) {
//            throw new \Exception(esc_html__('Cannot set max_execution_time. Aborting.', 'qibla-importer'));
//        }
//        if (false === ini_set('memory_limit', -1)) {
//            throw new \Exception(esc_html__('Cannot set memory_limit. Aborting.', 'qibla-importer'));
//        }

        @set_time_limit(0);
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', -1);
    }

    /**
     * Initialize Importer
     *
     * @since  1.0.0
     * @access protected
     *
     * @return void
     */
    protected function init()
    {
        if (! defined('QB_IMPORT')) {
            define('QB_IMPORT', true);
        }

        $this->loadRequirements();
        $this->setImporterPadding();
    }

    /**
     * Get the Demo slug
     *
     * Retrieve the demo slug to import
     *
     * @since  1.0.0
     * @access protected
     *
     * @return string The slug of the demo to import
     */
    protected function getDemoSlug()
    {
        // Retrieve the demo slug.
        return filter_input(INPUT_GET, 'import', FILTER_SANITIZE_STRING);
    }

    /**
     * Notice
     *
     * @since  1.0.0
     * @access public
     *
     * @param $type
     * @param $message
     */
    public function notice($type, $message)
    {
        printf(
            '<div class="notice notice-%1$s is-dismissible"><p>%2$s</p></div>',
            sanitize_html_class($type),
            esc_html($message)
        );
    }

    /**
     * Import
     *
     * @since  1.0.0
     * @access public
     *
     * @return void
     */
    public function import()
    {
        $self     = $this;
        $demoSlug = $this->getDemoSlug();

        if ($demoSlug) :
            // Check for nonce.
            check_admin_referer('qbimport_action', 'qbimport_nonce');

            if (! current_user_can('manage_options')) {
                wp_die('Cheatin&#8217; uh?');
            }

            try {
                $this->init();

                $demo     = new Demo($demoSlug);
                $importer = new Importer($demo);

                // Import the demo.
                $importer->import(new Demo($demoSlug));

                add_action('admin_notices', function () use ($self) {
                    $self->notice('success', esc_html__('Demo imported correctly. Have fun!', 'qibla-importer'));
                });
            } catch (\Exception $e) {
                add_action('admin_notices', function () use ($e, $self) {
                    $self->notice('error', $e->getMessage());
                });

                return;
            }//end try
        endif;

        // Flush the rewrite rules.
        flush_rewrite_rules();
    }
}
