<?php
/**
 * Events General Tax Field
 *
 * @since      1.0.0
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

namespace AppMapEvents\Filter;

use QiblaFramework\Filter\FilterFieldInterface;
use QiblaFramework\Form\Factories\FieldFactory;
use QiblaFramework\Functions as F;
use QiblaFramework\Geo\AddressFactory;
use QiblaFramework\ListingsContext\Types;
use QiblaFramework\Request\Nonce;
use QiblaFramework\ValueObject\QiblaString;

/**
 * Class BBtripGeneralTaxField
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class EventsGeneralTaxField extends EventsDateTaxOptions implements FilterFieldInterface
{
    /**
     * Field Name
     *
     * @since 1.0.0
     *
     * @var string The field name
     */
    private $name;

    /**
     * Field
     *
     * @since 1.0.0
     */
    private $field;

    /**
     * EventsGeneralTaxField constructor.
     *
     * @since 1.0.0
     *
     * @param string $name Name attribute for the input field.
     * @param string $type The type to use with the field.
     *
     * @throws \Exception
     */
    public function __construct($name, $type)
    {
        $factory     = new FieldFactory();
        $this->name  = $name;
        $this->field = $factory->base($this->args($name, $type));
    }

    /**
     * Field Title
     *
     * @since 1.0.0
     *
     * @return string The field title
     */
    public function fieldTitle()
    {
        $types  = new Types();
        $tax    = get_taxonomy($this->taxFromFilterName());
        $string = new QiblaString(F\ksesPost($tax->label));

        return '<h3 class="dlfilter-field-title">' .
               $string->fromSlugToLabel()
                      ->replace($types->types(), '')
                      ->val() .
               '</h3>';
    }

    /**
     * @inheritdoc
     */
    public function type()
    {
        return $this->field()->getType();
    }

    /**
     * @inheritdoc
     */
    public function field()
    {
        if ('event_dates' === $this->taxFromFilterName()) {
            $this->calendarScripts();
        }

        return $this->field;
    }

    /**
     * @return mixed
     */
    private function taxFromFilterName()
    {
        return str_replace(array('qibla_', '_filter'), '', $this->name);
    }

    /**
     * Geocode
     *
     * Create the geocode value from the request.
     *
     * @since 1.0.0
     *
     * @return string The address value
     */
    private static function geocode()
    {
        try {
            $geocoded = AddressFactory::createFromPostRequest(new Nonce('geocoded'));
            $geocoded = $geocoded->address();
        } catch (\Exception $e) {
            $geocoded = '';
        }

        return strtolower($geocoded);
    }

    /**
     * Geocode Value
     *
     * @since 1.0.0
     *
     * @param $value
     *
     * @return string
     */
    private static function geocodedValue($value)
    {
        $geocoded = self::geocode();

        if ($geocoded) {
            $value = $geocoded;
        }

        if (is_string($value)) {
            $value = strtolower($value);
        }

        return $value;
    }

    /**
     * Is Location Taxonomy Field
     *
     * @since 1.0.0
     *
     * @return bool
     */
    private function isLocationField()
    {
        return (false !== strpos($this->name, '_locations_'));
    }

    /**
     * Is Location Taxonomy Field
     *
     * @since 1.0.0
     *
     * @return bool
     */
    private function isAmanitiesField()
    {
        return (false !== strpos($this->name, '_tags_'));
    }

    /**
     * Retrieve value
     *
     * @since 1.0.0
     *
     * @return array|string Depending on the content may be a string or an array.
     */
    private function value()
    {
        $qObj = get_queried_object();

        // @codingStandardsIgnoreLine
        $value = F\filterInput($_POST, $this->name, FILTER_SANITIZE_STRING) ?: '';

        if (! $value && is_array($value)) {
            // @codingStandardsIgnoreLine
            $value = F\filterInput($_POST, $this->name, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?: array();
        }

        if (! $value) {
            $value = $qObj instanceof \WP_Term && is_tax($qObj->taxonomy) ? $qObj->slug : '';
        }

        if (is_string($value)) {
            $value = strtolower($value);
        }

        if ($this->isLocationField()) {
            $value = self::geocodedValue($value);
        }

        return $value;
    }

    /**
     * Options
     *
     * @since 1.0.0
     *
     * @param string $type The type to create.
     *
     * @return array The options list
     * @throws \Exception
     */
    private function options($type)
    {
        $options = F\getTermsList(array(
            'taxonomy'   => $this->taxFromFilterName(),
            'hide_empty' => false,
        ));

        /**
         * All Categories Filter Label
         *
         * @since 1.6.1
         *
         * @param string $label The label of the filter to use as option to select all categories.
         */
        $allOptionsLabel = esc_html__('All', 'qibla-events');

        // Switch taxonomy.
        switch ($this->taxFromFilterName()) {
            case 'event_tags':
                $options = $this->filterOptionsBasedOnTaxonomy();
                break;
            case 'event_categories':
                $allOptionsLabel = esc_html__('All Categories', 'qibla-events');
                break;
            case 'event_locations' :
                $allOptionsLabel = esc_html__('All Locations', 'qibla-events');
                break;
            case 'event_dates':
                $allOptionsLabel = '';
                $options         = $this->optionsDates();
                break;
        }

        if ('select' === $type && '' !== $allOptionsLabel) {
            $options = array('all' => $allOptionsLabel) + $options;
        }

        return $options;
    }

    /**
     * Filter Options Based On Taxonomy
     *
     * @since 1.0.0
     *
     * @return array The options list
     * @throws \Exception
     */
    private function filterOptionsBasedOnTaxonomy()
    {
        // Get the current queried object to use for comparison.
        $currObj = get_queried_object();

        if (! $currObj instanceof \WP_Term ||
            (isset($currObj->taxonomy) && 'event_categories' !== $currObj->taxonomy)
        ) {
            return F\getHierarchyTermsList(array(
                'taxonomy'     => 'event_tags',
                'hide_empty'   => false,
                'show_groups'  => true,
            ));
        }

        $list = F\getTermsByTermBasedOnContext(get_terms('event_tags'), 'event_categories');

        return $list;
    }

    /**
     * Retrieve arguments for field
     *
     * @since 1.0.0
     *
     * @param $name
     * @param $type
     *
     * @return array
     * @throws \Exception
     */
    private function args($name, $type)
    {
        $qObj     = get_queried_object();
        $taxonomy = $qObj instanceof \WP_Term && is_tax($qObj->taxonomy) ? $qObj->taxonomy : '';

        $args = array(
            'type'            => $type,
            'name'            => $this->name,
            'value'           => $this->value(),
            'container_class' => array('dl-field', "dl-field--{$type}"),
            'options'         => $this->options($type),
            'attrs'           => array(),
        );

        if ($taxonomy && $this->isLocationField() && strpos('locations', $taxonomy)) {
            $args['attrs']['data-placeholder'] = self::geocodedValue(
                $this->value()
            );
            $args['attrs']['data-taxonomy']    = $this->taxFromFilterName();
        }

        if ($this->isAmanitiesField()) {
            $args['exclude_all'] = true;
        }

        if ('select' === $type) {
            $args['exclude_none']   = true;
            $args['select2']        = true;
            $args['select2_theme']  = 'qibla-minimal';
            $args['attrs']['class'] = 'dllistings-ajax-filter-trigger';
        }

        return $args;
    }
}
