<?php
/**
 * Theme Functions
 *
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    GNU General Public License, version 2
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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

if (! defined('QB_ENV')) {
    define('QB_ENV', 'prod');
}

// Get the template directory to call the function once.
$themeDirectory = untrailingslashit(get_template_directory());

// Require basic.
require_once $themeDirectory . '/src/Theme.php';
require_once $themeDirectory . '/inc/requires.php';
require_once $themeDirectory . '/src/Autoloader.php';

global $content_width;
// Depend on the width of the row.
// @todo Make it as option? see the row property on scss _row.scss file.
if (! $content_width) {
    $content_width = 960;
}

// Bootstrap.
add_action('after_setup_theme', 'Qibla\\Functions\\setupTheme', 0);
add_action('init', function () use ($themeDirectory) {
    /**
     * Qibla Init
     *
     * @since 1.0.0
     */
    do_action('qibla_init');

    // Setup Auto-loader.
    $loaderMap = include $themeDirectory . '/inc/autoloaderMapping.php';
    $loader    = new \Qibla\Autoloader();

    $loader->addNamespaces($loaderMap);
    $loader->register();

    /**
     * Discard Init Loader
     *
     * @since 2.0.0
     *
     * @param bool False to load the theme filters. True to not load them.
     */
    if (! apply_filters('qibla_discard_init_loader', false)) {
        $filters = include $themeDirectory . '/inc/filters.php';
        $filters = is_admin() ?
            array_merge($filters, include $themeDirectory . '/inc/filtersAdmin.php') :
            array_merge($filters, include $themeDirectory . '/inc/filtersFront.php');

        $init = new Qibla\Init(new Qibla\Loader(), $filters);
        // Init.
        $init->init();
    }

    // WooCommerce Init.
    if (\Qibla\Functions\isWooCommerceActive()) {
        // Require basic functions.
        require_once $themeDirectory . '/inc/requiresWc.php';

        $wcInit = new Qibla\Woocommerce\Init(
            new Qibla\Loader(),
            include $themeDirectory . '/inc/wcFiltersList.php',
            include $themeDirectory . '/inc/removeWcFiltersList.php'
        );

        $wcInit->init();
    }

    /**
     * Qibla Did Init
     *
     * @since 1.0.0
     */
    do_action('qibla_did_init');
}, 0);

// Stupid Theme Checker
// use the preg_match( '/add_action\s*\(\s*("|\')widgets_init("|\')\s*,/', $php ) -.-.
add_action('widgets_init', function () {
    $sidebarRegister = new Qibla\Sidebars\Register(
        include Qibla\Theme::getTemplateDirPath('/inc/sidebarsList.php')
    );

    $sidebarRegister->register();
}, 20);


/*Added location on just below  header title on listing page */
function my_new_function_for_address(){
    global $post;
    if(is_single()){
$address = get_post_meta($post->ID,'_qibla_mb_map_location_all_data',true);
$data = explode(":",$address);
echo '<p class="dlsubtitle header_top_address">'.$data[1].'</p>';
}
}
add_action('qibla_fw_before_subtitle','my_new_function_for_address');

/*Added bottom footer text*/

function amend_footer_text(){
    echo '<div class="dlcontainer  dlcontainer--flex"><div class="dlsocials-links"></div>
<p class="dlcopyright__content">Techhub Moorgate 101 Finsbury Pavement LONDON EC2A 1RS.</br>Registered in England. Company Number 11167976.</p>'; 
echo '</div>';
}
add_action('qibla_after_colophon','amend_footer_text');


/*add_action( 'pre_get_posts', 'my_change_sort_order',1,1000); 
    function my_change_sort_order($query){
        if(is_post_type_archive('events')):
         if($query->get('post_type')=="events"):
           $query->set('orderby', 'meta_value');
           $query->set('meta_key', '_qibla_mb_event_dates_start_for_orderby');
           $query->set('order', 'ASC');
           //Set the orderby
           //$query->set( 'orderby', 'title' );
           endif;
        endif;    
    };*/

// Function to change email address
function wpb_sender_email( $original_email_address ) {
    return 'community@odays.co';
} 
// Function to change sender name
function wpb_sender_name( $original_email_from ) {
    return 'Odays Team';
}
// Hooking up our functions to WordPress filters 
add_filter( 'wp_mail_from', 'wpb_sender_email' );
add_filter( 'wp_mail_from_name', 'wpb_sender_name' );

