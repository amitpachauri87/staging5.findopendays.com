<?php
/**
 * Posts Functions
 *
 * @package QiblaFramework\Functions
 * @author  guido scialfa <dev@guidoscialfa.com>
 *
 * @license GPL 2
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

namespace QiblaFramework\Functions;

/**
 * Get term
 *
 * @since 1.0.0
 *
 * @param int|\WP_Term|object $term     The term to retrieve.
 * @param string              $taxonomy Optional. Taxonomy name that $term is part of.
 *
 * @return \WP_Term The term instance
 */
function getTerm($term, $taxonomy = '')
{
    // Retrieve the term.
    $term = get_term($term, $taxonomy, OBJECT, 'raw');

    if (is_wp_error($term)) {
        return new \WP_Term(new \stdClass());
    }

    return $term;
}

/**
 * Get Term on admin screen
 *
 * @since  1.0.0
 *
 * @throws \Exception If the function is called outside of admin.
 *
 * @return \WP_Term The post object.
 */
function getTermAdmin()
{
    if (! is_admin()) {
        throw new \Exception(
            sprintf('%s must be called only within the admin.', __FUNCTION__)
        );
    }

    global $pagenow, $taxnow;

    if ('term.php' !== $pagenow) {
        return new \WP_Term(new \stdClass());
    }

    // Retrieve the term.
    // @codingStandardsIgnoreLine
    return getTerm(filterInput($_GET, 'tag_ID', FILTER_SANITIZE_NUMBER_INT), $taxnow);
}

/**
 * Set parent term when post is saved or updated
 *
 * @see  wp_set_object_terms()
 *
 * @param int    $objectId Object ID.
 * @param array  $terms    An array of object terms IDs.
 * @param array  $ttIds    An array of term taxonomy IDs.
 * @param string $taxonomy Taxonomy slug.
 * @param bool   $append   Whether to append new terms to the old terms.
 * @param array  $oldTtIds Old array of term taxonomy IDs.
 */
function setParentTerm($objectId, $terms, $ttIds, $taxonomy, $append, $oldTtIds)
{
    if ('category' === $taxonomy || ! $terms) {
        return;
    }

    $list  = array();
    $terms = get_terms($taxonomy, array(
        'include' => $terms,
    ));

    if (is_wp_error($terms) || ! is_array($terms)) {
        return;
    }

    // Retrieve and store the parent terms.
    foreach ($terms as $term) {
        if ($term->parent) {
            array_push($list, $term->parent);
        }
    }

    if ($list) {
        // Set the post parent terms.
        wp_set_post_terms($objectId, $list, $taxonomy, true);
    }
}

/**
 * Get Term Meta
 *
 * Retrieve the term meta.
 * This function is a wrapper for get_term_meta that add a fourth argument
 * that is the default value to return in case the term meta has no value. ( false if not retrieved ).
 *
 * @since 1.0.0
 *
 * @uses  get_term_meta To retrieve the term meta
 *
 * @param string       $key     The meta key to retrieve the value.
 * @param int|\WP_Term $term    The term fro which retrieve the meta value.
 * @param mixed        $default The default value to return in case the get_term_meta return false. Optional.
 * @param bool         $single  If the meta value should be an array of meta key values or not. Optiona. Default true.
 *
 * @return bool|mixed The default value or the post meta.
 */
function getTermMeta($key, $term, $default = null, $single = true)
{
    // this is caused when we had saved the data and trying to rebuild the form fields.
    // I'm not sure but check it.
    if (! $term) {
        return false;
    }

    $termID = $term;

    if (! is_int($term)) {
        $termID = $term->term_id;
    }

    if (! $termID) {
        return $default;
    }

    if (! metadata_exists('term', $termID, $key) && null !== $default) {
        return $default;
    }

    // Retrieve the term meta.
    return get_term_meta($termID, $key, $single);
}

/**
 * Get The terms lists
 *
 * @since 1.0.0
 *
 * @throws \Exception In case the terms cannot be retrieved.
 *
 * @param array  $args  The arguments to pass to the get_terms function.
 * @param string $key   The key string to use to retrieve the value from the terms to use as key for the new array.
 * @param string $value The value string to use to retrieve the value from the terms to use as value for the new array.
 *
 * @return array The terms list
 */
