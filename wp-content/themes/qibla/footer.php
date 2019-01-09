<?php
/**
 * Footer
 *
 * @since   1.0.0
 *
 * @license GNU General Public License, version 2
 *
 *    This program is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU General Public License
 *    as published by the Free Software Foundation; either version 2
 *    of the License, or (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/**
 * Footer
 *
 * @since 1.0.0
 */
do_action('qibla_footer'); ?>

</div>
<!-- #dlpage-wrapper -->
<?php wp_footer() ?>
<?php if(is_archive()){?>
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/css/base/minified/jquery-ui.min.css"> -->
<?php }?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.min.js?v=1.1"></script>
<?php 
global $post;
$fieldsValues = get_post_meta($_GET['postid'],'_qibla_mb_ticket_check',true);
if($fieldsValues!="on")
{?>
  <style type="text/css">
  .no_ticket{display: none;}
</style>
<?php }
else{
  ?>
  <style type="text/css">
  .no_ticket{display: block;}
</style>
<?php
}
if(is_single() && is_singular('events'))
{
 
  $json_ld = array();
  global $post;
  $date = get_post_meta($post->ID,'_qibla_mb_event_dates_multidatespicker_start',true);
  $start_time = get_post_meta($post->ID,'_qibla_mb_event_start_time_timepicker',true);
  $end_time  = get_post_meta($post->ID,'_qibla_mb_event_end_time_timepicker',true);
  if($start_time)
  $start_time1 =  date('H:i', $start_time);
  if($end_time)
  $end_time1 =  date('H:i', $end_time);
  $event_end_date = get_post_meta($post->ID,'_qibla_mb_event_dates_multidatespicker_start',true);
  $event_end_date1 = $event_end_date.'T'.$start_time1.$end_time1;
  $src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full', false );
  
  $lat = get_post_meta($post->ID,'_qibla_mb_map_location_lat',true);
  $logi =  get_post_meta($post->ID,'_qibla_mb_map_location_lng',true);
  /*$address = get_post_meta($post->ID,'_qibla_mb_map_location_searcher',true);
  $a = explode(" ", $address);
  print_r($a);*/
  $address_data = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$logi."&key=AIzaSyDlW5Ml6kVJfQlNGJdA-ytxq8ETo3QN51k";
  $jsondata = json_decode(file_get_contents($address_data),true);
  $data = array();
 foreach($jsondata['results']['0']['address_components'] as $element){
     $data[ implode('',$element['types']) ] = $element['long_name'];
 }

 $addressLocality = $data['localitypolitical'];
 $streetAddress = $data['premise'];
 if($streetAddress=="")
 {
  $streetAddress = $data['route'];
 }
 $postalCode = $data['postal_code'];
 $addressRegion = $data['administrative_area_level_1political'];
 $addressCountry = $data['countrypolitical'];

  $json_ld["@context"] = "http://schema.org";
  $json_ld["@type"] = "EducationEvent";
  $json_ld["name"] = get_the_title($post->ID);

  $json_ld["startDate"]= $date.'T'.$start_time1.$end_time1;
  $json_ld["location"]["@type"] = "Place";
  $json_ld["location"]["name"] = get_post_meta($post->ID,'_qibla_mb_map_location_searcher',true);
  $json_ld["location"]["address"]["@type"] = "PostalAddress";
  $json_ld["location"]["address"]["streetAddress"] = $streetAddress;
  $json_ld["location"]["address"]["addressLocality"] = $addressLocality;
  $json_ld["location"]["address"]["postalCode"] = $postalCode;
  $json_ld["location"]["address"]["addressRegion"] = $addressRegion;
  $json_ld["location"]["address"]["addressCountry"] = $addressCountry;
  $json_ld["image"] = $src[0];
  $json_ld["description"] = get_the_content($post->ID);
  $json_ld["endDate"] = $event_end_date1;
}
$final_string = json_encode($json_ld,JSON_UNESCAPED_SLASHES);

?>
<script type="application/ld+json">
<?php echo $final_string;?>
</script>