function text_after_content($content) {
if(is_single() && is_singular('events')) {
    global $post;
    $userid =  $post->post_author;
    $user_info = get_userdata($userid);
    $userloginname = $user_info->user_login;
    $nicename = $user_info->user_nicename;
    $profile_image = get_user_meta($userid,'profile_photo',true);
    if($profile_image!="")
      $profile_image = $profile_image;
    else
      $profile_image = get_stylesheet_directory_uri().'/images/profile_image.jpeg';

    $profile_short_desc = get_user_meta($userid,'profile_description',true);

    $tags = get_the_terms( $post->ID, 'event_tags');
    $count = count($tags);
    $all_tags = '';
    $i=1;
    foreach ($tags as $value) {
        $all_tags .= '<a href="'.get_term_link($value->term_id).'">'.$value->name.'</a>';
        if($i<$count)
         {
                $all_tags .=',&nbsp;';
         }
    $i++;
}
$content .= "<div class='short-profile-box'>";
$content .= "<div class='short-profile-image'><img alt='Profile Photo' class='profile_image_class' width='200' style='border-radius:25px;' src='".$profile_image."'></div>";
$content .= '<div class="desc_box">';
$content .= '<h1 class="dlarticle__title">Organiser</h1>';
$content .= '<h3 class="dlarticle__title">'.$nicename.'</h3>';
$content .= '<p class="dlsubtitle">'.$profile_short_desc.'</p>';
$content .= "</div></div>";
$content.= "<p>Tags:<span>".$all_tags.'</span></p>';
//$content.= "<p>Don’t forget to check them at  <a href='http://firstsiteguide.com'>WP Loop</a></p>";
}
return $content;
}

add_filter ('the_content', 'text_after_content',1,1000);

/*Remove query strings*/
//* TN - Remove Query String from Static Resources
/*function remove_css_js_ver( $src ) {
if( strpos( $src, '?ver=' ) )
$src = remove_query_arg( 'ver', $src );
return $src;
}
add_filter( 'style_loader_src', 'remove_css_js_ver', 10, 2 );
add_filter( 'script_loader_src', 'remove_css_js_ver', 10, 2 ); 
*/
function ja_global_enqueues() {
  wp_enqueue_script(
    'global',
    get_template_directory_uri() . '/assets/js/global.min.js',
    array( 'jquery' ),
    '1.0.0',
    true
  );
  wp_localize_script(
    'global',
    'global',
    array(
      'ajax' => admin_url( 'admin-ajax.php' ),
    )
  );
}
add_action( 'wp_enqueue_scripts', 'ja_global_enqueues' );


function ja_ajax_search() {
    //print_r($_POST['search']['term']);
    //exit();
    $my_args = array( 
          'post_type'  => 'events',
          "s" => $_POST['search']['term'],
          'post_status'=>'publish'
          ); 
 
$custom_query = new WP_Query( $my_args );
 
 $args = array(
    'taxonomy'      => array('event_tags','event_categories' ), // taxonomy name
    'orderby'       => 'id', 
    'order'         => 'ASC',
    'hide_empty'    => false,
    'fields'        => 'all',
    'name__like'    => $_POST['search']['term']
); 
$terms = get_terms( $args );
$cat_array = array();
$i=0;
foreach ($terms as $term) {
$cat_array[$i]['data']   = get_term_link($term->term_id);
$cat_array[$i]['value'] = html_entity_decode($term->name);
$i++;
}
$j=0;
$post_array = array();
foreach ($custom_query->posts as $key => $value) {
    $post_array[$j]['data'] = get_the_permalink($value->ID);
    $post_array[$j]['value'] = html_entity_decode($value->post_title);
    $j++;
}
$main_array[] = $cat_array + $post_array;
//echo '<pre>';
//print_r($main_array);

    wp_send_json_success( $main_array[0] );
}
add_action( 'wp_ajax_search_site',        'ja_ajax_search' );
add_action( 'wp_ajax_nopriv_search_site', 'ja_ajax_search' );


