<?php defined( 'ABSPATH' ) or die( 'forbidden' );
echo '<div id="'.$w3pm_id.'" class="'.$w3pm_class.'" '.$w3pm_inline_style.'>' .
'<a href="'.$w3pm_href.'"'.$w3pm_href_blank .'>'.
__( 'You have ', 'wp-w3all-phpbb-integration' ) . $phpbb_user_session[0]->user_unread_privmsg . __( ' unread forum PM', 'wp-w3all-phpbb-integration' ) .
'</a></div>';