<script type="text/javascript">
	jQuery(document).ready(function(){
    jQuery('.contact_us_footer a').click(
      function(){
        Intercom('show');
    });
		jQuery(".dlsocials-links__item a").attr('target',"_blank");
    jQuery(".header_top_address").css('cursor','pointer');
    jQuery(".header_top_address").click(function(){
    jQuery('html,body').animate({
        scrollTop: jQuery("#location_maps").offset().top-150},
        'slow');

    });
    jQuery(document).on('click',".autocomplete-suggestion",function(e){
     // alert('ok');
     var attribute = jQuery("#search_events_form").attr('action');
     var arr = attribute.split('/');
     if(arr[1]=="events")
     {
      jQuery("#search_events_form").attr('action','https://www.findopendays.com/events/');
       jQuery("#dlautocomplete_context").remove();
       jQuery("#qibla_listing_categories_filter").remove();
     }
      //jQuery("#search_events_form").attr('action','https://odays.co/events/');
     /* jQuery("#dlautocomplete_context").remove();*/
       /*jQuery("#qibla_listing_categories_filter").remove();*/
    });
    /*added for event search*/
    var page = jQuery('body').hasClass('post-type-archive-events');
    if(page==true){
    jQuery("#custom_text_search").autocomplete({
        source: function(request, response) {
            searchRequest = jQuery.post(global.ajax, { search: request, action: 'search_site' },
                function(data) {
                   response( data.data );
                // response($.map(data.data, function(item) {
                //     return {data: data.data.data,value:data.data.data};
                // }));
            });
        },
        select: function(el, ui) {
          window.location = ui.item.data
        }
    });
    }
	});
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-121811835-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-121811835-1');
  gtag('config', 'AW-792628393');
</script>

<script type="text/javascript">
jQuery(document).ready(function(){

    //alert(months[s-1]);
    /*setTimeout(function(){
      var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    
    var date = jQuery("#select2-qibla_event_dates_filter-container").text();
    var arr = date.split('-');
    var s = arr[1].replace(/^0+/, '');
      jQuery("#select2-qibla_event_dates_filter-container").text(arr[2]+' '+months[s-1] );
    },2000);*/
  jQuery( "#qibla_event_dates_filter" ).change(function(){
    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    
    var date = jQuery(this).val();
    var arr = date.split('-');
    var s = arr[1].replace(/^0+/, '');
    //alert(months[s-1]);
    setTimeout(function(){
      jQuery("#select2-qibla_event_dates_filter-container").text(arr[2]+' '+months[s-1] );
    },100);
    
  });
  jQuery(".ticket_booking_class input").change(function(){
    var a = jQuery(this).is(":checked")
    if(a==true)
    {
      jQuery(".no_ticket").show();
    }
    else{
     jQuery(".no_ticket").hide(); 
    }
  });
  jQuery(".non-logged-in-booking").click(function(){

    jQuery(".is-login-register-toggler").trigger('click'); 

  }); 
 
/*booking button ajax*/
  //jQuery(".booking_now_button").click(function(){
     // jQuery("#booking_popup_form").serialize();
      /*jQuery("form#booking_popup_form").submit(function(e){
        var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
      e.preventDefault();
    
        //alert(" in submit form ");

          var formData = new FormData(this);
          formData.append('action', 'submit_review');
          jQuery.ajax({
              url: ajax_url,
              type: 'POST',
              data: formData,
              async: false,
              success: function (data) {
                jQuery(".popup_success_message").fadeIn('slow', function(){
               jQuery('.popup_success_message').delay(3000).fadeOut(); 
               //location.reload(true);
            });
               // alert(data);
                 // console.log(data);
                  //location.reload();
                  /*window.location.href = '<?php //echo Wo_SeoLink('index.php?link1=start-up');?>';*/

             /* },
              cache: false,
              contentType: false,
              processData: false
          });

          return false;
      });*/
  });
//});
</script>
<?php 

if(isset($_GET['eventid']))
{
  echo 'testtttttttttttttttttttttttttttt';?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#example1').DataTable({
        dom: 'Bfrtip',
buttons: [
    'csvHtml5',
    'pdfHtml5'
]
    });
});
</script>
  <?php }?>
</body>
</html>