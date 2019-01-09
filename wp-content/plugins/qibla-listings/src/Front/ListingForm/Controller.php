<?php
/**
 * Controller
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaListings\Front\ListingForm
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    GNU General Public License, version 2
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

use QiblaListings\Functions as F;
use QiblaFramework\Request\AbstractRequestFormController;
use QiblaListings\Crud\ListingCreate;
use QiblaFramework\Request\Response;
use QiblaFramework\Form\Interfaces\Forms;
use QiblaFramework\Utils\FormToPostDataConverter;
use QiblaListings\Crud\ListingUpdate;
use QiblaListings\Package\PackageFormBuilder;

/**
 * Class Controller
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Front\ListingForm
 */
class Controller extends AbstractRequestFormController
{
    /**
     * Action
     *
     * @since  1.0.0
     *
     * @var string The action to perform based on form
     */
    protected $action;

    /**
     * The Form
     *
     * @since  1.0.0
     *
     * @var Forms The form instance to work with
     */
    protected $form;

    /**
     * Controller constructor
     *
     * @since 1.0.0
     *
     * @param Forms  $form   The form instance to work with.
     * @param string $action The action to perform based on form.
     */
    public function __construct(Forms &$form, $action)
    {
        $this->form   = $form;
        $this->action = $action;
    }

    /**
     * Handle Action
     *
     * Handle the action retrieved by the form.
     * This include, create, read, delete, update a listing post.
     *
     * @since  1.0.0
     *
     * @return Response The response instance
     */
    public function handle()
    {
        $response = null;

        switch ($this->action) :
            case 'create':
            case 'edit':
                try {
                    // If the validator return a valid response.
                    if (! empty($this->data)) :
                        $dataConverter = new FormToPostDataConverter($this->form);
                        // Get converted data.
                        $data = $dataConverter->convert($this->data);

                        switch ($this->action) :
                            case 'create':
                                // Force the post status for the newly created post.
                                $data['post_status'] = ListingCreate::DEFAULT_POST_STATUS;
                                // Get the instance.
                                $createInstance = new ListingCreate(wp_get_current_user(), $data['post_title'], $data);

                                // Create the post if not exists.
                                if (! $createInstance->postExists()) {
                                    // Generally throw an Exception or return the newly post ID.
                                    $response = $createInstance->create();
                                    // Since the response is valid, set the returned value into the response instance.
                                    $response = new Response(
                                        201,
                                        esc_html__('Post has been created successfully.', 'qibla-listings'),
                                        array(
                                            'action' => 'create',
                                            'postID' => $response,
                                        )
                                    );
                                } else {
                                    $response = new Response(409, sprintf(
                                    /* Translators: %s is the name of the post */
                                        esc_html__('Post named %s exists.', 'qibla-listings'),
                                        $data['post_title']
                                    ));
                                }
                                break;

                            case 'edit':
                                // Set the post ID to update.
                                // @codingStandardsIgnoreStart
                                $postID = F\filterInput(
                                    $_POST,
                                    PackageFormBuilder::FORM_PREFIX_KEY . '_postid',
                                    FILTER_SANITIZE_NUMBER_INT
                                );
                                // @codingStandardsIgnoreEnd

                                // @todo Check if the post exists or not.
                                if (! $postID) {
                                    // Cheatin' Uh?
                                    die;
                                }

                                // Get the post.
                                $post = get_post($postID);

                                // Update the post and refresh the post instance, so we can work with fresh data.
                                $updatePost = new ListingUpdate(wp_get_current_user(), $post, $data);
                                $post       = get_post($updatePost->update());

                                $response = new Response(
                                    201,
                                    sprintf(
                                    /* Translators: The %s is the post link. */
                                        esc_html__('Post has been updated successfully. View %s ', 'qibla-listings'),
                                        '<a href="' . get_permalink($post->ID) . '">' .
                                        esc_html(sanitize_text_field($post->post_title)) .
                                        '</a>'
                                    ),
                                    array(
                                        'action' => 'edit',
                                        'postID' => $post->ID,
                                    )
                                );
                                break;
                        endswitch;
                    endif;
                } catch (\Exception $e) {
                    $response = new Response(500, sprintf(
                    /* Translators: %s is the dynamic message based on Exception thrown */
                        esc_html__('Error during create or update post. %s', 'qibla-listings'),
                        $e->getMessage()
                    ));
                }//end try
                break;
            case 'delete':
                break;
            default:
                break;
        endswitch;

        return $response;
    }
}
