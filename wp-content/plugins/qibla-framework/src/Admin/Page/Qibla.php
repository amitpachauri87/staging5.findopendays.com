<?php
namespace QiblaFramework\Admin\Page;

use QiblaFramework\Plugin;

/**
 * Class Locations
 *
 * @since      1.0.0
 * @package    QiblaFramework\Admin\Page
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
 * Class Qibla
 *
 * @since   1.0.0
 * @package QiblaFramework\Admin\Page
 */
class Qibla extends AbstractMenuPage
{
    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        parent::__construct(
            esc_html_x('Qibla', 'admin-menu', 'qibla-framework'),
            esc_html_x('Qibla', 'admin-menu', 'qibla-framework'),
            'qibla',
            Plugin::getPluginDirUrl('/assets/imgs/qibla-mark-16x16.png'),
            'edit_theme_options',
            array($this, 'callback'),
            59
        );
    }

    /**
     * Admin bar link
     *
     * @since 2.1.0
     *
     * @param $adminBar object The WP_Admin_Bar object
     */
    public function adminToolbar($adminBar)
    {
        if (! $adminBar instanceof \WP_Admin_Bar) {
            return;
        }

        $adminBar->add_menu(array(
            'id'    => $this->menuSlug,
            'title' => sprintf(
                '<span class="ab-icon"><img src="%s"/></span> <span class="ab-label">%s</span>',
                Plugin::getPluginDirUrl('/assets/imgs/qibla-mark-16x16.png'),
                $this->menuTitle
            ),
            'href'  => esc_url(admin_url('admin.php?page=' . $this->menuSlug)),
        ));
    }

    public function callback()
    {
        $s = new Settings();
        $s->callback();
    }
}
