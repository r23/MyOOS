<?php

defined( 'WP_UNINSTALL_PLUGIN' ) or die( 'forbidden' );

  unregister_widget( 'WP_w3all_widget_login' );
  unregister_widget( 'WP_w3all_widget_last_topics' );
  unregister_widget( 'WP_w3all_widget_phpbb_onlineStats' );

  delete_option( 'widget_wp_w3all_widget_login' );
  delete_option( 'widget_wp_w3all_widget_last_topics' );
  delete_option( 'widget_wp_w3all_widget_phpbb_onlinestats' );

  // clean up w3all db rows
  delete_option( 'w3all_conf_pref' );
  delete_option( 'w3all_conf_avatars' );
  delete_option( 'w3all_phpbb_cookie' );
  delete_option( 'w3all_conf_pref_template_embed_link' );
  delete_option( 'w3all_forum_template_wppage' );
  delete_option( 'w3all_bruteblock_phpbbulist' );
  delete_option( 'w3all_phpbb_dbconn' );

  delete_option( 'w3all_exclude_id1' );// not more used since 2.4.0
  delete_option( 'w3all_path_to_cms' );// not more used since 2.4.6 moved into w3all_phpbb_dbconn
  delete_option( 'w3all_url_to_cms' );// not more used since 2.4.6 moved into w3all_phpbb_dbconn
  delete_option( 'w3all_iframe_phpbb_link_yn' );// not more used this has been substituted by 'w3all_conf_pref_template_embed_link' option
  delete_option( 'w3all_u_signups_data' );// not more used since 2.3.8
  delete_option( 'w3all_pass_hash_way' );// not more used since 2.4.6 moved into w3all_phpbb_dbconn
  delete_option( 'w3all_not_link_phpbb_wp' );// not more used since 2.4.6 moved into w3all_phpbb_dbconn
  delete_option( 'widget_wp_w3all_widget_phpbb_mchat' );// not more used since 2.4.0

   global $wpdb;

  // if 'DELETE users in phpBB when deleted in WP' option was active
     $wpumetatab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'usermeta' : $wpdb->prefix . 'usermeta';
     $wpdb->query("DELETE FROM $wpumetatab WHERE meta_key = 'w3all_wpdelete_phpbbulist_delby'");
