<?php
/**
 * Element Facade
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaWcListings\Front\Element
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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

namespace QiblaWcListings\Front\Element;

use Qibla\Debug;

/**
 * Class ElementFacade
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaWcListings\Front\Element
 */
class ElementFacade
{
    /**
     * Post
     *
     * @since 2.0.0
     *
     * @var \WP_Post The post instance
     */
    private $post;

    /**
     * The internal element from which get the data
     *
     * @since  1.0.0
     *
     * @var ElementInterface
     */
    private $el;

    /**
     * Create the element
     *
     * @since  1.0.0
     *
     * @return ElementInterface|\stdClass The element instance or a standard class instance if element cannot be
     *                                    created.
     */
    private function createElement()
    {
        try {
            // Get the element factory instance.
            $elFactory = new ElementFactory($this->post);

            // Create the element.
            return $elFactory->createElement();
        } catch (\QiblaFramework\Exceptions\Error $e) {
            $debugInstance = new Debug\Exception($e);
            'dev' === QB_ENV && $debugInstance->display();

            // Return a standard class instance. Internal consistence.
            return new \stdClass();
        }
    }

    /**
     * ElementFacade constructor
     *
     * @since 1.0.0
     * @since 2.0.0 The parameter is a \WP_Post instance.
     *
     * @throws \InvalidArgumentException If the post cannot be retrieved
     *
     * @param \WP_Post $post The post instance.
     */
    public function __construct(\WP_Post $post)
    {
        $this->post = $post;
        $this->el   = $this->createElement();
    }

    /**
     * Call
     *
     * @since 1.0.0
     *
     * @param string $name The function name.
     * @param array  $args The arguments passed to the function
     *
     * @return mixed Whatever the method returns
     */
    public function __call($name, $args)
    {
        if (method_exists($this->el, $name) && is_callable(array($this->el, $name))) {
            return call_user_func_array(array($this->el, $name), $args);
        }
    }
}
