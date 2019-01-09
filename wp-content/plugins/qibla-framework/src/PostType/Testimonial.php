<?php
namespace QiblaFramework\PostType;

use QiblaFramework\Functions as F;

/**
 * Testimonial Post Type
 *
 * @since      1.0.0
 * @package    QiblaFramework\PostType
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
 * Testimonial
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class Testimonial extends AbstractPostType
{
    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        parent::__construct(
            'testimonial',
            esc_html__('testimonial', 'qibla-framework'),
            esc_html__('Testimonials', 'qibla-framework'),
            array(
                // Filter the location since other plugins may hide this or this may hide other menu items.
                'menu_position' => apply_filters('qibla_post_type_menu_position_testimonial', 25),
                'menu_icon'     => 'dashicons-groups',
                'has_archive'   => false,
                'supports'      => array('title', 'editor', 'thumbnail'),
                'description'   => '',
            )
        );
    }

    /**
     * Add Column Header
     *
     * @since  1.0.0
     *
     * @param array $columns The registered columns for this post type.
     *
     * @return array The filtered columns
     */
    public function columns($columns)
    {
        // Unset The date Column.
        unset(
            $columns['date'],
            $columns['author']
        );

        // Add custom columns.
        $columns = F\arrayInsertInPos(array(
            'thumbnail' => esc_html__('Avatar', 'qibla-framework'),
        ), $columns, 1);

        return $columns;
    }

    /**
     * Custom Column Content
     *
     * @since  1.0.0
     *
     * @param string $columnName The current custom column header
     * @param int    $postID     The current post id for which manage the column content
     *
     * @return void
     */
    public function customColumn($columnName, $postID)
    {
        switch ($columnName) {
            case 'thumbnail':
                echo F\ksesImage(get_the_post_thumbnail($postID, array(64, 64)));
                break;
            default:
                break;
        }
    }
}
