<?php
/**
 * Events Post Type
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

namespace AppMapEvents\PostType;

use QiblaFramework\Functions as F;
use QiblaFramework\Listings\ListingLocation;
use QiblaFramework\PostType\AbstractPostType;
use QiblaFramework\Utils\TimeZone;

/**
 * Class Events
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class Events extends AbstractPostType
{
    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        parent::__construct(
            'events',
            esc_html__('Event', 'qibla-events'),
            esc_html__('Events', 'qibla-events'),
            array(
                // Filter the location since other plugins may hide this or this may hide other menu items.
                'menu_position'   => apply_filters('appmap_ev_post_type_menu_position_events', 7),
                'menu_icon'       => 'dashicons-calendar-alt',
                'has_archive'     => true,
                'description'     => F\getThemeOption('listings', 'events_archive_description', true),
                'supports'        => array('title', 'editor', 'author', 'thumbnail', 'comments'),
                'capability_type' => 'listings',
                'map_meta_cap'    => true,
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
            $columns['author'],
            $columns['comments']
        );

        // Add custom columns.
        $columns = array_merge($columns, array(
            'event_date' => esc_html_x('Event Date', 'admin-table-list', 'qibla-events'),
            'location' => esc_html_x('Location', 'admin-table-list', 'qibla-events'),
            'category' => sprintf(
                '<span class="has-icon">%s</span>',
                esc_html_x('Category', 'admin-table-list', 'qibla-events')
            ),
//            'amenities' => esc_html_x('Tags', 'admin-table-list', 'qibla-events'),
            'featured' => sprintf(
                '<span class="has-icon">%s</span>',
                esc_html_x('Featured', 'admin-table-list', 'qibla-events')
            ),
//            'gallery'  => esc_html__('Gallery', 'qibla-events'),
            'creator'  => sprintf(
                '<span class="has-icon" data-icon="">%s</span>',
                esc_html_x('Author', 'admin-table-list', 'qibla-events')
            ),
            'comments' => sprintf(
                '<span class="has-icon">%s</span>',
                esc_html_x('Reviews', 'admin-table-list', 'qibla-events')
            ),
        ));

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
        $post = get_post($postID);

        switch ($columnName) :
            case 'event_date':
                $timeZone = new TimeZone();
                $timeZone = new \DateTimeZone($timeZone->getTimeZone()->getName());
                $time     = new \DateTime();
                $time->setTimezone($timeZone);

                $metaDateStart = F\getPostMeta('_qibla_mb_event_dates_multidatespicker_start', null, $postID);
                $metaDateEnd   = F\getPostMeta('_qibla_mb_event_dates_multidatespicker_end', null, $postID);
                $metaTimeStart = F\getPostMeta('_qibla_mb_event_start_time_timepicker', null, $postID);
                $metaTimeEnd   = F\getPostMeta('_qibla_mb_event_end_time_timepicker', null, $postID);

                if ($metaDateStart) {
                    if ('' !== $metaTimeStart) {
                        $time->setTimestamp($metaTimeStart);
                    }
                    echo sprintf('<i class="dlicon la la-calendar-o" aria-hidden="true"></i> %s - %s<br>',
                        esc_html(sanitize_text_field($metaDateStart)),
                        '' !== $metaTimeStart ? esc_html(sanitize_text_field($time->format('H:i'))) :
                            esc_html__('[No End Time]', 'qibla-events')
                    );
                }

                if ($metaDateEnd) {
                    if ('' !== $metaTimeEnd) {
                        $time->setTimestamp($metaTimeEnd);
                    }
                    echo sprintf('<i class="dlicon la la-calendar-o" aria-hidden="true"></i> %s - %s',
                        esc_html(sanitize_text_field($metaDateEnd)),
                        '' !== $metaTimeEnd ? esc_html(sanitize_text_field($time->format('H:i'))) :
                            esc_html__('[No End Time]', 'qibla-events')
                    );
                }
                break;
            case 'location':
                $location = new ListingLocation($post);
                $meta     = '<i class="dlicon la la-map-marker" aria-hidden="true"></i>' .
                            esc_html(sanitize_text_field($location->address()));

                echo $meta;
                break;
//            case 'gallery':
//                echo F\getPostMeta('_qibla_mb_images', null, $postID) ?
//                    '<span>' . esc_html__('Yes', 'qibla-events') . '</span><i class="la la-check la--active">' :
//                    '<span>' . esc_html__('No', 'qibla-events') . '</span><i class="la la-check">';
//                break;
            case 'category':
                $terms = get_the_term_list($post->ID, 'event_categories', '', ',');
                if (! is_wp_error($terms) && $terms) {
                    echo $terms;
                }
                break;
//            case 'amenities':
//                try {
//                    $html  = '';
//                    $terms = get_the_terms($postID, 'amenities');
//                    if ($terms && ! is_wp_error($terms)) {
//                        foreach ($terms as $term) {
//                            try {
//                                $icon = new Icon(F\getTermMeta('_qibla_tb_icon', $term->term_id, null));
//                                $html .= '<i class="dlicon ' . F\sanitizeHtmlClass($icon->getHtmlClass()) . '"></i> ';
//                            } catch (\Exception $e) {
//                                $html = '';
//                            }
//                        }
//                    }
//
//                    echo $html;
//                } catch (\Exception $e) {
//                    break;
//                }
//                break;
            case 'featured':
                $meta = F\getPostMeta('_qibla_mb_is_featured', null, $post->ID);
                echo 'on' === $meta ?
                    '<span>' . esc_html__('Yes', 'qibla-events') .
                    '</span><i class="la la-star dlicon dlicon--active">' :
                    '<span>' . esc_html__('No', 'qibla-events') .
                    '</span><i class="dlicon la la-star-o">';
                break;

            case 'creator':
                $author = new \WP_User($post->post_author);
                // Show the avatar.
                printf(
                    '<span title="%1$s">%2$s</span>',
                    sanitize_text_field($author->user_login),
                    get_avatar($author, 32)
                );
                break;
            default:
                break;
        endswitch;
    }
}