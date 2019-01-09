<?php
/**
 * View Related Posts
 *
 * @since      1.0.0
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

use QiblaFramework\Functions as F;
?>

<?php if ($data->relatedPosts) : ?>
    <div class="dlrelated-posts">
    <?php 
        global $wpdb;
        global $post;
        $title = get_the_title($post->ID);
        $sql = "SELECT * FROM `wpkg_posts` WHERE `post_title`='".$title."' AND `post_type`='events' AND `post_status`='publish' AND `ID` <> ".$post->ID;
        $query = $wpdb->get_results($sql);
        if(count($query)>=1)
        {?>

            <?php echo do_shortcode('[dl_section size="big" title="' . $data->cta['title'] . '" background-image="' . $data->cta['background-image'] . '" link_url="' . $data->cta['url'] . '" link="Other events from this organiser" links_style="fill"]');?>
            <div class="dlcontainer">
        <div class="dlsc-events dlsc-listings-type--events">
        <div class="dlgrid">
            <?php foreach ($query as  $postdata) {
                $pid = $postdata->ID;
                $url = get_the_permalink($pid);
                $title = get_the_title($pid);
               $src = wp_get_attachment_image_src( get_post_thumbnail_id($pid),'full', false);
                
                ?>
                <article id="post-<?php echo $pid;?>" class="dlarticle  dlarticle--card dlarticle--zoom dlarticle--has-button dlarticle--events dlarticle--overlay col col--md-6 col--lg-4">
                <div class="dlarticle-card-box">
                            <header class="dlarticle__header">
                                <a class="dlarticle__link" href="<?php echo $url; ?>">
                                <figure class="dlthumbnail">
                                    <img src="<?php echo $src[0];?>" class="dlthumbnail__image wp-post-image" alt="University for the Creative Arts" width="346" height="231">
                                </figure>
                                    <?php
                                    // Thumbnail.
                                    if ($title) : ?>
                                        <h2 class="dlarticle__title">
                                           <i class="dlarticle__icon glyphs icon-squareu"></i>
                                            <?php echo $title ; ?>
                                        </h2>
                                    <?php endif; ?>
                                </a>
                            <div class="dlevent-ticket-wrapper">
                            <?php 
                            //print_r(get_post_meta($pid));
                            $button_url = get_post_meta($pid,'_qibla_mb_button_url',true);
                            $button_text = get_post_meta($pid,'_qibla_mb_button_text',true);?>
                                <a class="dlbtn dlbtn--tiny event-ticket-button" href="<?php echo $button_url;?>"><?php echo $button_text;?></a>
                            </div>
                            </header>
                        <?php $subtitle = get_post_meta($pid,'_qibla_mb_sub_title',true);?>
                        <a class="dlarticle__link" href="<?php echo $url; ?>">
                           
                            <?php if (! empty($subtitle)) : ?>
                                <p class="dlsubtitle">
                                    <?php echo $subtitle; ?>
                                </p>
                            <?php endif;
                            $address = get_post_meta($pid,'geolocation_formatted_address',true);
                            $eventsDateStart = strtotime(get_post_meta($pid,'_qibla_mb_event_dates_multidatespicker_start',true));
                            if ($address || $eventsDateStart) : ?>
                                <footer class="dlarticle__meta">
                                        <div class="dlarticle__meta--time">
                                            <time class="dlarticle__meta--timein"
                                                  datetime="">
                                                <b class="screen-reader-text"><?php echo sprintf('%s',
                                                        esc_html__('Event date', 'qibla-events')); ?></b>
                                                <span class="dlarticle__meta--day">
                                                  <?php echo date('d',$eventsDateStart); ?>  
                                                </span>
                                                <span class="dlarticle__meta--mouth-day">
                                                <span class="dlarticle__meta--mouth"><?php echo date('M',$eventsDateStart); ?> </span>
                                                <span class="dlarticle__meta--day-text"><?php echo date('D',$eventsDateStart); ?></span>
                                                </span>
                                            </time>
                                        </div>
                                   
                                    
                                        <div class="dlarticle__meta--address">
                                            <?php echo $address; ?>
                                        </div>
                                    
                                </footer>
                        </a>
                    <?php endif; ?>
                    </div>
                    </article>
            <?php } ?>
            </div>
            </div>
        </div>
        <?php }
        ?>
        <div class="recent-viewed-posts">
    <?php 
    wp_reset_query();
    wp_reset_postdata();
    
    if (get_post_type( $post->ID ) == 'events' )
            update_post_meta( $post->ID, '_last_viewed', current_time('mysql') );
         get_post_meta($post->ID,'_last_viewed',true); ?>
            <?php 
            $sql = 'SELECT `post_id` FROM `wpkg_postmeta` WHERE `meta_key`="_last_viewed" ORDER BY `meta_value` DESC LIMIT 0,5';
            $result = $wpdb->get_results($sql);
           //echo "<pre>";
           // print_r($result);
            if(count($result)>=1)
        {?>

            <?php echo do_shortcode('[dl_section size="big" title="' . $data->cta['title'] . '" background-image="' . $data->cta['background-image'] . '" link_url="' . $data->cta['url'] . '" link="People also viewed these open days" links_style="fill"]');?>
            <div class="dlcontainer">
        <div class="dlsc-events dlsc-listings-type--events">
        <div class="dlgrid">
            <?php foreach ($result as  $recent_data) {
                $pid = $recent_data->post_id;
                $url = get_the_permalink($pid);
                $title = get_the_title($pid);
               $src = wp_get_attachment_image_src( get_post_thumbnail_id($pid),'full', false);
                
                ?>
                <article id="post-<?php echo $pid;?>" class="dlarticle  dlarticle--card dlarticle--zoom dlarticle--has-button dlarticle--events dlarticle--overlay col col--md-6 col--lg-4">
                <div class="dlarticle-card-box">
                            <header class="dlarticle__header">
                                <a class="dlarticle__link" href="<?php echo $url; ?>">
                                <figure class="dlthumbnail">
                                    <img src="<?php echo $src[0];?>" class="dlthumbnail__image wp-post-image" alt="University for the Creative Arts" width="346" height="231">
                                </figure>
                                    <?php
                                    // Thumbnail.
                                    if ($title) : ?>
                                        <h2 class="dlarticle__title">
                                           <i class="dlarticle__icon glyphs icon-squareu"></i>
                                            <?php echo $title ; ?>
                                        </h2>
                                    <?php endif; ?>
                                </a>
                            <div class="dlevent-ticket-wrapper">
                            <?php 
                            //print_r(get_post_meta($pid));
                            $button_url = get_post_meta($pid,'_qibla_mb_button_url',true);
                            $button_text = get_post_meta($pid,'_qibla_mb_button_text',true);?>
                                <a class="dlbtn dlbtn--tiny event-ticket-button" href="<?php echo $button_url;?>"><?php echo $button_text;?></a>
                            </div>
                            </header>
                        <?php $subtitle = get_post_meta($pid,'_qibla_mb_sub_title',true);?>
                        <a class="dlarticle__link" href="<?php echo $url; ?>">
                           
                            <?php if (! empty($subtitle)) : ?>
                                <p class="dlsubtitle">
                                    <?php echo $subtitle; ?>
                                </p>
                            <?php endif;
                            $address = get_post_meta($pid,'geolocation_formatted_address',true);
                            $eventsDateStart = strtotime(get_post_meta($pid,'_qibla_mb_event_dates_multidatespicker_start',true));
                            if ($address || $eventsDateStart) : ?>
                                <footer class="dlarticle__meta">
                                        <div class="dlarticle__meta--time">
                                            <time class="dlarticle__meta--timein"
                                                  datetime="">
                                                <b class="screen-reader-text"><?php echo sprintf('%s',
                                                        esc_html__('Event date', 'qibla-events')); ?></b>
                                                <span class="dlarticle__meta--day">
                                                  <?php echo date('d',$eventsDateStart); ?>  
                                                </span>
                                                <span class="dlarticle__meta--mouth-day">
                                                <span class="dlarticle__meta--mouth"><?php echo date('M',$eventsDateStart); ?> </span>
                                                <span class="dlarticle__meta--day-text"><?php echo date('D',$eventsDateStart); ?></span>
                                                </span>
                                            </time>
                                        </div>
                                   
                                    
                                        <div class="dlarticle__meta--address">
                                            <?php echo $address; ?>
                                        </div>
                                    
                                </footer>
                        </a>
                    <?php endif; ?>
                    </div>
                    </article>
            <?php } ?>
            </div>
            </div>
        </div>
        <?php }
            ?>
        </div>
        <?php
        if ($data->cta['label']) {
            echo do_shortcode('[dl_section size="big" title="' . $data->cta['title'] . '" background-image="' . $data->cta['background-image'] . '" link_url="' . $data->cta['url'] . '" link="' . $data->cta['label'] . '" links_style="fill"]');
        }

        if ('post' === get_post_type()) : ?>
            <h2 class="dlrelated-posts__title">
                <?php esc_html_e('Related Posts', 'qibla-events') ?>
            </h2>
        <?php endif; ?>

        <div class="dlcontainer">
            <?php
            // @codingStandardsIgnoreLine
            echo F\ksesPost($data->relatedPosts); ?>
        </div>
    </div>
<?php endif; ?>
