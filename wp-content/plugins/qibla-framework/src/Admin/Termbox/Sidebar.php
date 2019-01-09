<?php
namespace QiblaFramework\Admin\Termbox;

use QiblaFramework\Form\Interfaces\Fields;
use QiblaFramework\Functions as F;
use QiblaFramework\Plugin;

/**
 * Class Meta-box Sidebar
 *
 * @since      1.0.0
 * @package    QiblaFramework\Admin\Termbox
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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

/**
 * Class Sidebar
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Sidebar extends AbstractTermboxForm
{
    /**
     * Constructor.
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        parent::__construct(array(
            'id'            => 'sidebar',
            'title'         => esc_html__('Sidebar', 'qibla-framework'),
            'callback'      => array($this, 'callBack'),
            'screen' => apply_filters(
                'qibla_fw_termbox_sidebar_screen',
                array('category', 'post_tag', 'product_cat', 'product_tag')
            ),
            'context'       => 'normal',
            'priority'      => 'high',
            'callback_args' => array(),
        ));

        parent::setFields(include Plugin::getPluginDirPath('/inc/termboxFields/sidebarFields.php'));
    }

    /**
     * Filter Fields
     *
     * @since  1.0.0
     *
     * @param Fields $field The current field before display it.
     *
     * @return bool If the field must be displayed or not
     */
    public function displayField(Fields $field)
    {
        $display = true;
        // Retrieve the current taxonomy.
        $currentScreen = F\currentScreen();

        if (! $currentScreen || ! isset($currentScreen->taxonomy)) {
            return $display;
        }

        switch ($currentScreen->taxonomy) {
            // Remove the 'default' option from WooCommerce Taxonomies.
            case 'product_cat':
            case 'product_tag':
                $options = $field->getType()->getArg('options');
                if (isset($options['default'])) {
                    unset($options['default']);
                }
                $field->getType()->setArg('options', $options);
                break;
            default:
                break;
        }

        return $display;
    }
}
