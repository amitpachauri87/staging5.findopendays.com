<?php
/**
 * Geocoded
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
use QiblaFramework\Form\Factories\FieldFactory;
use QiblaFramework\Request\Nonce;
use QiblaFramework\TemplateEngine\TemplateInterface;
use QiblaFramework\Search\Field\SearchFieldInterface;
use QiblaFramework\Taxonomy\Term;
use QiblaFramework\Admin\PermalinkSettings;
use AppMapEvents\TemplateEngine\Engine as TEngine;

/**
 * Class Geocoded
 *
 * @since  1.1.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
final class Geocoded implements SearchFieldInterface, TemplateInterface
{
    /**
     * Field Slug
     *
     * @since 1.1.0
     *
     * @var string The field slug
     */
    const SLUG = 'geocoded';

    /**
     * Data
     *
     * The data for the view
     *
     * @since 1.1.0
     *
     * @var \stdClass The data for the view
     */
    private $data;

    /**
     * Fields
     *
     * @since 1.1.0
     *
     * @var SearchFieldInterface The field instance
     */
    private $field;

    /**
     * Taxonomy
     *
     * @since 1.1.0
     *
     * @var string The taxonomy name to use to retrieve the terms to build the geocoded suggestions
     */
    private $taxonomy;

    /**
     * Suggestion Nav
     *
     * The method add an action to wp_footer to print the locations terms data.
     * Since the suggestions works only if js is enabled, we don't need to print any markup.
     *
     * @since 1.1.0
     *
     * @return void
     */
    public function nav()
    {
        // Retrieve the terms.
        $items = get_terms(array(
            'taxonomy' => $this->taxonomy,
        ));

        if ($items instanceof \WP_Error) {
            throw new \DomainException($items->get_error_message());
        }

        // Create the data.
        $list = array();
        foreach ($items as $item) {
            $term              = new Term($item);
            $list[$item->name] = (object)array(
                'label' => $term->name(),
                'slug'  =>  $term->slug(),
                'icon'  => 'la la-map-marker',
            );
        }

        // Set the action to print the data.
        add_action('wp_footer', function () use ($list) {
            printf(
                "<script type=\"text/javascript\">/* <![CDATA[ */\n %s \n/* ]]> */</script>",
                'var dlnavGeocodeSuggestions = ' . wp_json_encode($list) . ';'
            );
        });
    }

    /**
     * Geocoded constructor
     *
     * Keeping in mind that the `$taxonomy` is not check to know if it's a suitable taxonomy to use as locations
     * container. So is up to you to pass the correct taxonomy.
     *
     * @since 1.1.0
     *
     * @throws \InvalidArgumentException If the passed taxonomy doesn't exists.
     *
     * @param FieldFactory $fieldFactory The instance of the field factory to use to build the field.
     * @param string       $taxonomy     The taxonomy from which retrieve the terms to build the navigation.
     * @param array        $fieldArgs    Additional arguments for the field.
     */
    public function __construct(FieldFactory $fieldFactory, $taxonomy, array $fieldArgs = array())
    {
        if (! taxonomy_exists($taxonomy)) {
            throw new \InvalidArgumentException(
                'Invalid taxonomy when build ' . __CLASS__ . ', taxonomy doesn\'t exist'
            );
        }

        // Default tax permalink.
        $defaultPermalink = isset(get_taxonomy($taxonomy)->rewrite['slug']) ?
            get_taxonomy($taxonomy)->rewrite['slug'] : $taxonomy;

        // Retrieve the permalink options.
        $permalinkOptions = F\getOption(PermalinkSettings::OPTION_NAME);
        $taxPermalink     = ! empty($permalinkOptions['permalink_' . $taxonomy . '_tax']) ?
            $permalinkOptions['permalink_' . $taxonomy . '_tax'] : $defaultPermalink;

        $this->data     = null;
        $this->taxonomy = $taxonomy;
        $this->field    = $fieldFactory->base(wp_parse_args($fieldArgs, array(
            'type'      => 'text',
            'name'      => 'geocoded',
            'container' => '',
            'attrs'     => array(
                'class'             => array(
                    'dlsearch__input',
                    'is-geocoded',
                ),
                'placeholder'       => F\getThemeOption('search', 'geocoded_placeholder', true),
                'data-taxonomy'     => $taxonomy,
                'data-taxpermalink' => $taxPermalink,
                'autocomplete'      => 'off',
            ),
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
    public function doField()
    {
        $this->tmpl($this->getData());

        $this->nav();
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
        $nonce = new Nonce('geocoded');

        $this->data = array(
            // The field.
            'field'      => $this->field,
            'noncefield' => $nonce->field(),
        );

        return (object)$this->data;
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new TEngine('search_geocoded_field', $data, '/views/search/field/geocoded.php');
        $engine->render();
    }
}