<?php

  // only uninstall if called from within Wordpress
  if(!defined('WP_UNINSTALL_PLUGIN')) { exit(); }

  delete_option('snapppt')
  delete_option('sauce_account_id')

?>
