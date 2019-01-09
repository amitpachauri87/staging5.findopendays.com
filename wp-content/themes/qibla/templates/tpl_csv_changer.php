<?php 
/*
Template Name:CSV changer
*/
get_header();?>
<?php 
$csvFile = get_stylesheet_directory().'/csv/open_day_launch_day_clearing.csv'; 
$file = fopen($csvFile,"r"); 
$import_data = array(); 
while(($products = fgetcsv($file)) !== FALSE){
 $import_data[] = $products;
 	}
 	//echo '<pre>';
 	//print_r($import_data);
 	$i= 1;
 	foreach ($import_data as $value) {
 		//$data = explode("https://odays.co/wp-content/uploads/2018/05/", $value[26]);
 		//echo $value[3];
 		//echo '<br>';
 		 $date = date($value[4]);
 		//echo '<br>';
// output
		echo strtotime($date);
	 	echo '<br>';
	 	$i++;
 }

 /*function pippin_get_image_id($image_url) {
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
        return $attachment[0]; 
}*/

?>
<?php get_footer();?>