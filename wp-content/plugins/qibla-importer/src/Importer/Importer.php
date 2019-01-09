<?php

namespace QiblaImporter\Importer;

use QiblaImporter\Functions as F;
use QiblaFramework\Admin\Settings\Import as DwImport;
use QiblaImporter\Demo;
use QiblaImporter\Plugin;

/**
 * Importer
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
 * Class Importer
 *
 * @since   1.0.0
 *
 * @package QiblaImporter\Importer
 */
class Importer
{
    /**
     * Demo
     *
     * @since  1.0.0
     * @access protected
     *
     * @var Demo The instance of the Demo class
     */
    protected $demo;

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @param Demo $demo The instance of the Demo class
     */
    public function __construct(Demo $demo)
    {
        $this->demo = $demo;
    }

    /**
     * Set Nav Locations
     *
     * @since  1.1.0
     * @access protected
     *
     * @param \stdClass $demoData The demo data.
     */
    protected function setNavLocations(\stdClass $demoData)
    {
        if (isset($demoData->navLocations)) {
            $locations = array();
            foreach ($demoData->navLocations as $obj) {
                $locations[$obj->location] = wp_get_nav_menu_object($obj->menu)->term_id;
            }

            $locations && set_theme_mod('nav_menu_locations', $locations);
        }
    }

    /**
     * Set Posts Data and options
     *
     * @since  1.1.0
     * @access protected
     *
     * @param \stdClass $demoData The demo data.
     */
    protected function setPosts(\stdClass $demoData)
    {
        $wooPages = array('shop', 'my-account', 'cart', 'checkout');

        if (isset($demoData->posts)) :
            // Update to set the front and blog page.
            update_option('show_on_front', 'page');

            // Through the posts to set the data.
            foreach ($demoData->posts as $data) :
                // Get the post.
                $post = new \WP_Query(array(
                    'post_type'      => $data->postType,
                    'post_name__in'  => array($data->name),
                    'posts_per_page' => 1,
                ));

                // And set the option if the option and post exists.
                if (! empty($post->posts) && isset($data->option)) :
                    $post = $post->posts[0];
                    if (in_array($data->name, $wooPages, true)) {
                        $exists = new \WP_Query(array(
                            'post_type'      => $data->postType,
                            'post_name__in'  => array($data->name),
                            'posts_per_page' => 1,
                        ));

                        if (! empty($exists->posts)) {
                            $exists = $exists->posts[0];
                            update_post_meta($exists->ID, '_qibla_mb_jumbotron_disable', 'on');

                            if ('shop' === $exists->post_name) {
                                update_post_meta($exists->ID, '_qibla_mb_sidebar_position', 'right');
                            }
                            $id = $exists->ID;
                        } else {
                            $id = wp_update_post(array(
                                'post_type'  => $post->post_type,
                                'ID'         => $post->ID,
                                'post_name'  => $data->name,
                                'post_title' => ucwords(str_replace('-', ' ', $data->name)),
                            ));
                        }
                        update_option($data->option, $id);
                    } else {
                        update_option($data->option, $post->ID);
                    }
                endif;
                unset($exists, $slug, $id);
            endforeach;
        endif;
    }

    /**
     * Import
     *
     * @since  1.0.0
     * @access public
     *
     * @param Demo $demo The demo instance from which retrieve the data to import.
     *
     * @return void
     */
    public function import(Demo $demo)
    {
        // Initialize the Importers.
        foreach ($demo->importers as $importer) :
            $data = array();

            // Same order must be found in details.json::actions.
            switch ($importer) :
                case 'demo':
                    $importer = new ImportDemo(new \WXRImporter\WXRImporter(array(
                        'prefill_existing_posts'    => true,
                        'prefill_existing_comments' => true,
                        'prefill_existing_terms'    => true,
                        'update_attachment_guids'   => false,
                        'fetch_attachments'         => true,
                        'aggressive_url_search'     => false,
                        'default_author'            => get_current_user_id(),
                    )));

                    $data = array(
                        'file' => untrailingslashit($this->demo->getDemoPath($demo->slug)) . '/demo.xml',
                    );
                    break;

                case 'settings':
                    $importer = new ImportSettings();
                    $data     = include Plugin::getPluginDirPath('/inc/settingsOptions.php');
                    $demoData = $demo->getDemoData();

                    // Set Posts.
                    $this->setPosts($demoData);
                    // Set nav locations.
                    $this->setNavLocations($demoData);
                    break;

                case 'themeOptions':
                    $importer = new ImportThemeOptions(new DwImport());
                    $filePath = untrailingslashit($this->demo->getDemoPath($demo->slug)) . '/options.json';
                    $data     = array(
                        'tmp_name' => $filePath,
                        'name'     => 'options',
                        'type'     => 'application/json',
                        'error'    => 0,
                        'size'     => filesize($filePath),
                    );
                    break;

                case 'widgets':
                    $importer = new ImportWidgets();
                    $data     = (array)F\getJsonContent(
                        untrailingslashit($this->demo->getDemoPath($demo->slug)) . '/widgets.json'
                    );
                    break;
            endswitch;

            if (! $data) {
                continue;
            }

            // Set the Logger.
            $importer->setLogger(new \WXRImporter\WPImporterLoggerServerSentEvents());
            // Import data.
            $importer->import($data);
            set_transient('qb_importing_' . esc_attr($demo->__get('slug')), 'importing_completed', 30);
        endforeach;
    }
}
