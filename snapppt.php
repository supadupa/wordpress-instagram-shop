<?php

if(!defined( 'ABSPATH' )) exit; // Exit if accessed directly

/*
  Plugin Name: Shoppable Social Media Galleries by Sauce
  Plugin URI: http://help.addsauce.com/woocommerce-and-wordpress/instagram-shop-plugin-for-wordpress-and-woocommerce-by-snapppt
  Description: Shoppable Social Media Galleries by Sauce is a free WP plugin that lets your customers shop your Instagram feed.
  Author: Sauce
  Version: 1.1.13
  Author URI: http://addsauce.com
  License: GPLv2
*/

/**
* Copyright (c) 2023 Sauce (email : tech@addsauce.com)
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License, version 2 or, at
* your discretion, any later version, as published by the Free
* Software Foundation.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('SNAPPPT_URL', 'https://app.addsauce.com');
define('SNAPPPT_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('SNAPPPT_PLUGIN_URL', plugin_dir_url(__FILE__));


# we have now moved away from 'snapppt_options' which required manual copy and paste
# preserving the usage of the value for a smoother transition to the new API setup
$snapppt_options = get_option('snapppt');
function snapppt_options() { register_setting('snapppt_options', 'snapppt'); }
add_action('admin_init', 'snapppt_options');

/********************** SAUCE VERSION ***************/
add_action('rest_api_init', 'register_version_endpoint');

function register_version_endpoint() {
    register_rest_route('sauce/v1', '/version', [
        'methods' => 'GET',
        'callback' => 'plugin_version'
    ]);
}

function plugin_version() {
    if (!function_exists('get_plugin_data')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $plugin_data = get_plugin_data(__FILE__);

    return [
        'name' => $plugin_data['Name'],
        'version' => $plugin_data['Version'],
    ];
}
/*******************END OF SAUCE VERSION ***************/


// make the setting available to the REST API
function sauce_register_setting() {
  register_setting('woocommerce', 'sauce_account_id');
}

function sauce_add_setting($settings) {
  $custom_settings = array(
      array(
          'title' => __('Sauce Account ID', 'woocommerce'),
          'desc' => __('Your Sauce account ID for integration.', 'woocommerce'),
          'id' => 'sauce_account_id',
          'type' => 'text',
          'default' => '',
          'desc_tip' => true,
      ),
  );

  // Insert our custom setting in the general WooCommerce settings array
  $settings = array_merge($settings, $custom_settings);
  return $settings;
}

add_action('admin_init', 'sauce_register_setting');
add_filter('woocommerce_general_settings', 'sauce_add_setting');

if(is_admin()) {
  include SNAPPPT_PLUGIN_PATH . 'snapppt-notice.php';
}

if(!is_admin()) {
  // Shortcode used in the Public pages to render the Snapppt embed snippet
  include SNAPPPT_PLUGIN_PATH . 'snapppt-frontend.php';
}
?>
