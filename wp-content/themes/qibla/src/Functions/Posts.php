<?php

namespace Qibla\Functions;

use Qibla\Debug;
use Qibla\Exception\InvalidPostException;
use Qibla\TemplateEngine\Engine as TEngine;
use QiblaFramework\ListingsContext\Types;

/**
 * Post Functions
 *
 * @license GNU General Public License, version 2
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

/**
 * Get the Thumbnail
 *
 * This function override the default wp the_post_thumbnail
 *
 * @see   the_post_thumbnail()
 *
 * @since 1.0.0
 *
 * @throws InvalidPostException If the $post cannot be retrieved.
 * @throws \Exception           If the theme doesn't support post-thumbnails.
 *
 * @param int|\WP_Post $post     Optional. Post ID or WP_Post object. Default current post.
 * @param string|array $size     Optional. Image size. Defaults to 'post-thumbnail', which theme sets using
 *                               set_post_thumbnail_size( $width, $height, $crop_flag );.
 * @param string|bool  $hasLink  If the image point to a post link. Default to false.
 * @param string|array $attr     Optional. Query string or array of attributes.
 *
 * @return \stdClass The data object.
 */
function getPostThumbnailData($post = null, $size = 'post-thumbnail', $hasLink = false, $attr = '')
{
    global $wp_query;

    // View data.
    $data = new \stdClass();

    // Get the post.
    $post = get_post($post);

    if (! $post) {
        throw new InvalidPostException();
    }

    // The theme doesn't support post thumbnails.
    if (! current_theme_supports('post-thumbnails')) {
        throw new \Exception('The theme doesn\'t support the post-thumbnails.');
    }

    // Set the thumbnail attributes.
    $attr = $attr ?: array();
    $attr = array_merge($attr, array(
        // Add the bem class to the post thumbnail image.
        // @see https://core.trac.wordpress.org/ticket/36996 about the class bug.
        'class' => getScopeClass('thumbnail', 'image'),
        'alt'   => getAttachmentImageAlt(get_post_thumbnail_id()),
    ));

    $data->ID            = $post->ID;
    $data->thumbnailAttr = $attr;
    $data->thumbnail     = get_the_post_thumbnail($post, $size, $data->thumbnailAttr);
    $data->caption       = get_the_post_thumbnail_caption($post);

    $html5Support       = current_theme_supports('html5', 'caption');
    $data->containerTag = ($html5Support ? 'figure' : 'div');

    // Post Thumbnail Anchor.
    if ($data->thumbnail && $hasLink && ! (is_singular() && true === ($post === $wp_query->post))) {
        $data->thumbnail = sprintf(
            '<a href="%s" class="%s">%s</a>',
            esc_url(get_permalink($post)),
            getScopeClass('thumbnail', 'link'),
            $data->thumbnail
        );
    }

    $data->captionTag = ($html5Support ? 'figcaption' : 'p');

    return $data;
}

/**
 * The Thumbnail Template
 *
 * @since 1.0.0
 *
 * @return void
 */
function thePostThumbnailTmpl()
{
    // Generally within the theme it is not necessary but,
    // in case of the use with framework the fallback to jumbotron will show the image.
    //if (is_page_template('templates/homepage.php')) {
    //    return;
    //}

    try {
        // Retrieve the data.
        $data = call_user_func_array('Qibla\\Functions\\getPostThumbnailData', func_get_args());
        // Only if there is an image to show.
        $engine = new TEngine('the_post_thumbnail', $data, 'views/posts/thumbnail.php');
        $engine->render();
    } catch (\Exception $e) {
        $debugInstance = new Debug\Exception($e);
        'dev' === QB_ENV && $debugInstance->display();

        return;
    }
}

/**
 * Post Author
 *
 * @since 1.0.0
 *
 * @throws InvalidPostException If the $post cannot be retrieved.
 *
 * @param int|\WP_Post $post The id or the object of the post from which retrieve the author.
 *
 * @return void
 */
function postAuthor($post = null)
{
    // Get the post.
    $post = get_post($post);

    if (! $post) {
        throw new InvalidPostException();
    }

    // Data for View.
    $data = new \stdClass();

    $data->ID        = $post->ID;
    $data->user      = new \WP_User($post->post_author);
    $data->authorUrl = get_author_posts_url($data->user->ID);

    // Remove fields for security.
    unset(
        $data->user->user_pass,
        $data->user->user_email,
        $data->user->user_activation_key,
        $data->user->user_status
    );

    $engine = new TEngine('the_post_author', $data, 'views/posts/meta/author.php');
    $engine->render();
}

/**
 * Get the published date for a post
 *
 * @since  1.0.0
 *
 * @throws InvalidPostException If the post parameter cannot be retrieved.
 *
 * @param  int|\WP_Post $post The id or the object of the post from which retrieve the publish date.
 *
 * @return \stdClass The data instance
 */
