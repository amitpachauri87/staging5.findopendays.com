<?php
/**
 * Taxonomy Tags
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

namespace AppMapEvents\Taxonomy;

use QiblaFramework\Taxonomy\AbstractTaxonomy;

/**
 * Class Tags
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class Tags extends AbstractTaxonomy
{
    /**
     * @inheritdoc
     */
    protected static $defaultRewriteRule = array(
        'slug' => 'event-tags',
    );
    
    /**
     * Construct
     *
     * Cannot be displayed with checkboxes because of https://core.trac.wordpress.org/ticket/28033
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        parent::__construct(
            'event_tags',
            'events',
            esc_html__('Tag', 'qibla-events'),
            esc_html__('Tags', 'qibla-events'),
            array(
                'show_tagcloud' => false,
                'hierarchical'  => false,
                'capabilities'  => array(
                    'manage_terms' => 'manage_listings',
                    'edit_terms'   => 'manage_listings',
                    'assign_terms' => 'assign_terms',
                ),
            )
        );
    }

    /**
     * Add Column Header
     *
     * @since 1.1.0
     *
     * @param array $columns The registered columns for this taxonomy.
     *
     * @return array The filtered columns
     */
    public function columns($columns)
    {
        // Add custom columns.
        $columns = wp_parse_args($columns, array(
            'cb'               => isset($columns['cb']) ? isset($columns['cb']) : '<input type="checkbox" />',
            'name'             => isset($columns['name']) ? $columns['name'] : __('Name'),
            'description'      => isset($columns['description']) ? $columns['description'] : __('Description'),
            'slug'             => isset($columns['slug']) ? $columns['slug'] : __('Slug'),
            'tax_terms_groups' => sprintf(
                '<span class="in-groups">%s</span>',
                esc_html_x('Group', 'admin-table-list', 'qibla-events')
            ),
            'tax_relation'     => sprintf(
                '<span class="relation">%s</span>',
                esc_html_x('Relations', 'admin-table-list', 'qibla-events')
            ),
            'posts'            => isset($columns['posts']) ? $columns['posts'] : __('Posts'),
        ));

        return $columns;
    }

    /**
     * Custom Column Content
     *
     * @since 1.1.0
     *
     * @param string $columnName The current custom column header
     * @param int    $termID     The current term id for which manage the column content
     *
     * @return void
     */
    public function customColumn($content, $columnName, $termID)
    {
        switch ($columnName) :
            case 'tax_terms_groups':
                $content = \QiblaFramework\Functions\getTermMeta('_qibla_tb_taxonomy_term_groups_title', $termID);
                if ('' !== $content && 'none' !== $content) {
                    echo esc_html($content);
                }
                break;

            case 'tax_relation':
                $content = \QiblaFramework\Functions\getTermMeta('_qibla_tb_taxonomy_term_relation', $termID);
                if (is_array($content) && ! empty($content)) {
                    foreach ($content as $c) {
                        echo sprintf('%s<br>', esc_html($c));
                    }
                } else {
                    echo esc_html($content);
                }
                break;
            default:
                break;
        endswitch;
    }
}
