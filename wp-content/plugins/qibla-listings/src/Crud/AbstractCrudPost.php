<?php
/**
 * AbstractPostCrud
 *
 * This class define the main Crud behavior for Posts, since the CRUD include Read context
 * there are only the strictly necessary to work in every context.
 * The Read behavior doesn't need more than Author, Post Status and Post name to work.
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

namespace QiblaListings\Crud;

use QiblaFramework\Functions as Fw;

/**
 * Class AbstractPostCrud
 *
 * @todo    Move into framework
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Crud
 */
abstract class AbstractCrudPost implements CrudPostInterface
{
    /**
     * Author
     *
     * @todo   Remove and use the value from the arguments?
     *
     * @since  1.0.0
     *
     * @var \WP_User The user author of the post
     */
    protected $author;

    /**
     * Post Slug
     *
     * @since  1.0.0
     *
     * @var string The post name. Slug version
     */
    protected $name;

    /**
     * Arguments
     *
     * @since  1.0.0
     *
     * @var array The list of the arguments used to store the post
     */
    protected $args;

    /**
     * Post Meta Prefix
     *
     * @since  1.0.0
     *
     * @var string The post meta prefix
     */
    protected static $postMetaPrefix = '_qibla_mb_';

    /**
     * Default Arguments
     *
     * @since  1.0.0
     *
     * @var array The default list
     */
    protected static $defaultArgs = array(
        'post_content'          => '',
        'post_content_filtered' => '',
        'post_excerpt'          => '',
        'post_type'             => 'post',
        'post_status'           => 'publish',
        'comment_status'        => '',
        'ping_status'           => '',
        'post_password'         => '',
        'to_ping'               => '',
        'pinged'                => '',
        'post_parent'           => 0,
        'menu_order'            => 0,
        'guid'                  => '',
        'import_id'             => 0,
        'context'               => '',
    );

    /**
     * AbstractPostCrud constructor
     *
     * @since 1.0.0
     *
     * @param \WP_User $author The author of the post.
     * @param string   $name   The slug of the post. Not the title.
     * @param array    $args   The arguments to create the content.
     */
    public function __construct(\WP_User $author, $name, array $args = array())
    {
        $this->author = $author;
        // Build the arguments.
        $this->args = wp_parse_args(array_merge($args, array(
            'post_name'   => sanitize_title($name),
            'post_author' => $this->author->ID,
            'tax_input'   => isset($args['tax_input']) ? $this->buildTaxInput($args['tax_input']) : array(),
            'meta_input'  => isset($args['meta_input']) ? $this->buildMetaInput($args['meta_input']) : array(),
        )), static::$defaultArgs);
    }

    /**
     * Post exists
     *
     * The method search for posts that are in 'any' status. Except auto-draft and trash.
     *
     * @since  1.0.0
     *
     * @return bool True if post exists. False otherwise.
     */
    public function postExists()
    {
        // Use the title, we don't know if the post exists or is a new one.
        return Fw\getPostByName($this->args['post_name'], $this->args['post_type'], 'any') instanceof \WP_Post;
    }

    /**
     * @inheritdoc
     */
    public function isUserAllowed()
    {
        // Author min level.
        return current_user_can(static::$userAllowedCapability);
    }

    /**
     * Build taxonomy terms list
     *
     * @since  1.0.0
     *
     * @param array $taxInput The list of the taxonomy => terms array.
     *
     * @return array The built tax list
     */
    public function buildTaxInput(array $taxInput)
    {
        $filteredTerms = array();

        foreach ($taxInput as $taxonomy => $terms) {
            foreach ($terms as $term) {
                // If the value is not a WP_Term object, let create it.
                if (! $term instanceof \WP_Term) {
                    $taxonomy_obj = get_taxonomy($taxonomy);
                    if (! $taxonomy_obj || ! term_exists($term, $taxonomy)) {
                        continue;
                    }

                    $term = get_term_by('slug', $term, $taxonomy);
                }

                // Always use ID, So we can work with hierarchy and flat terms without problems.
                $filteredTerms[$taxonomy][] = $term->term_id;
            }
        }

        return $filteredTerms;
    }

    /**
     * Build Meta Input Arguments
     *
     * @since  1.0.0
     *
     * @param array $metaInput
     *
     * @return array|bool
     */
    public function buildMetaInput(array $metaInput)
    {
        // @todo Check for registered meta keys.
        $keys = array_keys($metaInput);
        foreach ($keys as &$key) {
            // Add the post meta key prefix.
            $key = rtrim(static::$postMetaPrefix, '_') . '_' . $key;
        }

        $s = array_combine($keys, $metaInput);

        return $s;
    }
}
