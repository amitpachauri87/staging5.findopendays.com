<?php
/**
 * Package Form Builder
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

namespace QiblaListings\Package;

use QiblaFramework\Form\Factories\FieldFactory;
use QiblaFramework\Form\Forms\BaseForm;
use QiblaFramework\Form\Interfaces\Forms;
use QiblaFramework\Form\Types\Hidden;
use QiblaListings\Front\ListingForm\FormFactory;
use QiblaListings\Plugin;
use QiblaListings\Functions as QlF;

/**
 * Class PackageFormBuilder
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Front\ListingForm
 */
class PackageFormBuilder
{
    /**
     * The Form Instance
     *
     * @since  1.0.0
     *
     * @var FormFactory The Listing Form Instance
     */
    protected $formFactory;

    /**
     * Post
     *
     * Post associated to the form. From which retrieve the data to pass to the factory.
     *
     * @since  1.0.0
     *
     * @var \WP_Post The instance of the post
     */
    protected $post;

    /**
     * Action
     *
     * @since  1.0.0
     *
     * @var string The action that the form must be perform
     */
    protected $action;

    /**
     * Form Prefix Key
     *
     * @since  1.0.0
     *
     * @var string The form prefix name key
     */
    const FORM_PREFIX_KEY = 'qibla_listing_form';

    /**
     * Form Package Key
     *
     * @since  1.0.0
     *
     * @var string The form package key
     */
    const FORM_PACKAGE_KEY = 'qibla_listing_form_package';

    /**
     * Form Package Type
     *
     * @since  2.3.0
     *
     * @var string The form package post type
     */
    const FORM_PACKAGE_POST_TYPE = 'qibla_listing_form_post_type';

    /**
     * Form Package Action Key
     *
     * @since  1.0.0
     *
     * @var string The form package action key
     */
    const FORM_PACKAGE_ACTION_KEY = 'qibla_listing_form_action';

    /**
     * Get the fields
     *
     * @since  1.0.0
     *
     * @return array
     */
    protected function getFieldsDefList()
    {
        // @todo Remove, use the $this->post.
        $post = $this->post;

        return array_merge(
            include Plugin::getPluginDirPath('/inc/listingFormFields/baseFields.php'),
            include Plugin::getPluginDirPath('/inc/listingFormFields/galleryFields.php'),
            include Plugin::getPluginDirPath('/inc/listingFormFields/contentFields.php'),
            include Plugin::getPluginDirPath('/inc/listingFormFields/buttonFields.php'),
            include Plugin::getPluginDirPath('/inc/listingFormFields/locationFields.php'),
            include Plugin::getPluginDirPath('/inc/listingFormFields/taxTermsFields.php'),
            include Plugin::getPluginDirPath('/inc/listingFormFields/additionalInfoFields.php'),
            include Plugin::getPluginDirPath('/inc/listingFormFields/socialsFields.php')
        );
    }

    /**
     * Get the Submit field
     *
     * @since  1.0.0
     *
     * @return array The submit form input wrapped into an array for interface compatibility.
     */
    protected function getSubmitField()
    {
        $fieldFactory = new FieldFactory();

        // Set the properly submit label.
        switch ($this->action) {
            case 'edit':
                $label = esc_html__('Submit Changes', 'qibla-listings');
                break;
            default:
                $label = esc_html__('Create my Listing', 'qibla-listings');
                break;
        }

        return array(
            'qibla_listing_form-submit:submit' => $fieldFactory->base(array(
                'type'  => 'submit',
                'name'  => self::FORM_PREFIX_KEY . '-submit',
                'value' => apply_filters(
                    'qibla_listings_create_listing_submit_label',
                    $label
                ),
            )),
        );
    }

    /**
     * PackageFormBuilder constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Post $package The package post.
     * @param string   $action  Additional argument to know for which the form is used.
     * @param \WP_Post $post    The post name from which retrieve the listing data.
     */
    public function __construct(\WP_Post $package, $post, $action)
    {
        $this->package     = $package;
        $this->action      = $action;
        $this->post        = $post;
        $this->formFactory = null;
    }

