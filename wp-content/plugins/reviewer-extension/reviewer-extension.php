<?php
/**
 * Plugin Name:       Reviewer Extension
 * Plugin URI:        http://reviewerplugin.com
 * Description:       Extend Reviewer plugin functionality.
 * Version:           1.0.0
 * Author:            Michele Ivani
 * Author URI:        http://micheleivani.com
 * Text Domain:       reviewer_extension
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

/*
 |--------------------------------------------------------------------------
 | Reviewer Exstension
 |--------------------------------------------------------------------------
 |
 | Reviewer provides an additional plugin (Reviewer Extension)
 | that allows you to add your custom WordPress hooks for extending and
 | integrating the Reviewer plugin with your current theme.
 | The Reviewer plugin offers some predefined WordPress hooks;
 | you can find more info on the documentation.
 |
 | Waring: editing this file could break your site functionality
 | so make sure you have minimum development skills.
 |
 | Tips: Add to your custom PHP function the prefix "rex_".
 |
 */

 // *** START CODING FROM HERE *** //

// function rex_my_action ($review, $post_id) {
//     // Build your action after the review is saved.
// }
// add_action('rwp_after_saving_review', 'rex_my_action', 11, 2);

// function rex_my_filter ($review) {
//     // Filter the review before it is going to be saved.

//     return $review;
// }
// add_filter('rwp_before_saving_review', 'rex_my_filter', 11, 1);

// function rex_my_filter2 ($snippets) {
//     // Filter the Google Structured data before they are going to be inserted.

//     return $snippets;
// }
// add_filter('rwp_snippets', 'rex_my_filter2', 11, 1);
