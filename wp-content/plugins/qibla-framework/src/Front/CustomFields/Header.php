<?php
/**
 * Class Front-end Remove Header background
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

use QiblaFramework\Functions as F;
use QiblaFramework\Template\Subtitle;

/**
 * Class Header
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Header extends AbstractMeta
{
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
            'header_skin'     => "_qibla_{$this->mbKey}_header_skin",
            'hide_breadcrumb' => "_qibla_{$this->mbKey}_hide_breadcrumb",
        );
    }

    /**
     * Header Skin
     *
     * @since  1.0.0
     *
     * @param string $upxscope The scope prefix. Default 'upx'.
     * @param string $element  The current element of the scope.
     * @param string $block    The custom block scope. Default empty.
     * @param string $scope    The default scope prefix. Default 'upx'.
     * @param string $attr     The attribute for which scope we are filtering the string.
     *
     * @return string The list of the header class filtered
     */
    public function headerSkin($upxscope, $element, $block, $scope, $attr)
    {
        if ('class' !== $attr || 'header' !== $block) {
            return $upxscope;
        }

        // Initialize object.
        $this->init();

        // Retrieve the meta value.
        $meta = $this->getMeta('header_skin');

        if (! $meta) {
            return $upxscope;
        }

        // Apply the skin modifier.
        $upxscope .= " {$scope}{$block}--skin-" . sanitize_key($meta) .
                     " {$scope}{$block}--original-skin-" . sanitize_key($meta);

        return $upxscope;
    }

    /**
     * Sub Title
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function subtitle()
    {
        // Initialize Object.
        $this->init();

        $subtitle = new Subtitle(get_post($this->id()));

        $subtitle->tmpl($subtitle->getData());
    }

    /**
     * Disable Breadcrumb
     *
     * @since  1.5.0
     *
     * @return void
     */
    public function hideBreadcrumb()
    {
        $this->init();

        $meta = F\stringToBool($this->getMeta('hide_breadcrumb'));

        // If on, remove the breadcrumb.
        if ($meta) {
            add_filter('qibla_template_engine_data_breadcrumb', function (\stdClass $data) {
                return null;
            });
        }
    }
}