function getPostPubDateData($post = null)
{
    // Get the post.
    $post = get_post($post);

    if (! $post) {
        throw new InvalidPostException();
    }

    $data                  = new \stdClass();
    $data->ID              = $post->ID;
    $data->dateArchiveLink = ('page' !== get_post_type($post) ? get_day_link(
        get_the_time('Y', $post),
        get_the_time('m', $post),
        get_the_time('d', $post)
    ) : '');
    $data->datetime        = get_the_time('c', $post);
    $data->date            = get_the_date('', $post);
    $data->titleAttr       = get_the_date('l, F j, Y g:i a', $post);
    $data->label           = esc_html__('Published On: ', 'qibla');

    return $data;
}

/**
 * Post Pub date Template
 *
 * @since 1.0.0
 *
 * @return void
 */
function postPubDateTmpl()
{
    try {
        $data = call_user_func_array('Qibla\\Functions\\getPostPubDateData', func_get_args());
    } catch (\Exception $e) {
        $debugInstance = new Debug\Exception($e);
        'dev' === QB_ENV && $debugInstance->display();

        return;
    }

    $engine = new TEngine('the_post_pubdate', $data, 'views/posts/meta/pubdate.php');
    $engine->render();
}

/**
 * Post Terms
 *
 * @since 1.0.0
 *
 * @throws InvalidPostException If the post parameter cannot be retrieved.
 * @throws \Exception           If terms cannot be retrieved.
 *
 * @param  int|\WP_Post $post     The id or the object of the post from which retrieve the post terms.
 * @param string        $taxonomy The taxonomy slug from which retrieve the terms.
 *
 * @return \stdClass The data instance
 */
function getPostTermsData($taxonomy = 'category', $post = null)
{
    // Get the post.
    $post = get_post($post);

    if (! $post) {
        throw new InvalidPostException();
    }

    // Data for View.
    $data = new \stdClass();

    $data->ID             = $post->ID;
    $data->terms          = get_the_terms($post->ID, $taxonomy);
    $data->taxonomy       = $taxonomy;
    $data->termsSeparator = ',';

    if (is_wp_error($data->terms)) {
        throw new \Exception(sprintf('Cannot retrieve the post terms for post: %d', $post->ID));
    }

    return $data;
}

/**
 * The post Terms Template
 *
 * @since 1.0.0
 *
 * @return void
 */
function postTermsTmpl()
{
    try {
        $data = call_user_func_array('Qibla\\Functions\\getPostTermsData', func_get_args());
    } catch (\Exception $e) {
        $debugInstance = new Debug\Exception($e);
        'dev' === QB_ENV && $debugInstance->display();

        return;
    }

    if (! $data->terms) {
        return;
    }

    $engine = new TEngine('the_post_terms', $data, 'views/posts/meta/listTerms.php');
    $engine->render();
}

/**
 * Show the tags lists associated to the post
 *
 * @since 1.0.0
 *
 * @uses  get_post()
 * @uses  get_the_tag()
 *
 * @throws InvalidPostException If the post parameter cannot be retrieved.
 * @throws \Exception           In case some of the internal functions return a \WP_Error.
 *
 * @param int|\WP_Post $post The id of the post from which retrieve the post tags.
 *
 * @return void
 */
function singlePostTags($post = null)
{
    // Get the post.
    $post = get_post($post);

    if (! $post) {
        throw new InvalidPostException();
    }

    // Data for View.
    $data = new \stdClass();

    $data->ID   = $post->ID;
    $data->tags = get_the_tags($post->ID);

    if (is_wp_error($data->tags)) {
        throw new \Exception(sprintf('%s, cannot retrieve tags for post.', 'qibla'), __FUNCTION__);
    }

    $engine = new TEngine('the_post_tags', $data, 'views/posts/meta/tags.php');
    $engine->render();
}

/**
 * Post Loop Footer
 *
 * @since 1.0.0
 *
 * @uses  get_post()
 * @uses  get_post_meta()
 * @uses  get_post_type()
 *
 * @throws InvalidPostException If the post parameter cannot be retrieved.
 *
 * @param array $args A list of arguments to use within the function {
 *                    string $taxonomy The taxonomy from which retrieve the terms of the current post type.
 *                    }
 *
 * @return void
 */
