<?php
/*
Plugin Name: rankcookie
Description: This plugin provides a light weight cookie opt-in with support for the google tag manager
Author: rankeffect
Author URI: https://rankeffect.de/
Version: 1.0.1
License: GPLv2
*/

// Block direct call
if (!defined('WPINC')) {
  die;
}

// Include the cookie banner css
function rankcookie_add_styles() {
  
  // Cookie banner css is not required if the cookie is already set
  if (isset($_COOKIE['rankcookie'])) {
    return false;
  }
  
  wp_register_style('rankcookie_styles', plugin_dir_url(__FILE__) . '/css/rankcookie.min.css', array(), '1.0', 'all');
  wp_enqueue_style('rankcookie_styles'); // Enqueue it! 
}

add_action('wp_head', 'rankcookie_add_styles', 1);

// Include the cookie banner js
function rankcookie_add_script() {
  
  // Cookie banner js is not required if the cookie is already set
  if (isset($_COOKIE['rankcookie'])) {
    return false;
  }
  
  $options = get_option('rankcookie_options');
  
  // Will be passed to js
  $settings = array(
    'cookieName' => 'rankcookie', // Stores all permissions
    'cookieLifetime' => $options['rankcookie_field_lifetime'] ?: '365', // The cookie expires after this number of days
    'gtmId' => $options['rankcookie_field_gtm_id'] // The google tag manager id as specified by the user within the dashboard
  );
  
  wp_register_script('rankcookie_script', plugin_dir_url(__FILE__) . '/js/rankcookie.min.js', array(), '1.0.0');
  wp_localize_script('rankcookie_script', 'rankcookie_settings', $settings);
  wp_enqueue_script('rankcookie_script'); // Enqueue it!     
}

add_action('wp_head', 'rankcookie_add_script', 1);

// Include the cookie banner template
function rankcookie_add_banner() {
  
  // Don't show the cookie banner if the cookie is already set
  if (isset($_COOKIE['rankcookie'])) {
    return false;
  }
  
  include(dirname(__FILE__) . '/templates/cookie-banner.php');
}

add_action('wp_body_open', 'rankcookie_add_banner', 1);

// Include the Google Tag Manger Snippet
function rankcookie_add_gtm_snippet() {
  
  // Include the snippet only if the required cookie permission exists
  if (!isset($_COOKIE['rankcookie']) || strpos($_COOKIE['rankcookie'], 'statistics') === false) {
    return false;
  }
  
  include(dirname(__FILE__) . '/templates/gtm-snippet.php');
}

add_action('wp_head', 'rankcookie_add_gtm_snippet', 1);

// Include the settings page within the dashboard
include(dirname(__FILE__) . '/settings/rankcookie-settings.php');

// Load plugin textdomain.
function rankcookie_load_textdomain() {
  load_plugin_textdomain( 'rankcookie', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

add_action( 'init', 'rankcookie_load_textdomain' );