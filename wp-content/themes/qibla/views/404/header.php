<?php
use Qibla\Functions as F;

/**
 * 404 Header
 *
 * @since   1.0.0
 * @package Qibla\Views
 */
?>

<h1 <?php F\scopeClass('', 'title') ?>>
    <?php echo esc_html($data->title) ?>
</h1>
<p <?php F\scopeClass('', 'subtitle') ?>>
    <?php echo F\ksesPost($data->subtitle) ?>
</p>
