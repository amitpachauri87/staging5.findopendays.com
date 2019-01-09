<?php
/**
 * Front-end Filters
 *
 * @since     1.0.0
 * @author    guido scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2016, Guido Scialfa
 * @license   GNU General Public License, version 2
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

use Unprefix\Scripts;
use Qibla\Theme;
use Qibla\Post;

// Get the Scripts and Styles.
$scripts          = new Scripts\ScriptsFacade(include Theme::getTemplateDirPath('/inc/scripts.php'));
$scriptsLocalized = new Scripts\LocalizeScripts(include Theme::getTemplateDirPath('/inc/localizedScriptsList.php'));

/*
 * Ex.
 * 'context' => [
 *      'type' => [
 *          [
 *              'filter'             => 'filter_name',
 *              'callback'           => theCallback,
 *              'priority'           => Number,
 *              'accepted_arguments' => Number
 *          ],
 *      ],
 * ],
 */
return array(
    'front' => array(
        'action' => array(
            /*
             * After Theme Switch
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'after_switch_theme',
                'callback' => 'flush_rewrite_rules',
                'priority' => 20,
            ),

            /*
             * Scripts & Styles
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'wp_enqueue_scripts',
                'callback' => array($scripts, 'register'),
                'priority' => 20,
            ),
            array(
                'filter'   => 'wp_enqueue_scripts',
                'callback' => array($scriptsLocalized, 'localizeScripts'),
                'priority' => 20,
            ),
            array(
                'filter'   => 'wp_enqueue_scripts',
                'callback' => array($scripts, 'enqueuer'),
                'priority' => 30,
            ),

            /*
             * Head
             *
             * - Device Meta     @since 1.0.0
             * - Jumbotron style @since 1.2.0
             */
            array(
                'filter'   => 'wp_head',
                'callback' => 'Qibla\\Functions\\metaDevice',
                'priority' => 20,
            ),
            array(
                'filter'   => 'wp_head',
                'callback' => array('Qibla\\Jumbotron', 'customCss'),
                'priority' => 30,
            ),

            /*
             * Header
             *
             * - header    @since 1.0.0
             * - Jumbotron @since 1.0.0
             */
            array(
                'filter'   => 'qibla_header',
                'callback' => 'Qibla\\Functions\\header',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_header',
                'callback' => array('Qibla\\Jumbotron', 'jumbotronTmpl'),
                'priority' => 30,
            ),

            /*
             * Header Content
             *
             * - siteLogo @since 1.0.0
             * - navMain  @since 1.0.0
             */
            array(
                'filter'   => 'qibla_header_content',
                'callback' => 'Qibla\\Logo::logoFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_header_content',
                'callback' => array(new \Qibla\MainNav(), 'display'),
                'priority' => 30,
            ),

            /*
             * Jumbo-tron
             *
             * - Single Post Terms list @since 1.0.0
             * - Single Post Footer     @since 1.0.0
             */
            array(
                'filter'   => 'qibla_before_jumbotron',
                'callback' => 'Qibla\\Functions\\singlePostCategories',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_after_jumbotron',
                'callback' => 'Qibla\\Functions\\singlePostFooter',
                'priority' => 20,
            ),

            /*
             * Before Archive Posts Loop
             *
             * - Archive Title @since 1.0.0
             */
            array(
                'filter'   => 'qibla_before_archive_posts_loop',
                'callback' => 'Qibla\\Archive\\Title::archiveTitleFilter',
                'priority' => 20,
            ),

            /*
             * After Archive Posts Loop
             *
             * - Archive Pagination @since 1.0.0
             * - Author Pagination  @since 1.0.0
             */
            array(
                'filter'   => 'qibla_after_archive_posts_loop',
                'callback' => 'Qibla\\Functions\\archivePaginationTmpl',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_after_author_posts_loop',
                'callback' => 'Qibla\\Functions\\archivePaginationTmpl',
                'priority' => 20,
            ),

            /*
             * Scope Attribute
             *
             * - Text Only Post @since 1.0.0
             */
            array(
                'filter'        => 'qibla_scope_attribute',
                'callback'      => 'Qibla\\Functions\\loopPostTextOnly',
                'priority'      => 20,
                'accepted_args' => 5,
            ),

            /*
             * Loop Header
             *
             * - Post     thePostThumbnail @since 1.0.0
             * - Post     postTitle        @since 1.0.0
             * - Listings postTitle        @since 1.0.0
             */
            array(
                'filter'   => 'qibla_loop_header',
                'callback' => function () {
                    Qibla\Functions\thePostThumbnailTmpl(null, 'large');
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_loop_header',
                'callback' => array(new Post\Title(), 'postTitleTmpl'),
                'priority' => 30,
            ),
            array(
                'filter'   => 'qibla_listings_loop_header',
                'callback' => array(new Post\Title(), 'postTitleTmpl'),
                'priority' => 20,
            ),

            /*
             * Loop Entry Content
             *
             * - Loop Footer           @since 1.0.0
             * - Loop Listings Footer  @since 1.0.0 @todo Move in framework.
             * - Loop Content          @since 1.0.0
             * - Loop Listings Content @since 1.0.0
             */
            array(
                'filter'   => 'qibla_loop_entry_content',
                'callback' => 'Qibla\\Functions\\loopFooter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_listings_loop_entry_content',
                'callback' => 'Qibla\\Functions\\loopFooter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_loop_entry_content',
                'callback' => 'Qibla\\Functions\\excerptTmpl',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_listings_loop_entry_content',
                'callback' => 'Qibla\\Functions\\excerptTmpl',
                'priority' => 20,
            ),

            /*
             * Single Header
             *
             * - Post       thePostThumbnail @since 1.0.0
             * - Page       thePostThumbnail @since 1.0.0
             * - Single Post postTitle       @since 1.0.0
             * - Page       postTitle        @since 1.0.0
             * - Attachment postTitle        @since 1.0.0
             * - Listings   postTitle        @since 1.0.0
             */
            array(
                'filter'   => 'qibla_single_header',
                'callback' => function () {
                    Qibla\Functions\thePostThumbnailTmpl(null, 'qibla_large');
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_page_header',
                'callback' => function () {
                    Qibla\Functions\thePostThumbnailTmpl(null, 'qibla_large');
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_single_header',
                'callback' => array(
                    new Post\Title(array(
                        'screen_reader_text' => true,
                    )),
                    'postTitleTmpl',
                ),
                'priority' => 30,
            ),
            array(
                'filter'   => 'qibla_page_header',
                'callback' => array(
                    new Post\Title(array(
                        'screen_reader_text' => true,
                    )),
                    'postTitleTmpl',
                ),
                'priority' => 30,
            ),
            array(
                'filter'   => 'qibla_attachment_header',
                'callback' => array(
                    new Post\Title(array(
                        'screen_reader_text' => true,
                    )),
                    'postTitleTmpl',
                ),
                'priority' => 30,
            ),
            array(
                'filter'   => 'qibla_single_listings_header',
                'callback' => array(new Post\Title(), 'postTitleTmpl'),
                'priority' => 30,
            ),

            /*
             * Breadcrumb
             *
             * - Before Single @since 1.0.0
             * - Before Page   @since 1.0.0
             */
            array(
                'filter'   => 'qibla_before_single_loop_entry_content',
                'callback' => 'Qibla\\Functions\\breacrumbTmpl',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_before_page_loop_entry_content',
                'callback' => 'Qibla\\Functions\\breacrumbTmpl',
                'priority' => 20,
            ),

            /*
             * After Single Loop Entry Content
             *
             * - Post linkPages @since 1.0.0
             */
            array(
                'filter'   => 'qibla_after_single_loop_entry_content',
                'callback' => 'Qibla\\Functions\\linkPages',
                'priority' => 20,
            ),

            /*
             * After Single Loop
             *
             * - Post comments @since 1.0.0
             */
            array(
                'filter'   => 'qibla_after_single_loop',
                'callback' => 'Qibla\\Functions\\comments',
                'priority' => 40,
            ),

            /*
             * After Single Content
             *
             * - Post adjacentPostsNavigation @since 1.0.0
             */
            array(
                'filter'   => 'qibla_after_single_content',
                'callback' => 'Qibla\\Functions\\adjacentPostsNavigation',
                'priority' => 20,
            ),

            /*
             * After Page Loop
             *
             * - Page comments @since 1.0.0
             */
            array(
                'filter'   => 'qibla_after_page_loop',
                'callback' => 'Qibla\\Functions\\comments',
                'priority' => 20,
            ),

            /*
             * Single Posts Comments
             *
             * - Disable Comments                 @since 1.0.0
             * - Remove Logged In As Profile Link @since 1.6.0
             */
            array(
                'filter'   => 'qibla_disable_comments',
                'callback' => 'Qibla\\Functions\\disableCommentsOnPage',
                'priority' => 20,
            ),
            array(
                'filter'   => 'comment_form_defaults',
                'callback' => 'Qibla\\Functions\\filterLoggedInAs',
                'priority' => 20,
            ),

            /*
             * Before Comments List
             *
             * - commentsSectionTitle @since 1.0.0
             */
            array(
                'filter'   => 'qibla_before_comments_list',
                'callback' => 'Qibla\\Functions\\commentsSectionTitleTmpl',
                'priority' => 20,
            ),

            /*
             * After Comments List
             *
             * - paginateCommentsLinksTmpl @since 1.0.0
             */
            array(
                'filter'   => 'qibla_after_comments_list',
                'callback' => 'Qibla\\Functions\\paginateCommentsLinksTmpl',
                'priority' => 20,
            ),

            /*
             * After Single
             *
             * - Archive Listings Sidebar @since 1.0.0
             * - Listings Sidebar         @since 1.0.0
             * - Page Sidebar             @since 1.0.0
             * - Home(blog) Sidebar       @since 1.0.0
             * - Author Sidebar           @since 1.0.0
             * - Single Post Sidebar      @since 2.4.0
             */
            array(
                'filter'   => 'qibla_before_archive_listings_list',
                'callback' => 'Qibla\\Functions\\sidebar',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_after_single_listings',
                'callback' => 'Qibla\\Functions\\sidebar',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_after_page',
                'callback' => 'Qibla\\Functions\\sidebar',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_after_archive',
                'callback' => 'Qibla\\Functions\\sidebar',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_after_author',
                'callback' => 'Qibla\\Functions\\sidebar',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_after_single',
                'callback' => 'Qibla\\Functions\\sidebar',
                'priority' => 20,
            ),

            /*
             * Footer
             *
             * - footer @since 1.0.0
             */
            array(
                'filter'   => 'qibla_footer',
                'callback' => 'Qibla\\Functions\\footer',
                'priority' => 20,
            ),

            /*
             * Before Footer
             *
             * - sidebar @since 1.0.0
             */
            array(
                'filter'   => 'qibla_footer_content',
                'callback' => 'Qibla\\Functions\\footerSidebar',
                'priority' => 20,
            ),

            /*
             * After Footer Content
             *
             * - colophon @since 1.0.0
             */
            array(
                'filter'   => 'qibla_footer_content',
                'callback' => 'Qibla\\Functions\\colophon',
                'priority' => 20,
            ),

            /*
             * Right Side Colophon
             *
             * - copyright @since 1.0.0
             */
            array(
                'filter'   => 'qibla_colophon',
                'callback' => 'Qibla\\Functions\\copyright',
                'priority' => 20,
            ),

            /*
             * 404 Page
             *
             * - 404 Header content @since 1.0.0
             */
            array(
                'filter'   => 'qibla_404_header',
                'callback' => 'Qibla\\Functions\\header404',
                'priority' => 20,
            ),
        ),
        'filter' => array(
            /**
             * Header Video Settings
             *
             * @since 1.2.0
             */
            array(
                'filter'   => 'header_video_settings',
                'callback' => 'Qibla\\Functions\\headerVideoSettings',
                'priority' => 20,
            ),

            /*
             * Add extra body classes
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'body_class',
                'callback' => 'Qibla\\Functions\\bodyClass',
                'priority' => 20,
            ),

            /*
             * Embed
             *
             * - Responsive Embed @since 1.0.0
             */
            array(
                'filter'   => 'embed_oembed_html',
                'callback' => 'Qibla\\Functions\\responsiveEmbed',
                'priority' => 20,
            ),

            /*
             * Has Sidebar
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'qibla_show_sidebar',
                'callback' => 'Qibla\\Functions\\hasSidebar',
                'priority' => 20,
            ),

            /*
             * Excerpt
             *
             * - Excerpt More @since 1.0.0 @todo Check if still necessary
             */
            array(
                'filter'   => 'excerpt_more',
                'callback' => 'Qibla\\Functions\\morePostText',
                'priority' => 20,
            ),
        ),
    ),
);
