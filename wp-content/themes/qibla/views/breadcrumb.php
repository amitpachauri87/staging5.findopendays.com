<?php
use Qibla\Functions as F;

/**
 * Brand Logo
 *
 * @since   1.0.0
 * @package Qibla\Views
 */
?>

<div <?php F\ScopeClass('breadcrumb') ?>>
    <?php echo wp_kses($data->breadcrumbMarkup, array(
        'span' => array(
            'xmlns:v' => true,
            'typeof'  => true,
            'class'   => true,
        ),
        'a'    => array(
            'href'  => true,
            'class' => true,
            'id'    => true,
        ),
    )); ?>
</div>
