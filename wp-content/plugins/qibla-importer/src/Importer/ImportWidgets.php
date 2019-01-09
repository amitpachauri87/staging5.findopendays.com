<?php
namespace QiblaImporter\Importer;

use QiblaImporter\Functions as F;
use QiblaImporter\Plugin;

/**
 * Import Widgets
 *
 * @see       https://churchthemes.com/plugins/widget-importer-exporter
 *
 * @copyright Copyright (c) 2016, Guido Scialfa & churchthemes.com
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
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

defined('WPINC') || die;

/**
 * Class ImportWidgets
 *
 * @since   1.0.0
 *
 * @package QiblaImporter\Importer
 */
class ImportWidgets extends AbstractImporter
{
    /**
     * Available widgets
     *
     * @since  1.0.0
     * @access protected
     *
     * @see    https://churchthemes.com/plugins/widget-importer-exporter
     *
     * @return array Widget information
     */
    protected function getAvailableWidgets()
    {
        global $wp_registered_widget_controls;

        $widgetControls   = $wp_registered_widget_controls;
        $availableWidgets = array();

        foreach ($widgetControls as $widget) {
            if (! empty($widget['id_base']) && ! isset($availableWidgets[$widget['id_base']])) {
                $availableWidgets[$widget['id_base']]['id_base'] = $widget['id_base'];
                $availableWidgets[$widget['id_base']]['name']    = $widget['name'];
            }
        }

        return $availableWidgets;
    }

