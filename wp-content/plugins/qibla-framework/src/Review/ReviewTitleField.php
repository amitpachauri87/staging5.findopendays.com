<?php

namespace QiblaFramework\Review;

/**
 * Review Title Field
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaFramework\Review
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    GNU General Public License, version 2
 *
 * Copyright (C) 2017 Guido Scialfa <dev@guidoscialfa.com>
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
 * Class ReviewTitleField
 *
 * @since   1.2.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\Review
 */
class ReviewTitleField extends AbstractReviewField
{
    /**
     * ReviewTitleField constructor
     *
     * @since 1.2.0
     */
    public function __construct()
    {
        parent::__construct(array(
            'container_class' => array('comment-form-title'),
            'container'       => 'p',
            'type'            => 'text',
            'name'            => 'qibla_mb_comment_title',
            'label'           => sprintf(
                '%s <span class="required">*</span>',
                esc_html__('Title\'s review', 'qibla-framework')
            ),
            'exclude_none'    => true,
            'attrs'           => array(
                'required'      => 'required',
                'aria-required' => 'true',
            ),
        ));
    }
}