function getTermsList(array $args, $key = 'slug', $value = 'name')
{
    // Retrieve the terms.
    $terms = get_terms($args);

    // Throw Exception in case of wp error.
    if (is_wp_error($terms)) {
        throw new \Exception(sprintf(
            '%s. Cannot retrieve terms: %s', 'qibla-framework',
            __FUNCTION__,
            $terms->get_error_code()
        ));
    }

    // Return an empty array if get_terms return int value.
    if (! is_array($terms)) {
        return array();
    }

    return wp_list_pluck($terms, $value, $key);
}

/**
 * Get hierarchical terms list
 *
 * @since 2.4.0
 *
 * @param array  $args  The arguments to pass to the get_categories function.
 * @param string $field The key string to use to retrieve the value from the terms to use as key for the new array.
 * @param string $value The value string to use to retrieve the value from the terms to use as value for the new array.
 *
 * @return array The terms list
 * @throws \Exception
 */
function getHierarchyTermsList(array $args, $field = 'slug', $value = 'name')
{
    $args = wp_parse_args($args, array(
        'taxonomy'    => 'category',
        'orderby'     => 'name',
        'order'       => 'ASC',
        'hide_empty'  => true,
        'show_groups' => false,
    ));

    $categories = get_categories($args);

    if (is_wp_error($categories)) {
        throw new \Exception(
            sprintf(
                '%s. Cannot retrieve categories: %s', 'qibla-framework',
                __FUNCTION__,
                $categories->get_error_code()
            )
        );
    }

    if (isset($args['show_groups']) && true === $args['show_groups']) {
        $optionsTitle  = get_option('qibla_options_tax_term_groups_title');
        $noGroupsTerms = array();
        $groupsTerms   = array();
        $termsTitle    = array();

        if (is_array($optionsTitle) && ! empty($optionsTitle)) {
            foreach ($optionsTitle as $title) {
                $termsTitle[sanitize_title_with_dashes($title)] = $title;
            }
        }

        foreach ($categories as $key => $cat) {
            // Groups title.
            $metaTitle = getTermMeta('_qibla_tb_taxonomy_term_groups_title', $cat->term_id);

            if (array_key_exists(sanitize_title_with_dashes($metaTitle), $termsTitle) && 'none' !== $metaTitle) {
                $groupsTerms[sanitize_title_with_dashes($metaTitle)]['title']        = $metaTitle;
                $groupsTerms[sanitize_title_with_dashes($metaTitle)][$cat->{$field}] = "{$cat->{$value}} ($cat->count)";
            } else {
                $noGroupsTerms['title']        = esc_html__('Other', 'qibla-framework');
                $noGroupsTerms[$cat->{$field}] = "{$cat->{$value}} ($cat->count)";
            }
        }

        if (! empty($noGroupsTerms)) {
            $noGroupsTerms = array('other' => $noGroupsTerms);
        }

        if (! empty($groupsTerms)) {
            $groupsTerms = array_merge($groupsTerms, $noGroupsTerms);

            return array_filter($groupsTerms);
        }
    }

    // Fallback functions if show_groups is false.
    return getTermsList(array(
        'taxonomy'   => $args['taxonomy'],
        'hide_empty' => $args['hide_empty'],
    ));
}

/**
 * Taxonomy List
 *
 * @since 2.0.0
 *
 * @param string $postType The post type from which retrieve the taxonomies.
 * @param array  $exclude  The taxonomies to exclude.
 *
 * @return array List of taxonomies
 */
function getTaxonomyList($postType, array $exclude = array())
{
    if (! is_string($postType) || ! $postType) {
        throw new \InvalidArgumentException('Post Type must be a string.');
    }

    $list       = array();
    $taxonomies = get_object_taxonomies($postType, 'object');

    if (! $taxonomies) {
        return array();
    }

    foreach ($taxonomies as $key => $data) {
        if (in_array($key, $exclude, true)) {
            continue;
        }

        $list[$key] = $data->label;
    }

    return $list;
}

