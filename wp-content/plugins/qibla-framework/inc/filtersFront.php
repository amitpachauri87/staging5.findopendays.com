<?php
/**
 * FrontEnd Filters
 *
 * @todo      Improve the number of object created. May be put all filters within a function and create there the
 *            object?
 *
 * @since     1.0.0
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2016, Guido Scialfa
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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

use Unprefix\Scripts\ScriptsFacade;
use Unprefix\Scripts\LocalizeScripts;
use QiblaFramework\Plugin;
use QiblaFramework\Front;
use QiblaFramework\Parallax;
use QiblaFramework\LoginRegister\Register\RegisterFormFacade;
use QiblaFramework\LoginRegister\Login\LoginFormFacade;
use QiblaFramework\LoginRegister\LostPassword\LostPasswordFormFacade;
use QiblaFramework\VisualComposer;
use QiblaFramework\Review\AverageRating;
use QiblaFramework\ListingsContext\Context;
use QiblaFramework\ListingsContext\Types;

// Scripts and Styles.
$scripts           = new ScriptsFacade(include Plugin::getPluginDirPath('/inc/scripts.php'));
$deregisterScripts = new ScriptsFacade(include Plugin::getPluginDirPath('/inc/deScriptsList.php'));
// Custom Fields Classes.
$singularHeaderMeta = new Front\CustomFields\Header();
$jumbotronMeta      = new Front\CustomFields\Jumbotron(new Parallax\Settings());
// Settings Classes.
$pageSettings = new Front\Settings\Page();
$blogSettings = new Front\Settings\Blog();
$page404      = new Front\Settings\Page404();

/**
 * Filter Front Filters
 *
 * @since 1.0.0
 *
 * @param array $array The list of the filters list
 */
