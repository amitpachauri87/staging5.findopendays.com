<?php
/**
 * Title
 *
 * @since      1.0.0
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

namespace AppMapEvents\Post;

use AppMapEvents\Debug;
use AppMapEvents\TemplateEngine\Engine as TEngine;
use QiblaFramework\Listings\ListingsPost;
use QiblaFramework\ListingsContext\Context;

/**
 * Class Title
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class Title
{
    /**
     * Get Post Title
     *
     * @since 1.0.0
     *
     * @param \WP_Post|int $post The post from which retrieve the data.
     *
     * @return \stdClass The data object.
     */
    public function getPostTitleData($post = null)
    {
        // Initialize Object.
        $data = new \stdClass();

        // Get the post.
        $post = get_post($post);

        if (! $post) {
            return $data;
        }

        $isSingleListings = is_singular('events');
        if (class_exists('QiblaFramework\\ListingsContext\\Context')) {
            $isSingleListings = Context::isSingleListings();
        }

        $data->ID       = $post->ID;
        $data->titleTag = $isSingleListings ? 'h1' : 'h2';
        $data->title    = get_the_title($post);

        $post = new ListingsPost($post);
        $data->icon = $post->icon();

        return $data;
    }

    /**
     * The post title Template
     *
     * {@inheritdoc}
     *
     * @return void
     */
    public function postTitleTmpl()
    {
        try {
            // Retrieve the data.
            $data = call_user_func_array(array($this, 'getPostTitleData'), func_get_args());

            // Create and render the template.
            $engine = new TEngine('the_post_title', $data, 'views/title.php');
            $engine->render();
        } catch (\Exception $e) {
            $debugInstance = new Debug\Exception($e);
            'dev' === QB_ENV && $debugInstance->display();

            return;
        }//end try
    }
}
