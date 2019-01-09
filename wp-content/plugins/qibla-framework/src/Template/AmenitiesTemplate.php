<?php
/**
 * Class Front-end Term Amenities
 *
 * @since      1.0.0
 * @package    QiblaFramework\Front\Settings
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa
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

namespace QiblaFramework\Template;

use QiblaFramework\Debug;
use QiblaFramework\Functions as F;
use QiblaFramework\IconsSet;
use QiblaFramework\TemplateEngine as T;

/**
 * Class Amenities
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
final class AmenitiesTemplate implements T\TemplateInterface
{
    /**
     * WP Post
     *
     * @since 1.7.0
     *
     * @var \WP_Post The post instance from which retrieve the amenities terms
     */
    private $post;

    /**
     * Template path
     *
     * @since 2.4.0
     *
     * @var string The path of the template
     */
    private $path;

    /**
     * Have Group
     *
     * @since 2.4.0
     *
     * @var bool True we have a group, False no group
     */
    public $haveGroups;

    /**
     * Get Terms Icon & Label
     *
     * @param array $terms An array of \WP_Term objects.
     *
     * @return array The array containing icon an label's terms
     */
    private function getTermsIconLabel(array $terms)
    {
        $listsGroup    = array();
        $groupsTerms   = array();
        $noGroupsTerms = array();

        foreach ($terms as $term) {
            // Retrieve the icon.
            $icon = F\getTermMeta('_qibla_tb_icon', $term);
            // Groups title.
            $metaTitle = F\getTermMeta('_qibla_tb_taxonomy_term_groups_title', $term);

            try {
                $icon          = new IconsSet\Icon($icon, 'Lineawesome::la-check');
                $iconHtmlClass = $icon->getHtmlClass();
            } catch (\Exception $e) {
                $debugInstance = new Debug\Exception($e);
                'dev' === QB_ENV && $debugInstance->display();

                $iconHtmlClass = '';
            }

            // Group title.
            $groupTitle = isset($metaTitle) && '' !== $metaTitle && 'none' !== $metaTitle ? $metaTitle : null;

            $listsGroup[$term->slug] = array(
                'icon_html_class' => $iconHtmlClass,
                'label'           => $term->name,
                'id'              => $term->term_id,
                'groups'          => $groupTitle,
                'href'            => get_term_link(
                    $term,
                    /**
                     * Filter the taxonomy, for retrieved the icon label for different amenities.
                     *
                     * @since 2.3.0
                     */
                    apply_filters('qibla_amenities_template_taxonomy_icon_label', 'amenities')
                ),
            );
        }

        /**
         * Filter for disable tax term group template.
         *
         * @since 2.4.0
         */
        if ('no' === apply_filters('qibla_disable_tax_term_groups_template', 'no')) {
            // Set path template for groups.
            $this->path = 'views/terms/amenitiesGroups.php';

            // Terms groups.
            foreach ($listsGroup as $item) {
                if ($item['groups']) {
                    $groupsTerms[sanitize_title_with_dashes($item['groups'])]['title']     = $item['groups'];
                    $groupsTerms[sanitize_title_with_dashes($item['groups'])][$item['id']] = array(
                        'icon'  => $item['icon_html_class'],
                        'label' => $item['label'],
                        'href'  => $item['href'],
                    );
                } else {
                    $noGroupsTerms['title']     = esc_html__('Other', 'qibla-framework');
                    $noGroupsTerms[$item['id']] = array(
                        'icon'  => $item['icon_html_class'],
                        'label' => $item['label'],
                        'href'  => $item['href'],
                    );
                }
            }

            if (! empty($groupsTerms)) {
                $this->haveGroups = true;

                if (! empty($noGroupsTerms)) {
                    $noGroupsTerms = array('other' => $noGroupsTerms);
                }

                $lists = array_filter(array_merge($groupsTerms, $noGroupsTerms));
            } else {
                unset($noGroupsTerms['title']);
                $lists = array_filter($noGroupsTerms);
            }
        } else {
            $lists = array_filter($listsGroup);
        }

        return $lists;
    }

    /**
     * AmenitiesTemplate constructor
     *
     * @since 1.7.0
     *
     * @param \WP_Post $post
     */
    public function __construct(\WP_Post $post)
    {
        $this->post       = $post;
        $this->path       = 'views/terms/amenities.php';
        $this->haveGroups = false;
    }

    /**
     * @inheritDoc
     *
     * @since 1.7.0
     *
     * @throws \Exception If terms cannot be retrieved
     */
    public function getData()
    {
        // Initialize data object.
        $data = new \stdClass();

        // Set the ID.
        $data->ID = $this->post->ID;
        // Section Title.
        /**
         * Filter Title template.
         *
         * @since 2.3.0
         */
        $data->title = apply_filters(
            'qibla_amenities_template_title',
            esc_html__('Amenities', 'qibla-framework')
        );

        // Retrieve the terms.
        $data->terms = get_the_terms(
            $this->post->ID,
            /**
             * Filter the taxonomy, for retrieved the data for different amenities.
             *
             * @since 2.3.0
             */
            apply_filters('qibla_amenities_template_taxonomy_get_data', 'amenities')
        );

        // No terms?
        if (! $data->terms) {
            return $data;
        }

        // Got a WP_Error? Make it as Exception.
        if (is_wp_error($data->terms)) {
            throw new \Exception(sprintf(
                '%1$s Cannot retrieve terms for the post with ID %2$d.',
                __METHOD__,
                $data->ID
            ));
        }

        $data->list = $this->getTermsIconLabel($data->terms);

        // Have Groups?
        $data->haveGroups = $this->haveGroups;

        return $data;
    }

    /**
     * @inheritDoc
     *
     * @since 1.7.0
     */
    public function tmpl(\stdClass $data)
    {
        if ($data->terms) {
            $engine = new T\Engine('single_listings_amenities', $data, $this->path);
            $engine->render();
        }
    }

    /**
     * Amenities Section Filter
     *
     * This method use the `get_post()` function to retrieve the current post.
     * So, use it only within a loop or where the post you want to use is globally set.
     *
     * @since 1.7.0
     *
     * @return void
     */
    public static function amenitiesSectionFilter()
    {
        $instance = new static(get_post());
        $instance->tmpl($instance->getData());
    }
}
