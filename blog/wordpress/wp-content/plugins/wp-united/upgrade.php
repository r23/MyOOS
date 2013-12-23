<?php

/** 
* @package WP-United
* @version $Id: 0.9.2.0  2013/1/4 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
* A (long) list of upgrade actions that upgrade WP-United between versions.
* 
*/

/**
 */
if ( !defined('IN_PHPBB') && !defined('ABSPATH') ) exit;

function wpu_do_upgrade($action) {
	global $phpbbForum, $wpUnited, $debug, $db, $wpuDebug, $phpbb_root_path, $phpEx;


	switch($action) {
		
		/**
		 *	UPGRADING <0.9.2.0 TO 0.9.2.0
		 *	-----------------------------
		 * in this version, we move the cross-posting column from POSTS_TABLE to TOPICS_TABLE
		 */
		case 'from <0.9.2.0';
			$wpuDebug->add("Upgrading WP-United $action");
			
			$fStateChanged = $phpbbForum->foreground();
			$db->sql_return_on_error(true);
			
			$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' 
				ADD topic_wpu_xpost VARCHAR(10) NULL DEFAULT NULL';

			@$db->sql_query($sql);
			
			
			//Now copy across data from posts column to topics column
			$sql = 'SELECT post_wpu_xpost, topic_id FROM ' . POSTS_TABLE . ' 
					WHERE post_wpu_xpost > 0';
					
			if($result = $db->sql_query($sql)) {
				while ($row = $db->sql_fetchrow($result)) {
					$sql = 'UPDATE ' . TOPICS_TABLE . ' 
						SET topic_wpu_xpost = ' . (int) $row['post_wpu_xpost'] . '
						WHERE topic_id = ' . (int) $row['topic_id'];
					@$db->sql_query($sql);
				}
			}
			$db->sql_freeresult($result);
			
			// Now delete the posts column
			$sql = 'ALTER TABLE ' . POSTS_TABLE . ' DROP COLUMN post_wpu_xpost';
			@$db->sql_query($sql);
			
			$db->sql_return_on_error(false);
			$phpbbForum->restore_state($fStateChanged);
			
		// no break!
		case 'from 0.9.2.0-r0':
		
			$wpuDebug->add("Upgrading WP-United $action");
		
			$fStateChanged = $phpbbForum->foreground();
			$db->sql_return_on_error(true);
			
			// Add new x-posting columns
			$sql = 'ALTER TABLE ' . POSTS_TABLE . ' 
				ADD post_wpu_xpost_parent VARCHAR(10) NULL DEFAULT NULL, 
				ADD post_wpu_xpost_meta1 VARCHAR(255) NULL DEFAULT NULL, 
				ADD post_wpu_xpost_meta2 VARCHAR(255) NULL DEFAULT NULL';

			@$db->sql_query($sql);
			
			
			$db->sql_return_on_error(false);
			$phpbbForum->restore_state($fStateChanged);
		
		// no break!
		
		case 'from 0.9.2.0-r1':
		
			$wpuDebug->add("Upgrading WP-United $action");
			
			$fStateChanged = $phpbbForum->foreground();
			
			// Setup $auth_admin class so we can add permission options
			include_once($phpbb_root_path . 'includes/acp/auth.' . $phpEx);
			$auth_admin = new auth_admin();
			
			// Add permissions
			$auth_admin->acl_add_option(array(
				'local'      => array('f_wpu_xpost', 'f_wpu_xpost_comment'),
				'global'   => array('u_wpu_subscriber','u_wpu_contributor','u_wpu_author','m_wpu_editor','a_wpu_administrator')
			));
		
			$phpbbForum->restore_state($fStateChanged);
		
		
		default;
		break;
	}


}


// Done