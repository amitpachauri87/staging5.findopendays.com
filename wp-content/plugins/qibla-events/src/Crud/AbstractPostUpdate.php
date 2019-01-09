<?php
/**
 * AbstractPostUpdate
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2017, Guido Scialfa
 * @license   GNU General Public License, version 2
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

namespace AppMapEvents\Crud;

/**
 * Class AbstractPostUpdate
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package AppMapEvents\Crud
 */
abstract class AbstractPostUpdate extends AbstractCrudPost
{
    /**
     * Allowed Capability or Role
     *
     * @since  1.0.0
     *
     * @var string The capability or the user role that is allowed to CUD
     */
    protected static $userAllowedCapability = 'edit_posts';

    /**
     * Update the post
     *
     * @since 1.0.0
     *
     * @return bool True if the post has been updated. False otherwise.
     */
    abstract public function update();
}