/*add_action('admin_head', 'custom_code_for_add_listing_fix');


  function custom_code_for_add_listing_fix() {
    // Initialized.
    $startDate = '';

    $args = array(
        'post_type'              => 'events',
        'post_status'            => 'pending',
        'posts_per_page'         => -1,
        'cache_results'          => false,
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
        'no_found_rows'          => true,
        'fields'                 => 'ids',
    );

    $query = new \WP_Query($args);

    $update = false;

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Check if meta exists.
            if (metadata_exists('post', get_the_ID(), '_qibla_mb_event_dates_start_for_orderby')) {
                wp_reset_query();
                return;
            }

            $multiDates = \QiblaFramework\Functions\getPostMeta('_qibla_mb_event_dates_multidatespicker') ?: null;
            if ($multiDates) {
                $multiDates = explode(',', $multiDates);

                $dates = array();
                foreach ($multiDates as $date) {
                    $date    = new \DateTime($date);
                    $dates[] = (string)$date->format('Y-m-d');
                }

                $startDate = reset($dates);
            }

            // Date for order.
            $eventTimeStar = \QiblaFramework\Functions\getPostMeta('_qibla_mb_event_start_time_timepicker', '');
            $date          = \AppMapEvents\Functions\setDateTimeFromTimeAndDate(intval($eventTimeStar), $startDate);
            $sortDate      = $date instanceof \DateTime ? $date->format('YmdHi') : '';
            update_post_meta(get_the_ID(), '_qibla_mb_event_dates_start_for_orderby', $sortDate);

            $update = true;
        }
    }
    wp_reset_query();

} */

/*Change address menu name in my account page */

add_filter( 'woocommerce_account_menu_items', 'bbloomer_rename_address_my_account', 999 );

function bbloomer_rename_address_my_account( $items ) {
$items['edit-address'] = 'Address Info';
return $items;
}

/*change text of favorites menu*/

add_filter( 'gettext', 'theme_change_comment_field_names', 20, 3 );
/**
 * Change comment form default field names.
 *
 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
 */
function theme_change_comment_field_names( $translated_text, $text, $domain ) {


        switch ( $translated_text ) {

            case "You haven't any favorite right now." :

                $translated_text = __( "You haven't any favorite's right now.", 'theme_text_domain' );
                break;

            case "When navigate the site, save the items you wish so, you'll find them here." :

                $translated_text = __( '', 'theme_text_domain' );
                break;
        }

    return $translated_text;
}

/*events expired*/

/*function my_events_archive(){
$myquery = array (
'post_type' => 'events',
    'numberposts'=>-1,
    'meta_key' => '_qibla_mb_event_dates_multidatespicker',
    'type'=> 'DATETIME',
    'meta_value' => date("Y-m-d"),
    'meta_compare' => '<'
 );

$query = new WP_Query( $myquery );
echo '<pre>';
print_r($query->posts);
exit();
}
add_action('wp_head','my_events_archive');*/
/*function Add_My_filter($views){

  
  $count = 0;
  //$my_post = [];
  $MetaQuery[] = array(
  'key'     => '_qibla_mb_event_dates_multidatespicker_end',
  'value'   => date("Y-m-d"),
  'compare' => '<',
  );

$args = array(
  'post_type'      => 'events',
  'post_status'    => 'publish',
  'posts_per_page' =>-1,
  'meta_query'     => $MetaQuery
  );

$query = new WP_Query( $args );
foreach ($query->posts as  $value) {
  $count++;
}
if($_GET['post_status']=="expired")
{
  $current = "current";
}
  $url = admin_url();
  $views['expired'] = "<a class='".$current."' href='edit.php?post_status=expired&post_type=events'>Expired <span class='count'>".$count."</span></a>";

  return $views;
}
function query_add_filter($q){
  //$q->query('post_status', 'expired');
  if(is_admin())
  {
    add_filter('views_edit-events', 'Add_My_filter');
  }
  if($_GET['post_status'] == 'expired')
  {
    //post_status
    $mq = $q->get('meta_query');
    $mq[0] = [
        'key' => '_qibla_mb_event_dates_multidatespicker_end',
        'value' => date("Y-m-d"),
        'compare' => '<'
      ];
   
    $q->set('meta_query', $mq);
  }
  
}
add_action('pre_get_posts', 'query_add_filter');*/


function create_functionality_for_book_open_day(){
  include('inc/booking_popup.php');
}
add_shortcode('book_open_day','create_functionality_for_book_open_day');