function loopFooter($post = null, $args = array())
{
    $post = get_post($post);

    // Default condition.
    $isPostType = false;
    if (class_exists('QiblaFramework\\ListingsContext\\Types')) {
        $types      = new Types();
        $isPostType = $types->isListingsType($post->post_type);
    }

    if (! $post) {
        throw new InvalidPostException();
    }

    $args = wp_parse_args($args, array(
        'taxonomy' => 'category',
    ));

    // Initialize Object.
    $data = new \stdClass();

    // Set the current post data.
    $data->ID = $post->ID;
    // Meta.
    $data->meta = array();
    // Published Date.
    $data->meta['pubdate'] = ! $isPostType;
    // Terms list from category.
    $data->meta['terms'] = $isPostType ? false : array(
        'taxonomy' => $args['taxonomy'],
    );

    // Clean the value. Keep only the ones that have a real value.
    $data->meta = array_filter($data->meta);

    $engine = new TEngine('post_loop_footer', $data, '/views/posts/loopFooter.php');
    $engine->render();
}

/**
 * Add Pagination to single post
 *
 * The function is made coherent with the pagination links that use the list format
 *
 * @since 1.0.0
 */
function linkPages()
{
    global $multipage;

    if (! $multipage) {
        return;
    }

    // Filter the items to be coherent with the pagination links.
    add_filter('wp_link_pages_link', function ($markup, $i) {
        // It's the current page or the link?
        if (false === strpos($markup, '<a')) {
            // Page text it's not added to the current page.
            $markup = '<span class="screen-reader-text">' . esc_html__('Page', 'qibla') . '</span>';
            $markup .= '<span class="page-numbers current">' . $i . '</span>';
        }

        return '<li class="' . getScopeClass('pagination', 'item') . '">' . $markup . '</li>';
    }, 10, 2);

    // Data for View.
    $data = new \stdClass();

    $data->linkPagesArgs = array(
        'echo'        => 0,
        'before'      => '<ul class="' . getScopeClass('pagination', 'list') . '">',
        'after'       => '</ul>',
        'link_before' => '<span class="screen-reader-text">' . esc_html__('Page', 'qibla') . '</span>',
        'link_after'  => '',
    );

    $data->pagination = wp_link_pages($data->linkPagesArgs);

    $engine = new TEngine('the_post_link_pages', $data, 'views/pagination/paginatePagination.php');
    $engine->render();
}

/**
 * Show the adjacent posts links in single posts.
 *
 * @todo  Move to link template file.
 * @todo  Check for in_the_loop() because of get_adjacent_post().
 *
 * @since 1.0.0
 *
 * @return void
 */
function adjacentPostsNavigation()
{
    // Data for View.
    $data = new \stdClass();

    // Only the first will be used.
    $data->taxonomies = get_object_taxonomies(get_post_type());

    if (empty($data->taxonomies)) {
        return;
    }

    /**
     * Whether post should be in a same taxonomy term.
     *
     * @since 1.0.0
     *
     * @param string 'yes' to get the post from the same term, 'no' otherwise. Default 'yes'
     */
    $data->inSameTerm = apply_filters('qibla_adjacent_posts_navigation_same_term', 'yes');
    // Clean the value.
    $data->inSameTerm = str_replace(array('yes', 'no'), array(true, false), $data->inSameTerm);

    // Excluded Terms.
    $data->excludedTerms = array();
    // Taxonomy from which retrieve the adjacent posts.
    $data->taxonomy = $data->taxonomies[0];

    // Previous and Next posts.
    $data->adjacentPosts['prev'] = get_previous_post(
        $data->inSameTerm,
        $data->excludedTerms,
        $data->taxonomy
    ) ?: '';
    $data->adjacentPosts['next'] = get_next_post(
        $data->inSameTerm,
        $data->excludedTerms,
        $data->taxonomy
    ) ?: '';

    if (! $data->adjacentPosts['prev'] && ! $data->adjacentPosts['next']) {
        return;
    }

    $engine = new TEngine('the_post_adjacent_posts', $data, 'views/posts/adjacentPosts.php');
    $engine->render();
}

/**
 * Excerpt
 *
 * @since 1.0.0
 *
 * @throws \InvalidArgumentException In case the post cannot be retrieved.
 *
 * @return \stdClass The data containing the excerpt properties
 */
