<?php
namespace QiblaFramework\Admin\Metabox;

use QiblaFramework\Functions as F;
use QiblaFramework\Plugin;

/**
 * Meta-box Testimonial
 *
 * @since      1.0.0
 * @package    QiblaFramework\Admin\Metabox
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
 * Class Testimonials
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Testimonial extends AbstractMetaboxForm
{

    /**
     *  Testimonial Constructor.
     *
     * @inheritdoc
     */
    public function __construct(array $args = array())
    {
        parent::__construct(wp_parse_args($args, array(
            'id'       => 'extra-info',
            'title'    => esc_html__('Extra Info', 'qibla-framework'),
            'screen'   => array('testimonial'),
            'callback' => array($this, 'callBack'),
            'context'  => 'normal',
            'priority' => 'high',
        )));

        parent::setFields(include Plugin::getPluginDirPath('/inc/metaboxFields/testimonialFields.php'));
    }
}
