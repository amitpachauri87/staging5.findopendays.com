<?php
namespace Qibla\Functions\Woocommerce;

use Qibla\TemplateEngine\Engine as TEngine;

/**
 * My Account Functions
 *
 * @package Qibla\Functions\Woocommerce
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

function orderActionsData($actions, $order)
{
    // Initialize Data.
    $data = new \stdClass();

    /**
     * Additional Actions
     *
     * @param array     $actions The default actions.
     * @param \WC_Order $order   The current order.
     */
    $actions = apply_filters('woocommerce_my_account_my_orders_actions', $actions, $order);

    if ($actions) {
        // Actions List.
        $data->actions = array();

        foreach ($actions as $key => $action) {
            $data->actions[sanitize_key($key)] = $action;
        }
        unset($key, $action);
    }

    return $data;
}

function orderActionsTmpl()
{
    $data = call_user_func_array('Qibla\\Functions\\Woocommerce\\orderActionsData', func_get_args());

    if (! $data->actions) {
        return;
    }

    $engine = new TEngine('myaccount_order_actions', $data, '/views/myAccount/orderActions.php');
    $engine->render();
}
