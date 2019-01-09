<?php

/**
 * Import Page View
 *
 * @copyright Copyright (c) 2016, Guido Scialfa
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
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

defined('WPINC') || die; ?>

<div class="wrap">
    <header>
        <h1><?php echo esc_html($data->pageTitle) ?></h1>
        <div class="dlimppage-description">
            <p><?php echo esc_html($data->pageDescription) ?></p>
        </div>
    </header>

    <div id="demo-browser" class="theme-browser rendered">
        <div id="demos" class="themes wp-clearfix">
            <?php
            if ($data->demoList) :
                foreach ($data->demoList as $demo) :
                    /**
                     * Filter view for demo
                     *
                     * @since: ${SINCE}
                     */
                    if ('yes' === apply_filters('qibla_importer_exclude_demo_view', 'no', $demo->slug)) {
                        continue;
                    }
                    $demo = $demo->getDemoData(); ?>
                    <div id="qibla-<?php echo esc_attr($demo->slug) ?>" class="theme demo" tabindex="0">
                        <div class="demo-screenshot theme-screenshot">
                            <img src="<?php echo esc_url($demo->screenshot) ?>" alt=""/>
                        </div>

                        <h2 class="demo-name theme-name" id="qibla-<?php echo esc_attr($demo->slug) ?>">
                            <?php echo esc_html($demo->name) ?>
                        </h2>

                        <?php
                        if ($demo->actions) :
                            foreach ($demo->actions as $action) : ?>
                                <div class="demo-actions">
                                    <a class="button button-primary action-<?php echo sanitize_key(sanitize_html_class($action->label)) ?>"
                                       href="<?php echo esc_url(admin_url('admin-ajax.php?action=import_demo&import=' . sanitize_key($demo->slug))) ?>"
                                       data-slugreferer="qibla-<?php echo esc_attr($demo->slug) ?>">
                                        <?php echo esc_html($action->label) ?>
                                    </a>
                                </div>
                            <?php
                            endforeach;
                        endif; ?>
                    </div>
                <?php
                endforeach;
            endif; ?>
        </div>
    </div>
</div>
