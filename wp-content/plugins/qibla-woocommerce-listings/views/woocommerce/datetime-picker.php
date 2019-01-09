<?php
if (wp_script_is('wc-bookings-date-picker', 'registered')) {
    wp_enqueue_script('wc-bookings-date-picker');
}

if (wp_script_is('wc-bookings-time-picker', 'registered')) {
    wp_enqueue_script('wc-bookings-time-picker');
}
extract($field);

$month_before_day = strpos(__('F j, Y'), 'F') < strpos(__('F j, Y'), 'j');
?>
<fieldset class="wc-bookings-date-picker <?php echo implode(' ', $class); ?>">
    <legend>
        <span class="label"><?php echo $label; ?></span>:
        <small class="wc-bookings-date-picker-choose-date"><?php _e('Choose...', 'qibla-woocommerce-listings'); ?></small>
    </legend>
    <div class="picker" data-display="<?php echo esc_attr($display); ?>"
         data-availability="<?php echo esc_attr(json_encode($availability_rules)); ?>"
         data-default-availability="<?php echo esc_attr($default_availability) ? 'true' : 'false'; ?>"
         data-fully-booked-days="<?php echo esc_attr(json_encode($fully_booked_days)); ?>"
         data-partially-booked-days="<?php echo esc_attr(json_encode($partially_booked_days)); ?>"
         data-min_date="<?php echo ! empty($min_date_js) ? esc_attr($min_date_js) : 0; ?>"
         data-max_date="<?php echo esc_attr($max_date_js); ?>" data-default_date="<?php echo esc_attr($default_date); ?>"></div>
    <div class="wc-bookings-date-picker-date-fields">
        <?php
        // woocommerce_bookings_mdy_format filter to choose between month/day/year and day/month/year format
        if ($month_before_day && apply_filters('woocommerce_bookings_mdy_format', true)) : ?>
            <label>
                <input type="text" name="<?php echo esc_attr($name); ?>_month"
                       placeholder="<?php esc_html_e('mm', 'qibla-woocommerce-listings'); ?>" size="2"
                       class="required_for_calculation booking_date_month"/>
                <span><?php _e('Month', 'qibla-woocommerce-listings'); ?></span>
            </label> / <label>
                <input type="text" name="<?php echo esc_attr($name); ?>_day"
                       placeholder="<?php esc_html_e('dd', 'qibla-woocommerce-listings'); ?>" size="2"
                       class="required_for_calculation booking_date_day"/>
                <span><?php _e('Day', 'qibla-woocommerce-listings'); ?></span>
            </label>
        <?php else : ?>
            <label>
                <input type="text" name="<?php echo esc_attr($name); ?>_day"
                       placeholder="<?php esc_html_e('dd', 'qibla-woocommerce-listings'); ?>" size="2"
                       class="required_for_calculation booking_date_day"/>
                <span><?php _e('Day', 'qibla-woocommerce-listings'); ?></span>
            </label> / <label>
                <input type="text" name="<?php echo esc_attr($name); ?>_month"
                       placeholder="<?php esc_html_e('mm', 'qibla-woocommerce-listings'); ?>" size="2"
                       class="required_for_calculation booking_date_month"/>
                <span><?php _e('Month', 'qibla-woocommerce-listings'); ?></span>
            </label>
        <?php endif; ?>
        / <label>
            <input type="text" value="<?php echo date('Y'); ?>" name="<?php echo esc_attr($name); ?>_year"
                   placeholder="<?php esc_html_e('YYYY', 'qibla-woocommerce-listings'); ?>" size="4"
                   class="required_for_calculation booking_date_year"/>
            <span><?php _e('Year', 'qibla-woocommerce-listings'); ?></span>
        </label>
    </div>
</fieldset>

<div class="form-field form-field-wide">
    <label for="<?php echo esc_attr($name); ?>" class="screen-reader-text"><?php esc_html_e('Time', 'qibla-woocommerce-listings'); ?>:</label>
    <ul class="block-picker">
        <li class="screen-reader-text"><?php esc_html_e('Choose a date above to see available times.', 'qibla-woocommerce-listings'); ?></li>
    </ul>
    <input type="hidden" class="required_for_calculation" name="<?php echo esc_attr($name); ?>_time" id="<?php echo esc_attr($name); ?>"/>
</div>
