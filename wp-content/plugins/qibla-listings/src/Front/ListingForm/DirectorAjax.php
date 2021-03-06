<?php
/**
 * Director Ajax
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

namespace QiblaListings\Front\ListingForm;

/**
 * Class DirectorAjax
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Front\ListingForm
 */
class DirectorAjax extends Director
{
    /**
     * Get Fields Properties
     *
     * @todo   This will be removed in a feaature version when the Iterators and Array access will be implemented in
     *       Forms, Fields and Types.
     *
     * @since  1.0.0
     * @access protected
     *
     * @param array $fields    The Field objects from which extract the arguments.
     * @param array $fieldsKey An array of keys referencing the field arguments.
     *
     * @return array The array containg the fields to return from the ajax request.
     */
    protected function getFieldsProperties($fields, $fieldsKey)
    {
        $fieldsProperties = array();

        foreach ($this->getForm()->getFields() as $field) {
            if (in_array($field->getArg('name'), $fieldsKey, true)) {
                $fieldsProperties[$field->getArg('name')] = array(
                    'selector'           => '#' . $field->getType()->getArg('id'),
                    'invalidDescription' => $field->getInvalidDescription(),
                    'attrs'              => $field->getArg('attrs'),
                    'containerClass'     => $field->getArg('container_class'),
                );
            }
        }

        return $fieldsProperties;
    }

    /**
     * @inheritdoc
     */
    public function director()
    {
        $response = parent::director();

        // If the response is invalid, most probably the validation failed, so we need to inform the user.
        // Make the properties fields and set them as response data.
        $responseData = $response->getData();
        if (! $response->isValidStatus() && isset($responseData['validation_data']['invalid'])) {
            $validationInvalidFields = $this->getFieldsProperties(
                $this->form->getFields(),
                array_keys($responseData['validation_data']['invalid'])
            );
            $response->setData($validationInvalidFields, false);
        }

        return $response;
    }
}
