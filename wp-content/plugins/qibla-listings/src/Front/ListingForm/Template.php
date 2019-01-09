<?php
/**
 * PageTemplate
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

use QiblaFramework\Functions as Fw;
use QiblaFramework\Modal\ModalTemplate;
use QiblaListings\TemplateEngine\Engine;

/**
 * Class PageTemplate
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Front\ListingForm
 */
class Template
{
    /**
     * Template Path
     *
     * @since  1.0.0
     *
     * @var string The template form path
     */
    protected static $templatePath = '/views/listings/listingFormPackage.php';

    /**
     * Template Loader Wrapper
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function pageTmpl()
    {
        $engine = new Engine('page_form_template', new \stdClass(), static::$templatePath);
        $engine->render();
    }

    /**
     * Template Filter
     *
     * Set the content filter to allow to show the form within the page.
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function setTemplate()
    {
        global $post;

        // Allowed only within the single listing package post.
        if (is_singular('listing_package')) :
            // Set the content for the page.
            // This is a space to able to pass the condition of the theme.
            $post->post_content = ' ';

            // Add the title filter.
            add_filter('the_title', 'QiblaListings\\Front\\ListingForm\\Template::titleFilter', 0);
            // Add the content filter.
            add_filter('the_content', 'QiblaListings\\Front\\ListingForm\\Template::templateContentFilter', 0);

            // @todo Move into a separated class.
            if (! is_user_logged_in()) {
                add_filter('wp_footer', function () {
                    $modal = new ModalTemplate(
                        'QiblaFramework\\LoginRegister\\LoginRegisterFormTemplate::loginRegisterFormHelper',
                        array(
                            'class_container'   => Fw\getScopeClass('modal', '', 'login-register'),
                            'context'           => 'html',
                            'show_close_button' => false,
                        )
                    );

                    $modal->tmpl($modal->getData());
                });
            }
        endif;
    }

    /**
     * Filter Single post title
     *
     * @since  1.0.0
     *
     * @param string $title The title of the single listing package
     *
     * @return string The filtered title
     */
    public static function titleFilter($title)
    {
        if (in_the_loop() && is_main_query()) {
            $title = esc_html__('Add your Listing', 'qibla-listings') . ' - ' . $title;
            // Remove after done.
            remove_filter('the_title', 'QiblaListings\\Front\\ListingForm\\Template::titleFilter', 0);
        }

        return $title;
    }

    /**
     * Template Content Filter
     *
     * @since  1.0.0
     *
     * @return void
     */
    public static function templateContentFilter()
    {
        $instance = new static();

        if (in_the_loop() && is_main_query()) :
            // If the filter wpautop must be set again.
            $rf = false;

            // Only remove the filter if has been set.
            // Removing the filter prevent issues with the input form tags.
            if (has_filter('the_content', 'wpautop')) {
                remove_filter('the_content', 'wpautop', 10);
                $rf = true;
            }

            // Show the content.
            $instance->pageTmpl();

            // Re-introduce the filter after done.
            $rf && add_filter('the_content', 'wpautop', 10);

            // Remove the filter after done.
            remove_filter('the_content', 'QiblaListings\\Front\\ListingForm\\Template::templateContentFilter', 0);

            // Enqueue the submit handler.
            if (wp_script_is('listing-submit-handler', 'registered')) {
                wp_enqueue_script('listing-submit-handler');
            }
        endif;
    }
}
