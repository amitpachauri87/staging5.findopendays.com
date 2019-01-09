<?php
/**
 * ReviewerData
 *
 * @since      2.4.0
 * @package    QiblaFramework\Reviewer
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

namespace QiblaFramework\Reviewer;

use QiblaFramework\Functions as Ffw;
use QiblaFramework\ListingsContext\Context;
use QiblaFramework\ListingsContext\Types;

/**
 * Class ReviewerData
 *
 * @since  2.4.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class ReviewerData
{
    /**
     * Data
     *
     * @since 2.4.0
     *
     * @var string The ids string data
     */
    private $post;

    /**
     * Template ID
     *
     * @since 2.4.0
     *
     * @var string the template id
     */
    public static $templateID = '';

    /**
     * The Box data array
     *
     * @since 2.4.0
     *
     * @var array|bool
     */
    private static $boxData;

    /**
     * Context
     *
     * @since 2.4.0
     *
     * @var object The Context class object
     */
    public $context;

    /**
     * ReviewerDataFilter constructor.
     *
     * @since 2.4.0
     *
     * @param \WP_Post $post
     * @param Context  $context
     */
    public function __construct(\WP_Post $post, Context $context)
    {
        $this->post       = $post;
        $this->context    = $context;
        $type             = $post->post_type;
        self::$templateID = Ffw\getThemeOption('listings', $type . '_reviewer_template_id', true);
        self::$boxData    = self::dataBox($this->post);
    }

    /**
     * Check Dependencies
     *
     * @since 2.4.0
     *
     * @return bool True if check pass, false otherwise
     */
    public static function checkDependencies()
    {
        if (! function_exists('is_plugin_active')) {
            require_once untrailingslashit(ABSPATH) . '/wp-admin/includes/plugin.php';
        }

        return is_plugin_active('reviewer/reviewer.php') && class_exists('RWP_API');
    }

    /**
     * Last Title Review
     *
     * @since 2.4.0
     *
     * @return bool|\stdClass
     */
    public static function lastTitleReview()
    {
        $boxData = self::$boxData;

        // Initialized data.
        $data = new \stdClass();

        if (! $boxData) {
            return false;
        }

        $data->lastTitleReview = null;

        $reviews = ! empty($boxData['reviews']) ? (array)$boxData['reviews'] : null;
        if ($reviews) {
            $data->lastTitleReview = sprintf('"%s"', $reviews[0]['rating_title']);
        }

        return $data;
    }

    /**
     * Get Average Rating
     *
     * @since 2.4.0
     *
     * @return bool|\stdClass
     */
    public static function averageRating()
    {
        $boxData = self::$boxData;

        if (! $boxData) {
            return false;
        }

        // Initialized data.
        $data  = new \stdClass();
        $value = ! empty($boxData['overall']) ? $boxData['overall'] : 0;

        $data->averageRating      = intval($value / 2);
        $data->averageRatingwidth = (intval((0 < $value ? ($value / 5) * 100 : 0) / 2));
        $data->averageRatingLabel = isset($value) ? (int)($value / 2) : esc_html__('Unknown', 'bbtrip');

        return $data;
    }

    /**
     * Get Reviews Count
     *
     * @since 2.4.0
     *
     * @return bool|\stdClass
     */
    public static function reviewsCount()
    {
        $boxData = self::$boxData;

        if (! $boxData) {
            return false;
        }

        // Initialized data.
        $data = new \stdClass();

        // Review number.
        $count              = ! empty($boxData['count']) ? $boxData['count'] : 0;
        $data->reviewsCount = sprintf(esc_html(
        /* Translators: %1 is the number of reviews. */
            _n('1 review', '%1$s reviews', $count, 'bbtrip')
        ), $count);

        return $data;
    }

    /**
     * Get Box Data
     *
     * @since 2.4.0
     *
     * @param $post
     *
     * @return array|bool
     */
    private static function dataBox($post)
    {
        $post = get_post($post);

        if (! self::checkDependencies() || ! $post) {
            return false;
        }

        // Get Reviews Box.
        $boxReviews = \RWP_API::get_reviews_box_users_reviews($post->ID, -1, self::$templateID);

        return $boxReviews;
    }

    /**
     * Init
     *
     * @param null $object
     *
     * @return ReviewerData
     */
    public static function init($object = null)
    {
        $instance = new self(get_post(), new Context(Ffw\getWpQuery(), new Types()));

        return $instance;
    }
}