require_once dirname(__FILE__).'/html2pdf/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
add_action( 'wp_ajax_submit_review', '_submit_review' );    // If called from admin panel
    add_action( 'wp_ajax_nopriv_submit_review', '_submit_review' );    // If called from front end
    function _submit_review() {
      $tickets_available = get_post_meta($_POST['event_id'],'_qibla_mb_no_ticket',true);
      $remaining_tickets = $tickets_available - $_POST['no_of_tickets_requested'];
      $remaining_tickets1 = update_post_meta($_POST['event_id'],'_qibla_mb_no_ticket',$remaining_tickets);
      if($remaining_tickets1 < $_POST['no_of_tickets_requested'])
      {
        echo 'Sorry tickets not available';
        exit();
      }
      $table_name = 'ticket_booking';
      global $wpdb;
      $wpdb->insert( 
                    $table_name, 
                    array( 
                    'event_id' => $_POST['event_id'],
                    'event_author_id' => $_POST['event_autor_id'],
                    'event_author_email' => $_POST['event_autor_email'],
                    'user_id' => $_POST['current_user_id'],
                    'user_name' => $_POST['current_user_name'],
                    'user_email' => $_POST['current_user_email'],
                    'no_of_ticket_booked' => $_POST['no_of_tickets_requested'],
                    'date_created' =>current_time('mysql', 1),
                    )
                  );

try {
    //ob_start();
    $content = '<page backtop="25mm" backbottom="10mm" backleft="20mm" backright="20mm">
    <page_header>
        <table style="width: 100%;">
            <tr>
                <td style="text-align: left;    width: 50%"><img height="52" width="150" src="https://www.staging5.findopendays.com/wp-content/uploads/2018/11/new-logo2.png"></td>
                <td style="text-align: right;    width: 50%">hello@findopendays.com<br>https://www.findopendays.com</td>
            </tr>
        </table>
    </page_header>

    <page_footer>
        <table style="width: 100%;">
            <tr>
                <td style="text-align: left;    width: 50%">All Rights Reserved.</td>
                <td style="text-align: right;    width: 50%">page [[page_cu]]/[[page_nb]]</td>
            </tr>
        </table>
    </page_footer>
    <div style="width:70%;float:left;">'.
    '<h1 class="dlarticle__title">'.$_POST['event_title'].'</h1>
    <p class="dlsubtitle header_top_address">'.$_POST['event_location'].'</p>
    <p class="dlevents__times"><i class="la la-calendar"></i>
        From &nbsp;<span>'.
                date('d-M',strtotime($_POST['event_start_date'])).'</span>&nbsp;to &nbsp;
                <span class="dlarticle__times-out">'.
                date('d-M',strtotime($_POST['event_end_date'])).'</span>&nbsp;'.$_POST['event_start_time'].' - '.$_POST['event_end_time'].'</p>
                <p>Customer Name : '.$_POST["current_user_name"].'</p>
                <p>Number of tickets Booked : '.$_POST["no_of_tickets_requested"].'</p>
  <p>Contact Us for Queries</p>
  <p>Email : '.$_POST["event_autor_email"].'</p>
  <p>Phone : '.$_POST["event_autor_phone"].'</p>
  <br>
  <a href="#" style="padding: 0.75rem 1.75rem;background: #f26522;color: white;font-size: 20px;font-weight: normal;" class="dllisting-edit-link__link">Add to calendar</a>
    </div>
    <div style="width:30%;float:right;">
    <img src="'.$_POST['eventimage_url'].'" width="300px" height="150px">
    </div>
</page>';
    //$content = ob_get_clean();
    $event_author = $_POST["event_autor_email"];
    $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 3);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    //$html2pdf->output(dirname(__FILE__).'/example03.pdf','F');
  $to = $_POST["current_user_email"];
  $from = "hello@findopendays.com";
  $subject = "Your Event Booking";
  $message = "<p>Please see the attachment.</p>";
  $separator = md5(time());
  $eol = PHP_EOL;
  $filename = "ticket-booking.pdf";
  $pdfdoc = $html2pdf->Output('', 'S');
  $attachment = chunk_split(base64_encode($pdfdoc));


  $headers = "From: ".$from.$eol;
  $headers .= "MIME-Version: 1.0".$eol;
  $headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"".$eol.$eol;



  $body .= "Content-Transfer-Encoding: 7bit".$eol;
  $body .= "This is a MIME encoded message.".$eol; //had one more .$eol


  $body .= "--".$separator.$eol;
  $body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
  $body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
  $body .= $message.$eol; 


  $body .= "--".$separator.$eol;
  $body .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol;
  $body .= "Content-Transfer-Encoding: base64".$eol;
  $body .= "Content-Disposition: attachment".$eol.$eol;
  $body .= $attachment.$eol;
  $body .= "--".$separator."--";


  $mail = mail($to, $subject, $body, $headers);
  $mail1 = mail($event_author, $subject, $body, $headers);
  
  if($mail)
  {
    echo 'success';
  }
  else{
    echo 'not send';
  }
    //$pdfdoc = $html2pdf->output('', 'S');
} catch (Html2PdfException $e) {
    $html2pdf->clean();

    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}
      die();
    }
