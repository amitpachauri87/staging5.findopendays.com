<?php
/**
 * Form Factory
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    AppMapEvents\Front\ListingForm
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

namespace AppMapEvents\Front\ListingForm;

use QiblaFramework\Functions as Fw;
use QiblaFramework\Form\Interfaces\Forms;
use AppMapEvents\Package\PackageRestrictionsList;

/**
 * Class FormFactory
 *
 * @todo    Need Set restrictions method.
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package AppMapEvents\Front\ListingForm
 */
class FormFactory
{
    /**
     * Fields
     *
     * @since  1.0.0
     *
     * @var array The fields list for this form
     */
    protected $fields;

    /**
     * The form instance
     *
     * @since  1.0.0
     *
     * @var Forms The form instance
     */
    protected $form;

    /**
     * Restrictions
     *
     * @since  1.0.0
     *
     * @var PackageRestrictionsList The instance form which retrieve the restrictions
     */
    protected $restrictions;

    /**
     * Build Form
     *
     * @since  1.0.0
     *
     * @return $this The current instance for chaining
     */
    protected function buildForm()
    {
        $this->form->addFields($this->fields);

        return $this;
    }

    /**
     * Set the fields
     *
     * @since  1.0.0
     *
     * @param array $fields The fields to use with the form.
     *
     * @return $this The instance for chaining
     */
    protected function setFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Apply Fields Restrictions
     *
     * @since  1.0.0
     *
     * @return $this The instance for chaining
     */
    protected function applyRestrictions()
    {
        // Set the restrictions to use within the closure.
        $restrictions = $this->restrictions;

        // Filter the fields based on 'restriction' argument.
        // The restriction argument is defined within the field it self.
        $this->setFields(array_filter($this->fields, function ($field) use ($restrictions) {
            // By default allow all fields.
            $allowed = true;

            // If the restriction is set and it's exists within the restrictions list,
            // we'll allow or not depending on the value of the that restriction.
            if ($field->hasArg('restriction')) {
                // Get the restriction key from the field.
                $restriction = $field->getArg('restriction');
                // Allow field based on restriction value.
                if ($restrictions->offsetExists($restriction)) {
                    $allowed = Fw\stringToBool($restrictions->offsetGet($restriction));
                }
            }

            return $allowed;
        }));

        return $this;
    }

    /**
     * ListingForm constructor
     *
     * @since 1.0.0
     *
     * @param Forms $form The instance of the form to use.
     */
    public function __construct(Forms $form, array $fields, PackageRestrictionsList $restrictions)
    {
        $this->form         = $form;
        $this->fields       = $fields;
        $this->restrictions = $restrictions;
    }

    /**
     * Get the Form
     *
     * @since  1.0.0
     *
     * @return Forms The form instance
     */
    public function getForm()
    {
        return $this
            ->applyRestrictions()
            ->buildForm()
            ->form;
    }
}
