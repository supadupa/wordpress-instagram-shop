<?php

if(!defined( 'ABSPATH' )) exit; // Exit if accessed directly

// add the 'Add SNPT Embed' feed button to the editor
function snapppt_embed_editor_button($context) {
  $button_logo = SNAPPPT_PLUGIN_URL . 'images/snapppt-logo-square-small.png';
  $context .= '<a class="button" href="#" onclick="tinyMCE.activeEditor.execCommand(\'openSnappptEmbedSelector\')"; return false">Add SNPT Embed</a>';
  return $context;
}

// 'Add SNPT Embed' button removed pending support for newer Embeds
// add_filter('media_buttons_context', 'snapppt_embed_editor_button');

function snapppt_settings_content() {
  global $snapppt_options;
  include SNAPPPT_PLUGIN_PATH .'snapppt_settings_template.php';
}


function register_snapppt_tinymce_plugin() {
  function tinymce_snapppt_plugin($plugins) {
    $plugins['snappptEmbedSelector'] = plugins_url('tinymce/', __FILE__) . 'editor_plugin.js';
    return $plugins;
  }
  add_filter('mce_external_plugins', 'tinymce_snapppt_plugin');
}
add_filter('admin_init', 'register_snapppt_tinymce_plugin');


function snapppt_is_setup() {
 global $snapppt_options;
 return isset($snapppt_options['account_id']) && strlen($snapppt_options['account_id']) > 5;
}


// add SNPT to the left hand admin menu
function snapppt_settings_menu() {
 $menu_icon = SNAPPPT_PLUGIN_URL .'/images/snapppt-logo-square.png';
 $menu_title = "Sauce";

 if(!snapppt_is_setup()) { $menu_title .= '<span style="margin-left: 5px" class="update-plugins"><span class="update-count">Setup</span></span>'; }
 add_menu_page('Sauce', $menu_title, 'manage_options', 'snapppt_settings', 'snapppt_settings_content', $menu_icon);
}
add_action('admin_menu', 'snapppt_settings_menu');


// have the CSS for the SNPT settings page loaded in when suitable
function snapppt_settings_styles($hook) {
  if($hook != 'toplevel_page_snapppt_settings') { return; }
  wp_enqueue_style('snapppt_settings_styles', plugins_url('assets/css/snapppt-settings-page.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'snapppt_settings_styles');


// CSS to affect the SNPT admin menu icon
function snapppt_admin_styles($hook) {
  wp_enqueue_style('snapppt_admin_styles', plugins_url('assets/css/snapppt-admin.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'snapppt_admin_styles');

?>