/**
 * Account menu items
 *
 * @param arr $items
 * @return arr
 */
function iconic_account_menu_items( $items ) {
 
    $items['ticket-booking'] = __( 'My Booking', 'iconic' );
 
    return $items;
 
}
 
add_filter( 'woocommerce_account_menu_items', 'iconic_account_menu_items', 100, 1 );

/**
 * Add endpoint
 */
function iconic_add_my_account_endpoint() {
 
    add_rewrite_endpoint( 'ticket-booking', EP_ROOT | EP_PAGES );
 
}
 
add_action( 'init', 'iconic_add_my_account_endpoint' );

/**
 * Ticket Booking content
 */

function get_no_of_tickets_booked_for_event($eventid){
  $current_user_id = get_current_user_id();
  global $wpdb;
$Tickets_booked = "SELECT SUM(no_of_ticket_booked) as ticket_booked FROM `ticket_booking` WHERE event_author_id = '".$current_user_id."' AND `event_id`='".$eventid."' ";
  $Tickets_booked1 = $wpdb->get_row($Tickets_booked);
  //print_r($Tickets_booked1);
  return $Tickets_booked1->ticket_booked;
}

function get_event_date_for_booking($eventid){
  $date = get_post_meta($eventid,'_qibla_mb_event_dates_multidatespicker_start',true);
  if($date)
  return  date('d-M',strtotime($date));
    
}
function get_event_time_for_booking($eventid){
    $start_time = get_post_meta($eventid,'_qibla_mb_event_start_time_timepicker',true);
    $end_time  = get_post_meta($eventid,'_qibla_mb_event_end_time_timepicker',true);
    if($start_time)
    $start_time1 =  date('H:i', $start_time);
    
    if($end_time)
    $end_time1 =  date('H:i', $end_time);
    return $start_time1 .'-'.$end_time1;
}

