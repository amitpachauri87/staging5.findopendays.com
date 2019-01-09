<?php
namespace QiblaFramework\Admin\Metabox;

use QiblaFramework\Form\Interfaces\Fields;
use QiblaFramework\Functions as F;
use QiblaFramework\ListingsContext\Types;
use QiblaFramework\Plugin;

/**
 * Meta-box Sidebar
 *
 * @since      1.0.0
 * @package    QiblaFramework\Admin\Metabox
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
 * Class Sidebar
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Sidebar extends AbstractMetaboxForm
{
    /**
     * Not Allowed Page Templates
     *
     * @since 1.0.0
     *
     * @var array The list of the page templates not allowed to show sidebar metabox
     */
    private static $notAllowedPageTemplates = array(
        'templates/events-search.php',
        'templates/homepage.php',
        'templates/homepage-fullwidth.php',
    );

    /**
     * Sidebar Constructor
     *
     * @inheritdoc
     */
    public function __construct(array $args = array())
    {
        $types = new Types();

        parent::__construct(wp_parse_args($args, array(
            'id'       => 'sidebar',
            'title'    => esc_html__('Sidebar', 'qibla-framework'),
            'screen'   => array_merge(array('page', 'product', 'post'), $types->types()),
            'callback' => array($this, 'callBack'),
            'context'  => 'side',
            'priority' => 'low',
        )));

        parent::setFields(include Plugin::getPluginDirPath('/inc/metaboxFields/sidebarFields.php'));
    }

    /**
     * Exclude Metabox
     *
     * @since 1.6.1
     * @since 2.0.0 Add $post parameter to allow to check of data related to the post from which exclude the sidebar.
     *
     * @param \WP_Post $post The current post.
     *
     * @return bool True to exclude, false otherwise
     */
    public function exclude(\WP_Post $post = null)
    {
        $post = get_post($post);

        return (
            // Exclude from page_for_posts.
            // @todo check end remove. sidebar position is "none" in wpml translation page.
            //$post->ID === intval(get_option('page_for_posts')) ||
            // Exclude from page templates.
            in_array(get_page_template_slug($post), self::$notAllowedPageTemplates, true)
        );
    }

    /**
     * Display Callback
     *
     * @since  1.0.0
     *
     * @param Fields $field The current field
     *
     * @return void
     */
    public function displayField(Fields $field)
    {
        $types = new Types();

        if ('qibla_mb_sidebar_position' === $field->getType()->getArg('name')) {
            if ($types->isListingsType(get_post_type())) {
                // Remove the display 'none' option for listings post type.
                // Why? I don't know, seriously I'll keep it.
                $options = $field->getType()->getArg('options');
                unset($options['none']);

                $field->getType()->setArg('options', $options);

                // Set the default value to 'right' since the default value for sidebar is set to 'none'
                // And we had removed it.
                $field->getType()->setArg('value', F\getPostMeta('_qibla_mb_sidebar_position', 'right'));
            }
        }
    }
}
