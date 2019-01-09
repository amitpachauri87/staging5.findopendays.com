<?php

use Qibla\Functions as F;

/**
 * View Archive Title
 *
 * @since   1.0.0
 * @package Qibla
 */

if ($data->archiveTitle) : ?>
    <header <?php F\scopeClass('', 'header') ?>>

        <?php
        /**
         * Before Title
         *
         * @since 2.0.0
         *
         * @param \stdClass $data The current data object.
         */
        do_action('qibla_archive_before_title', $data); ?>

        <h1 <?php F\scopeClass('', 'title') ?>>
            <?php echo wp_kses($data->archiveTitle, array('span' => array('class' => true, 'id' => true))) ?>
        </h1>

        <?php
        /**
         * Before Title
         *
         * @since 2.0.0
         *
         * @param \stdClass $data The current data object.
         */
        do_action('qibla_archive_after_title', $data); ?>

        <?php if ($data->archiveDescription) : ?>
            <p <?php F\scopeClass('', 'description') ?>>
                <?php
                // @codingStandardsIgnoreLine
                echo F\ksesPost($data->archiveDescription) ?>
            </p>
        <?php endif; ?>
    </header>
<?php endif; ?>