function iconic_ticket_booking_endpoint_content() {
 $current_user_id = get_current_user_id();
  global $wpdb;
  $query = "select * from `ticket_booking` WHERE `event_author_id` ='".$current_user_id."' group by `event_id`";
  
  $results = $wpdb->get_results($query);  
  if(!empty($results) && !$_GET['eventid']){?>

    <table>
      <thead>
        <tr class="booking-heading">
          <td>Open Day</td>
          <td>Event Date</td>
          <td>Event Time</td>
          <td>Tickets Available</td>
          <td>Tickets Booked</td>
          <td>GoTo Checkin</td>
        </tr>
      </thead>
      <tbody>
      <?php if(!empty($results)){
        foreach ($results as $value) {?>
         <tr>
           <td><?php echo get_the_title($value->event_id);?></td>
           <td><?php echo get_event_date_for_booking($value->event_id);?></td>
            <td><?php echo get_event_time_for_booking($value->event_id);?></td>
           <td><?php echo get_post_meta($value->event_id,'_qibla_mb_no_ticket',true);?></td>
           <td><?php echo get_no_of_tickets_booked_for_event($value->event_id);?></td>
           <td><a href="?eventid=<?php echo $value->event_id;?>">Goto checking</a></td>
           </tr>
         <?php } 
         }?>
        
          
        
      </tbody>
    </table>
    <?php 
  }
  elseif ($_GET['eventid']) {
    $current_user_id = get_current_user_id();
  global $wpdb;
  $query = "select * from `ticket_booking` WHERE `event_author_id` ='".$current_user_id."' AND `event_id`=".$_GET['eventid'];
  
  $results = $wpdb->get_results($query);  
  if(!empty($results)){?>
    <h3><?php echo get_the_title($_GET['eventid']);?></h3>
    <p>Ticket Type : <?php echo get_post_meta($_GET['eventid'],'_qibla_mb_ticket_name',true);?></p>
    <h5 style="text-decoration: underline;">Tickets that are booked Showing below</h5>
    <table id="example1">
      <thead>
        <tr class="booking-heading">
          <td>Name</td>
          <td>Email</td>
          <td>Phone</td>
          <td>Number of tickets</td>
          <td>Check In</td>
        </tr>
      </thead>
      <tbody>
      <?php if(!empty($results)){
        foreach ($results as $value) {
          $user_info = get_userdata($value->user_id);
          $user_email = $user_info->user_email;
          $user_name = $user_info->display_name;
          $phone = get_user_meta($value->user_id,'phone_number',true);
          ?>
         <tr>
           <td><?php echo $user_name;?></td>
           <td><?php echo $user_email;?></td>
            <td><?php echo $phone;?></td>
           <td><?php echo $value->no_of_ticket_booked;?></td>
           <td><input type="checkbox" name="user_checking"></td>
           </tr>
         <?php } 
         }?>
        
          
        
      </tbody>
    </table>
  <?php 
}
}
  else{
    echo '<p>You have no booking events available Please add listings if you want to sell tickets <a href="'.home_url().'/event-package/free/">Add Open Day</a></p>';
  }
    ?>
<?php }

add_action( 'woocommerce_account_ticket-booking_endpoint', 'iconic_ticket_booking_endpoint_content' );


/*
 * Change the order of the endpoints that appear in My Account Page - WooCommerce 2.6
 * The first item in the array is the custom endpoint URL - ie http://mydomain.com/my-account/my-custom-endpoint
 * Alongside it are the names of the list item Menu name that corresponds to the URL, change these to suit
 */
function wpb_woo_my_account_order() {
  $myorder = array(
    'dashboard'         => __('My Profile','woocommerce'),
    'edit-account'         => __('Edit Profile','woocommerce'),
    'my-listings'         => __('My Listings','woocommerce'),
    'ticket-booking'    => __( 'My Booking', 'woocommerce' ),
    'my-favorites'         => __('My Favorites','woocommerce'), 
    'customer-logout'    => __( 'Logout', 'woocommerce' ),
  );
  return $myorder;
}
add_filter ( 'woocommerce_account_menu_items', 'wpb_woo_my_account_order',100,1 );

/**
 * Helper: is endpoint
 */
/*function iconic_is_endpoint( $endpoint = false ) {

    global $wp_query;

    if( !$wp_query )
        return false;

    return isset( $wp_query->query[ $endpoint ] );

}*/

/*show user stats page in my account section*/
function count_current_user_events(){
$args = array(
    'post_type'  => 'events',
    'author'     => get_current_user_id(),
);
$user_posts = get_posts($args);
if (count($user_posts)) 
  return count($user_posts);
}

