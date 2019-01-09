<?php 
global $post;
//print_r(get_post_meta($post->ID));
	$title = get_the_title($post->ID);
    $subtitle = get_post_meta($post->ID,'_qibla_mb_sub_title',true);
    $image = get_post_meta($post->ID,'_qibla_mb_jumbotron_background_image',true);
    $image_url1 = wp_get_attachment_image_src($image,'full');
    $eventimage_url = trim($image_url1[0]);
	$address = get_post_meta($post->ID,'_qibla_mb_map_location_all_data',true);
	$address_data = explode(":",$address);
	$date = get_post_meta($post->ID,'_qibla_mb_event_dates_multidatespicker_start',true);
    $start_time = get_post_meta($post->ID,'_qibla_mb_event_start_time_timepicker',true);
    $end_time  = get_post_meta($post->ID,'_qibla_mb_event_end_time_timepicker',true);
    $start_time1 =  date('H:i', $start_time);
    $end_time1 =  date('H:i', $end_time);
    $event_end_date = get_post_meta($post->ID,'_qibla_mb_event_dates_multidatespicker_end',true);
    $event_end_date1 = $event_end_date.'T'.$start_time1.$end_time1;
    $src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full', false );
    $lat = get_post_meta($post->ID,'_qibla_mb_map_location_lat',true);
    $logi =  get_post_meta($post->ID,'_qibla_mb_map_location_lng',true);
    $email = get_post_meta($post->ID,'_qibla_mb_business_email',true);
    $phone = get_post_meta($post->ID,'_qibla_mb_business_phone',true);
    $available_tickets = get_post_meta($post->ID,'_qibla_mb_no_ticket',true);
    $ticket_name = get_post_meta($post->ID,'_qibla_mb_ticket_name',true);
    $ticket_description = get_post_meta($post->ID,'_qibla_mb_ticket_description',true);
    $minimum_tickets = get_post_meta($post->ID,'_qibla_mb_order_minimum_tickets',true);
    $maximum_tickets = get_post_meta($post->ID,'_qibla_mb_order_maximum_tickets',true);

    /*user information*/
    $current_user = wp_get_current_user();
    $current_user_id = get_current_user_id();
    $current_user_email = $current_user->user_email;
    $current_user_name = $current_user->display_name;

    /*event author info*/
    $author_id = $post->post_author; 
   // echo $author_name = author_meta( 'user_nicename' , $author_id );
    //echo "<pre>";
    //print_r(get_post_meta($post->ID));
?>

<h1 class="dlarticle__title"><?php echo $title;?></h1>
<p class="dlsubtitle header_top_address"><?php echo $address_data[1];?></p>
<p class="dlsubtitle"><?php echo $subtitle;?></p>
<p class="dlevents__times"><i class="la la-calendar"></i>
        From &nbsp;<time class="dlarticle__times-in">
                <?php echo date('d-M',strtotime($date));?></time>&nbsp;to &nbsp;
                <time class="dlarticle__times-out">
                <?php echo date('d-M',strtotime($event_end_date));?></time>&nbsp;<?php echo $start_time1;?> - <?php echo $end_time1;?> </p>
<b class="popup_success_message" style="display: none;color: green;">Your booking has been done will send you tickets via email</b>
<form action="" id="booking_popup_form" method="post">
	<label>Number of Tickets Requested (Available tickets: <?php echo $available_tickets;?>)</label>
	<input type="number" required="required" min="<?php echo $minimum_tickets;?>" max="<?php echo $maximum_tickets;?>" name="no_of_tickets_requested" value="">
	<input type="hidden" name="event_id" value="<?php echo $post->ID;?>">
	<input type="hidden" name="event_title" value="<?php echo $title;?>">
    <input type="hidden" name="eventimage_url" value="<?php echo $eventimage_url;?>">
	<input type="hidden" name="event_start_date" value="<?php echo $date;?>">
	<input type="hidden" name="event_end_date" value="<?php echo $event_end_date;?>">
	<input type="hidden" name="event_start_time" value="<?php echo $start_time1;?>">
	<input type="hidden" name="event_end_time" value="<?php echo $end_time1;?>">
	<input type="hidden" name="event_location" value="<?php echo $address_data[1];?>">
	<input type="hidden" name="event_autor_id" value="<?php echo $author_id;?>">
	<input type="hidden" name="event_autor_email" value="<?php echo $email;?>">
	<input type="hidden" name="event_autor_phone" value="<?php echo $phone;?>">
	<input type="hidden" name="current_user_id" value="<?php echo $current_user_id;?>">
	<input type="hidden" name="current_user_email" value="<?php echo $current_user_email;?>">
	<input type="hidden" name="current_user_name" value="<?php echo $current_user_name;?>">
	<label></label>
	<br>
	<input type="submit" name="submit" class="booking_now_button" value="Book Now" placeholder="No of Tickets">
</form>
<br>
<p>If any issues please contact us</p>
<p>Contact email of organiser  <a href="mailto:<?php echo $email;?>"><?php echo $email;?></a></p>
<p>Contact phone number <a href="tel:<?php echo $phone;?>"><?php echo $phone;?></a></p>