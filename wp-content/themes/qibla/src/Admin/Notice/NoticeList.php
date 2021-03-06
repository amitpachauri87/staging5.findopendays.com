<?php
namespace Qibla\Admin\Notice;

use Qibla\Functions as F;

/**
 * Admin Notice List
 *
 * @since      1.0.0
 * @package    Qibla\Admin\Notice
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    GNU General Public License, version 2
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
 * Class NoticeList
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class NoticeList extends Notice
{
    /**
     * Requirements List
     *
     * Es.
     *  [
     *      'key' => [
     *          'type'    => 'error|warning|info',
     *          'message' => esc_html__('The message', 'text-domain')
     *      ]
     *  ]
     *
     * @since  1.0.0
     *
     * @var array A list of messages and their info
     */
    private $list;

    /**
     * Construct
     *
     * @since 1.0.0
     *
     * @param string $message     The main message to show above the list of notices.
     * @param array  $list        The list of messages.
     * @param string $type        The type of the notice. Optional. Default 'info'.
     * @param bool   $dismissible If the notice must be dismissible or not. Optional. Default to false.
     */
    public function __construct($message, array $list, $type = 'info', $dismissible = false)
    {
        $this->list = $list;

        parent::__construct($message, $type, $dismissible);
    }

    /**
     * Notice
     *
     * @todo   Create view and tmpl method.
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function notice()
    {
        // We want to show message only if there is a list of messages to show.
        // The message here become the title.
        if (! $this->list) {
            return;
        }

        // @todo Move to views. Separate logic.
        ?>
        <div class="<?php echo esc_attr($this->getHtmlClass()) ?>">
            <?php if ($this->message) : ?>
                <p><?php echo F\ksesPost($this->message) ?></p>
            <?php endif; ?>

            <?php if ($this->list) : ?>
                <ul class="upb-notices-list">
                    <?php foreach ($this->list as $key => $notice) : ?>
                        <li class="upb-notice upb-notice--<?php echo sanitize_html_class($notice['type']) ?>">
                            <?php echo esc_html($notice['message']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
    }
}