if(count_current_user_events()>=1)
{
  function wpb_stats_my_account_order() {
  $myorder = array(
    'dashboard'         => __('My Profile','woocommerce'),
    'edit-account'         => __('Edit Profile','woocommerce'),
    'my-listings'         => __('My Listings','woocommerce'),
    'stats-dashboard'         => __('My Stats','woocommerce'),
    'ticket-booking'    => __( 'My Booking', 'woocommerce' ),
    'my-favorites'         => __('My Favorites','woocommerce'), 
    'customer-logout'    => __( 'Logout', 'woocommerce' ),
  );
  return $myorder;
}
add_filter ( 'woocommerce_account_menu_items', 'wpb_stats_my_account_order',100,1 );

  function iconic_stats_menu_items( $items ) {
 
    $items['stats-dashboard'] = __( 'My Stats', 'iconic' );
 
    return $items;
 
}
 
add_filter( 'woocommerce_account_menu_items', 'iconic_stats_menu_items', 100, 1 );

/**
 * Add endpoint
 */
function iconic_stats_my_account_endpoint() {
 
    add_rewrite_endpoint( 'stats-dashboard', EP_ROOT | EP_PAGES );
 
}
 
add_action( 'init', 'iconic_stats_my_account_endpoint' );


function iconic_stats_dashboard_endpoint_content(){?>
  <table>
    <thead>
      <tr>
        <td>Event Name</td>
        <td>Comments Count</td>
        <td>Event Views</td>
        <td>Event Impressions</td>
        <td>Avg. Rating</td>
      </tr>
    </thead>
    <tbody>
      <?php $current_user_id = get_current_user_id();
  wp_reset_query();
  global $wpdb;
  $args = array(
    'post_type'  => 'events',
    'author'     => get_current_user_id(),
    'post_status'=>'publish',
    'posts_per_page'=>-1,
);
  $user_query = new WP_Query($args);

  if ($user_query->have_posts()) {
        while ($user_query->have_posts()) {
            $user_query->the_post();
            $pid = get_the_ID();?>
            <tr>
              <td><a href="<?php the_permalink();?>"><?php the_title();?></td>
              <td> <?php echo get_comments_number( $pid ); ?> </td>
              <td><?php  echo pvc_get_post_views($pid);?></td>
              <td><?php echo wp_statistics_pages( 'total', "", $pid); ?></td>
              <td><?php echo average_rating($pid);?></td>
            </tr>
            <?php }
            }?>
    </tbody>
  </table>
<?php }
add_action( 'woocommerce_account_stats-dashboard_endpoint', 'iconic_stats_dashboard_endpoint_content' );

}

/*Average rating*/
function average_rating($postid) {
    global $wpdb;
    $post_id = $postid;
    $ratings = $wpdb->get_results("

      SELECT $wpdb->commentmeta.meta_value
      FROM $wpdb->commentmeta
      INNER JOIN $wpdb->comments on $wpdb->comments.comment_id=$wpdb->commentmeta.comment_id
      WHERE $wpdb->commentmeta.meta_key='_qibla_mb_comment_rating' 
      AND $wpdb->comments.comment_post_id=$post_id 
      AND $wpdb->comments.comment_approved =1

      ");
    $counter = 0;
    $average_rating = 0;    
    if ($ratings) {
      foreach ($ratings as $rating) {
        $average_rating = $average_rating + $rating->meta_value;
        $counter++;
      } 
      //echo $average_rating;
      //echo $counter;
      //round the average to the nearast 1/2 point
      return ((($average_rating/$counter)*2)/2);  
    } else {
      //no ratings
      return 'no rating';
    }
  }

add_action( 'woocommerce_edit_account_form', 'my_woocommerce_edit_account_form' );
add_action( 'woocommerce_save_account_details', 'my_woocommerce_save_account_details' );

function my_woocommerce_save_account_details( $user_id ) {

        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        
       // $_FILES['profile_photo'];
        
        
            if ($_FILES['profile_photo']['error']!=4) {      
                $_FILES = array("profile_photo" => $_FILES['profile_photo']);
                $attachment_id = media_handle_upload("profile_photo", 0);
               $attachment_url = wp_get_attachment_url($attachment_id);
                $attachment_url; 
            update_user_meta( $user_id, 'profile_photo', $attachment_url );
            
}
update_user_meta($user_id,'profile_description',$_POST['profile_description']);
}

function my_woocommerce_edit_account_form() {

$user_id = get_current_user_id();
$user = wp_get_current_user();
    $value = get_the_author_meta( 'profile_photo', $user->ID );

    $allowed_mime_types = array('jpg', 'jpeg', 'gif', 'png');
    ?>

    <fieldset>
      <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <?php if(get_user_meta( $user_id, 'profile_photo',true )!=""){?>
        <label class="">Change Profile Photo</label>
        <img src="<?php echo get_user_meta( $user_id, 'profile_photo',true );?>" width="150"><br>
          <input type="file" name="profile_photo">
          <?php }
          else{?>
          <label class="">Profile Photo</label>
          <input type="file" name="profile_photo">
          <?php }?>
        </p>
    </fieldset>
    <fieldset>
      <label>Short Description</label>
      <textarea name="profile_description" cols="20" rows="4"><?php echo get_user_meta( $user_id, 'profile_description',true );?></textarea>
    </fieldset>
<?php }