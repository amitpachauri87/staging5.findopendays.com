<?php
//define('WP_USE_THEMES', true);

/* Loads the WordPress Environment and Template */

require_once("wp-load.php");

	$args = array(
		'post_type'=>'events',
		'post_status'=>'publish',
		'posts_per_page'=>-1
		);
	$query = new WP_Query($args);
if ( $query->have_posts() ) :
	while ( $query->have_posts() ) : $query->the_post();
		 $id = get_the_ID();
		$open_day_event_date = strtotime(get_post_meta($id,'_qibla_mb_event_dates_multidatespicker_end',true));

		$this_week_start  = strtotime(date("Y") . 'W' . date("W"));
		//echo "<br>";
		 $this_week_end = strtotime(date("Y") . 'W' . date("W")) + 518400;
		//echo "<br>";
		 $next_week_start = strtotime(date("Y") . 'W' . (date("W")+1));
		//echo "<br>";
		 $next_week_end = strtotime(date("Y") . 'W' . (date("W")+1)) + 518400;
		//echo "<br>";
		 $this_month_start = strtotime(date('Y-m-d', strtotime('first day of this month')));
		//echo "<br>";
		 $this_month_end = strtotime(date('Y-m-d', strtotime('last day of this month')));
		//echo "<br>";
		 $next_month_start = strtotime(date('Y-m-d', strtotime('first day of next month')));
		//echo "<br>";
		 $next_month_end = strtotime(date('Y-m-d', strtotime('last day of next month')));
		//echo "<br>";
		if (($open_day_event_date > $this_week_start) && ($open_day_event_date < $this_week_end)){
			wp_set_post_terms( $id, array('this-week'), 'event_tags',true);
		}
		if (($open_day_event_date > $next_week_start) && ($open_day_event_date < $next_week_end)){
			wp_set_post_terms( $id, array('next-week'), 'event_tags',true);
		}
		if (($open_day_event_date > $this_month_start) && ($open_day_event_date < $this_month_end)){
			wp_set_post_terms( $id, array('this-month'), 'event_tags',true);
		}
		if (($open_day_event_date > $next_month_start) && ($open_day_event_date < $next_month_end)){
			wp_set_post_terms( $id, array('next-month'), 'event_tags',true);
		}
		if($open_day_event_date < time())
		{
			wp_remove_object_terms( $id, array('next-month','next-week','this-month','this-week'),'event_tags');
			wp_update_post(array(
					        'ID'    =>  $id,
					        'post_status'   =>  'draft'
        					));
		}
		//wp_set_post_terms( $id, 1423, 'event_tags');
	endwhile;
endif;

mail('piyush.dhanotiya@cisinlabs.com','cron_main_domain','main domain cron working');