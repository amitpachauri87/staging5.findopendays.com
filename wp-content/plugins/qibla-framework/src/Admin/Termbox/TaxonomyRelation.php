<?php
/**
 * TaxonomyRelation
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

namespace QiblaFramework\Admin\Termbox;

use QiblaFramework\Form\Interfaces\Fields;
use QiblaFramework\Plugin;

/**
 * Class TaxonomyRelation
 *
 * @since   2.0.0
 * @package QiblaFramework\Admin\Termbox
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class TaxonomyRelation extends AbstractTermboxForm
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
            'screen'        => apply_filters('qibla_fw_termbox_tax_relation_screen', array('amenities')),
            'context'       => 'normal',
            'priority'      => 'high',
            'callback_args' => array(),
        ));

        if (wp_script_is('dl-remove-title-group', 'registered')) {
            wp_enqueue_script('dl-remove-title-group');
        }

        parent::setFields(include Plugin::getPluginDirPath('/inc/termboxFields/taxonomyRelationFields.php'));
    }
}