    /**
     * Import widget JSON data
     *
     * @todo Need further check for throws and consistency with other importer classes
     *
     * @since   1.0.0
     * @access  public
     *
     * @link    https://churchthemes.com/plugins/widget-importer-exporter
     *
     * @throws \InvalidArgumentException If the data is empty or not valid.
     *
     * @param array $data The list of the data to store.
     *
     * @return void
     */
    public function import(array $data)
    {
        if (! $data) {
            throw new \InvalidArgumentException(
                esc_html__('Cannot import Theme Option. Data is empty.', 'qibla-importer')
            );
        }

        global $wp_registered_sidebars;

        // Clean all sidebars before start.
        update_option('sidebars_widgets', '');

        // Get all available widgets site supports.
        $availableWidgets = $this->getAvailableWidgets();

        // Get all existing widget instances.
        $widgetInstances = array();
        foreach ($availableWidgets as $widget_data) {
            $widgetInstances[$widget_data['id_base']] = get_option('widget_' . $widget_data['id_base']);
        }

        // Begin results.
        $results = array();

        // Loop import data's sidebars.
        foreach ($data as $sidebarId => $widgets) :
            // Skip inactive widgets (should not be in export file).
            if ('wp_inactive_widgets' === $sidebarId) {
                continue;
            }

            // Check if sidebar is available on this site.
            // Otherwise add widgets to inactive, and say so.
            if (isset($wp_registered_sidebars[$sidebarId])) {
                $sidebarAvailable   = true;
                $useSidebarId       = $sidebarId;
                $sidebarMessageType = 'success';
                $sidebarMessage     = '';
            } else {
                $sidebarAvailable = false;
                // Add to inactive if sidebar does not exist in theme.
                $useSidebarId       = 'wp_inactive_widgets';
                $sidebarMessageType = 'error';
                $sidebarMessage     = esc_html__(
                    'Sidebar does not exist in theme (using Inactive)',
                    'qibla-importer'
                );
            }

            // Result for sidebar.
            // Sidebar name if theme supports it; otherwise ID.
            $results[$sidebarId]['name']         = ! empty($wp_registered_sidebars[$sidebarId]['name']) ?
                $wp_registered_sidebars[$sidebarId]['name'] :
                $sidebarId;
            $results[$sidebarId]['message_type'] = $sidebarMessageType;
            $results[$sidebarId]['message']      = $sidebarMessage;
            $results[$sidebarId]['widgets']      = array();

            // Loop widgets.
            foreach ($widgets as $widgetInstanceId => $widget) :
                $fail = false;

                // Get id_base (remove -# from end) and instance ID number.
                $idBase           = preg_replace('/-[0-9]+$/', '', $widgetInstanceId);
                $instanceIdNumber = str_replace($idBase . '-', '', $widgetInstanceId);

                // Does site support this widget?
                if (! $fail && ! isset($availableWidgets[$idBase])) {
                    $fail              = true;
                    $widgetMessageType = 'error';
                    // Explain why widget not imported.
                    $widgetMessage = esc_html__('Site does not support widget', 'qibla-importer');
                }

                // Convert multidimensional objects to multidimensional arrays
                // Some plugins like Jetpack Widget Visibility store settings as multidimensional arrays
                // Without this, they are imported as objects and cause fatal error on Widgets page
                // If this creates problems for plugins that do actually intend settings in objects
                // then may need to consider other approach:
                // https://wordpress.org/support/topic/problem-with-array-of-arrays
                // It is probably much more likely that arrays are used than objects, however.
                $widget = json_decode(wp_json_encode($widget), true);

                // Does widget with identical settings already exist in same sidebar?
                if (! $fail && isset($widgetInstances[$idBase])) :
                    // Get existing widgets in this sidebar.
                    $sidebarsWidgets = get_option('sidebars_widgets');
                    // Check Inactive if that's where will go.
                    $sidebarWidgets = isset($sidebarsWidgets[$useSidebarId]) ?
                        $sidebarsWidgets[$useSidebarId] :
                        array();

                    // Loop widgets with ID base.
                    $singleWidgetInstances = ! empty($widgetInstances[$idBase]) ?
                        $widgetInstances[$idBase] :
                        array();

                    foreach ($singleWidgetInstances as $checkId => $checkWidget) {
                        // Is widget in same sidebar and has identical settings?
                        if (in_array("$idBase-$checkId", $sidebarWidgets, true) &&
                            (array)$widget === $checkWidget
                        ) {
                            $fail              = true;
                            $widgetMessageType = 'warning';
                            // Explain why widget not imported.
                            $widgetMessage = esc_html__('Widget already exists', 'qibla-importer');

                            break;
                        }
                    }
                endif;

                if (! $fail) :
                    // Add widget instance.
                    // All instances for that widget ID base, get fresh every time.
                    $singleWidgetInstances   = get_option('widget_' . $idBase);
                    $singleWidgetInstances   = ! empty($singleWidgetInstances) ?
                        $singleWidgetInstances :
                        array('_multiwidget' => 1);
                    $singleWidgetInstances[] = $widget;

                    // Get the key it was given.
                    end($singleWidgetInstances);
                    $newInstanceIdNumber = key($singleWidgetInstances);

                    // If key is 0, make it 1
                    // When 0, an issue can occur where adding a widget causes data from other widget to load,
                    // and the widget doesn't stick (reload wipes it).
                    if ('0' === strval($newInstanceIdNumber)) {
                        $newInstanceIdNumber                         = 1;
                        $singleWidgetInstances[$newInstanceIdNumber] = $singleWidgetInstances[0];
                        unset($singleWidgetInstances[0]);
                    }

                    // Move _multiwidget to end of array for uniformity.
                    if (isset($singleWidgetInstances['_multiwidget'])) {
                        $multiwidget = $singleWidgetInstances['_multiwidget'];

                        unset($singleWidgetInstances['_multiwidget']);

                        $singleWidgetInstances['_multiwidget'] = $multiwidget;
                    }

                    // Update option with new widget.
                    update_option('widget_' . $idBase, $singleWidgetInstances);

                    // Assign widget instance to sidebar
                    // Which sidebars have which widgets, get fresh every time.
                    $sidebarsWidgets = get_option('sidebars_widgets') ?: array();
                    // Use ID number from new widget instance.
                    $newInstanceId = $idBase . '-' . $newInstanceIdNumber;
                    // Add new instance to sidebar.
                    $sidebarsWidgets[$useSidebarId][] = $newInstanceId;
                    // Save the amended data.
                    update_option('sidebars_widgets', $sidebarsWidgets);

                    // Success message.
                    if ($sidebarAvailable) {
                        $widgetMessageType = 'success';
                        $widgetMessage     = esc_html__('Imported', 'qibla-importer');
                    } else {
                        $widgetMessageType = 'warning';
                        $widgetMessage     = esc_html__('Imported to Inactive', 'qibla-importer');
                    }
                endif;

                // Result for widget instance.
                // Widget name or ID if name not available (not supported by site).
                $results[$sidebarId]['widgets'][$widgetInstanceId]['name'] = isset($availableWidgets[$idBase]['name']) ?
                    $availableWidgets[$idBase]['name'] :
                    $idBase;

                // Show "No Title" if widget instance is untitled.
                $results[$sidebarId]['widgets'][$widgetInstanceId]['title']        = ! empty($widget['title']) ?
                    $widget['title'] :
                    esc_html__('No Title', 'qibla-importer');
                $results[$sidebarId]['widgets'][$widgetInstanceId]['message_type'] = $widgetMessageType;
                $results[$sidebarId]['widgets'][$widgetInstanceId]['message']      = $widgetMessage;
            endforeach;
        endforeach;
    }
}
