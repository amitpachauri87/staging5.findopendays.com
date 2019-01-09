<?php
namespace QiblaImporter\Importer;

use QiblaImporter\Functions as F;
use QiblaImporter\Demo;
use WXRImporter\WPImporterLoggerServerSentEvents;

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

class AjaxController extends Controller
{
    /**
     * Emit a Server-Sent Events message.
     *
     * @see    WXR_Import_UI::emit_see_message()
     *
     * @since  1.0.0
     * @access protected
     *
     * @param mixed $data Data to be JSON-encoded and sent in the message.
     *
     * @return void
     */
    protected function emitSseMessage($data)
    {
        echo "event: message\n";
        echo 'data: ' . wp_json_encode($data) . "\n\n";

        // Extra padding.
        echo ':' . str_repeat(' ', 2048) . "\n\n";

        flush();
    }

    /**
     * Send message when a post has been imported.
     *
     * @since  1.0.0
     * @access public
     *
     * @uses   AjaxController::emitSseMessage()
     *
     * @param int   $id   Post ID.
     * @param array $data Post data saved to the DB.
     *
     * @return void
     */
    public function importedPost($id, $data)
    {
        $this->emitSseMessage(array(
            'action' => 'updateDelta',
            'type'   => ($data['post_type'] === 'attachment') ? 'media' : 'posts',
            'delta'  => 1,
        ));
    }

    /**
     * Send message when a comment has been imported.
     *
     * @since  1.0.0
     * @access public
     *
     * @uses   AjaxController::emitSseMessage()
     *
     * @return void
     */
    public function importedComment()
    {
        $this->emitSseMessage(array(
            'action' => 'updateDelta',
            'type'   => 'comments',
            'delta'  => 1,
        ));
    }

    /**
     * Send message when a term has been imported.
     *
     * @since  1.0.0
     * @access public
     *
     * @uses   AjaxController::emitSseMessage()
     *
     * @return void
     */
    public function importedTerm()
    {
        $this->emitSseMessage(array(
            'action' => 'updateDelta',
            'type'   => 'terms',
            'delta'  => 1,
        ));
    }

    /**
     * Import
     *
     * @todo   Need security Check (nonce and user role).
     *
     * @since  1.0.0
     * @access public
     *
     * @return void
     */
    public function import()
    {
        header("Content-Type: text/event-stream");
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no');
        header('Content-Encoding: none');

        // 2KB padding for IE
        echo ':' . str_repeat(' ', 2048) . "\n\n";

        // Ensure we're not buffered.
        wp_ob_end_flush_all();
        flush();

        // Let the browser know we're done.
        $complete = array(
            'action'  => 'complete',
            'error'   => false,
            'success' => esc_html__('Demo Content imported.', 'qibla-importer'),
        );

        // Retrieve the demo slug.
        $demoSlug = $this->getDemoSlug();

        if ('importing_completed' !== get_transient('qb_importing_' . esc_attr($demoSlug))) {
            set_transient('qb_importing_' . esc_attr($demoSlug), 'importing_start', 30);

            if ($demoSlug) :
                // Keep track of our progress.
//            add_action('wxr_importer.processed.post', array($this, 'importedPost'), 10, 2);
//            add_action('wxr_importer.process_failed.post', array($this, 'importedPost'), 10, 2);
//            add_action('wxr_importer.processed.comment', array($this, 'importedComment'));
//            add_action('wxr_importer.processed.term', array($this, 'importedTerm'));
//            add_action('wxr_importer.process_failed.term', array($this, 'importedTerm'));

                try {
                    $this->init();

                    $demo     = new Demo($demoSlug);
                    $importer = new Importer($demo);

                    // Import the demo.
                    $importer->import(new Demo($demoSlug));
                } catch (\Exception $e) {
                    $complete['error'] = $e->getMessage();
                }//end try
            else :
                $complete['error'] = esc_html__('Wrong demo slug. Aborting.', 'qibla-importer');
            endif;
        } else {
            $complete['success'] = esc_html__('You allready have imported the demo content.');
        }

        $this->emitSseMessage($complete);
        // Die.
        die;
    }
}
