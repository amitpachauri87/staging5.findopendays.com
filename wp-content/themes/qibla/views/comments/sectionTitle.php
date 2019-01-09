<?php
use Qibla\Functions as F;

/**
 * View Comments Section Title
 *
 * @since   1.0.0
 * @package Qibla\Views
 */

if ($data->commentTitle) : ?>
    <h2 <?php F\scopeClass('comments', 'title') ?>>
        <?php echo esc_html(wp_strip_all_tags($data->commentTitle)) ?>
    </h2>
<?php endif; ?>