/**
 * Get Terms By Term Based on context
 *
 * The function return the terms associated to another term within a specific taxonomy within the current request.
 * Since it's use the `get_queried_object` to retrieve the current request, this function can only be used
 * where a \WP_Term context is set.
 *
 * @since 2.0.0
 *
 * @uses  get_queried_object To retrieve the current request instance of \WP_Term.
 *
 * @param array  $terms    The terms to filter.
 * @param string $taxonomy The taxonomy related to the terms.
 *
 * @return array The terms list
 */
function getTermsByTermBasedOnContext(array $terms, $taxonomy)
{
    if (! is_string($taxonomy) || ! $taxonomy) {
        throw new \InvalidArgumentException('Wrong value for taxonomy parameter.');
    }

    if (empty($terms)) {
        return array();
    }

    // Get the current queried object to use for comparison.
    $currObj = get_queried_object();
    // Initialize the list to fill.
    $list = array();

    // Works only if a request has been made.
    if (! $currObj instanceof \WP_Term) {
        return array();
    }

    $optionsTitle  = get_option('qibla_options_tax_term_groups_title');
    $termsTitle    = array();
    $noGroupsTerms = array();
    $groupsTerms   = array();

    // Create Terms title array.
    foreach ($optionsTitle as $title) {
        $termsTitle[sanitize_title_with_dashes($title)] = $title;
    }

    // Build the list.
    foreach ($terms as $index => $item) {
        // Terms relations.
        $term = getTermMeta('_qibla_tb_taxonomy_term_relation', $item->term_id);
        // Title Groups.
        $termTitleGroups = getTermMeta('_qibla_tb_taxonomy_term_groups_title', $item->term_id);

        if (gettype($term) === 'string') {
            if (($taxonomy === $currObj->taxonomy && $currObj->slug === $term) ||
                in_array($term, array('', false, 'none'), true)
            ) {
                if (array_key_exists(sanitize_title_with_dashes($termTitleGroups), $termsTitle) &&
                    '' !== $termTitleGroups
                    && 'none' !== $termTitleGroups
                ) {
                    $groupsTerms[sanitize_title_with_dashes($termTitleGroups)]['title']     = $termTitleGroups;
                    $groupsTerms[sanitize_title_with_dashes($termTitleGroups)][$item->slug] = $item->name;
                }
                if (! array_key_exists(sanitize_title_with_dashes($termTitleGroups), $termsTitle) &&
                    '' === $termTitleGroups || 'none' === $termTitleGroups) {
                    $noGroupsTerms['title']     = esc_html__('Other', 'qibla-framework');
                    $noGroupsTerms[$item->slug] = $item->name;
                } else {
                    $list[$item->slug] = $item->name;
                }
            }
        }

        if (gettype($term) === 'array') {
            if (($taxonomy === $currObj->taxonomy && in_array($currObj->slug, $term, true)) ||
                in_array($term, array('', false, 'none'), true)
            ) {
                if (array_key_exists(sanitize_title_with_dashes($termTitleGroups), $termsTitle) &&
                    '' !== $termTitleGroups
                    && 'none' !== $termTitleGroups
                ) {
                    $groupsTerms[sanitize_title_with_dashes($termTitleGroups)]['title']     = $termTitleGroups;
                    $groupsTerms[sanitize_title_with_dashes($termTitleGroups)][$item->slug] = $item->name;
                } elseif (! array_key_exists(sanitize_title_with_dashes($termTitleGroups), $termsTitle) &&
                          '' === $termTitleGroups || 'none' === $termTitleGroups) {
                    $noGroupsTerms['title']     = esc_html__('Other', 'qibla-framework');
                    $noGroupsTerms[$item->slug] = $item->name;
                } else {
                    $list[$item->slug] = $item->name;
                }
            }
        }
    }

    if (! empty($noGroupsTerms) && ! empty($groupsTerms)) {
        $groupsTerms = array_merge($groupsTerms, array('other' => $noGroupsTerms));

        return $groupsTerms;
    } else {
        unset($noGroupsTerms['title']);
        $list = $noGroupsTerms;
    }

    return $list;
}