return apply_filters('qibla_filters_front', array(
    'front' => array(
        'action' => array(
            /**
             * Template Redirect
             *
             *  Set Last Viewed Cookie @since 2.1.0
             */
            array(
                'filter'   => 'template_redirect',
                'callback' => 'QiblaFramework\\Functions\\setViewedCookie',
                'priority' => 20,
            ),
            /**
             * Requests
             *
             * - Search                     @since 1.7.0
             * -  Filter Request By Geocode @since 1.7.0
             */
            array(
                'filter'   => 'init',
                'callback' => 'QiblaFramework\\Search\\Request\\RequestSearch::handleRequestFilter',
                'priority' => 10,
            ),
            array(
                'filter'   => 'init',
                'callback' => 'QiblaFramework\\Geo\\Request\\RequestByGeocodedAddress::handleRequestFilter',
                'priority' => 20,
            ),

            // @todo Create a dispatcher for Register/Login/LostPassword and remove the getInstance method.
            array(
                'filter'   => 'wp_loaded',
                'callback' => function () {
                    $rff = RegisterFormFacade::getInstance();
                    $rff->handle();
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'wp_loaded',
                'callback' => function () {
                    $rff = LoginFormFacade::getInstance();
                    $rff->handle();
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'wp_loaded',
                'callback' => function () {
                    $rff = LostPasswordFormFacade::getInstance();
                    $rff->handle();
                },
                'priority' => 20,
            ),

            /**
             * Store
             *
             * - Review @since 1.2.0
             */
            array(
                'filter'        => 'comment_post',
                'callback'      => array(
                    'QiblaFramework\\Review\\ReviewFieldsStore',
                    'reviewFieldsStoreFilter',
                ),
                'priority'      => 20,
                'accepted_args' => 3,
            ),

            /**
             * Enqueue Scripts
             *
             * - Deregister Scripts / Style @since 1.0.0
             * - Register Scripts / Style   @since 1.0.0
             * - Lazy Localized             @since 2.0.0
             * - Enqueue Scripts / Style    @since 1.0.0
             */
            array(
                'filter'   => 'wp_enqueue_scripts',
                'callback' => array($deregisterScripts, 'deregister'),
                'priority' => 20,
            ),
            array(
                'filter'   => 'wp_enqueue_scripts',
                'callback' => array($scripts, 'register'),
                // Leave it to 20 or horrible things will happens.
                'priority' => 20,
            ),
            array(
                'filter'   => 'init',
                'callback' => function () {
                    LocalizeScripts::lazyLocalize('/inc/lazyLocalizedScriptsList.php', 'wp_enqueue_scripts');
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'init',
                'callback' => function () {
                    LocalizeScripts::lazyLocalize('/inc/localizedScriptsList.php', 'wp_enqueue_scripts');
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'wp_enqueue_scripts',
                'callback' => array($scripts, 'enqueuer'),
                'priority' => 30,
            ),

            /**
             * Pre Get Posts
             *
             * - posts per page    @since 1.0.0
             * - order by featured @since 1.0.0
             */
            array(
                'filter'   => 'pre_get_posts',
                'callback' => 'QiblaFramework\\Front\\Settings\\Listings::postsPerPage',
                'priority' => 20,
            ),
            array(
                'filter'        => 'the_posts',
                'callback'      => 'QiblaFramework\\Front\\Settings\\Listings::orderByFeatured',
                'priority'      => 20,
                'accepted_args' => 2,
            ),

            /**
             * Head
             *
             * - Jumbotron customCss @since 1.0.0
             * - Jumbotron Parallax  @since 1.4.0
             * - 404 background page @since 1.0.0
             */
            array(
                'filter'   => 'wp_head',
                'callback' => array($jumbotronMeta, 'customCss'),
                'priority' => 20,
            ),
            array(
                'filter'   => 'wp_head',
                'callback' => array($jumbotronMeta, 'setParallax'),
                'priority' => 30,
            ),
            array(
                'filter'   => 'wp_head',
                'callback' => function () use ($page404) {
                    is_404() && $page404->background();
                },
                'priority' => 20,
            ),

            /**
             * Search
             *
             * - Header Search    @since 1.0.0
             * - Within Jumbotron @since 1.0.0
             */
            array(
                'filter'   => 'qibla_after_nav_main',
                'callback' => 'QiblaFramework\\Search\\Search::searchFilter',
                'priority' => 10,
            ),
            array(
                'filter'   => array(
                    'qibla_after_jumbotron',
                    'qibla_after_heromap',
                ),
                'callback' => 'QiblaFramework\\Search\\Search::searchByOptionsFilter',
                // Related to the filters within the theme.
                'priority' => 30,
            ),

            /**
             * Navigation
             *
             * - Header Main Nav walker @since 1.5.0
             */
            array(
                'filter'   => 'qibla_nav_main_walker',
                'callback' => 'QiblaFramework\\Front\\Walker\\NavMainWalker::walkerFilter',
                'priority' => 20,
            ),

            /**
             * Archive
             *
             * - Remove the blog archive title if jumbotron @since 1.0.0
             * - Listings Form Filters                      @since 1.0.0
             * - Listings Toolbar                           @since 1.0.0
             * - Listings Found Posts                       @since 1.0.0
             * - Listings Archive Description               @since 1.0.0
             * - Listings Google Map                        @since 1.0.0
             */
            array(
                'filter'   => 'qibla_before_archive_posts_loop',
                'callback' => function () {
                    is_home() && remove_action(
                        'qibla_before_archive_posts_loop',
                        'Qibla\\Functions\\theArchiveTitle',
                        20
                    );
                },
                // Before the theme archive post loop.
                'priority' => 10,
            ),
            array(
                'filter'   => 'qibla_before_archive_listings_list',
                'callback' => 'QiblaFramework\\Filter\\Form::formFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_before_archive_listings_list',
                'callback' => 'QiblaFramework\\Functions\\listingsToolbarTmpl',
                'priority' => 30,
            ),
            array(
                'filter'   => 'qibla_listings_toolbar',
                'callback' => 'QiblaFramework\\Functions\\foundPostsTmpl',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_after_archive_listings_list',
                'callback' => 'QiblaFramework\\Template\\ListingsArchiveFooter::template',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_after_archive_listings_list',
                'callback' => 'Qibla\\Functions\\theArchiveDescription',
                'priority' => 30,
            ),
            array(
                'filter'   => 'qibla_before_archive_listings',
                'callback' => 'QiblaFramework\\Template\\GoogleMap::template',
                'priority' => 20,
            ),

            /**
             * Loop
             *
             * - Listings Post Thumbnail Size     @since 1.0.0
             * - Listings Post Thubmnail Template @since 2.0.0
             * - Listings Post Icon               @since 2.0.0
             * - Listings Average Rating          @since 1.2.0
             * - Listings Footer Loop Location    @since 2.0.0
             */
            array(
                'filter'   => 'post_thumbnail_size',
                'callback' => 'QiblaFramework\\Functions\\postThumbnailSize',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_listings_loop_header',
                'callback' => 'QiblaFramework\\Template\\Thumbnail::template',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_listings_loop_header_after',
                'callback' => 'QiblaFramework\\Front\\CustomFields\\Button::buttonFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_template_engine_data_the_post_title',
                'callback' => 'QiblaFramework\\Functions\\listingsPostTitleIcon',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_listings_loop_entry_content',
                'callback' => 'QiblaFramework\\Template\\Subtitle::template',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_before_post_title',
                'callback' => function (\stdClass $data) {
                    $post  = get_post($data->ID);
                    $types = new Types();

                    if ($types->isListingsType($post->post_type) && ! Context::isSingleListings()) {
                        \QiblaFramework\Review\AverageRating::averageRatingFilter();
                    }
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_template_engine_data_post_loop_footer',
                'callback' => 'QiblaFramework\\Functions\\listingsLoopFooterLocation',
                'priority' => 20,
            ),

            /**
             * Wishlist
             *
             * @since 2.0.0
             */
            array(
                'filter'   => 'qibla_before_listings_loop_header',
                'callback' => 'QiblaFramework\\Wishlist\\Template::template',
                'priority' => 20,
            ),

            /**
             * Hero Map
             *
             * @since 2.4.0
             */
            array(
                'filter'   => 'qibla_header',
                'callback' => 'QiblaFramework\\Front\\CustomFields\\HeroMap::heroMapFilter',
                'priority' => 20,
            ),

            /**
             * Jumbotron
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'qibla_header',
                'callback' => array($jumbotronMeta, 'jumbotron'),
                // After the theme header - 10, but before the theme jumbo-tron - 30.
                'priority' => 25,
            ),

            /**
             * Single
             *
             * - Listings Open Graph image tag     @since 2.5.0
             * - Listings Terms list               @since 1.0.0
             * - Listings Sub Title                @since 1.0.0
             * - Listings Average Rating           @since 1.2.0
             * - Listings Header Subtitle          @since 1.0.0
             * - Listings Section Single Listing   @since 1.0.0
             * - Listings Review                   @since 1.2.0
             * - Listings Related Posts            @since 1.0.0
             * - Post Related Posts                @since 1.0.0
             */
            array(
                'filter'   => 'wp_head',
                'callback' => 'QiblaFramework\\Functions\\insertOGImageTag',
                'priority' => 1,
            ),
            array(
                'filter'   => 'qibla_single_listings_header',
                'callback' => 'QiblaFramework\\Functions\\listingsTermsListTmpl',
                // Before the single listings title.
                'priority' => 10,
            ),
            array(
                'filter'   => 'qibla_single_listings_header',
                'callback' => array($singularHeaderMeta, 'subtitle'),
                // After the title.
                'priority' => 40,
            ),
            array(
                'filter'   => 'qibla_after_post_title',
                'callback' => function () {
                    if (Context::isSingleListings()) {
                        AverageRating::averageRatingFilter();
                    }
                },
                // After the title in theme.
                'priority' => 40,
            ),
            array(
                'filter'   => 'qibla_before_listings_sidebar',
                'callback' => 'QiblaFramework\\Front\\CustomFields\\Button::buttonFilter',
                'priority' => 5,
            ),
            array(
                'filter'   => 'qibla_before_listings_sidebar',
                'callback' => 'QiblaFramework\\Front\\CustomFields\\CustomContent::customContentFilter',
                'priority' => 10,
            ),
            array(
                'filter'   => 'qibla_before_listings_sidebar',
                'callback' => 'QiblaFramework\\Front\\CustomFields\\ListingsLocation::listingsLocationFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_before_listings_sidebar',
                'callback' => 'QiblaFramework\\Template\\ShareAndWish::template',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_before_listings_sidebar',
                'callback' => 'QiblaFramework\\Front\\CustomFields\\ListingsSocials::socialLinksFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_before_listings_sidebar',
                'callback' => 'QiblaFramework\\Template\\OpeningHours::template',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_after_single_listings_loop_entry_content',
                'callback' => 'QiblaFramework\\Template\\AmenitiesTemplate::amenitiesSectionFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_after_single_listings_content',
                'callback' => 'QiblaFramework\\Front\\CustomFields\\RelatedPosts::relatedPostsFilter',
                'priority' => 40,
            ),
            array(
                'filter'   => 'qibla_after_single_listings_loop',
                'callback' => 'QiblaFramework\\Review\\ReviewList::reviewListFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_after_single_content',
                'callback' => 'QiblaFramework\\Front\\CustomFields\\RelatedPosts::relatedPostsFilter',
                // After adjacent posts component.
                'priority' => 30,
            ),

            /**
             * Breadcrumb
             *
             * @since 1.5.0
             */
            array(
                'filter'   => array(
                    'qibla_before_single_loop_entry_content',
                    'qibla_before_page_loop_entry_content',
                ),
                'callback' => array($singularHeaderMeta, 'hideBreadcrumb'),
                // Before the ones within the theme.
                'priority' => 10,
            ),

            /**
             * Settings
             *
             * - Footer Social Links @since 1.0.0
             */
            array(
                'filter'   => 'qibla_colophon',
                'callback' => 'QiblaFramework\\Front\\Settings\\Footer::socialLinks',
                // Before the theme colophon.
                'priority' => 10,
            ),

            /**
             * Footer
             *
             * - Listings Map Templates            @since 1.0.0
             * - Listings Togglers Templates       @since 1.0.0
             * - Listings Json Collection          @since 1.0.0
             * - Listings Form Togglers Templates  @since 1.0.0
             * - Google Analytics                  @since 1.0.0
             * - Custom javascript                 @since 1.0.0
             * - Dropzone Template                 @since 1.5.0
             * - Copyright                         @since 1.0.0
             */
            array(
                'filter'   => 'wp_footer',
                'callback' => 'QiblaFramework\\Functions\\mapTmpls',
                // Before the scripts are loaded.
                'priority' => 10,
            ),
            array(
                'filter'   => 'wp_footer',
                'callback' => 'QiblaFramework\\Functions\\togglersTmpls',
                // Before the scripts are loaded.
                'priority' => 10,
            ),
            array(
                'filter'   => 'wp_footer',
                'callback' => 'QiblaFramework\\Listings\\ListingsLocalizedScript::printScriptFilter',
                'priority' => 0,
            ),
            array(
                'filter'   => 'wp_footer',
                'callback' => 'QiblaFramework\\Front\\Settings\\Footer::googleAnalytics',
                'priority' => 32,
            ),
            array(
                'filter'   => 'wp_footer',
                'callback' => function () {
                    $customeCodeSettingInstance = new Front\Settings\CustomCode();
                    $customeCodeSettingInstance->js();
                },
                'priority' => 30,
            ),
            array(
                'filter'   => 'wp_footer',
                'callback' => 'QiblaFramework\\Form\\DropzoneTemplate::dropzoneTemplateFilter',
                'priority' => 40,
            ),
            array(
                'filter'   => 'wp_footer',
                'callback' => 'QiblaFramework\\Shortcode\\Alert::alertAjaxTmpl',
                'priority' => 40,
            ),
            array(
                'filter'   => 'wp_footer',
                'callback' => 'QiblaFramework\\Functions\\searchNavigationTmpl',
                'priority' => 40,
            ),
            array(
                'filter'   => 'qibla_colophon',
                'callback' => 'QiblaFramework\\Front\\Settings\\Footer::copyrightText',
                // Before the theme copyright.
                'priority' => 10,
            ),

            /**
             * Third Party Plugins
             *
             * - Social Login Plugin Override template position @since 1.6.0
             */
            array(
                'filter'   => 'comment_form_before',
                'callback' => 'QiblaFramework\\SocialLogin\\SocialLoginPluginTemplatePositionOverride::resetPositionFilter',
                'priority' => 20,
            ),

            /**
             * Reviewer Template
             *
             * @since 2.4.0
             */
            array(
                'filter'   => 'qibla_after_single_listings_loop',
                'callback' => 'QiblaFramework\\Reviewer\\ReviewerTemplate::template',
                'priority' => 20,
            ),
        ),
        'filter' => array(
            /**
             * Disable reviews if Reviewer plugin is active
             *
             * @since 2.4.0
             */
            array(
                'filter'   => 'qibla_fw_disable_reviews',
                'callback' => 'QiblaFramework\\Reviewer\\ReviewerFilter::filterFilter',
                'priority' => 40,
            ),

            /**
             * Filter Wp Template for Listings Context
             *
             * This is the main hook used to load the correct template when the main context is for listings
             * post type.
             *
             * @since 2.0.0
             */
            array(
                'filter'   => 'template_include',
                'callback' => 'QiblaFramework\\ListingsContext\\TemplateIncludeFilter::templateIncludeFilterFilter',
                'priority' => 20,
            ),

            /**
             * Fix issue with shortcode unautop
             *
             * This function must be removed after https://core.trac.wordpress.org/ticket/34722 as been fixed.
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'the_content',
                'callback' => 'DLshortcodeUnautop',
                // Must be set to 10 or will not work.
                'priority' => 10,
            ),

            /**
             * Limit Words
             *
             * - Limit Excerpt Length @since 1.0.0
             */
            array(
                'filter'   => 'excerpt_length',
                'callback' => array($blogSettings, 'limitExcerpt'),
                'priority' => 20,
            ),

            /**
             * The post thumbnail Data viewer
             *
             * - Logo Style @since 1.0.0
             */
            array(
                'filter'   => 'qibla_template_engine_data_the_brand',
                'callback' => 'QiblaFramework\\Front\\Settings\\Logo::filterLogoFilter',
                'priority' => 20,
            ),

            /**
             * Html Scope Attributes
             *
             * - Header Fixed              @since 1.7.0
             * - Singular Header Sub Title @since 1.0.0
             * - Text Only Post            @since 1.0.0
             * - Featured Listings         @since 1.1.0
             */
            array(
                'filter'        => 'qibla_scope_attribute',
                'callback'      => 'QiblaFramework\\Front\\Settings\\Header::setHeaderFixedIfStickyFilter',
                'priority'      => 20,
                'accepted_args' => 5,
            ),
            array(
                'filter'        => 'qibla_scope_attribute',
                'callback'      => 'QiblaFramework\\Functions\\getPostTextOnlyModifier',
                // Before the theme filter to able to remove it.
                'priority'      => 19,
                'accepted_args' => 5,
            ),
            array(
                'filter'        => 'qibla_scope_attribute',
                'callback'      => array($singularHeaderMeta, 'headerSkin'),
                'priority'      => 20,
                'accepted_args' => 5,
            ),
            array(
                'filter'        => array(
                    'qibla_scope_attribute',
                    'qibla_fw_scope_attribute',
                ),
                'callback'      => 'QiblaFramework\\Functions\\listingsFeaturedScopeModifier',
                'priority'      => 30,
                'accepted_args' => 5,
            ),

            /**
             * Listings Data
             *
             * - Filter the listing thumbnail @since 1.0.0
             */
            array(
                'filter'   => 'qibla_template_engine_data_the_post_thumbnail',
                'callback' => 'QiblaFramework\\Functions\\postThumbToJumbotronData',
                'priority' => 20,
            ),

            /**
             * Single Comments
             *
             * - Disable Comments                        @since 1.0.0
             * - Disable Reviews Listings                @since 1.2.0
             * - Prevent Reply on Listings If not author @since 1.6.0
             * - Show Review Form                        @since 1.6.0
             */
            array(
                'filter'   => 'qibla_disable_comments',
                'callback' => array($pageSettings, 'forceDisableComments'),
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_fw_disable_reviews',
                'callback' => 'QiblaFramework\\Front\\Settings\\Listings::forceDisableReviews',
                'priority' => 20,
            ),
            array(
                'filter'   => 'preprocess_comment',
                'callback' => 'QiblaFramework\\Review\\ReviewReplyCommenterCheck::checkAllowedReplyFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_after_comments',
                'callback' => 'QiblaFramework\\Review\\ReviewForm::reviewFormFilter',
                'priority' => 20,
            ),

            /**
             * Sidebar
             *
             * This include the sidebar hooks.
             *
             * - Sidebar Blog Position      @since 1.0.0
             * - Sidebar Listings Position  @since 1.0.0
             * - Sidebar Page Position      @since 1.0.0
             * - Sidebar Page Shop Position @since 1.0.0
             */
            array(
                'filter'        => array(
                    'qibla_sidebar_blog_scope_class',
                    'qibla_sidebar_listings_scope_class',
                    'qibla_sidebar_page_scope_class',
                    'qibla_sidebar_shop_scope_class',
                    'qibla_sidebar_single-product_scope_class',
                ),
                'callback'      => 'QiblaFramework\\Functions\\setModifier',
                'priority'      => 20,
                'accepted_args' => 2,
            ),
            array(
                'filter'   => array(
                    'qibla_has_sidebar',
                    'qibla_show_sidebar',
                ),
                'callback' => 'QiblaFramework\\Functions\\hasSidebar',
                // After the theme.
                'priority' => 30,
            ),

            /**
             * 404
             *
             * - Filter header Theme content @since 1.0.0
             * - Search Form                 @since 2.0.0
             */
            array(
                'filter'   => 'qibla_template_engine_data_header_404',
                'callback' => array($page404, 'header'),
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_404_content',
                'callback' => 'QiblaFramework\\Search\\Search::searchByOptionsFilter',
                'priority' => 20,
            ),

            /**
             * Extras
             *
             * - Body Class            @since 1.0.0
             * - Post Class            @since 2.0.0
             * - Is Jumbo-tron Allowed @since 1.1.0
             */
            array(
                'filter'   => 'body_class',
                'callback' => 'QiblaFramework\\Functions\\bodyClass',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_is_jumbotron_allowed',
                'callback' => 'QiblaFramework\\Functions\\isJumbotronAllowed',
                'priority' => 20,
            ),

            /*
             * WooCommerce Custom Templates
             *
             * @since 1.5.0
             */
            array(
                'filter'        => 'wc_get_template',
                'callback'      => array(
                    new QiblaFramework\Woocommerce\WooCommerceTemplateFilter(array(
                        'myaccount/form-login.php',
                        'myaccount/form-lost-password.php',
                    )),
                    'filterTemplateFilter',
                ),
                'priority'      => 20,
                'accepted_args' => 5,
            ),

            /**
             * Visual Composer
             *
             * - Filter Shortcode Templates @since 1.6.0
             * - Filter Css Classes         @since 1.6.0
             */
            array(
                'filter'   => 'vc_before_init_vc',
                'callback' => array(new VisualComposer\ShortcodeTemplatePathOverride(), 'init'),
                'priority' => 20,
            ),
            array(
                'filter'        => 'vc_shortcodes_css_class',
                'callback'      => 'QiblaFramework\\VisualComposer\\BoxModelClassesAdapter::filterCssClassesFilter',
                'priority'      => 20,
                'accepted_args' => 2,
            ),

            /**
             * Social Login
             *
             * @since 1.6.0
             */
            array(
                'filter'        => 'wsl_render_auth_widget_alter_provider_icon_markup',
                'callback'      => 'QiblaFramework\\SocialLogin\\SocialLoginProviderTemplate::filterSocialLoginProviderFilter',
                'priority'      => 20,
                'accepted_args' => 3,
            ),

            /**
             * User
             *
             * - Wp Logout Url     @since 1.7.0
             * - Disable Admin Bar @since 2.0.0
             */
            array(
                'filter'   => 'logout_url',
                'callback' => 'QiblaFramework\\User\\User::userLogoutRedirect',
                'priority' => 20,
            ),
            array(
                'filter'   => 'show_admin_bar',
                'callback' => 'QiblaFramework\\User\\DisableAdminBar::hideAdminBarFilter',
                'priority' => 20,
            ),

            /**
             * WooCommerce Endpoint Filters
             *
             * - Customer Logout redirect @since 1.7.0
             */
            array(
                'filter'        => 'woocommerce_get_endpoint_url',
                'callback'      => 'QiblaFramework\\Woocommerce\\EndPointsUrlFilter::userLogoutWcRedirect',
                'priority'      => 20,
                'accepted_args' => 2,
            ),

            /**
             * Footer
             *
             * - Show or not Footer on Listings Archive @since 1.0.0
             */
            array(
                'filter'   => 'qibla_template_engine_data_footer',
                'callback' => 'QiblaFramework\\Functions\\removeFooterFromArchiveListingsIfMap',
                'priority' => 20,
            ),

            /**
             * Set current lang in json search factory
             *
             * @since 2.1.0
             */
            array(
                'filter'   => 'qibla_search_json_encoder_factory',
                'callback' => function ($args) {
                    $lang = \QiblaFramework\Functions\setCurrentLang();
                    if (\QiblaFramework\Functions\isWpMlActive() && $lang) {
                        $args['lang'] = $lang;
                    }

                    return $args;
                },
                'priority' => 10,
            ),

            /**
             * Search
             *
             * - Search result title @since 1.0.0
             */
            array(
                'filter'   => array(
                    'wp_title',
                    'document_title_parts',
                    'get_the_archive_title'
                ),
                'callback' => 'QiblaFramework\\Search\\SearchResultTitle::filterFilter',
                'priority' => 20,
            ),
        ),
    ),
));
