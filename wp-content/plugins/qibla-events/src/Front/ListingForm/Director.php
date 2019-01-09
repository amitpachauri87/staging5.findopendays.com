<?php
/**
 * Director
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

namespace AppMapEvents\Front\ListingForm;

use QiblaFramework\Form\Interfaces\Forms;
use QiblaFramework\Form\Interfaces\Validators;
use QiblaFramework\Request\AbstractDirectorRequestForm;
use QiblaFramework\Request\RequestFormControllerInterface;
use QiblaFramework\Request\Response;
use QiblaFramework\Functions as Fw;
use QiblaFramework\User\User;

/**
 * Class Director
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class Director extends AbstractDirectorRequestForm
{
    /**
     * template
     *
     * @since  1.0.0
     *
     * @var Template The template instance
     */
    protected $template;

    /**
     * Package post
     *
     * @since  1.0.0
     *
     * @var \WP_Post The post that contain the info about the package
     */
    protected $packagePost;

    /**
     * Director constructor
     *
     * @since 1.0.0
     *
     * @param Forms                          $form
     * @param Validators                     $validator
     * @param RequestFormControllerInterface $controller
     * @param \WP_Post                       $packagePost
     * @param Template                       $template
     */
    public function __construct(
        Forms &$form,
        Validators $validator,
        RequestFormControllerInterface $controller,
        \WP_Post $packagePost,
        Template $template
    ) {
        parent::__construct($form, $validator, $controller);
        $this->packagePost = $packagePost;
        $this->template    = $template;
    }

    /**
     * Add Form to Package Template
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function addFormToPackageTemplateContent()
    {
        $self = $this;
        add_action('qibla_events_package_form', function () use ($self) {
            // @codingStandardsIgnoreLine
            echo Fw\ksesPost($self->getForm());
        });
    }

    /**
     * Director
     *
     * @since  1.0.0
     *
     * @throws \LogicException In case the package post has not been set.
     *
     * @return \QiblaFramework\Request\Response A instance of the Response class
     */
    public function director()
    {
        // Check for a valid request.
        $validationResponse = $this->validate(array('allow_empty' => true));

        // Check for invalid fields before dispatch to the controller.
        if (! empty($validationResponse['invalid'])) :
            $response = new Response(
                400,
                esc_html__('Sorry! Some fields seems not be valid. Please check them.', 'qibla-events'),
                array(
                    'validation_data' => $validationResponse,
                )
            );
        else :
            $this->injectDataIntoController($validationResponse['valid']);
            unset($validationResponse);

            $currUser = wp_get_current_user();
            // Before dispatch to the controller, let's upgrade the user
            // that need to be allowed to create listings.
            if (! User::isListingsManager() && ! current_user_can('administrator')) {
                $currUser->add_role('listings_author');
            }

            $response = $this->dispatchToController();
        endif;

        return $response;
    }

    /**
     * Initialize the Form Template
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function initializeTemplate()
    {
        $this->template->setTemplate();
        $this->addFormToPackageTemplateContent();
    }
}
