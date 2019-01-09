<?php
/**
 * Abstract PostType
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

namespace QiblaFramework\PostType;

use QiblaFramework\Admin\PermalinkSettings;
use QiblaFramework\Functions as F;

/**
 * Class Abstract PostType
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class AbstractPostType
{
    /**
     * Type
     *
     * @since  1.0.0
     *
     * @var string The type of the current post type
     */
    protected $type;

    /**
     * Arguments
     *
     * @since  1.0.0
     *
     * @var array A list of post type arguments
     */
    protected $args;

    /**
     * Build the Rewrite Rule arguments
     *
     * @since  1.5.0
     *
     * @return array|null The arguments or null to use the default rewrite options
     */
    protected function buildRewriteRuleArg()
    {
        $key             = sanitize_key($this->type);
        $permalinkOption = F\getOption(PermalinkSettings::OPTION_NAME);
        $rewrite         = null;

        if (! empty($permalinkOption["permalink_{$key}_cpt"])) {
            $rewrite = array(
                'slug' => $permalinkOption["permalink_{$key}_cpt"],
            );
        }

        return $rewrite;
    }

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param string $type     The type of the current post.
     * @param string $singular The singular name of the post type.
     * @param string $plural   The plural name of the post type.
     * @param array  $args     The arguments of the current post type. Optional.
     */
    protected function __construct($type, $singular, $plural, $args = array())
    {
        $this->type = $type;
        $this->args = wp_parse_args($args, array(
            'label'       => $plural,
            'labels'      => array(
                'name'                  => $plural,
                'singular_name'         => $singular,
                'add_new'               => sprintf(esc_html__('Add %s', 'qibla-framework'), $singular),
                'add_new_item'          => sprintf(esc_html__('New %s', 'qibla-framework'), $singular),
                'edit_item'             => sprintf(esc_html__('Edit %s', 'qibla-framework'), $singular),
                'new_item'              => sprintf(esc_html__('New %s', 'qibla-framework'), $singular),
                'view_item'             => sprintf(esc_html__('View %s', 'qibla-framework'), $singular),
                'search_item'           => sprintf(esc_html__('Search %s', 'qibla-framework'), $plural),
                'not_found'             => sprintf(esc_html__('%s not found', 'qibla-framework'), $plural),
                'not_found_in_trash'    => sprintf(esc_html__('%s not found in trash', 'qibla-framework'), $singular),
                'parent_item_colon'     => sprintf(esc_html__('Parent %s', 'qibla-framework'), $singular),
                'all_items'             => sprintf(esc_html__('All %s', 'qibla-framework'), $plural),
                'archives'              => sprintf(esc_html__('%s Archives', 'qibla-framework'), $plural),
                'insert_into_item'      => sprintf(esc_html__('Insert into %s', 'qibla-framework'), $singular),
                'uploaded_to_this_item' => sprintf(esc_html__('Uploaded to this %s', 'qibla-framework'), $singular),
            ),
            'description' => '',
            'public'      => true,
            'supports'    => array('title', 'editor', 'author', 'thumbnail'),
            'rewrite'     => $this->buildRewriteRuleArg(),
        ));
    }

    /**
     * Get Args
     *
     * @since  1.0.0
     *
     * @return array The arguments of the current post type
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Get Type
     *
     * @since  1.0.0
     *
     * @return string The type of the current post type
     */
    public function getType()
    {
        return $this->type;
    }
}
