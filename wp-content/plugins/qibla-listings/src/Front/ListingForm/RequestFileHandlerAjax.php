<?php
/**
 * Ajax File Uploader
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2017, Guido Scialfa
 * @license   GNU General Public License, version 2
 *
 * Copyright (C) 2017 Guido Scialfa <dev@guidoscialfa.com>
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

namespace QiblaListings\Front\ListingForm;

use QiblaFramework\Form\Ajax\AjaxStoreMediaFile;
use QiblaFramework\Form\Handlers\WpMedia;
use QiblaFramework\Request\Response;
use QiblaFramework\Request\ResponseAjax;
use QiblaFramework\Functions as Fw;
use QiblaFramework\Form\Validate;
use QiblaListings\Debug\Exception;
use QiblaListings\Functions as F;
use QiblaListings\Package\PackageFormBuilder;

/**
 * Class RequestFileHandlerAjax
 *
 * @todo   Split in: Director, Request, Controller.
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class RequestFileHandlerAjax extends AjaxStoreMediaFile
{
    /**
     * Get Data File
     *
     * Get the Data file from Input
     *
     * @since  1.0.0
     * @access protected
     *
     * @return array An array created from a decoded json string
     */
    protected function getDataFile()
    {
        // @codingStandardsIgnoreStart
        $data = Fw\filterInput($_POST, 'data_file', FILTER_SANITIZE_STRING);
        // @codingStandardsIgnoreEnd
        // Clean the data to allow to decode as json.
        $data = wp_unslash(html_entity_decode($data));

        if (Fw\isJson($data)) {
            $data = Fw\isJson($data) ? json_decode($data, true) : array();
        }

        return $data;
    }

    /**
     * Get the fields
     *
     * @since  1.0.0
     * @access protected
     *
     * @return array
     */
    protected function getFields()
    {
        // Create the form to get the filters.
        // @codingStandardsIgnoreStart
        $package = Fw\getPostByName(F\filterInput($_POST, PackageFormBuilder::FORM_PACKAGE_KEY), 'listing_package');
        $action  = Fw\filterInput($_POST, PackageFormBuilder::FORM_PACKAGE_ACTION_KEY);
        // @codingStandardsIgnoreEnd
        $form = PackageFormBuilder::getFormHelper($package, null, $action);

        if(! $package instanceof \WP_Post) {
            return null;
        }

        // Get only the files fields.
        $fields = $this->filterFileFields($form);

        return $fields;
    }

    /**
     * Validate Form
     *
     * @since  1.0.0
     * @access protected
     *
     * @return array The response of the validation
     */
    protected function validateForm(array $fields)
    {
        $validator = new Validate();

        // Then retrieve only the field that is passed with the request.
        // When the dropzone is used multiple input files are send one by one via xhr, means that
        // $_FILES will have only one element per time.
        $key    = key($_FILES);
        $fields = array($key => $fields[$key]);
        // Validate.
        $response = $validator->validate($fields);

        return $response;
    }

    /**
     * Convert Input Media List into Array
     *
     * @since  1.0.0
     * @access protected static
     *
     * @param string $key The key of the input value
     *
     * @return array The list of the Media Id's or empty array.
     */
    protected static function convertInputMediaListIntoArray($key)
    {
        // @codingStandardsIgnoreStart
        $keepMediaList = Fw\filterInput($_POST, $key, FILTER_SANITIZE_STRING);
        // @codingStandardsIgnoreEnd
        $keepMediaList = $keepMediaList ? explode(',', $keepMediaList) : array();

        return $keepMediaList;
    }

    /**
     * Delete Attachments
     *
     * @since  1.0.0
     * @access protected static
     *
     * @param array $oldList The old list of the id's.
     * @param array $newList the new list of the id's.
     *
     * @return bool True on success, false otherwise or partial delete action.
     */
    protected static function deleteAttachments(array $oldList, array $newList)
    {
        $toRemove = array_diff($oldList, $newList);
        $response = array();
        foreach ($toRemove as $mediaID) {
            // If can be deleted ok, if not sorry but leave it where is it.
            $response[$mediaID] = false !== wp_delete_attachment(intval($mediaID), true);
        }

        // If the number for response and toRemove are the same, all of the files
        // have been remove correctly.
        return count($response) === count($toRemove);
    }

    /**
     * Get Media List From Post Meta
     *
     * @since  1.0.0
     * @access protected static
     *
     * @param string $key    The post meta key.
     * @param int    $postID The ID of the post from which retrieve the meta.
     *
     * @return array The list of the id's from the db
     */
    protected static function getMediaListFromMeta($key, $postID)
    {
        $list = Fw\getPostMeta($key, '', $postID);

        return array_map(function ($item) {
            return intval($item);
        }, explode(',', $list));
    }

    /**
     * Check if we want to perform Sub task
     *
     * @since  1.0.0
     * @access protected
     *
     * @param string $key The key to use to check for $_POST value.
     *
     * @return bool True if the subtask is the same of the $key. False otherwise.
     */
    protected function isSubTask($key)
    {
        $subTask = Fw\filterInput($_POST, 'dlajax_subaction', FILTER_SANITIZE_STRING);

        return $key === $subTask;
    }

    /**
     * Mock the Response
     *
     * Mock the response to use it with the subtask.
     *
     * @since  1.0.0
     * @access protected
     *
     * @param array $fields The list of the fields from which retrieve the name attribute.
     *
     * @return array The mocked response
     */
    protected function mockResponse($fields)
    {
        $response = array(
            'invalid' => array(),
            'valid'   => array(),
        );

        foreach ($fields as $field) {
            $response['valid'] = array_merge($response['valid'], array(
                $field->getType()->getArg('name') => true,
            ));
        }

        return $response;
    }

    /**
     * Handle the upload
     *
     * @since  1.0.0
     * @access public
     *
     * @return int
     */
    public function handleRequest()
    {
        if (! $this->isValidRequest()) {
            return 0;
        }

        // Initialize the request.
        Fw\setHeaders();
        $this->initializeEnv();

        $fields = $this->getFields();

        if(! $fields && ! is_array($fields)) {
            return null;
        }

        if (! $this->isSubTask('sync_media_ids')) {
            // Validate the form and retrieve the response.
            $response = $this->validateForm($fields);
        } else {
            $response = $this->mockResponse($fields);
        }

        if (empty($response['invalid']) && ! empty($response['valid'])) :
            // Retrieve the data for perform tasks with files.
            $filePostData = $this->getDataFile();

            if ($filePostData) :
                // Process the tasks.
                foreach ($response['valid'] as $key => $dataFile) :
                    // Retrieve and set the data needed to upload the files.
                    $postID = isset($filePostData['post']['ID']) ? $filePostData['post']['ID'] : 0;
                    // Upload the media files.
                    $wpMediaHandler = new WpMedia();

                    try {
                        switch ($key) :
                            // Thumbnail Upload.
                            case (PackageFormBuilder::FORM_PREFIX_KEY . '-thumbnail'):
                                // Media Id's currently into the db.
                                $oldMediaList = static::getMediaListFromMeta(
                                    '_qibla_mb_jumbotron_background_image',
                                    $postID
                                );
                                // The media id's we want to keep into the db.
                                $keepMediaList = static::convertInputMediaListIntoArray(
                                    'qibla_listing_form-thumbnail_exists_ids'
                                );

                                if (! $this->isSubTask('sync_media_ids')) {
                                    // Update the meta with the new ID's.
                                    // Always handle one element.
                                    $id       = $wpMediaHandler->mediaHandleSideload($dataFile[0], get_post($postID));
                                    $response = update_post_meta($postID, '_qibla_mb_jumbotron_background_image', $id);

                                    if ($response) {
                                        $response = new ResponseAjax(
                                            201,
                                            esc_html__('Files stored successfully.', 'qibla-listings')
                                        );
                                    }
                                }
                                break;

                            // Gallery Upload.
                            case (PackageFormBuilder::FORM_PREFIX_KEY . '-gallery'):
                                // Media Id's currently into the db.
                                $oldMediaList = static::getMediaListFromMeta('_qibla_mb_images', $postID);
                                // The media id's we want to keep into the db.
                                $keepMediaList = static::convertInputMediaListIntoArray(
                                    'qibla_listing_form-gallery_exists_ids'
                                );

                                if (! $this->isSubTask('sync_media_ids')) :
                                    $ids = array();
                                    // Upload the files.
                                    foreach ((array)$dataFile as $data) {
                                        // @todo What to do if an Exception is generated?
                                        // Should all files previously uploaded removed?
                                        $ids[] = $wpMediaHandler->mediaHandleSideload($data, get_post($postID));
                                    }

                                    // After the files has been uploaded and we got the newly ID's,
                                    // lets add the old media Id's currently stored within the database,
                                    // but remove the ones that were removed by the client.
                                    $ids      = array_merge(array_intersect($oldMediaList, $keepMediaList), $ids);
                                    $response = update_post_meta($postID, '_qibla_mb_images', implode($ids, ','));

                                    if ($response) {
                                        $response = new ResponseAjax(
                                            201,
                                            esc_html__('Files stored successfully.', 'qibla-listings')
                                        );
                                    }
                                endif;
                                break;
                        endswitch;

                        // Then try to remove the old files.
                        // Remove the old ones only if the response is valid,
                        // if the new files cannot be uploaded we don't remove the old ones,
                        // don't leave the user without images.
                        if ((($response instanceof Response && $response->isValidStatus()) ||
                             $this->isSubTask('sync_media_ids')
                            ) &&
                            'edit' === $filePostData['post']['action'] &&
                            isset($oldMediaList) &&
                            isset($keepMediaList)
                        ) {
                            static::deleteAttachments($oldMediaList, $keepMediaList);

                            if ($this->isSubTask('sync_media_ids')) {
                                $response = new ResponseAjax(
                                    200,
                                    esc_html_x('Files Sync success.', 'listing-submit', 'qibla-listings')
                                );
                            }
                        }
                    } catch (\Exception $e) {
                        $debugInstance = new Exception($e);
                        'dev' === QB_ENV && $debugInstance->display();

                        $response = new ResponseAjax(
                            418,
                            esc_html__('Some file return an error during upload. Aborting', 'qibla-listings')
                        );
                    }//end try
                endforeach;
            endif;
        else :
            $response = new ResponseAjax(400, esc_html__(
                'Sorry! Some fields seems not be valid. Please check them.',
                'qibla-listings'
            ));
        endif;

        // Give feedback.
        $response->sendAjaxResponse();
    }

    /**
     * Handle Filter
     *
     * @since  1.0.0
     * @access public static
     *
     * @return void
     */
    public static function handleFilter()
    {
        $instance = new static;
        $instance->handleRequest();
    }
}
