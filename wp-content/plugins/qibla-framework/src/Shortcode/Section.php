<?php
/**
 * Short-code Section
 *
 * @since      1.0.0
 * @package    QiblaFramework\Shortcode
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

namespace QiblaFramework\Shortcode;

use QiblaFramework\Functions as F;
use QiblaFramework\IconsSet\Icon;
use QiblaFramework\Plugin;

/**
 * Class Section
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class Section extends AbstractShortcode implements ShortcodeVisualComposerInterface
{
    /**
     * Default link buttons style
     *
     * @since  1.4.0
     *
     * @var string The default style for the buttons links
     */
    private static $defaultLinksStyle = 'ghost';

    /**
     * Links Styles
     *
     * @since  1.4.0
     *
     * @var array The list of the html classes for the buttons links style
     */
    private static $linkStyles = array(
        'ghost' => array(
            'dlbtn',
            'dlbtn--gray',
            'dlbtn--ghost',
        ),
        'fill'  => array(
            'dlbtn',
        ),
    );

    /**
     * Create the icon class attribute
     *
     * @since 1.6.0
     *
     * @param string $icon The icon style may be a compact version of the icon or a simple icon string.
     *
     * @return string
     */
    private function buildIconClassAttribute($icon)
    {
        $newIcon = str_replace('la ', '', $icon);

        // Try to retrieve the btn style from Icon compact version.
        // This is only for visual composer support right know.
        if (false !== strpos($icon, '::')) {
            try {
                $icon    = new Icon($icon);
                $newIcon = $icon->getIconSlug();
            } catch (\InvalidArgumentException $e) {
                $newIcon = '';
            }
        }

        $icon = $newIcon;

        return $icon;
    }

    /**
     * Set the links style
     *
     * @since  1.4.0
     *
     * @param \stdClass $data The current shortcode data.
     * @param array     $atts The shortcode attributes.
     */
    private function setStyleData(\stdClass &$data, array $atts)
    {
        // Data Container Style.
        $data->style = array(
            'background-color' => sanitize_hex_color('#' . ltrim($atts['background-color'], '#')),
        );

        switch ($atts['size']) {
            case 'big':
                $data->style['padding-top']    = '7.5em';
                $data->style['padding-bottom'] = '7em';
                break;
            default:
                break;
        }

        // Include additional background size.
        if ($atts['background-image']) {
            $image = $atts['background-image'];
            // May be an attachment ID.
            if (is_numeric($atts['background-image'])) {
                $image = wp_get_attachment_image_url(intval($atts['background-image']), 'full');
            }

            $image = esc_url($image);
            if ($image) {
                $data->style = array_merge($data->style, array(
                    'background-image' => 'url(' . $image . ')',
                    'background-size'  => 'cover',
                ));

                // Set the modifier for the background image..
                $data->scopeClassModifiers[] = 'has-background-image';
            }
        }
    }

    /**
     * Set Links Style
     *
     * @since  1.4.0
     *
     * @param \stdClass $data The current shortcode data.
     * @param array     $atts The shortcode attributes.
     */
    private function setLinksStyle(\stdClass &$data, $atts)
    {
        // Set the correct link style.
        $linkStyle = in_array($atts['links_style'], array_keys(self::$linkStyles), true) ?
            self::$linkStyles[$atts['links_style']] :
            (self::$linkStyles[self::$defaultLinksStyle]);

        // Set links data.
        $data->links[] = array(
            'link'             => $atts['link'],
            'linkUrl'          => $atts['link_url'],
            'linkClass'        => array_merge(array('dlsc-section__link'), $linkStyle),
            'linkIconClass'    => array(
                'la',
                'dlbtn__icon--' . F\sanitizeHtmlClass($atts['link_icon_position']),
                $this->buildIconClassAttribute($atts['link_icon']),
            ),
            'linkIconPosition' => $atts['link_icon_position'],
        );

        $data->links[] = array(
            'link'             => $atts['link_2'],
            'linkUrl'          => $atts['link_2_url'],
            'linkClass'        => array_merge(array('dlsc-section__link'), $linkStyle),
            'linkIconClass'    => array(
                'la',
                'dlbtn__icon--' . F\sanitizeHtmlClass($atts['link_2_icon_position']),
                $this->buildIconClassAttribute($atts['link_2_icon']),
            ),
            'linkIconPosition' => $atts['link_2_icon_position'],
        );
    }

    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        $this->tag      = 'dl_section';
        $this->defaults = array(
            'title'                => '',
            'subtitle'             => '',
            'background-color'     => 'transparent',
            'background-image'     => '',
            'size'                 => 'normal',
            'links_style'          => 'ghost',
            'link'                 => '',
            'link_url'             => '',
            'link_icon'            => 'la-long-arrow-right',
            'link_icon_position'   => 'after',
            'link_2'               => '',
            'link_2_url'           => '',
            'link_2_icon'          => 'la-long-arrow-right',
            'link_2_icon_position' => 'after',
        );
    }

    /**
     * Build Data
     *
     * @since  1.0.0
     *
     * @param array  $atts    The short-code attributes.
     * @param string $content The content within the short-code.
     *
     * @return \stdClass The data instance or null otherwise.
     */
    public function buildData(array $atts, $content = '')
    {
        // Initialize Data.
        $data = new \stdClass();

        // The Container.
        $data->containerClass = array(
            'dlsc-section',
        );

        // Set the data for the short-code.
        $data->sectionTitle        = $atts['title'];
        $data->sectionSubTitle     = $atts['subtitle'];
        $data->content             = $content ? do_shortcode($content) : '';
        $data->scopeClassModifiers = array();

        // Set Styles.
        $this->setStyleData($data, $atts);
        // Set Links styles.
        $this->setLinksStyle($data, $atts);

        return $data;
    }

    /**
     * Callback
     *
     * @since  1.0.0
     *
     * @param array  $atts    The short-code's attributes.
     * @param string $content The content within the short-code.
     *
     * @return string The short-code markup
     */
    public function callback($atts = array(), $content = '')
    {
        $atts = $this->parseAttrsArgs($atts);

        // Build Data.
        $data = $this->buildData($atts, $content);

        // Load the template.
        return $this->loadTemplate('dl_sc_section', $data, '/views/shortcodes/section.php');
    }

    /**
     * @inheritDoc
     */
    public function visualComposerMap()
    {
        return include Plugin::getPluginDirPath('/inc/vcMapping/section.php');
    }
}
