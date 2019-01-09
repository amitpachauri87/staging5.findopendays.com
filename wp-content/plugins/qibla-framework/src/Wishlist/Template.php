<?php
/**
 * Template
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

namespace QiblaFramework\Wishlist;

use QiblaFramework\Functions as F;
use QiblaFramework\Request\Nonce;
use QiblaFramework\Template\TemplateInterface;
use QiblaFramework\TemplateEngine\Engine;

/**
 * Class Template
 *
 * @since   2.0.0
 * @package QiblaFramework\Wishlist
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class Template implements TemplateInterface
{
    /**
     * Post
     *
     * @since 2.0.0
     *
     * @var \WP_Post The current post to work with
     */
    private $post;

    /**
     * Template constructor
     *
     * @since 2.0.0
     *
     * @param \WP_Post $post The current post to work with
     */
    public function __construct(\WP_Post $post)
    {
        $this->post = $post;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $nonce  = new Nonce('wishlist');
        $meta   = new WishList(wp_get_current_user());
        $action = 'store';

        // Initialize the attribute class list.
        $class = array(
            F\getScopeClass('wishlist-adder'),
        );

        // Mark the element as in wishlist
        // if set currently in the wishlist of the user.
        if ($meta->hasItem($this->post)) {
            $class[] = 'is-stored';

            // Set to 'remove' in case exists.
            $action = 'remove';
        }

        return (object)array(
            'label'     => esc_html__('Add to Wishlist', 'qibla-framework'),
            'post'      => $this->post,
            'class'     => $class,
            'queryArgs' => add_query_arg(array(
                'nonce'  => $nonce->nonce(),
                'action' => $action,
            )),
        );
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new Engine('wishlist_cta', $data, '/views/wishlist/wishlist.php');

        // Render the view and enqueue the script.
        if ($engine->render() && wp_script_is('wishlist-adder', 'registered')) {
            wp_enqueue_script('wishlist-adder');
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
