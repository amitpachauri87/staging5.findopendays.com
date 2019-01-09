<?php
namespace Qibla\Functions;

/**
 * Kses Functions
 *
 * @package Qibla\Functions
 * @author  guido scialfa <dev@guidoscialfa.com>
 *
 * @license GNU General Public License, version 2
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
 * Kses Image
 *
 * This is a wrapper function for wp_kses that allow only specific  html attributes for images.
 *
 * @uses  wp_kses()
 *
 * @since 1.0.0
 *
 * @param string $img The image string to process.
 *
 * @return string The processed string containing only the allowed attributes
 */
function ksesImage($img)
{
    /**
     * Filter Kses Image
     *
     * @since 1.0.0
     *
     * @param array $list The list of the allowed attributes
     */
    $attrs = array(
        'img' => apply_filters('qibla_kses_image_allowed_attrs', array(
            'src'      => true,
            'srcset'   => true,
            'sizes'    => true,
            'class'    => true,
            'id'       => true,
            'width'    => true,
            'height'   => true,
            'alt'      => true,
            'longdesc' => true,
            'usemap'   => true,
            'align'    => true,
            'border'   => true,
            'hspace'   => true,
            'vspace'   => true,
        )),
    );

    return wp_kses($img, $attrs);
}

/**
 * Sanitize content for allowed HTML tags for post content.
 *
 * Post content refers to the page contents of the 'post' type and not $_POST
 * data from forms.
 *
 * @todo  Remove if the issue will be fixed. See below.
 *
 * @see   https://core.trac.wordpress.org/ticket/37085
 *
 * @since 1.0.0
 *
 * @param string $data Post content to filter
 *
 * @return string Filtered post content with allowed HTML tags and attributes intact.
 */
function ksesPost($data)
{
    global $allowedposttags;

    $tagsInputIncluded = array_merge($allowedposttags, array(
        'input'    => array(
            'accept'                 => true,
            'autocomplete'           => true,
            'autofocus'              => true,
            'checked'                => true,
            'class'                  => true,
            'disabled'               => true,
            'id'                     => true,
            'height'                 => true,
            'min'                    => true,
            'max'                    => true,
            'minlenght'              => true,
            'maxlength'              => true,
            'name'                   => true,
            'pattern'                => true,
            'placeholder'            => true,
            'readony'                => true,
            'required'               => true,
            'size'                   => true,
            'src'                    => true,
            'step'                   => true,
            'type'                   => true,
            'value'                  => true,
            'width'                  => true,
            'data-dzref'             => true,
            'data-autocomplete-type' => true,
        ),
        'select'   => array(
            'autofocus'        => true,
            'class'            => true,
            'id'               => true,
            'disabled'         => true,
            'form'             => true,
            'multiple'         => true,
            'name'             => true,
            'required'         => true,
            'size'             => true,
            'data-placeholder' => true,
        ),
        'option'   => array(
            'disabled' => true,
            'label'    => true,
            'selected' => true,
            'value'    => true,
        ),
        'optgroup' => array(
            'disabled' => true,
            'label'    => true,
        ),
        'textarea' => array(
            'placeholder' => true,
            'cols'        => true,
            'rows'        => true,
            'disabled'    => true,
            'name'        => true,
            'id'          => true,
            'readonly'    => true,
            'required'    => true,
            'autofocus'   => true,
            'form'        => true,
            'wrap'        => true,
        ),
        'picture'  => true,
        'source'   => array(
            'sizes'  => true,
            'src'    => true,
            'srcset' => true,
            'type'   => true,
            'media'  => true,
        ),
    ));

    // Form attributes.
    $tagsInputIncluded['form'] = array_merge($tagsInputIncluded['form'], array('novalidate' => true));
    // Fieldset attributes.
    // WordPress have an empty array.
    $tagsInputIncluded['fieldset'] = array_merge($tagsInputIncluded['fieldset'], array(
        'id'    => true,
        'class' => true,
        'form'  => true,
        'name'  => true,
    ));

    return wp_kses($data, $tagsInputIncluded);
}