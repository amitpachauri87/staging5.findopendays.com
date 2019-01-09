<?php
/**
 * FilterFieldInterface
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

namespace QiblaFramework\Filter;

/**
 * Interface FilterFieldInterface
 *
 * @since   2.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
interface FilterFieldInterface
{
    /**
     * Field Type
     *
     * @since 2.0.0
     *
     * @return \QiblaFramework\Form\Interfaces\Types The field type instance
     */
    public function type();

    /**
     * Field
     *
     * @since 2.0.0
     *
     * @return \QiblaFramework\Form\Interfaces\Fields The field associated to the filter field
     */
    public function field();
}
