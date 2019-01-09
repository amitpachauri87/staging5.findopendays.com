<?php
/**
 * ReviewerTemplate
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
use QiblaFramework\Template\TemplateInterface;
use QiblaFramework\TemplateEngine\Engine;

/**
 * Class ReviewerTemplate
 *
 * @since  2.4.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class ReviewerTemplate implements TemplateInterface
{
    /**
     * The Post
     *
     * @since 2.4.0
     *
     * @var \WP_Post
     */
    private $post;

    /**
     * Template ID
     *
     * @since 2.4.0
     *
     * @var mixed
     */
    private $templateId;

    /**
     * Reviewer constructor.
     *
     * @since 1.0.0
     *
     * @param \WP_Post $post
     */
    public function __construct(\WP_Post $post)
    {
        $this->post       = $post;
        $type             = $this->post->post_type;
        $this->templateId = Ffw\getThemeOption('listings', $type . '_reviewer_template_id', true);
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $data = new \stdClass();

        $data->postId     = $this->post->ID;
        $data->templateId = isset($this->templateId) && '' !== $this->templateId ? $this->templateId : null;

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new Engine('reviewer_filter', $data, '/views/reviewer.php');
        $engine->render();
    }

    /**
     * @inheritdoc
     */
    public static function template($object = null)
    {
        $instance = new self(get_post());
        $instance->tmpl($instance->getData());
    }
}
