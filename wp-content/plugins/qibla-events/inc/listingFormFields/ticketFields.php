<?php
/**
 * Listing Form Base Fields List
 *
 * The list of the fields for the form used to create the post listing.
 *
 * The Media types that use dropzone may have some additional data key like:
 * dzrefFilesData and dzPrefilledDataRef needed by the submit script to able to update and remove the medias from the
 * server.
 *
 * Some additional keys are not allowed within the original File type. Use with caution.
 *
 * Some notes:
 * 1. use -tax- to indicate that field is a taxonomy terms container.
 * 2. use -meta- to indicate that field is a post meta.
 *
 * For more info see the names attributes and the keys of the items list.
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
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

use QiblaFramework\Functions as Fw;
use QiblaFramework\Form\Factories\FieldFactory;

$fieldFactory       = new FieldFactory();
$fieldsValues = array(
   
    'ticket' => Fw\getPostMeta('_qibla_mb_ticket_check', '', $post),
    'no_ticket' => Fw\getPostMeta('_qibla_mb_no_ticket', '', $post),
    'ticket_name' => Fw\getPostMeta('_qibla_mb_ticket_name', '', $post),
    'ticket_description' => Fw\getPostMeta('_qibla_mb_ticket_description', '', $post),
    'order_minimum_tickets' => Fw\getPostMeta('_qibla_mb_order_minimum_tickets', '', $post),
    'order_maximum_tickets' => Fw\getPostMeta('_qibla_mb_order_maximum_tickets', '', $post),
);
/*if($fieldsValues['ticket']!="on")
{?>
	<style type="text/css">
  .no_ticket{display: none;}
</style>
<?php }*/

/**
 * Listing Form Button Fields
 *
 * @since 1.0.0
 *
 * @param array $args The list of the fields
 */
return apply_filters('qibla_listings_form_ticket_fields', array(
   

    /**
     * Ticket ticket
     *
     * @since 1.2.1
     */
    'qibla_listing_form-meta-ticket_check:checkbox' => $fieldFactory->base(array(
        'type'            => 'checkbox',
        'style'           => 'toggler',
        'name'            => 'qibla_listing_form-meta-ticket_check',
        'label'           => esc_html__('Add booking to this listing', 'qibla-events'),
        'description'     => esc_html__('We	offer a	free booking function.See here for more	information', 'qibla-events'),
        'container_class' => array('ticket_booking_class','dl-field', 'dl-field--target', 'dl-field--clear-in-column'),
        'value'           => $fieldsValues['ticket'],
    )),

     'qibla_listing_form-meta-no_ticket:text'                          => $fieldFactory->base(array(
        'type'                => 'text',
        'name'                => 'qibla_listing_form-meta-no_ticket',
        'label'               => esc_html__('No	of tickets available', 'qibla-events'),
        'description'		  => esc_html__('(or leave blank for no limit)'),
        'container_class'     => array('dl-field','no_ticket', 'dl-field--text', 'dl-field--in-column'),
        'invalid_description' => esc_html__('Please only valid characters.', 'qibla-events'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
        'attrs'               => array(
            'placeholder' => esc_html_x('No of tickets available', 'placeholder', 'qibla-events'),
            'required' => 'required',
            // For tests only.
            'value'       => $fieldsValues['no_ticket'],
        ),
    )),

     'qibla_listing_form-meta-ticket_name:text'                          => $fieldFactory->base(array(
        'type'                => 'text',
        'name'                => 'qibla_listing_form-meta-ticket_name',
        'label'               => esc_html__('Event Ticket Name', 'qibla-events'),
        'description'         => esc_html__('(Early Bird,RSVP....)'),
        'container_class'     => array('dl-field','no_ticket', 'dl-field--text', 'dl-field--in-column'),
        'invalid_description' => esc_html__('Please only valid characters.', 'qibla-events'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
        'attrs'               => array(
            'placeholder' => esc_html_x('Event Ticket Name', 'placeholder', 'qibla-events'),
            'required' => 'required',
            // For tests only.
            'value'       => $fieldsValues['ticket_name'],
        ),
    )),

     'qibla_listing_form-meta-ticket_description:text'=> $fieldFactory->base(array(
        'type'                => 'text',
        'name'                => 'qibla_listing_form-meta-ticket_description',
        'label'               => esc_html__('Event Ticket Description', 'qibla-events'),
        'description'         => esc_html__('(Add Ticket description)'),
        'container_class'     => array('dl-field','no_ticket', 'dl-field--text', 'dl-field--clear-in-column'),
        'invalid_description' => esc_html__('Please only valid characters.', 'qibla-events'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
        'attrs'               => array(
            'placeholder' => esc_html_x('Tell your attendee more about the ticket type', 'placeholder', 'qibla-events'),
            'required' => 'required',
            // For tests only.
            'value'       => $fieldsValues['ticket_description'],
        ),
    )),


     'qibla_listing_form-meta-order_minimum_tickets:text'                          => $fieldFactory->base(array(
        'type'                => 'text',
        'name'                => 'qibla_listing_form-meta-order_minimum_tickets',
        'label'               => esc_html__('Order Minimum Ticket', 'qibla-events'),
        'container_class'     => array('dl-field','no_ticket','order_minimum_tickets', 'dl-field--text', 'dl-field--in-column'),
        'invalid_description' => esc_html__('Please only valid characters.', 'qibla-events'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
        'attrs'               => array(
            'placeholder' => esc_html_x('Order Minimum Ticket', 'placeholder', 'qibla-events'),
            'required' => 'required',
            // For tests only.
            'value'       => $fieldsValues['order_minimum_tickets'],
        ),
    )),

    'qibla_listing_form-meta-order_maximum_tickets:text'                          => $fieldFactory->base(array(
        'type'                => 'text',
        'name'                => 'qibla_listing_form-meta-order_maximum_tickets',
        'label'               => esc_html__('Order Maximum Ticket', 'qibla-events'),
        'container_class'     => array('dl-field','no_ticket', 'order_maximum_tickets', 'dl-field--text', 'dl-field--in-column'),
        'invalid_description' => esc_html__('Please only valid characters.', 'qibla-events'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
        'attrs'               => array(
            'placeholder' => esc_html_x('Order Maximum Ticket', 'placeholder', 'qibla-events'),
            'required' => 'required',
            // For tests only.
            'value'       => $fieldsValues['order_maximum_tickets'],
        ),
    )),

), $fieldsValues, $post);