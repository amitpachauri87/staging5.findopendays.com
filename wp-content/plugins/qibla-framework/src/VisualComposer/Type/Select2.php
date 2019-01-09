<?php
/**
 * Select2
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

use QiblaFramework\Form\Types\Select;
use QiblaFramework\Plugin;

/**
 * Class Select2
 *
 * @since   1.6.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\VisualComposer\Type
 */
final class Select2 implements VisualComposerTypeInterface
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
                'wpb-qibla_select2_' . $settings['param_name'],
                'wpb_vc_param_value',
                'wpb-input',
                'wpb-qibla_select2',
                'type',
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
        return 'qibla_select2';
    }

    /**
     * @inheritDoc
     */
    public function callback($settings, $value)
    {
        $this->settings = $settings;
        $this->value    = $value;

        $type = new Select(array(
            'name'    => $settings['param_name'],
            'select2' => true,
            'options' => array_flip((array)$settings['value']),
            'value'   => $value,
            'attrs'   => $this->attrs($settings),
        ));

        // Reorder the fields based on the value if multiple.
        // So we can show the option into the user defined order.
        if ($settings['attrs']['multiple']) {
            add_filter('qibla_fw_forms_type_select_options_before_build', array($this, 'reorderOptionsBasedOnValue'));
        }

        return $type->getHtml();
    }

    /**
     * @inheritDoc
     */
    public function scriptUrl()
    {
        return Plugin::getPluginDirUrl('/assets/js/vc/param/qibla-select2.js');
    }

    /**
     * Reorder Options Based on values
     *
     * @since 1.6.0
     *
     * @throws \BadMethodCallException If method is not hooked in qibla_fw_forms_type_select_options_before_build
     *                                 filter.
     *
     * @param array $options The option items to build the options elements.
     *
     * @return array The re-ordered option items
     */
    public function reorderOptionsBasedOnValue($options)
    {
        if ('qibla_fw_forms_type_select_options_before_build' !== current_filter()) {
            throw new \BadMethodCallException(
                'Method must be hooed in qibla_fw_forms_type_select_options_before_build filter'
            );
        }

        $optValues = $this->value;
        $tmpList   = array();

        // Be sure we are working with the correct type.
        if (! is_array($this->value)) {
            $optValues = explode(',', $optValues);
        }

        if ($optValues) {
            foreach ($optValues as $value) {
                // Just to sleep well.
                if (! isset($options[$value])) {
                    continue;
                }

                // Remember the value is the key for the options value attributes.
                $tmpList = array_merge($tmpList, array($value => $options[$value]));
                // Then remove the item from the options.
                unset($options[$value]);
            }

            // Rebuild the options moving the ones selected on top of others.
            $options = array_merge($tmpList, $options);

            // Remove the none option since we have a value.
            unset($options['none']);
        }

        // Remove after done.
        remove_filter('qibla_fw_forms_type_select_options_before_build', array($this, 'reorderOptionsBasedOnValue'));

        unset(
            $this->settings,
            $this->value
        );

        // Return the filtered list.
        return $options;
    }
}