function getExcerptData()
{
    $post = get_post();

    // Initialize data object.
    $data = new \stdClass();

    // Post Title and Permalink are here because some post may have no post title.
    // In this situation we use the post content as link to the single post.
    $data->postTitle   = get_the_title();
    $data->permalink   = get_permalink();
    $data->postContent = '';

    // No listings allowed.
    $isListingsType = false;
    if (class_exists('QiblaFramework\\ListingsContext\\Types')) {
        $types          = new Types();
        $isListingsType = $types->isListingsType(get_post_type());
    }
    if (false !== $isListingsType) {
        return $data;
    }

    // Set the excerpt length.
    // Use the WordPress filter to permit plugins to hook it.
    // Limit is the same of the default number within the framework.
    $excerptLength = apply_filters('excerpt_length', 15);
    // More text.
    $moreText = morePostText();

    // Get the post excerpt only if supported and if the post has excerpt.
    if (post_type_supports(get_post_type(), 'excerpt') && (has_excerpt() || is_search())) {
        $theContent = get_the_excerpt();
    } else {
        $theContent = get_the_content('');
    }

    if ($theContent) :
        // Strip Short-codes.
        $theContent = strip_shortcodes($theContent);
        // Apply the filters applied by the the_content() function.
        $theContent = apply_filters('the_content', $theContent);
        $theContent = str_replace(']]>', ']]&gt;', $theContent);

        // If the more tag is not found we need to generate an excerpt.
        // Don't leave post without content.
        if (! preg_match('/<!--more(.*?)?-->/', $post->post_content, $matches)) {
            // Allow plugins to hook in trim excerpt.
            // The wp_trim_excerpt function doesn't trim anything if the parameter is passed.
            // Called only to able to apply the filters.
            $theContent = wp_trim_excerpt($theContent);
            // Then make a real trim.
            $theContent = wp_trim_words($theContent, $excerptLength, '');
        }
        unset($matches);
    endif;

    if ($theContent) {
        // Strip the tags.
        $theContent = wp_strip_all_tags($theContent);
        // Assign the more string.
        $theContent = str_replace($moreText, '', $theContent) . $moreText;

        // Finally set the post content property.
        $data->postContent = wpautop($theContent);
        // And set the more link label.
        $data->moreLinkLabel = esc_html__('Continue Reading', 'qibla');
    }

    return $data;
}

/**
 * Excerpt Template
 *
 * @since 1.0.0
 *
 * @return void
 */
function excerptTmpl()
{
    try {
        $data = getExcerptData();

        if ($data->postContent) {
            $engine = new TEngine('dl_post_excerpt', $data, '/views/posts/excerpt.php');
            $engine->render();
        }
    } catch (\Exception $e) {
        $debugInstance = new Debug\Exception($e);
        'dev' === QB_ENV && $debugInstance->display();

        return;
    }
}

/**
 * Get Post only text modifier
 *
 * @since 1.0.0
 *
 * @param int    $postID   The ID of the post.
 * @param string $upxscope The scope prefix. Default 'upx'.
 * @param string $element  The current element of the scope.
 * @param string $block    The custom block scope. Default empty.
 * @param string $scope    The default scope prefix. Default 'upx'.
 * @param string $attr     The attribute for which the value has been build.
 *
 * @return string The filtered scope
 */
function getPostTextOnlyModifier($postID, $upxscope, $element, $block, $scope, $attr)
{
    if ((is_singular() && is_main_query()) ||
        'article' !== $block || '' !== $element || 'class' !== $attr
    ) {
        return $upxscope;
    }

    $modifier = has_post_thumbnail($postID) ? '' : 'text-only';

    if ($modifier) {
        $upxscope = $upxscope . ' ' . $scope . $block . '--' . $modifier;
    }

    return $upxscope;
}

/**
 * Post Text Only
 *
 * @since 1.0.0
 *
 * @param string $upxscope The scope prefix. Default 'upx'.
 * @param string $element  The current element of the scope.
 * @param string $block    The custom block scope. Default empty.
 * @param string $scope    The default scope prefix. Default 'upx'.
 * @param string $attr     The attribute for which the value has been build.
 *
 * @return string The filtered scope
 */
function loopPostTextOnly($upxscope, $element, $block, $scope, $attr)
{
    return getPostTextOnlyModifier(get_the_ID(), $upxscope, $element, $block, $scope, $attr);
}

/**
 * Single post categories
 *
 * Show the single post categories.
 *
 * @since 1.0.0
 *
 * @return void
 */
function singlePostCategories()
{
    if (is_singular('post')) {
        echo '<p class="dlpost-categories">' . get_the_category_list(', ') . '</p>';
    }
}

/**
 * Single post Footer
 *
 * Show the post footer only in single.
 *
 * @since 1.0.0
 *
 * @return void
 */
function singlePostFooter()
{
    if (is_singular('post')) {
        try {
            loopFooter(null, array('taxonomy' => 'post_tag'));
        } catch (\Exception $e) {
            $debugInstance = new Debug\Exception($e);
            'dev' === QB_ENV && $debugInstance->display();

            return;
        }
    }
}

/**
 * Header 404
 *
 * @since 1.0.0
 *
 * @return void
 */
function header404()
{
    // Initialize Data.
    $data = new \stdClass();

    // The title.
    $data->title = esc_html__('Sorry this page doesn\'t exists', 'qibla');
    // The header subtitle.
    $data->subtitle = esc_html__(
        'It looks like nothing was found at this location. Maybe try a search?',
        'qibla'
    );

    $engine = new TEngine('header_404', $data, '/views/404/header.php');
    $engine->render();
}

/**
 * The more text
 *
 * @since 1.0.0
 *
 * @return string
 */
function morePostText()
{
    // Able plugins to edit it.
    return apply_filters('the_content_more_link', '&hellip;');
}
