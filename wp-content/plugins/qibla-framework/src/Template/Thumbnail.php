<?php
/**
 * Thumbnail
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @package   dreamlist-framework
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

namespace QiblaFramework\Template;

use QiblaFramework\Functions as F;
use QiblaFramework\TemplateEngine\Engine;

/**
 * Class Thumbnail
 *
 * @since   2.0.0
 * @package QiblaFramework\Template
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class Thumbnail implements TemplateInterface
{
    /**
     * Post
     *
     * @since 2.0.0
     *
     * @var \WP_Post The post for which show the thumbnail
     */
    private $post;

    /**
     * Arguments
     *
     * @since 2.0.0
     *
     * @var array The list of the arguments
     */
    private $args;

    /**
     * Thumbnail constructor
     *
     * @since 2.0.0
     *
     * @param \WP_Post $post The post for which show the thumbnail.
     * @param array    $args The arguments for the thumbnail.
     */
    public function __construct(\WP_Post $post, array $args = array())
    {
        $this->post = $post;
        $this->args = wp_parse_args($args, array(
            /**
             * Filter size for post thumbnail loop.
             *
             * @since 2.4.0
             */
            'size' => apply_filters('qibla_thumbnail_image_size_post_loop', 'qibla-post-thumbnail-loop'),
        ));
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return (object)array(
            'image' => F\getPostThumbnailAndFallbackToJumbotronImage($this->post, $this->args['size']),
        );
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        if ($data->image) {
            $engine = new Engine('thumbnail', $data, '/views/thumbnail.php');
            $engine->render();
        }
    }

    /**
     * @inheritDoc
     */
    public static function template($object = null)
    {
        $instance = new self($object);
        $instance->tmpl($instance->getData());
    }
}
