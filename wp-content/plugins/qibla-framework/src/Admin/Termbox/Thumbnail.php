<?php
namespace QiblaFramework\Admin\Termbox;

use QiblaFramework\Functions as F;
use QiblaFramework\Admin\Functions as Af;
use QiblaFramework\Plugin;

/**
 * Class Taxonomy Listing Categories
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
 * Class Thumbnail
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Thumbnail extends AbstractTermboxForm
{
    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        parent::__construct(array(
            'id'            => 'thumbnail',
            'title'         => esc_html__('Thumbnail', 'qibla-framework'),
            'screen' => apply_filters(
                'qibla_fw_termbox_thumbnail_screen',
                array('locations', 'listing_categories')
            ),
            'callback'      => array($this, 'callBack'),
            'callback_args' => array(),
        ));

        parent::setFields(include Plugin::getPluginDirPath('/inc/termboxFields/thumbnailFields.php'));
    }
}
