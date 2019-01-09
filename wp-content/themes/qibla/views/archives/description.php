<?php
use Qibla\Functions as F;

/**
 * Archive Description
 *
 * @since   1.0.0
 * @package Qibla\Views
 */

if ($data->description) : ?>

    <div <?php F\scopeClass('archive-description') ?>>

        <?php
        /**
         * Before Archive Description Content
         *
         * @since 1.0.0
         */
        do_action('qibla_before_archive_description_content'); ?>

        <?php if ($data->description) : ?>
            <div <?php F\scopeClass('archive-description', 'content') ?>>
                <?php
                // @codingStandardsIgnoreLine
                echo F\ksesPost($data->description) ?>
            </div>
        <?php endif; ?>

        <?php
        /**
         * After Archive Description Content
         *
         * @since 1.0.0
         */
        do_action('qibla_after_archive_description_content'); ?>

    </div>

    <?php
endif;
