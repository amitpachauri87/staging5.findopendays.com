<?php
/**
 * IconList
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

namespace QiblaFramework\VisualComposer\Type;

use QiblaFramework\IconsSet\Lineawesome;
use QiblaFramework\Plugin;

/**
 * Class IconList
 *
 * @since   1.6.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\VisualComposer\Type
 */
final class IconList implements VisualComposerTypeInterface
{
    /**
     * Build Attrs
     *
     * @since 1.6.0
     *
     * @param array $settings The settings for the input to fill with additional data.
     *
     * @return array The settings
     */
    private function attrs($settings)
    {
        $attrs = array(
            'class' => array(
                $settings['param_name'],
                'wpb_vc_param_value',
                'wpb-input',
                'wpb-select',
                'type',
                'dropdown',
            ),
        );

        if (isset($settings['attrs'])) {
            $attrs = array_merge($settings['attrs'], $attrs);
        }

        return $attrs;
    }

    /**
     * @inheritDoc
     */
    public function slug()
    {
        return 'qibla_icon';
    }

    /**
     * @inheritDoc
     */
    public function callback($settings, $value)
    {
        $icon = new \QiblaFramework\Form\Types\IconList(array(
            'options' => array(new Lineawesome()),
            'value'   => $value,
            'name'    => $settings['param_name'],
            'attrs'   => $this->attrs($settings),
        ));

        return $icon->getHtml();
    }

    /**
     * @inheritDoc
     */
    public function scriptUrl()
    {
        return Plugin::getPluginDirUrl('/assets/js/types/iconList.js');
    }
}
