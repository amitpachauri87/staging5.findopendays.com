<?php
/**
 * Class Front-end Jumbo-tron
 *
 * @since      1.0.0
 * @package    QiblaFramework\Front\CustomFields
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

namespace QiblaFramework\Front\CustomFields;

use Qibla\Archive\Title as ArchiveTitle;
use QiblaFramework\Functions as F;
use QiblaFramework\Parallax;
use QiblaFramework\Slider\RevolutionSlider;
use QiblaFramework\Template\Subtitle;
use QiblaFramework\TemplateEngine as T;
use QiblaFramework\ListingsContext\Context;

/**
 * Class Jumbotron
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Jumbotron extends AbstractMeta
{
    /**
     * Parallax Settings
     *
     * @since  1.4.0
     *
     * @var Parallax\Settings
     */
    protected $parallaxSettings;

    /**
     * Get Background Image
     *
     * @since  1.0.0
     *
     * @return array The background image data
     */
    private function getBackgroundImage()
    {
        static $data;

        if ($data) {
            return $data;
        }

        $backgroundColor = $this->getMeta('bg_color');
        $backgroundId    = $this->getMeta('bg_image');
        $backgroundSrc   = wp_get_attachment_image_src($backgroundId, 'qibla-jumbotron');

        $data = array(
            'background_color' => $backgroundColor,
            'background_id'    => $backgroundId,
            'background_size'  => 'qibla-jumbotron',
            'background_src'   => $backgroundSrc,
        );

        /**
         * Filter background Data
         *
         * @since 1.7.0
         *
         * @param array     $data            The data to filter.
         * @param string    $backgroundColor The color of the background.
         * @param int       $backgroundId    The id of the background image.
         * @param string    $backgroundSrc   The background image src.
         * @param Jumbotron $this            The instance of the Jumbotron class.
         */
        $data = apply_filters(
            'qibla_fw_jumbotron_background',
            $data,
            $backgroundColor,
            $backgroundId,
            $backgroundSrc,
            $this
        );

        return $data;
    }

    /**
     * Filter Jumbotron Data for Slider
     *
     * @since 1.6.0
     *
     * @param \stdClass $data The data object to filter
     *
     * @return \stdClass The filtered data
     */
    private function filterJumbtronDataForSlider(\stdClass $data)
    {
        $meta = $this->getMeta('slider');

        $slider            = new \stdClass();
        $slider->shortcode = '';

        if (RevolutionSlider::sliderIsActive() && $meta && 'none' !== $meta) {
            $slider->shortcode = '[rev_slider alias="' . sanitize_title($meta) . '"]';
        }

        $data->slider = $slider;

        return $data;
    }

    /**
     * Get the Title
     *
     * Get the title based on context
     *
     * @since  1.0.0
     *
     * @return string The title of the current context
     */
    protected function getContextTitle()
    {
        // Get the archive title by default.
        $title = new ArchiveTitle();
        $title = $title->title();

        switch ($this->context) {
            case 'term':
                $title = get_term_field('name', $this->id, $this->taxonomy);
                break;
            case 'post':
                $title = get_the_title($this->id);
                break;
        }

        if (is_wp_error($title)) {
            $title = '';
        }

        return $title;
    }

    /**
     * Get Attribute Class
     *
     * @since  1.2.0
     *
     * @param \stdClass $data The jumbotron's data for template.
     *
     * @return array The class for the markup
     */
    protected function getAttributeClass(\stdClass $data)
    {
        // Get the background.
        $background = $this->getBackgroundImage();

        $class = array(
            'dljumbotron',
            $data->hasGallery ? 'dljumbotron--has-gallery' : '',
        );

        // Has image background.
        if (! empty($background['background_src'][0])) {
            $class[] = 'dljumbotron--has-background-image';
        }
        // Has video and that video is eligible to be played on page.
        if (F\isHeaderVideoEligible()) {
            $class[] = 'dljumbotron--has-background-video';
        }
        // Allow overlay but not in singular listings.
        if (! Context::isSingleListings() and (! empty($background['background_src'][0]) || F\isHeaderVideoEligible())) {
            $class[] = 'dljumbotron--overlay';
        }
        // Parallax.
        if ($this->parallaxSettings->isEnabled() &&
            $background['background_src'] &&
            ! empty($background['background_src'][0])
        ) {
            $class[] = 'dljumbotron--use-parallax';
        }

        $class = array_unique($class);

        return $class;
    }

    /**
     * Jumbotron constructor
     *
     * @since 1.4.0
     */
    public function __construct(Parallax\Settings $parallaxSettings)
    {
        parent::__construct();

        // Set the Settings for the parallax.
        $this->parallaxSettings = $parallaxSettings;
    }

    /**
     * Initialize Object
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        // Build the meta-keys array.
        $this->meta = array(
            'subtitle' => "_qibla_{$this->mbKey}_sub_title",
            'bg_image' => "_qibla_{$this->mbKey}_jumbotron_background_image",
            'bg_color' => "_qibla_{$this->mbKey}_jumbotron_background_color",
            'disabled' => "_qibla_{$this->mbKey}_jumbotron_disable",
            'height'   => "_qibla_{$this->mbKey}_jumbotron_min_height",
            'slider'   => "_qibla_{$this->mbKey}_jumbotron_slider",
        );
    }

    /**
     * Jumbo-tron
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function jumbotron()
    {
        if (! F\isJumbotronAllowed()) {
            return;
        }

        // Search page use the theme jumbo-tron until the options
        // to customize it will be implemented.
        if (! is_search()) :
            // Remove the Theme jumbo-tron filter.
            // Remove the theme jumbo-tron after the jumbo-tron allowed in this way the pages where the jumbo-tron
            // is not implemented yet will be served by the Theme.
            // See for example the is_date or is_year archive pages.
            remove_action('qibla_header', array('Qibla\\Jumbotron', 'jumbotronTmpl'), 30);

            $this->init();

            // Initialize Data Object.
            $data = new \stdClass();

            // Check if slider is set.
            $data = $this->filterJumbtronDataForSlider($data);

            // No Slider Shortcode is set?
            if (! $data->slider->shortcode) :
                // Has gallery is false by default.
                // Right now the gallery is only available within the single post type listings.
                $data->hasGallery = false;
                $data->dataLabel  = 'no';

                if (Context::isSingleListings()) {
                    // A bool to know if the current object has a gallery.
                    $data->hasGallery = (bool)get_post_meta($this->id, '_qibla_mb_images', true);
                    // Set the gallery label for data attribute.
                    $data->dataLabel = esc_html__('See gallery', 'qibla-framework') . ':';
                    $data->dataLabel .= implode(',', array('la', 'la-camera-retro'));
                    // Set the action to able to show the gallery data within the page.
                    add_action('wp_footer', array($this, 'galleryTmpl'));
                }

                // Set title and subtitle.
                $data->title    = $this->getContextTitle();
                $data->subtitle = new Subtitle($this->obj);

                // Set if the parallax is enabled.
                $data->parallaxIsEnabled = $this->parallaxSettings->isEnabled();

                // Get the classes for the markup.
                $data->class = $this->getAttributeClass($data);
            endif;

            // Load the template.
            $this->loadTemplate("{$this->mbKey}_jumbotron", $data, '/views/customFields/jumbotron.php');
        endif;
    }

    /**
     * Set Parallax Json Data
     *
     * @since  1.4.0
     *
     * @return void
     */
    public function setParallax()
    {
        $this->init();

        $dataParallax = array();

        // Is parallax allowed?
        if ($this->parallaxSettings->isEnabled() && F\isJumbotronAllowed()) :
            $background = $this->getBackgroundImage();

            if (! $background['background_src']) {
                return;
            }

            // Retrieve and set the data for the parallax image.
            if ($background['background_src'] && ! empty($background['background_src'][0])) {
                $dataParallax = array(
                    'imgSrc'    => esc_url($background['background_src'][0]),
                    'imgWidth'  => $background['background_src'][1] ? intval($background['background_src'][1]) : '',
                    'imgHeight' => $background['background_src'][1] ? intval($background['background_src'][2]) : '',
                );
            }

            // Allow video parallax within the homepage template.
            // Allowed only and if only the video can be played.
            if ((is_page_template('templates/homepage.php') ||
                 is_page_template('templates/homepage-fullwidth.php') ||
                 is_page_template('templates/events-search.php')) &&
                F\isHeaderVideoEligible()
            ) {
                // Get the settings for the video.
                $videoSettings = get_header_video_settings();
                // Set the video Src.
                if ($videoSettings['videoUrl']) {
                    $dataParallax = array_merge($dataParallax, array(
                        'videoSrc' => esc_url($videoSettings['videoUrl']),
                    ));
                }
            }

            if (! empty($dataParallax['videoSrc']) || ! empty($dataParallax['imgSrc'])) {
                // Get and merge the parallax Options.
                $dataParallax = array_merge($dataParallax, $this->parallaxSettings->getOptions());
            };
        endif;

        /**
         * Filter data Parallax
         *
         * @since 1.4.0
         *
         * @param array $dataParallax the options for the parallax script.
         */
        $dataParallax = apply_filters('qibla_fw_parallax_options', $dataParallax);

        // Make it as json.
        $dataParallax = wp_json_encode($dataParallax);

        if (F\isJSON($dataParallax)) {
            // Show the data.
            echo "<script type=\"text/javascript\">/* <![CDATA[ */\n var dlDataParallax = " . $dataParallax . "\n" . '/* ]]> */</script>';

            if (wp_script_is('dlparallax', 'registered')) {
                wp_enqueue_script('dlparallax');
            }
        }
    }

    /**
     * Custom Css
     *
     * @since  1.0.0
     * @return void
     */
    public function customCss()
    {
        if (! F\isJumbotronAllowed()) {
            return;
        }

        // Remove the custom css that refer to the Theme Jumbo-tron.
        remove_action('wp_head', array('Qibla\\Jumbotron', 'customCss'), 30);

        // Initialize instance.
        $this->init();

        // Initialize Data.
        $data = array();

        // Get the background image data.
        $background = $this->getBackgroundImage();

        // Set the background color.
        if ($background['background_color']) {
            $data['style'] = array(
                'background-color' => $background['background_color'],
            );
        }

        // Jumbo-tron minHeight.
        $minHeight = $this->getMeta('height');
        if (0 < $minHeight) {
            // Override the computed {max|min}-height.
            $data['style']['max-height'] = '100% !important';
            $data['style']['min-height'] = intval($minHeight) . 'px' . ' !important';
        }

        // Don't perform any action if the parallax is enabled.
        if (! $this->parallaxSettings->isEnabled()) {
            // Background Style.
            if (! empty($background['background_src'][0])) {
                $data['style'] = array(
                    'background'          => $background['background_color'] . ' url(' . esc_url($background['background_src'][0]) . ') no-repeat center center',
                    'background-size'     => 'cover',
                    'background-position' => 'center',
                    'background-repeat'   => 'no-repeat',
                );
            }
        }

        /**
         * Filter Jumbotron Style Data
         *
         * Filter data before build the style string.
         *
         * @since 1.0.0
         *
         * @param array        $data The data to filter.
         * @param AbstractMeta $this The instance of the Jumbotron class.
         */
        $data = apply_filters('qibla_fw_jumbotron_data_style', $data, $this);

        // Build the style.
        $style = '';
        if (! empty($data['style']) && is_array($data['style'])) {
            $style = F\implodeAssoc(';', ':', $data['style']);
        }

        if ($style) {
            // @todo Need cssTidy
            echo '<style type="text/css">.dljumbotron{' . $style . '}</style>';
        }
    }

    /**
     * Gallery
     *
     * Include the gallery list data object.
     * The data is consumed by the PhotoSwipe script.
     *
     * @since  1.0.0
     *
     * @return array The list of the image data.
     */
    public function getGallery()
    {
        // Initialize the data object.
        $list = array();

        /**
         * Add gallery if exists within the single post.
         *
         * - Filter ids for add extra ids to gallery
         *
         * @since 2.2.0
         */
        $ids = apply_filters(
            'qibla_mb_images_gallery_ids',
            get_post_meta($this->id, '_qibla_mb_images', true)
        );

        if ($ids) :
            $ids = explode(',', $ids);
            foreach ($ids as $id) {
                $id  = intval($id);
                $img = wp_get_attachment_image_src($id, 'full');

                if (! $img) {
                    continue;
                }

                $post = get_post($id);
                /**
                 * Get Filtered post object.
                 *
                 * - Use for modify image title.
                 *
                 * @since 2.2.0
                 */
                $postFiltered  = apply_filters('qibla_mb_images_gallery_post_data', $id);
                $titleFiltered = is_object($postFiltered) ? $postFiltered->post_title : '';
                $title         = $post instanceof \WP_Post ? sanitize_text_field($post->post_title) : '';

                // Set the data for the element.
                // See the http://photoswipe.com/documentation/getting-started.html for more info.
                $list[] = array(
                    'src'   => $img[0],
                    'w'     => intval($img[1]),
                    'h'     => intval($img[2]),
                    'title' => isset($title) && ! is_object($postFiltered) ? $title : $titleFiltered,
                );
            }
        endif;

        return $list;
    }

    /**
     * Gallery Template
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function galleryTmpl()
    {
        if (! Context::isSingleListings() ||
            ! wp_script_is('dlphotoswipe', 'registered') ||
            ! wp_style_is('photoswipe-skin', 'registered')
        ) {
            return;
        }

        $list = $this->getGallery();

        // Append the list as json object.
        if (empty($list)) {
            return;
        }

        wp_enqueue_style('photoswipe-skin');
        wp_enqueue_script('dlphotoswipe');

        printf(
            '<script type="text/javascript" id="dlgallery">%s</script>',
            wp_json_encode($list)
        );

        // Render the photo swipe template.
        $engine = new T\Engine('jumbotron_photoswipe_view', new \stdClass(), '/views/photoswipe.php');
        $engine->render();
    }
}