/**
 * Get Terms title options
 *
 * The function returns an array with titles to set the tag groups.
 *
 * @since 2.4.0
 *
 * @param int $termID The current term id.
 *
 * @return array The tax groups title.
 */
function getTermsTitleOptions($termID)
{
    $termsMeta = getTermMeta('_qibla_tb_taxonomy_term_groups_title', $termID, '', false);
    $termsMeta = ! empty($termsMeta) && 'none' !== $termsMeta[0] && $termsMeta[0] !== false ? $termsMeta : array();

    // Get tax groups title.
    $taxGroupsTitle = array_merge(
        (array)get_option('qibla_options_tax_term_groups_title'),
        $termsMeta
    );

    // @codingStandardsIgnoreLine
    $currentTitleRemove = filterInput($_POST, 'remove_title_group', FILTER_SANITIZE_STRING);
    $titleToRemove      = explode(',', $currentTitleRemove);

    if (is_array($titleToRemove) && ! empty($titleToRemove)) {
        foreach (array_filter($titleToRemove) as $title) {
            if (array_search($title, $taxGroupsTitle, true)) {
                unset($taxGroupsTitle[$title]);
            }
        }
    }

    $options = array();
    if (! empty($taxGroupsTitle) && is_array($taxGroupsTitle)) {
        foreach (array_filter($taxGroupsTitle) as $title) {
            $options[$title] = $title;
        }
        $options = array_filter(array_unique($options));
    }

    $cache = get_transient('qibla_options_tax_cache_groups');

    if (! $cache || ! ($cache === $options)) {
        // Update options tax groups title.
        update_option('qibla_options_tax_term_groups_title', $options);
        set_transient('qibla_options_tax_cache_groups', $options, 12 * HOUR_IN_SECONDS);
    }

    return $options;
}

/**
 * Get term color scoped style.
 *
 * @since 2.5.0
 *
 * @param {string} $taxonomy The taxonomy to retrieved term color
 * @param int $post          The current post.
 *
 * @return bool|string       False or The scoped style
 */
function getTermColorScopedStyle($taxonomy, $post = 0)
{
    $post = get_post($post);

    if (! $post) {
        return false;
    }

    $terms = get_the_terms($post->ID, $taxonomy);

    if (is_wp_error($terms)) {
        return false;
    }

    /**
     * Filter scoped style.
     * If NO disable scoped style, YES is active.
     *
     * @since 2.5.0
     */
    if ('no' === apply_filters('qibla_allow_term_color_scoped_style', 'yes')) {
        return false;
    }

    if (is_array($terms) && null === $terms[0]) {
        return false;
    }

    $color = getTermMeta('_qibla_tb_terms_color', $terms[0]->term_id);
    if (! $color) {
        return false;
    }

    // Listing article rules.
    $cssRules = "#post-{$post->ID}.dlarticle .dlwishlist-adder.is-stored::before,
    #post-{$post->ID}.dlarticle .dlwishlist-adder:hover::before{color:{$color};}
    #post-{$post->ID}.dlarticle .dlarticle-card-box::after{border-bottom-color:{$color};border-right-color:{$color};}
    #post-{$post->ID}.dlarticle .dlarticle-card-box:hover{border-color:{$color};}
    #post-{$post->ID}.dlarticle .dlarticle__header::after{border-left-color:{$color};border-top-color:{$color};}";

    // Event article rules.
    if ('events' === $post->post_type) {
        $cssRules .= "#post-{$post->ID}.dlarticle .dlevent-ticket-wrapper 
        .dlbtn{background-color:{$color};border-color:{$color};}";
    }
    // Tours article rules.
    if ('tours' === $post->post_type) {
        $cssRules .= "#post-{$post->ID}.dlarticle .dltour-ticket-wrapper 
        .dlbtn{background-color:{$color};border-color:{$color};}
        #post-{$post->ID}.dlarticle--current .dlarticle-card-box{border-color:{$color};}";
    }

    /**
     * Filter css rule
     *
     * @since 2.5.0
     */
    $cssRule = apply_filters('qibla_term_color_scoped_style', $cssRules, $color, $post->ID);

    $style = "<style id='post-{$post->ID}' scoped='scoped'>{$cssRule}</style>";

    return $style;
}
