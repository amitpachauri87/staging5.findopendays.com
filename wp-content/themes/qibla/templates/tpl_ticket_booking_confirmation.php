<?php 
/*
Template Name:Ticket Booking Confirmation
*/
get_header();?>
<?php 
require_once dirname(__FILE__).'/../html2pdf/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;


/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/
	$post_id = $_POST['event_id'];
	$title = get_the_title($post_id);
  $my_post = get_post($post_id);
   $my_post_content = $my_post->post_content;
    $my_post_content1 = apply_filters('the_content', $my_post_content);
    $subtitle = get_post_meta($post_id,'_qibla_mb_sub_title',true);
    $image = get_post_meta($post_id,'_qibla_mb_jumbotron_background_image',true);
    $image_url1 = wp_get_attachment_image_src($image,'full');
    $eventimage_url = trim($image_url1[0]);
	$address = get_post_meta($post_id,'_qibla_mb_map_location_all_data',true);
	$address_data = explode(":",$address);
	$date = get_post_meta($post_id,'_qibla_mb_event_dates_multidatespicker_start',true);
    $start_time = get_post_meta($post_id,'_qibla_mb_event_start_time_timepicker',true);
    $end_time  = get_post_meta($post_id,'_qibla_mb_event_end_time_timepicker',true);
    if($start_time)
    $start_time1 =  date('H:i', $start_time);
   if($end_time)
    $end_time1 =  date('H:i', $end_time);
    $event_end_date = get_post_meta($post_id,'_qibla_mb_event_dates_multidatespicker_end',true);
    $event_end_date1 = $event_end_date.'T'.$start_time1.$end_time1;
    $src = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full', false );
    $lat = get_post_meta($post_id,'_qibla_mb_map_location_lat',true);
    $logi =  get_post_meta($post_id,'_qibla_mb_map_location_lng',true);
    $email = get_post_meta($post_id,'_qibla_mb_business_email',true);
    $phone = get_post_meta($post_id,'_qibla_mb_business_phone',true);
    $available_tickets = get_post_meta($post_id,'_qibla_mb_no_ticket',true);
    $ticket_name = get_post_meta($post_id,'_qibla_mb_ticket_name',true);
    $ticket_description = get_post_meta($post_id,'_qibla_mb_ticket_description',true);
    $minimum_tickets = get_post_meta($post_id,'_qibla_mb_order_minimum_tickets',true);
    $maximum_tickets = get_post_meta($post_id,'_qibla_mb_order_maximum_tickets',true);

    $tickets_available = get_post_meta($_POST['event_id'],'_qibla_mb_no_ticket',true);
      $remaining_tickets = $tickets_available - $_POST['no_of_tickets_requested'];
      $remaining_tickets1 = update_post_meta($_POST['event_id'],'_qibla_mb_no_ticket',$remaining_tickets);
      if(empty($_POST))
        {?>
        <div id="dlcontent" class="dlwrapper ticket_booking_div">
      <div class="dlcontainer  dlcontainer--flex">
      <main id="dlmain" class="dlarchive">
      <h3>Please select the ticket and then check back later</h3>
      </main>
      </div>
      </div>
        <?php }
      else if($remaining_tickets1 < $_POST['no_of_tickets_requested'])
      {
        echo 'Sorry tickets not available';
        exit();
      }
      else{
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
                <td style="text-align: left;    width: 50%"></td>
                <td style="text-align: right;    width: 50%"></td>
    </page_header>

    <page_footer>
        <table style="width: 100%;">
            <tr>
                <td style="text-align: left;    width: 50%">All Rights Reserved.</td>
                <td style="text-align: right;    width: 50%">page [[page_cu]]/[[page_nb]]</td>
            </tr>
        </table>
    </page_footer>
   <!doctype html>
<html>
<head>
<meta charset="utf-8">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
</head>
<body style="padding: 0;margin: 0; font-family: "Open Sans", Arial, Helvetica, sans-serif;">
<table cellpadding="0" cellspacing="0" width="100%" align="center" >
    <tr>
        <td style="padding:5px;background-color: #f26522;"></td>
    </tr>
    <tr>
        <td style="width:100%;padding:30px;background-image: url(https://www.staging5.findopendays.com/wp-content/uploads/2018/12/header_img2-1.jpg);background-repeat: no-repeat;background-position: center;
width: 100%;text-align: center;background-size:100% 100%;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;">
<a href="#" style="border: 0;outline: none;text-decoration: none;color: #000000;margin: auto;text-align: center; padding:0 0 30px 0;display: block;"><img alt="Event Logo" title="Event Logo" src="https://www.staging5.findopendays.com/wp-content/uploads/2018/11/new-logo.png"></a>

            <h1 style="font-size: 32px;color: #ffffff;font-weight: 100;line-height: 40px;margin: 0;padding:0 0 30px; display:block">'.$title.'</h1>
            
            <p style="font-size: 14px;color: #ffffff;font-weight: 100;line-height: 20px;margin:0; padding:0 0 10px;">'.$my_post_content.'</p>
            <p style="font-size: 14px;color: #ffffff;font-weight: 100;line-height: 20px;margin:0; padding:0 0 10px;">If you have any queries, <br>please call or email the organiser: +44 (0)20 3897 2032 or email: study@sgul.ac.uk</p>
            <a href="#" style="font-size: 14px;color: #ffffff !important;font-weight: bold;border: 0;outline: none;text-decoration: none"><font style="color: #ffffff !important;" color="#ffffff">You are ready to go!</font></a> <br />
            <br />
            <a href="#" style="text-decoration: none;color: #ffffff;font-size: 14px;font-weight: 100;background-color: #f26522;padding: 5px 10px 5px 20px;display: inline-block;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/btn.jpg" alt="add to calendar"></a></td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0" width="100%" align="center" >
    <tr>
        <td style="text-align:center;background-color: #2d2d2d; padding:30px 20px; width:50%" valign="top">
        <img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/location-1.png" alt="Location" title="Location" style="text-align: center;"><br><br>
            <h2 style="color: #dddddd;font-size: 15px;margin: 0;padding: 10px 0 10px;font-weight: normal;text-align: center;">'.$address_data[1].'</h2><br>
            <a href="#" style="color: #999999;text-align: center !important;border: 0;outline: none;text-decoration: none;font-size: 14px;">'.$title.'</a></td>
        <td style="text-align:center;background-color: #2d2d2d; padding:30px 20px; width:50%" valign="top"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/time-1.png" alt="Location" title="Location" style="text-align: center;"><br><br>
            <h2 style="color: #dddddd;font-size: 15px;margin: 0;padding: 10px 0 10px;font-weight: normal;text-align: center;">'.date('l,d F Y',strtotime($_POST['event_start_date'])).'</h2><br>
            <a href="#" style="color: #999999;text-align: center !important;border: 0;outline: none;text-decoration: none;font-size: 14px;">'.$_POST['event_start_time'].' - '.$_POST['event_end_time'].'</a></td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0" width="100%" align="center" >';
for($i=1; $i<= $_POST['no_of_tickets_requested'];$i++){
    $content .='<tr>
        <td><table cellpadding="0" cellspacing="0" width="100%" align="center" >
                <tr>
                    <td style=" padding:20px 30px; background-color:#fff" align="center"><barcode type="C39" label="none" value="45" style="width: 40mm; height: 18mm; font-size: 4mm"></barcode></td>
                    <td style=" padding:30px 0; background-color:#fff" align="center" valign="top"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/ticket-1.png" alt="Ticket" title="Ticket" style="text-align: center;"></td>
                    <td style=" padding:20px; background-color:#fff"><h3 style="color: #333333;font-size: 16px;font-weight: normal; margin:0; padding:0 0 10px 0">Ticket details</h3>
                        <p style="color: #333333 ;text-decoration: none !important; font-size:14px; margin:0">02515684654 </p>
                        <p style="color: #333333 ;text-decoration: none !important; font-size:14px; margin:0">'.$title.' </p>
                        <p style="color: #333333 ;text-decoration: none !important; font-size:14px; margin:0">'.$ticket_name.' </p>
                        <p style="color: #333333 ;text-decoration: none !important; font-size:14px; margin:0">'.$_POST['current_user_name'].' </p>
                        </td>
                </tr>
            </table></td>
    </tr>
    <tr>
        <td><table cellpadding="0" cellspacing="0" width="100%" align="center" >
                <tr>
                    <td style="background-color: #fff;" align="center"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/cut1.png" style="border: 0px solid #ffffff !important;outline: none;display: block;background-color: #ffffff;margin: auto;"></td>
                    <td style="background-color: #fff;" align="center"><img class="cut2" src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/cut2.png" style="border: 0px solid #ffffff !important;outline: none;display: block;background-color: #ffffff;margin: auto;"></td>
                </tr>
            </table></td>
    </tr>';
}    
$content .='</table>
<table cellpadding="0" cellspacing="0" width="100%" align="center" >
    <tr>
        <td style="background-color: #f26522; padding:20px;" align="center"><p style="color: #ffffff; margin:0; font-size:12px;"><font>©2018 Straite Ltd. All Rights Reserved. Techhub Moorgate 101 Finsbury Pavement LONDON EC2A 1RS. Registered in England. Company Number 11167976.</font></p></td>
    </tr>
</table>
</body>
</html>
</page>';


/*$message ='<body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
    <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
      <tr>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
        <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
          <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">
            <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>
            <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">
              <tr>
                <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                    <tr>
                      <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                        <p style="font-family: sans-serif; font-size: 20px; font-weight: bold; margin: 0; Margin-bottom: 15px;">Your Tickets for '.$_POST['event_title'].' - '.date('l,d F Y',strtotime($_POST['event_start_date'])).'</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Today at '.date("H:i").'</p>
                        <p style="font-family: sans-serif; font-size: 14px; text-align:center; font-weight: normal; margin: 0; Margin-bottom: 15px;"><img height="52" width="150" src="https://www.staging5.findopendays.com/wp-content/uploads/2018/11/new-logo2.png"></p>
                        <p style="font-family: sans-serif; font-size: 20px; text-align:center;font-weight: bold; margin: 0; Margin-bottom: 15px;">'.$_POST["current_user_name"].'</p>
                        <p style="font-family: sans-serif; font-size: 20px; text-align:center;font-weight: bold; margin: 0; Margin-bottom: 15px;">You are good to go</p>
                        
                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;">
                          <tbody>
                            <tr>
                              <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;">
                                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                                  <tbody>
                                    <tr>
                                      
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <p style="font-family: sans-serif; font-size: 18px; font-weight: bold; margin: 0; Margin-bottom: 15px;">Summary</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Order:#'.rand(1,100).' - '.date('d F Y').'</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">'.$_POST["current_user_name"].' - '.$_POST['no_of_tickets_requested'].' X '.$ticket_name.'</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: bold; margin: 0; Margin-bottom: 15px;">Printable PDF tickets are attached to this email</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">This order is subject to findopendays <a href="#">Terms of Service </a>,<a href="#">Privacy Policy </a> and <a href="#">Cookie Policy</a></p>
                        <p style="font-family: sans-serif; font-size: 18px; font-weight: bold; margin: 0; Margin-bottom: 15px;margin-top:20px;">Additional Information</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">The Event organiser has provided following information:</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 35px;">Thankyou for booking you place at our findopenday. We will shortly send you a programme outlining the talks and activities on offer that day so you can plan your time with us accordingly.</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Meanwhile, if you have any queries, please call or email our helpful enquiries team: <a href="tel:+44 20 3355 8633">+44 20 3355 8633</a> or email: <a href="mailto:hello@findopendays.com">hello@findopendays.com</a></p>

                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Best Wishes,</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">'.$_POST["event_title"].'</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
              <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                <tr>
                  <td class="content-block" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                    <p style="font-family: sans-serif; font-size: 14px; text-align:center; font-weight: normal; margin: 0; Margin-bottom: 15px;"><img height="52" width="150" src="https://www.staging5.findopendays.com/wp-content/uploads/2018/11/new-logo2.png"></p>
                    <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">This email was sent to <a href="mailto:'.$_POST["current_user_email"].'">'.$_POST["current_user_email"].'</a></span></br>
                    <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">Techhub Moorgate 101 Finsbury Pavement LONDON EC2A 1RS.</span>
                  </td>
                </tr>
                <tr>
                  <td class="content-block powered-by" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                    ©2018 Odays Digital Ltd. All Rights Reserved.
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </td>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
      </tr>
    </table>
  </body>';*/
  $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
  <style type="text/css">
    div[style*="margin: 16px 0"] { 
      margin:0 !important;
    }
    
    a[href^=tel], a[href^="x-apple-data-detectors:"] { color: inherit; text-decoration: none; }
    
    @media screen and (max-width: 640px), screen and (max-device-width: 640px), screen and (min-resolution: 3dppx) and (max-device-width: 1920px)  {
      .cut2 {
        display: none !important;
      }
      
      .two-column .column {
        max-width: 100% !important;
      }
      
      .intro_table {
        background-size: cover !important;
      }
    }
  </style>
</head>
<body style="padding: 0;background-color: #dddddd;margin: 0 !important;">
  
  <center style="width: 100%;table-layout: fixed;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0;width: 100%;background-color: #dddddd;font-family:"Open Sans", Arial, Helvetica, sans-serif;font-size: 12px;border: 0px solid #0000000 !important;">
      <tr><td style="padding: 0;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert30.gif" style="border: 0;outline: none;display: block;"></td></tr>
      <tr>
        <td style="padding: 0;">
          <div style="max-width: 600px;margin: 0 auto;width: 100%;">
          
            <table align="center" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0;margin: 0 auto;width: 100%;max-width: 600px;">
              <tr>
                <td style="padding: 0;">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" align="center" style="border-spacing: 0;background-color: #f26522;text-align: center;">
                    <tr>
                      <td style="padding: 0;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert10.gif" style="border: 0;outline: none;display: block;"></td>
                    </tr>
                  </table>
                </td>
              </tr>
              
              <tr>
                <td class="intro_table" background="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/header_img.jpg" bgcolor="#2a4f7c" width="600" valign="top" style="padding: 0;background-color: #2a4f7c;background-image: url(https://www.staging5.findopendays.com/wp-content/uploads/2018/12/header_img2.jpg);background-repeat: no-repeat;background-position: center;width: 100%;text-align: center;">
                  <div style="background-color: #2a4f7c;width:100%">
                
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="intro_table" align="center" style="border-spacing: 0;background-image: url(https://www.staging5.findopendays.com/wp-content/uploads/2018/12/header_img.jpg);background-repeat: no-repeat;background-position: center;width: 100%;text-align: center;">
                    <tr>
                      <td width="35" style="padding: 0;width: 35px;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_hor30.gif" style="border: 0;outline: none;display: block;"></td>
                      <td style="padding: 0;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert30.gif" style="border: 0;outline: none;display: block;"></td>
                      <td width="35" style="padding: 0;width: 35px;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_hor30.gif" style="border: 0;outline: none;display: block;"></td>
                    </tr>
                    <tr>
                      <td style="padding: 0;">&nbsp;</td>
                      <td style="padding: 0;width: 100%;text-align: center;">
                        <a href="#" style="border: 0;outline: none;text-decoration: none;color: #000000;margin: auto;display: inline-block;text-align: center;"><img width="152" alt="Event Logo" title="Event Logo" src="https://www.staging5.findopendays.com/wp-content/uploads/2018/11/new-logo.png" align="left" style="border: 0;outline: none;display: block;"></a>
                      </td>
                      <td style="padding: 0;">&nbsp;</td>
                    </tr>
                    <tr>
                      <td style="padding: 0;">&nbsp;</td>
                      <td style="padding: 0;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert30.gif" style="border: 0;outline: none;display: block;"></td>
                      <td style="padding: 0;">&nbsp;</td>
                    </tr>
                    <tr>
                      <td style="padding: 0;">&nbsp;</td>
                      <td style="padding: 0;width: 100%;text-align: center;">
                        <h1 style="font-size: 32px;color: #ffffff;font-weight: 100;line-height: 40px;margin: 0;padding: 0;">'.$title.'</h1>
                      </td>
                      <td style="padding: 0;">&nbsp;</td>
                    </tr>
                    <tr>
                      <td style="padding: 0;">&nbsp;</td>
                      <td style="padding: 0;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert10.gif" style="border: 0;outline: none;display: block;"></td>
                      <td style="padding: 0;">&nbsp;</td>
                    </tr>
                    <tr>
                      <td style="padding: 0;">&nbsp;</td>
                      <td style="padding: 0;width: 100%;text-align: center;">
                        <p style="font-size: 16px;color: #ffffff;font-weight: 100;line-height: 20px;margin-bottom: 10px;">'.$my_post_content.'</p>
                        <p style="font-size: 16px;color: #ffffff;font-weight: 100;line-height: 20px;margin-bottom: 10px;">If you have any queries, please call or email the organiser: +44 (0)20 3897 2032 or email: study@sgul.ac.uk</p>
                      </td>
                      <td style="padding: 0;">&nbsp;</td>
                    </tr>
                    <tr>
                      <td style="padding: 0;">&nbsp;</td>
                      <td style="padding: 0;width: 100%;text-align: center;">
                        <a href="javascript:void(0);" style="font-size: 16px;color: #ffffff !important;font-weight: bold;border: 0;outline: none;text-decoration: none"><font color="#ffffff" style="color: #ffffff !important;">You are ready to go!</font></a>
                      </td>
                      <td style="padding: 0;">&nbsp;</td>
                    </tr>
                    <tr>
                      <td style="padding: 0;">&nbsp;</td>
                      <td style="padding: 0;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert30.gif" style="border: 0;outline: none;display: block;"></td>
                      <td style="padding: 0;">&nbsp;</td>
                    </tr>
                  
                    
                    <tr>
                      <td style="padding: 0;">&nbsp;</td>
                      <td style="padding: 0;">
                          <table cellspacing="0" cellpadding="0" border="0" align="center" style="border-spacing: 0;width: 100%;">
                            <tr>
                              <td style="padding: 0;">
                                <table cellspacing="0" cellpadding="0" border="0" align="center" style="border-spacing: 0;margin: auto;background-color: #f26522;text-align: center;">
                                  <tr>
                                    <td style="padding: 0;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_hor10.png" style="border: 0;outline: none;display: block;"></td>
                                    <td style="padding: 0;"><a href="http://www.google.com/calendar/event?action=TEMPLATE&dates='.date('Ymd',strtotime($_POST['event_start_date'])).'/'.date('Ymd',strtotime($_POST['event_end_date'])).'&times=0445/0545&text='.$title.'&location='.$address_data[1].'&details='.$my_post_content.'" style="border: 0;outline: none;text-decoration: none;color: #ffffff;font-size: 14px;font-weight: 100;">ADD TO CALENDAR&nbsp;</a></td>
                                    <td style="padding: 0;width: 35px;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/plus.png" alt="+" style="border: 0;outline: none;display: block;"></td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>
                      </td>
                      <td style="padding: 0;">&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="35" style="padding: 0;width: 35px;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_hor30.gif" style="border: 0;outline: none;display: block;"></td>
                      <td style="padding: 0;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert30.gif" style="border: 0;outline: none;display: block;"></td>
                      <td width="35" style="padding: 0;width: 35px;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_hor30.gif" style="border: 0;outline: none;display: block;"></td>
                    </tr>
                  </table>
                  </div>
                      
                </td>
              </tr>
              <tr>
                <td class="two-column" bgcolor="#2d2d2d" style="padding: 0;text-align: center;font-size: 0;background-color: #2d2d2d;">
                
                  <div class="column" style="width: 100%;max-width: 300px;display: inline-block;vertical-align: middle;text-align: center;">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="border-spacing: 0;">
                      <tr><td colspan="3" style="padding: 0;color: #999999;font-size: 14px;text-align: center;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert30.gif" style="border: 0;outline: none;display: block;"></td></tr>
                      <tr>
                        <td style="padding: 0;width: 50%;color: #999999;font-size: 14px;text-align: center;">&nbsp;</td>
                        <td style="padding: 0;width: 1%;background-color: #f26522;text-align: center;color: #999999;font-size: 14px;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/location.png" alt="Location" title="Location" style="border: 0;outline: none;display: block;background-color: #f26522;text-align: center;"></td>
                        <td style="padding: 0;width: 50%;color: #999999;font-size: 14px;text-align: center;">&nbsp;</td>
                      </tr>
                    </table>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="border-spacing: 0;">
                      <tr><td colspan="3" style="padding: 0;color: #999999;font-size: 14px;text-align: center;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert10.gif" style="border: 0;outline: none;display: block;"></td></tr>
                      <tr>
                        <td style="padding: 0;color: #999999;font-size: 14px;text-align: center;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_hor30.gif" style="border: 0;outline: none;display: block;"></td>
                        <td style="padding: 0;color: #999999;font-size: 14px;text-align: center;"><h2 style="color: #dddddd;font-size: 16px;margin: 0;padding: 0;font-weight: normal;text-align: center;">'.$address_data[1].'</h2></td>
                        <td style="padding: 0;color: #999999;font-size: 14px;text-align: center;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_hor30.gif" style="border: 0;outline: none;display: block;"></td>
                      </tr>
                      <tr><td colspan="3" style="padding: 0;color: #999999;font-size: 14px;text-align: center;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert10.gif" style="border: 0;outline: none;display: block;"></td></tr>
                      <tr>
                        <td style="padding: 0;color: #999999;font-size: 14px;text-align: center;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_hor30.gif" style="border: 0;outline: none;display: block;"></td>
                        <td style="text-align: center !important;padding: 0;color: #999999;font-size: 14px;" align="center">
                          <a href="#" style="color: #999999;text-align: center !important;border: 0;outline: none;text-decoration: none;font-size: 14px;">'.$title.'</a>
                        </td>
                        <td style="padding: 0;color: #999999;font-size: 14px;text-align: center;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_hor30.gif" style="border: 0;outline: none;display: block;"></td>
                      </tr>
                      <tr><td colspan="3" style="padding: 0;color: #999999;font-size: 14px;text-align: center;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert30.gif" style="border: 0;outline: none;display: block;"></td></tr>
                    </table>
                  </div>
                  
                  <div class="column" style="width: 100%;max-width: 300px;display: inline-block;vertical-align: top;text-align: center;">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="border-spacing: 0;">
                      <tr><td colspan="3" style="padding: 0;color: #999999;font-size: 14px;text-align: center;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert30.gif" style="border: 0;outline: none;display: block;"></td></tr>
                      <tr>
                        <td style="padding: 0;width: 50%;color: #999999;font-size: 14px;text-align: center;">&nbsp;</td>
                        <td style="padding: 0;width: 1%;background-color: #f26522;text-align: center;color: #999999;font-size: 14px;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/time.png" alt="Time" title="Time" style="border: 0;outline: none;display: block;background-color: #f26522;text-align: center;"></td>
                        <td style="padding: 0;width: 50%;color: #999999;font-size: 14px;text-align: center;">&nbsp;</td>
                      </tr>
                    </table>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="border-spacing: 0;">
                      <tr><td colspan="3" style="padding: 0;color: #999999;font-size: 14px;text-align: center;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert10.gif" style="border: 0;outline: none;display: block;"></td></tr>
                      <tr><td colspan="3" style="padding: 0;color: #999999;font-size: 14px;text-align: center;"><h2 style="color: #dddddd;font-size: 16px;margin: 0;padding: 0;font-weight: normal;text-align: center;">'.date('l,d F Y',strtotime($_POST['event_start_date'])).'</h2></td></tr>
                      <tr><td colspan="3" style="padding: 0;color: #999999;font-size: 14px;text-align: center;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert10.gif" style="border: 0;outline: none;display: block;"></td></tr>
                      <tr><td colspan="3" style="text-align: center;color: #999999 !important;padding: 0;font-size: 14px;">'.$_POST['event_start_time'].' - '.$_POST['event_end_time'].'</td></tr>
                      <tr><td colspan="3" style="padding: 0;color: #999999;font-size: 14px;text-align: center;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert30.gif" style="border: 0;outline: none;display: block;"></td></tr>
                    </table>
                  </div>
                  
                </td>
              </tr>
              <tr>
                <td style="padding: 0;background-color: #2d2d2d;color:#ffffff !important">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-spacing: 0;background-color: #f26522;text-align: center;color:#ffffff !important">
                    <tr>
                      <td width="35" style="padding: 0;width: 35px;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_hor30.gif" style="border: 0;outline: none;display: block;"></td>
                      <td style="padding: 0;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert20.gif" style="border: 0;outline: none;display: block;"></td>
                      <td width="35" style="padding: 0;width: 35px;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_hor30.gif" style="border: 0;outline: none;display: block;"></td>
                    </tr>
                    <tr>
                      <td style="padding: 0;">&nbsp;</td>
                      <td style="padding: 0;width: 100%;text-align: center;font-size: 12px;color: #ffffff !important;">
                        <span style="color: #ffffff !important;"><font color="#ffffff" style="color:#ffffff !important">©2018 Straite Ltd. All Rights Reserved. Techhub Moorgate 101 Finsbury Pavement LONDON EC2A 1RS.
Registered in England. Company Number 11167976.</font></span>
                      </td>
                      <td style="padding: 0;">&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="35" style="padding: 0;width: 35px;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_hor30.gif" style="border: 0;outline: none;display: block;"></td>
                      <td style="padding: 0;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert20.gif" style="border: 0;outline: none;display: block;"></td>
                      <td width="35" style="padding: 0;width: 35px;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_hor30.gif" style="border: 0;outline: none;display: block;"></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </div>
        </td>
      </tr>
      <tr><td style="padding: 0;"><img src="https://www.staging5.findopendays.com/wp-content/uploads/2018/12/spacer_vert30.gif" style="border: 0;outline: none;display: block;"></td></tr>
    </table>
    </center>
</body>
</html>';
    //$content = ob_get_clean();
    $event_author = $_POST["event_autor_email"];
    $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 3);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    //$html2pdf->output(dirname(__FILE__).'/example03.pdf','F');
  $to = $_POST["current_user_email"];
  $from = "hello@findopendays.com";
  $subject = "Your Event Booking";
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
  if($mail1)
  {
  	//echo 'author done';
  }
  /*if($mail)
  {
    echo 'success';
  }*/
  else{
    echo 'not send';
  }
    //$pdfdoc = $html2pdf->output('', 'S');
} catch (Html2PdfException $e) {
    $html2pdf->clean();

    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}
