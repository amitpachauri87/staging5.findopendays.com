<?php
/**
 * Search
 *
 * @since      1.1.0
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

namespace AppMapEvents\Search\Field;

use QiblaFramework\Functions as F;
use QiblaFramework\Request\Nonce;
use QiblaFramework\Form\Factories\FieldFactory;
use QiblaFramework\Search\Field\SearchFieldInterface;
use AppMapEvents\TemplateEngine\Engine as TEngine;
use QiblaFramework\TemplateEngine\TemplateInterface;
use QiblaFramework\Utils\Json\Encoder;

/**
 * Class Search
 *
 * @since  1.1.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
final class Search implements SearchFieldInterface, TemplateInterface
{
    /**
     * Field Slug
     *
     * @since 1.7.0
     *
     * @var string The field slug
     */
    const SLUG = 'search';

    /**
     * Fields
     *
     * @since 1.7.0
     *
     * @var SearchFieldInterface The field instance
     */
    private $field;

    /**
     * Autocomplete
     *
     * If the search field must use autocomplete or not.
     *
     * @since 1.7.0
     *
     * @var bool True to use autocomplete feature, false otherwise
     */
    private $autocomplete;

    /**
     * Json Encoder Factory
     *
     * @since 2.0.0
     *
     * @return Encoder Instance of the class
     */
    private function jsonEncoderFactory()
    {
        /**
         * Search Json encoder factory
         *
         * To filter the arguments to be passed to the encoder.
         *
         * @since 2.1.0
         */
        $args = apply_filters('qibla_search_json_encoder_factory', array(
            'type'             => 'events',
            'containers'       => array(
                'taxonomies' => 'event_categories',
            ),
            'initialContainer' => 'event_categories'
        ));

        return new Encoder($args);
    }

    /**
     * Build Attributes for Form
     *
     * @since 1.7.0
     *
     * @return array The list of the attributes for the form markup.
     */
    private function attrs()
    {
        // Set default attributes.
        $attrs = array(
            'class'       => array(
                'dlsearch__input',
            ),
            'placeholder' => F\getThemeOption('search', 'placeholder'),
        );

        // Set if the search must use autocomplete.
        if ($this->autocomplete) {
            $attrs['class'][]           = 'use-autocomplete';
            $attrs['data-autocomplete'] = $this->jsonEncoderFactory()->prepare()->json();
        }

        return $attrs;
    }

    /**
     * Search constructor
     *
     * @since 1.7.0
     *
     * @param FieldFactory $fieldFactory The instance of the field factory to use to build the field.
     * @param array        $fieldArgs    Additional arguments for the field.
     * @param bool         $autocomplete If the search must use autocomplete feature. Default to false.
     * @param array        $args         Additional args to set for field.
     */
    public function __construct(
        FieldFactory $fieldFactory,
        array $fieldArgs = array(),
        $autocomplete = false,
        array $args = array()
    ) {
        $this->autocomplete = $autocomplete;
        $this->field        = $fieldFactory->base(wp_parse_args($fieldArgs, array(
            'type'      => 'search',
            'name'      => 's',
            'id'        => 'dlsearch_input',
            'container' => '',
            'attrs'     => array_merge($this->attrs(), $args),
        )));
    }

    /**
     * @inheritDoc
     */
    public function slug()
    {
        return self::SLUG;
    }

    /**
     * @inheritDoc
     */
    public function field()
    {
        return $this->field;
    }

    /**
     * If use autocomplete
     *
     * @since 1.7.0
     *
     * @return bool True if search field use autocomplete or false otherwise
     */
    public function useAutocomplete()
    {
        return $this->autocomplete;
    }

    /**
     * @inheritDoc
     */
    public function doField()
    {
        $this->tmpl($this->getData());
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $nonce = new Nonce('dlsearch_input');

        if ($this->autocomplete) {
            $taxNonce = new Nonce('dlsearch_taxonomy');

            return (object)array(
                // The field.
                'field'         => $this->field,

                // Hidden field for filter by taxonomy.
                'taxonomy'      => 'event_categories',
                'taxonomyNonce' => $taxNonce->field(),
                'searchType'    =>   F\getThemeOption('search', 'type', true),

                // Nonce Field.
                'nonce'         => $nonce->field(),
            );
        }

        return (object)array(
            // The field.
            'field' => $this->field,

            // Nonce Field.
            'nonce' => $nonce->field(),
        );
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new TEngine('search_events_input_field', $data, '/views/search/field/search.php');
        $engine->render();
    }
}