    /**
     * Build Form
     *
     * @since  1.0.0
     *
     * @return $this For chaining
     */
    public function buildForm()
    {
        $this->formFactory = new FormFactory(
            new BaseForm(array(
                'method' => 'post',
                'name'   => self::FORM_PREFIX_KEY,
                'attrs'  => array(
                    'novalidate' => 'novalidate',
                    'enctype'    => 'multipart/form-data',
                    'class'      => 'dlform dlform--listing',
                    'id'         => 'qibla_listing_form',
                ),
            )),
            $this->getFieldsDefList() + $this->getSubmitField(),
            new PackageRestrictionsList($this->package)
        );

        return $this;
    }

    /**
     * Set the post referer
     *
     * @since  1.0.0
     *
     * @return $this For chaining
     */
    public function setPostReferer()
    {
        // Set the related post value.
        $this->formFactory->getForm()->addHidden(new Hidden(array(
            'name'  => static::FORM_PACKAGE_KEY,
            'attrs' => array(
                'value' => $this->package->post_name,
            ),
        )));

        return $this;
    }


    /**
     * Set the current lang
     *
     * @since  2.1.0
     *
     * @return $this For chaining
     */
    public function setCurrentLang()
    {
        // Set the related post value.
        $this->formFactory->getForm()->addHidden(new Hidden(array(
            'name'  => 'qibla_listing_form_lang',
            'attrs' => array(
                'value' => QlF\isWpMlActive() && defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : '',
            ),
        )));

        return $this;
    }

    /**
     * Set package post type
     *
     * @since  2.3.0
     *
     * @return $this For chaining
     */
    public function setPackagePostType()
    {
        // Set the related post value.
        $this->formFactory->getForm()->addHidden(new Hidden(array(
            'name'  => 'qibla_listing_form_post_type',
            'attrs' => array(
                'value' => esc_attr(get_post_type()),
            ),
        )));

        return $this;
    }

    /**
     * Set Form action
     *
     * This set the action that will be performed after submitted
     *
     * @since  1.0.0
     *
     * @return $this For chaining
     */
    public function setAction()
    {
        // Set the action for the form.
        $this->formFactory->getForm()->addHidden(new Hidden(array(
            'name'  => 'qibla_listing_form_action',
            'attrs' => array(
                'value' => $this->action,
            ),
        )));

        return $this;
    }

    /**
     * Set the post ID
     *
     * @since  1.0.0
     *
     * @return $this The instance of the class for chaining
     */
    public function setPostID()
    {
        if ('edit' === $this->action && $this->post instanceof \WP_Post) {
            // Set the action for the form.
            $this->formFactory->getForm()->addHidden(new Hidden(array(
                'name'  => 'qibla_listing_form_postid',
                'attrs' => array(
                    'value' => $this->post->ID,
                ),
            )));
        }

        return $this;
    }

    /**
     * Get Form
     *
     * This is a proxy for form->getForm()
     *
     * @since  1.0.0
     *
     * @return \QiblaFramework\Form\Interfaces\Forms The form instance
     */
    public function getForm()
    {
        // Remove the wplink, may cause issues with the custom autocomplete.
        // @todo To move in framework to allow plugins filtering.
        add_filter('teeny_mce_plugins', function ($array) {
            $index = array_search('wplink', $array, true);
            if (false !== $index) {
                unset($array[$index]);
            }

            return $array;
        });

        return $this->formFactory->getForm();
    }

    /**
     * Show Form based on Post Slug
     *
     * Used to show the form based on the requested page
     *
     * @since  1.0.0
     *
     * @throws \InvalidArgumentException If the $postSlug is not a valid string or the post type doesn't exists.
     *
     * @param \WP_Post $package The slug of the post from which retrieve the settings for the form.
     * @param string   $action  Additional argument to know for which the form is used.
     *
     * @return Forms The Form
     */
    public static function getFormHelper(\WP_Post $package, $post, $action)
    {
        // Get the instance.
        $instance = new static($package, $post, $action);

        // Return the form.
        return $instance
            ->buildForm()
            ->setPostReferer()
            ->setCurrentLang()
            ->setPackagePostType()
            ->setPostID()
            ->setAction()
            ->getForm();
    }
}