?>
	<div id="dlcontent" class="dlwrapper ticket_booking_div">
   		<div class="dlcontainer  dlcontainer--flex">
			<main id="dlmain" class="dlarchive">
			<h1 class="h1_title">Booking Confirmed</h1>
			<h4>An email has been sent to &nbsp;<b><?php echo $_POST['current_user_email'];?></b></h4>
			<h1 class="h1_title dlarticle__title"><?php echo $title;?></h1>
			<p class="dlsubtitle"><?php echo $subtitle;?></p>
			<p class="dlsubtitle header_top_address"><?php echo $address_data[1];?></p>
			<p class="dlevents__times"><i class="la la-calendar"></i>
        						From &nbsp;<time class="dlarticle__times-in">
                <?php echo date('d-F',strtotime($date));?></time>&nbsp;to &nbsp;
                <time class="dlarticle__times-out">
                <?php echo date('d-F',strtotime($event_end_date));?></time>&nbsp;<?php echo $start_time1;?> - <?php echo $end_time1;?> </p>
                <div class="summary_div">
                <h3>Summary</h3>
                <p>You have booked</p>
                <p>Type : <?php echo $ticket_name;?></p>
                <p><?php echo $ticket_description;?></p>
                <p>Number of tickets : <?php echo $_POST['no_of_tickets_requested'];?></p>

                <span>For questions about the event, contact the event organiser at <a href="mailto:<?php echo $_POST['event_autor_email'];?>">email address </a> <?php echo $_POST['event_autor_email'];?> or <a href="tel:<?php echo $_POST['event_autor_phone'];?>">contact phone number</a> <?php echo $_POST['event_autor_phone'];?></span>
                </div>
			</main>
		</div>
	</div>
<?php }?>
<?php get_footer();?>