<?php
/**
 * Taxonomy
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

use QiblaFramework\Form\Interfaces\Fields;
use QiblaFramework\Functions as F;
use QiblaFramework\Form\Factories\FieldFactory;
use QiblaFramework\Request\Nonce;
use QiblaFramework\Search\Field\SearchFieldInterface;
use AppMapEvents\TemplateEngine\Engine as TEngine;
use QiblaFramework\TemplateEngine\TemplateInterface;

/**
 * Class Taxonomy
 *
 * @since  1.1.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class Taxonomy implements SearchFieldInterface, TemplateInterface
{
    /**
     * Slug
     *
     * @since 1.1.0
     *
     * @var string The slug of the field
     */
    const SLUG = 'taxonomy';

    /**
     * Taxonomy
     *
     * @since 1.1.0
     *
     * @var string The taxonomy name for which retrieve the terms
     */
    private $taxonomy;

    /**
     * Field
     *
     * @since 1.1.0
     *
     * @var Fields The field instance used internally
     */
    private $field;

    /**
     * Taxonomy constructor
     *
     * @since 1.1.0
     *
     * @throws \InvalidArgumentException In case the taxonomy isn't a valid string or doesn't exists.
     * @throws \Exception                If the terms cannot be retrieved correctly.
     *
     * @param FieldFactory $fieldFactory The instance of the field factory.
     * @param \WP_Taxonomy $taxonomy     The taxonomy from which retrieve the terms.
     */
    public function __construct(FieldFactory $fieldFactory, \WP_Taxonomy $taxonomy)
    {
        $options = array_merge(
        // Translators the %1 is the name of the taxonomy.
            array('all' => sprintf(esc_html__('All %s'), $taxonomy->label)),
            F\getTermsList(array(
                'taxonomy' => $taxonomy->name,
            ))
        );

        $this->taxonomy = $taxonomy;
        $this->field    = $fieldFactory->base(array(
            'name'         => "qibla_{$taxonomy->name}_filter",
            'type'         => 'select',
            'exclude_none' => true,
            'options'      => $options,
        ));
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
    public function doField()
    {
        $this->tmpl($this->getData());
    }

    /**
     * @inheritDoc
     */
    public function field()
    {
        return $this->field;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $nonce = new Nonce('dlsearch_taxonomy');

        return (object)array(
            // The field.
            'field'    => $this->field,
            // Taxonomy.
            'taxonomy' => $this->taxonomy->name,
            // Nonce Field.
            'nonce'    => $nonce->field(),
        );
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new TEngine('search_events_taxonomy_field', $data, '/views/search/field/taxonomy.php');
        $engine->render();
    }
}
