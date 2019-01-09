<?php
use QiblaFramework\Functions as F;

/**
 * View Terms List
 *
 * @since 1.0.0
 *
 * Copyright (C) 2016 Guido Scialfa
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
?>

<?php 
global $post;
if ($data->termsList) : ?>
<div class="custom_sharing_like_comment">
<ul>

<?php 
$event_date = get_post_meta($post->ID,'_qibla_mb_event_dates_multidatespicker_start',true);
$newDate = date("d M", strtotime($event_date));
$tickets_available_for_booking = get_post_meta($post->ID,'_qibla_mb_ticket_check',true);?>
<li class="custom_date "><b><?php echo $newDate ;?></b></li>
<?php     if($tickets_available_for_booking==on){
                if(is_user_logged_in()){?>
                <li><b><a class="custom_date" href="<?php home_url();?>/ticket-booking-page/?event_id=<?php echo $post->ID;?>">Book</a></b></li>
                        <?php }
                else{
                    echo '<li><b><a class="non-logged-in-booking custom_date" href="JavaScript:Void(0);">Book</a></b></li>';

                    } ?>
                    
    
    <?php }?>

<?php if(get_post_meta($post->ID,'_qibla_mb_business_email',true)!=''){?>
    
    <li class="dlevents__items--calendar"></li>
    <!-- <li class="custom_comment"><a href="mailto:<?php echo get_post_meta($post->ID,'_qibla_mb_business_email',true);?>" class="dlsocials-links__link  dlsocials-links__link--email" target="_blank">
<span class="dlsocials-links__label">email</span></a></li> -->
<?php }?>
    
    <li class="custom_save dlactions-lists__item dlactions-lists__item--wish"><span class="dlwishlist-adder-wrapper">
<a data-post-id="<?php echo $post->ID;?>" href="<?php echo get_the_permalink($post->ID);?>?nonce=b732af7065&amp;action=store" class="dlwishlist-adder"></a></span></li>
<li class="custom_share dlactions-lists__item dlactions-lists__item--share"><a id="share_popup_trigger1" class="dlshare" href="javascript:">Share</a></li>
</ul>
        
    </div>
    <nav class="dllisting-terms">
        <ul class="dllisting-terms__list">
            <?php foreach ($data->termsList as $key => $term) : ?>
                <li class="dllisting-terms__item">
                    <a class="dllisting-terms__link" href="<?php echo esc_url($term['link']) ?>">
                        <i class="<?php echo esc_attr(F\sanitizeHtmlClass($term['icon_html_class'])) ?>"></i>
                        <?php echo esc_html(sanitize_text_field($term['label'])) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
<?php endif; ?>