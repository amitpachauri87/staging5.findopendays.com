<?php
/**
 * RequestForm
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

use QiblaFramework\Form\Validate;
use QiblaFramework\Request\AbstractRequestForm;
use QiblaFramework\Request\Nonce;
use QiblaListings\Functions as F;
use QiblaListings\Package\PackageFormBuilder;

/**
 * Class RequestForm
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Front\ListingForm
 */
class RequestForm extends AbstractRequestForm
{
    /**
     * Package Post
     *
     * Package Post related to the request
     *
     * @since  1.0.0
     * @access protected
     *
     * @var \WP_Post The package post instance
     */
    protected $packagePost;

    /**
     * Post
     *
     * The post listings to work with
     *
     * @since 1.0.0
     *
     * @var \WP_Post The post listings instance
     */
    protected $post;

    /**
     * RequestForm constructor
     *
     * @inheritdoc
     */
    public function __construct(\WP_Post $packagePost, $post, $action)
    {
        $this->packagePost = $packagePost;
        $this->post        = $post;
        $form              = PackageFormBuilder::getFormHelper($this->packagePost, $post, $action);

        parent::__construct($form, $action);
    }

    /**
     * @inheritdoc
     */
    public function isValidRequest()
    {
        switch ($this->actionName) :
            case 'edit':
                $valid = $this->isValidRequestForEditAction();
                break;
            default:
                $valid = parent::isValidRequest();
                break;
        endswitch;

        return $valid;
    }

    /**
     * Is Valid Request for Edit Action
     *
     * @since  1.0.0
     * @access public
     *
     * @return bool True if valid, false otherwise
     */
    public function isValidRequestForEditAction()
    {
        $nonce = new Nonce('_nonce', 'GET', true);

        return $nonce->verify() &&
               $this->actionName &&
               current_user_can('edit_listingss') &&
               intval($this->post->post_author) === intval(wp_get_current_user()->ID);
    }

    /**
     * @inheritdoc
     */
    public function handleRequest()
    {
        $director = new Director(
            $this->form,
            new Validate(),
            new Controller($this->form, $this->actionName),
            $this->packagePost,
            new Template()
        );
        $director->initializeTemplate();

        if (! $this->isValidRequest() || 'edit' === $this->actionName) {
            return;
        }

        // Non logged in users are evil.
        if (! is_user_logged_in()) {
            die;
        }

        $director->director();
    }

    /**
     * Handle Request Filter
     *
     * @since  1.0.0
     * @access public static
     *
     * @return void
     */
    public static function handleRequestFilter()
    {
        // @todo Add modal login/register?
        if (F\isAjaxRequest() || ! is_singular('listing_package')) {
            return;
        }

        // @codingStandardsIgnoreStart
        $packagePost  = get_post(F\filterInput($_GET, 'package_post', FILTER_SANITIZE_NUMBER_INT) ?: 0);
        $postIDToEdit = F\filterInput($_GET, 'postid', FILTER_SANITIZE_NUMBER_INT) ?: 0;
        // @codingStandardsIgnoreEnd
        // Don't get the post for values that are evaluated as null, false or 0,
        // or a wrong post will be returned.
        $post = $postIDToEdit ? get_post($postIDToEdit) : false;
        // Request to edit a post but no valid post has been provided.
        if ($postIDToEdit && ! $post) {
            // Die because we don't want to give information about the error.
            // Most probably someone want to try to view what is inside.
            die;
        }

        // @codingStandardsIgnoreStart
        // Default to Create.
        $action = F\filterInput($_GET, 'dlaction', FILTER_SANITIZE_STRING) ?: 'create';
        // @codingStandardsIgnoreEnd

        $instance = new static($packagePost, $post, $action);
        $instance->handleRequest();
    }
}
