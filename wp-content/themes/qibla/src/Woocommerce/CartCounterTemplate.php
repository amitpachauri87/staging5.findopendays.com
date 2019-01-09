<?php
/**
 * CartCounterTemplate
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

namespace Qibla\Woocommerce;

use QiblaFramework\TemplateEngine\TemplateInterface;
use Qibla\TemplateEngine\Engine as TEngine;

/**
 * Class CartCounterTemplate
 *
 * @since  1.5.1
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class CartCounterTemplate implements TemplateInterface
{
    /**
     * @inheritdoc
     */
    public function getData()
    {
        // Initialize Data.
        $data = new \stdClass();

        // The counter.
        $data->counter = WC()->cart->cart_contents_count;
        // The cart url.
        $data->cartUrl = wc_get_cart_url();
        // The cart url Label.
        $data->cartLabel = esc_html__('View cart', 'qibla');

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new TEngine('cart_counter', $data, '/views/cart/cartCounter.php');
        $engine->render();
    }

    /**
     * Cart Counter Template Filter
     *
     * @since 1.5.1
     *
     * @return void
     */
    public static function cartCounterTmplFilter()
    {
        $instance = new static;
        $instance->tmpl($instance->getData());
    }
}
