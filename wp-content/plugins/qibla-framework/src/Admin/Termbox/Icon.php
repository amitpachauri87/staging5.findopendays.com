<?php
namespace QiblaFramework\Admin\Termbox;

use QiblaFramework\Plugin;

/**
 * Class Icons
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
 * Class Icon
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Icon extends AbstractTermboxForm
{
    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        parent::__construct(array(
            'id'            => 'icon',
            'title'         => esc_html__('Icon', 'qibla-framework'),
            'callback'      => array($this, 'callBack'),
            'callback_args' => array(),
            'screen'        => apply_filters('qibla_fw_termbox_icon_screen', array('listing_categories', 'amenities')),
        ));

        parent::setFields(include Plugin::getPluginDirPath('/inc/termboxFields/iconFields.php'));
    }
}
