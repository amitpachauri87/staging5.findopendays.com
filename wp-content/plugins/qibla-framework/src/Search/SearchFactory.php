<?php
/**
 * SearchBySettings
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

namespace QiblaFramework\Search;

use QiblaFramework\Form\Factories\FieldFactory;
use QiblaFramework\Search\Field\Geocoded as GeocodedField;
use QiblaFramework\Search\Field\Search as SearchField;
use QiblaFramework\Search\Field\Taxonomy;

/**
 * Class SearchBySettings
 *
 * @since   2.0.0
 * @package QiblaFramework\Search
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class SearchFactory
{
    /**
     * Type
     *
     * @since 2.0.0
     *
     * @var string The search type to create
     */
    private $type;

    /**
     * Types
     *
     * @since 2.0.0
     *
     * @var array The post types to search in
     */
    private $types;

    /**
     * ID
     *
     * @since 2.0.0
     *
     * @var string The ID attribute value for search form
     */
    private $id;

    /**
     * Method
     *
     * @since 2.0.0
     *
     * @var string The Request Method for form. May be POST or GET
     */
    private $method;

    /**
     * SearchFactory constructor
     *
     * @since 2.0.0
     *
     * @param string $type   The search type to create.
     * @param array  $types  The post types to search in.
     * @param string $id     The ID attribute value for search form.
     * @param string $method The Request Method for form. May be POST or GET.
     */
    public function __construct($type, array $types, $id, $method)
    {
        if (! is_string($type) || '' === $type) {
            throw new \InvalidArgumentException('Type for search form is not a valid string or empty.');
        }

        if (! is_string($id) || '' === $id) {
            throw new \InvalidArgumentException('The ID for the search form is not a valid string or empty.');
        }

        if (! is_string($method) || '' === $method) {
            throw new \InvalidArgumentException('The Method for the search form is not a valid string or emtpy.');
        }

        $this->type   = $type;
        $this->id     = $id;
        $this->types  = $types;
        $this->method = $method;
    }

    /**
     * Create
     *
     * @since 2.0.0
     *
     * @return FormTemplate The instance of the form template to use.
     */
    public function create()
    {
        $fields       = array();
        $fieldFactory = new FieldFactory();

        switch ($this->type) :
            case 'simple':
                $fields = array(
                    new SearchField($fieldFactory, array(), true),
                );
                break;

            case 'geocoded':
                $fields = array(
                    new SearchField($fieldFactory, array(), true),
                    new GeocodedField($fieldFactory, 'locations', array()),
                );
                break;

            case 'combo':
                $fields = array(
                    new SearchField($fieldFactory, array(), true, array(
                        'data-exclude' => 'term',
                    )),
                    new GeocodedField($fieldFactory, 'locations', array()),
                    new Taxonomy($fieldFactory, get_taxonomy('listing_categories')),
                );
                break;
        endswitch;

        // Build the form template.
        $search = new FormTemplate($this->id, $this->types, $fields, $this->type, $this->method);

        return $search;
    }
}
