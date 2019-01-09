<?php
/**
 * Taxonomy Relation Fields
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @package   dreamlist-framework
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

use \QiblaFramework\Functions as F;
use \QiblaFramework\Form\Factories\FieldFactory;

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

return apply_filters('qibla_tb_taxonomy_relation_field', array(

    'qibla_tb_taxonomy_term_groups_title:select' => $fieldFactory->termbox(array(
        'type'         => 'select',
        'select2'      => true,
        'name'         => 'qibla_tb_taxonomy_term_groups_title',
        'value'        => F\getTermMeta('_qibla_tb_taxonomy_term_groups_title', self::$currTerm->term_id),
        'label'        => esc_html__('The group title', 'qibla-framework'),
        'description'  => esc_html__('Enter or select the title for the group (default: none).', 'qibla-framework'),
        'exclude_all'  => true,
        'exclude_none' => true,
        'display'      => array($this, 'displayField'),
        'options' => array_merge(
            array('none' => esc_html__('No Group', 'qibla-framework')),
            F\getTermsTitleOptions(self::$currTerm->term_id)
        ),
        'attrs'        => array(
            'data-tagging' => 'yes',
            'class'        => 'dlselect2--wide',
        ),
    )),

    'qibla_tb_taxonomy_term_relation:multicheck' => $fieldFactory->termbox(array(
        'type'        => 'multi_check',
        'name'        => 'qibla_tb_taxonomy_term_relation',
        'value'       => F\getTermMeta('_qibla_tb_taxonomy_term_relation', self::$currTerm->term_id, 'all'),
        'label'       => esc_html__('Category Term Relation', 'qibla-framework'),
        'description' => esc_html__(
            'Choose the term from the Listings Categories related to the current one.',
            'qibla-framework'
        ),
        'exclude_all' => true,
        'display'     => array($this, 'displayField'),
        'options'     => F\getTermsList(array(
            'taxonomy'   => apply_filters('qibla_taxonomy_relation_term_list', 'listing_categories'),
            'hide_empty' => false,
        )),
    )),
));
