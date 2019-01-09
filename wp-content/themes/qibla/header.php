<?php
/**
 * Header
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
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
header('Cache-Control: max-age=900000');?>
<!DOCTYPE html>
<html <?php \Qibla\Functions\scopeID('html');  language_attributes() ?> class="dl dlu-no-js">

<head>
    <meta charset="<?php bloginfo('charset') ?>" />
    
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1"> 
    
    <?php wp_head() ?>
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri();?>/customstyle.css?v=1.08">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- place this in a head section -->
<!-- <link rel="apple-touch-icon" href="https://odays.co/wp-content/uploads/2018/07/logo.png">
<link rel="apple-touch-icon" sizes="152x152" href="https://odays.co/wp-content/uploads/2018/07/logo.png">
<link rel="apple-touch-icon" sizes="180x180" href="https://odays.co/wp-content/uploads/2018/07/logo.png">
<link rel="apple-touch-icon" sizes="167x167" href="https://odays.co/wp-content/uploads/2018/07/logo.png">
 -->
<!-- place this in a head section -->
<!-- <meta name="apple-mobile-web-app-capable" content="yes" />
<link href="<?php echo get_stylesheet_directory_uri();?>/images/apple_splash_2048x2732.png" sizes="2048x2732" rel="apple-touch-startup-image" />
<link href="<?php echo get_stylesheet_directory_uri();?>/images/apple_splash_1668x2224.png" sizes="1668x2224" rel="apple-touch-startup-image" />
<link href="<?php echo get_stylesheet_directory_uri();?>/images/apple_splash_1536x2048.png" sizes="1536x2048" rel="apple-touch-startup-image" />
<link href="<?php echo get_stylesheet_directory_uri();?>/images/apple_splash_1125x2436.png" sizes="1125x2436" rel="apple-touch-startup-image" />
<link href="<?php echo get_stylesheet_directory_uri();?>/images/apple_splash_1242x2208.png" sizes="1242x2208" rel="apple-touch-startup-image" />
<link href="<?php echo get_stylesheet_directory_uri();?>/images/apple_splash_750x1334.png" sizes="750x1334" rel="apple-touch-startup-image" />
<link href="<?php echo get_stylesheet_directory_uri();?>/images/apple_splash_640x1136.png" sizes="640x1136" rel="apple-touch-startup-image" />
<link href="<?php echo get_stylesheet_directory_uri();?>/images/apple_splash_640x960.png" sizes="640x960" rel="apple-touch-startup-image" /> -->
<!-- <?php $current_user1 = get_userdata(get_current_user_id());
$current_user = $current_user1->data;
 ?>

<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/fkmto4as';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
<?php if(is_user_logged_in()){?>
<script>
  window.intercomSettings = {
    app_id: "fkmto4as",
    name: "<?php echo $current_user->display_name; ?>", // Full name
    email: "<?php echo $current_user->user_email; ?>", // Email address
    created_at: "<?php echo strtotime(get_userdata($current_user->ID)->user_registered); ?>", // Signup date as a Unix timestamp
  };
  </script>
<?php }
else{?>
<script>
  window.intercomSettings = {
    app_id: "fkmto4as"
  };
</script>
<?php }?>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/fkmto4as';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script> -->
</head>

<!-- <script type="text/javascript">
    const isIos = () => {
  const userAgent = window.navigator.userAgent.toLowerCase();
  return /iphone|ipad|ipod/.test( userAgent );
}
// Detects if device is in standalone mode
const isInStandaloneMode = () => ('standalone' in window.navigator) && (window.navigator.standalone);

// Checks if should display install popup notification:
if (isIos() && !isInStandaloneMode()) {
  this.setState({ showInstallMessage: true });
}
import { ConnectedRouter, push } from 'react-router-redux';

class PersistedConnectedRouter extends ConnectedRouter {
  componentWillMount() {
    const { store: propsStore, history, isSSR } = this.props;
    this.store = propsStore || this.context.store;

    if (!isSSR) {
      this.unsubscribeFromHistory = history.listen(this.handleLocationChange);
    }

    //this is the tweak which will prefer persisted route instead of that in url:
    const location = this.store.getState().router.location || {};
    if (location.pathname !== history.location.pathname) {
      this.store.dispatch(push(location.pathname));
    }
    this.handleLocationChange(history.location);
    // 
  }
}

export default PersistedConnectedRouter;
</script> -->
<body <?php body_class() ?>>

    <div id="dlpage-wrapper">
        <?php
        /**
         * Header
         *
         * @since 1.0.0
         */
        do_action('qibla_header');
