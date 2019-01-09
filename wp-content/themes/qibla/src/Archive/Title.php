<?php
/**
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    Qibla\Archive
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    GNU General Public License, version 2
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

namespace Qibla\Archive;

use Qibla\Functions as F;
use Qibla\TemplateEngine\Engine as TEngine;
use Qibla\TemplateEngine\TemplateInterface;

/**
 * Class Title
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package Qibla\Archive
 */
class Title implements TemplateInterface
{
    /**
     * Arguments
     *
     * @since  1.0.0
     *
     * @var array The arguments for the instance
     */
    protected $args;

    /**
     * ArchiveTitle constructor
     *
     * @since 1.0.0
     *
     * @param array $args The arguments for the instance
     */
    public function __construct(array $args = array())
    {
        $this->args = wp_parse_args($args, array(
            'screen_reader_text' => false,
        ));
    }

    /**
     * Set Hooks
     *
     * @since 2.0.0
     *
     * @return void
     */
    public function setHooks()
    {
        // Generally used within the singular post where there is the jumbo-tron and we want to
        // show the title on it but not lost the markup hierarchy nor the semantic of the page.
        // The same for archives.
        if ($this->args['screen_reader_text'] &&
            ! has_filter('qibla_scope_attribute', array($this, 'screenReaderHeaderScopeModifier')) &&
            ! F\isDateArchive() &&
            F\isJumbotronAllowed()
        ) {
            add_filter('qibla_scope_attribute', array($this, 'screenReaderHeaderScopeModifier'), 0, 5);
        }
    }

    /**
     * Show the archive title based on the query, plus the term description
     *
     * @since 1.0.0
     *
     * @return \stdClass The data for the view
     */
    public function title()
    {
        // Try to get the title of the page for posts option.
        $pageForPosts = intval(get_option('page_for_posts'));
        // Shop page may not be set.
        $shopPostID = intval(get_option('woocommerce_shop_page_id'));

        if (is_home() && $pageForPosts) {
            $archiveTitle = get_the_title($pageForPosts);
        } elseif (is_search()) {
            // @codingStandardsIgnoreLine
            $taxonomyFilter = F\filterInput($_POST, 'qibla_taxonomy_filter_taxonomy', FILTER_SANITIZE_STRING);
            $value          = F\filterInput($_POST, 'qibla_' . $taxonomyFilter . '_filter', FILTER_SANITIZE_STRING);
            $geocoded       = F\filterInput($_POST, 'geocoded', FILTER_SANITIZE_STRING);

            if (! $geocoded && ! $taxonomyFilter) {
                $geocoded = isset($_POST['geocoded']) ? $_POST['geocoded']['address'] : '';
            }

            if ($geocoded) {
                $value = $geocoded;
            }

            if ($value) {
                $value = ucfirst(str_replace('-', ' ', $value));
            } else {
                $value = get_search_query();
            }
            $archiveTitle = sprintf(
            // Translators: The %s is the query search term.
                esc_html__('Search Result: %s', 'qibla'),
                '<span class="queried-term">' . $value . '</span>'
            );
        } elseif (F\isShop()) {
            $archiveTitle = 0 < $shopPostID ? get_post($shopPostID)->post_title : esc_html__('Shop', 'qibla');
        } else {
            $archiveTitle = get_the_archive_title();
        }

        return $archiveTitle;
    }

    /**
     * @inheritdoc
     *
     * @since  1.0.0
     */
    public function getData()
    {
        // Data for template.
        $data = new \stdClass();

        $data->archiveTitle       = $this->title();
        $data->archiveDescription = F\getTheArchiveDescription();

        return $data;
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new TEngine('archive_title', $data, 'views/archives/title.php');
        $engine->render();
    }

    /**
     * Scope Title Modifier Filter
     *
     * Filter the scope string before it is returned.
     *
     * @since 1.0.0
     *
     * @param string $upxscope The scope prefix. Default 'upx'.
     * @param string $element  The current element of the scope.
     * @param string $block    The custom block scope. Default empty.
     * @param string $scope    The default scope prefix. Default 'upx'.
     * @param string $attr     The attribute for which the value has been build.
     *
     * @return string The filtered $upxscope parameter
     */
    public function screenReaderHeaderScopeModifier($upxscope, $element, $block, $scope, $attr)
    {
        // Only for article__title.
        if ('' === $block && 'header' === $element && $this->args['screen_reader_text']) {
            // Don't use a modifier here for portability.
            $upxscope .= ' screen-reader-text';
        }

        // Remove after done. We don't want to execute always the same filter if title doesn't
        // set explicitly the use of the screen-reader-text.
        remove_filter('qibla_scope_attribute', array($this, 'screenReaderHeaderScopeModifier'), 0, 5);

        return $upxscope;
    }

    /**
     * Archive Title Filter
     *
     * @since 2.0.0
     *
     * @return void
     */
    public static function archiveTitleFilter()
    {
        $instance = new static(array(
            'screen_reader_text' => true,
        ));

        $instance->setHooks();
        $instance->tmpl($instance->getData());
    }
}
