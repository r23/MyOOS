<?php
/** 
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
 * Represents individual users in the mapper -- either on the left-hand or right-hand side.
 * Echoing out an instance of this class directly provides a nicely formatted user block
 *
 * The main user mapper class
 * Parses user mapper options and loads in data for all required users
 */
 
class WPU_User_Mapper {
	
	private 
		$leftSide = 'wp',
		$numToShow = 50,
		$showUsers = 0,
		$usersToShow = 0,
		$showSpecificUsers = false,
		$showUsersLike = false,
		$numStart = 0,
		$showOnlyInt = 0,
		$showOnlyUnInt = 0,
		$showOnlyPosts = 0,
		$showOnlyNoPosts = 0,
		$numUsers = 0;

	public $users;
	
	/**
	 * Constructor. Parses the incoming options and filters and loads in the relevant user objects
	 * @param string $args: A url-style list of args and filters to process:
	 *	leftSide: The main user list to show (on the LHS of the user mapper)
	 *  numToShow: The number of records to return
	 *  numStart:  The offset to start showing records from
	 *  showOnlyInt: Filter out unintegrated users
	 *	showOnlyUnInt: Filter out integrated users
	 *  showOnlyPosts: Filter out users without any posts
	 *  showOnlyNoPosts: Filter out users with posts
	 */
	public function __construct($args, $showSpecificUserIDs = 0, $showLike = '') {
		global $phpbb_root_path, $phpEx;
		
		$argDefaults = array(
			'leftSide' 					=> 	'wp',
			'numToShow' 				=> 	50,
			'numStart'					=> 	0,
			'showOnlyInt' 				=> 	0,
			'showOnlyUnInt' 			=> 	0,
			'showOnlyPosts' 			=> 	0,
			'showOnlyNoPosts' 			=> 	0,
			'showLike'					=>	'',
		);
		$procArgs = array();
		parse_str($args, $procArgs);
		extract(array_merge($argDefaults, (array)$procArgs));

		@include_once($phpbb_root_path . 'includes/functions_display.' . $phpEx);
	
		$this->leftSide = ($leftSide == 'phpbb') ? 'phpbb' : 'wp';
		$this->numToShow = $numToShow;
		$this->numStart = $numStart;
		$this->showOnlyInt = (int)$showOnlyInt;
		$this->showOnlyUnInt = (int)$showOnlyUnInt;
		$this->showOnlyPosts= (int)$showOnlyPosts;
		$this->showOnlyNoPosts= (int)$showOnlyNoPosts;
		$this->showSpecificUsers = false;
		$this->showUsersLike = (empty($showLike)) ? false : (string)str_replace(array('|QUOT|', '|AMP|'), array('"', '&'), $showLike);

		if(is_array($showSpecificUserIDs)) { 
			$this->showSpecificUsers = true;
			$this->usersToShow = $showSpecificUserIDs;
		} else {
			if(!empty($showSpecificUserIDs)) {
				$this->showSpecificUsers = true;
				$this->usersToShow = (array)$showSpecificUserIDs;
			} 
			// else leave set at default
		}
		

		$this->users = array();
	
		if($this->leftSide != 'phpbb') { 
			// Process WP users on the left
			$this->load_wp_users();
			if($this->numUsers) {
				$this->find_phpbb_for_wp_users();
			}
			
		} else { 
			// Process phpBB users on the left
			$this->load_phpbb_users();
		}

	}
	
	/**
	 * Loads a list of WordPress users according to the user mapper options
	 * @access private
	 */
	private function load_wp_users() {
		global $wpdb, $phpbbForum, $db;
		
		$where = '';
		$mainWhere = '';
		$mainWhereAnd = '';
		$postClause = '';
		if(!empty($this->showSpecificUsers)) {
			/**
			 * We don't have a need for "show specific users" yet
			 */
			$mainWhere = '';
			trigger_error('UNIMPLEMENTED');
			die();
			
		} 
		if(!empty($this->showUsersLike)) {
			// find similar users for autocomplete
			$mainWhere =  $wpdb->prepare("UCASE(user_login) LIKE '%s'", '%' . strtoupper($this->showUsersLike) . '%');
			$mainWhereAnd = " AND $mainWhere";
			$mainWhere = " WHERE $mainWhere";
		}
		
		/** 
		 * For all normal queries we need to calculate a total user count so the results can be paginated
		 * @TODO: This pulls post count and/or integrated phpBB usr info. These are pulled later on again as
		 * part of the user load. This is inefficient -- should consider grabbing all data here (at the expense of readability)
		 */
			
		// if the total number of users depends on an integrated/unintegrated filter, need to start in phpBB
		if(!empty($this->showOnlyInt) || !empty($this->showOnlyUnInt))  {
			
			/**
			 * First we pull the users to use as a filter
			 * The filter could be a block or a pass filter, so we don't use LIMIT here.
			 */
			
			$fStateChanged = $phpbbForum->foreground();
			
			$sql = 'SELECT user_wpuint_id 
						FROM ' . USERS_TABLE . ' 
						WHERE (user_type = ' . USER_NORMAL . ' OR user_type = ' . USER_FOUNDER . ') 
							AND user_wpuint_id > 0';
				
			if(!$fResults = $db->sql_query($sql)) {
				$phpbbForum->background($fStateChanged);
				return;
			}
			$usersToFetch = array();
			while($fResult = $db->sql_fetchrow($fResults)) {
				$usersToFetch[] = $fResult['user_wpuint_id'];
			}
			
			$this->numUsers = sizeof($usersToFetch);
			
			$db->sql_freeresult();
			$phpbbForum->background($fStateChanged);
			
			if( (!empty($this->showOnlyInt)) && (!sizeof($usersToFetch)) ) {
				return;
			}

			 // Now create the filter for the WP query
			if(sizeof($usersToFetch)) {
				$set = implode(',', $usersToFetch);
				if(!empty($this->showOnlyInt)) {
					$where = ' WHERE ID IN (' . $set . ')' . $mainWhereAnd;
				} else {
					$where = ' WHERE ID NOT IN (' . $set . ')' . $mainWhereAnd;
				}
			}
			
			// If this is "show only unintegrated", we need to run a separate count query
			$this->numUsers = $wpdb->get_var( 'SELECT COUNT(*) AS numusers
				FROM ' . $wpdb->users .
				$where
			);
			
		// For other filter types (or no filter), we can just count in WordPress.
		} else {
			if($this->showOnlyPosts) {
				$postClause = ', ' . $wpdb->posts . ' ';
				$where = ' WHERE (u.ID = post_author AND post_type = \'post\' AND ' . get_private_posts_cap_sql('post') . ')' . $mainWhereAnd;
				$this->numUsers = $wpdb->get_var( 'SELECT COUNT(*) AS numusers
						FROM ' . $wpdb->users . ' AS u' . $postClause .
						$where
				);
			} else if($this->showOnlyNoPosts) {
				$postClause = ', ' . $wpdb->posts . ' ';
				$where = ' WHERE (u.ID <> post_author AND post_type = \'post\' AND ' . get_private_posts_cap_sql('post') . ')' . $mainWhereAnd;
				$this->numUsers = $wpdb->get_var( 'SELECT COUNT(*) AS numusers
						FROM ' . $wpdb->users . ' AS u' . $postClause .
						$where
				);
			} else {
				$where = $mainWhere;
				$this->numUsers = $wpdb->get_var('SELECT COUNT(*) AS count
						FROM ' . $wpdb->users . 
						$where
				);
			}
		}
		
		// return for all other than autocomplete if there aren't any users
		if(!$this->numUsers) {
			return;
		}
		
		// Now fetch the users
		$sql = "SELECT u.ID
				FROM {$wpdb->users} AS u {$postClause} 
				{$where} 
				ORDER BY user_login 
				LIMIT {$this->numStart}, {$this->numToShow}";
				
		$results = $wpdb->get_results($sql);
		
		foreach ((array) $results as $item => $result) {
			$mUser =  new WPU_Mapped_WP_User($result->ID);
			$this->users[$result->ID] = $mUser;
		}
	}
	
	/**
	 * Loads a list of phpBB users according to the loaded user mapper options
	 * @access private
	 */
	private function load_phpbb_users() {
		global $db, $phpbbForum;
		
		$fStateChanged = $phpbbForum->foreground();
		
		// for normal requests, get the count first
		if(empty($this->showSpecificUsers)) {
			$countSql =$this->phpbb_userlist_sql(false, $this->showUsersLike, true); 
			if($countResult = $db->sql_query($countSql)) {  
				if($count = $db->sql_fetchrow($countResult)) {
					$this->numUsers = $count['numusers'];
				}
			}
			$db->sql_freeresult();
			if(!$this->numUsers) {
				$phpbbForum->background($fStateChanged);
				return;
			} 
		}
		
		// now do the full query
		$sql =$this->phpbb_userlist_sql($this->showSpecificUsers, $this->showUsersLike, false);

		if($result = $db->sql_query_limit($sql, $this->numToShow, $this->numStart)) {
			while($r = $db->sql_fetchrow($result)) { 
				$user = new WPU_Mapped_Phpbb_User(
					$r['user_id'], 
					$this->transform_result_to_phpbb($r),
					'left'
				);
				$this->users[$r['user_id']] = $user;
				if(!empty($r['user_wpuint_id'])) {
					
					$integWpUser = new WPU_Mapped_WP_User($r['user_wpuint_id']);
					if($integWpUser->exists()) {
						$this->users[$r['user_id']]->set_integration_partner($integWpUser);
					}
				}
			}
		}
		
		$db->sql_freeresult();
		$phpbbForum->background($fStateChanged);
	
	}
	
	/**
	 * Find phpBB users that are integrated to WP users
	 * We treat phpBB users differently from WP users, since we can run a single
	 * query to pull in all the phpBB details rather than looping thru one by one
	 * @access private
	 */
	private function find_phpbb_for_wp_users() {
		global $phpbbForum, $db, $user;
		
		if(!sizeof($this->users)) {
			return;
		}
		
		$arrUsers = array_keys((array)$this->users);
		
		// The phpBB DB is the canonical source for user integration -- don't trust the WP marker
		$fStateChanged = $phpbbForum->foreground();
		
		$sql =$this->phpbb_userlist_sql($arrUsers);
		
		$results = array();
		
		if($pResult = $db->sql_query_limit($sql, $this->numToShow)) {
			while($r = $db->sql_fetchrow($pResult)) {
				$pUser = new WPU_Mapped_Phpbb_User(
					$r['user_id'], 
					$this->transform_result_to_phpbb($r),
					'right'
				);
				$this->users[$r['user_wpuint_id']]->set_integration_partner($pUser);
			}
		}
		
		$db->sql_freeresult();
		$phpbbForum->background($fStateChanged);
		
		return $results;
		
	}
	/**
	 * Transforms the returned DB object into something our mapped phpBB user class will accept
	 * @access private
	 */
	private function transform_result_to_phpbb($dbResult) {
		global $user, $phpbbForum;
		
		$fStateChanged = $phpbbForum->foreground();
		
		$arrToLoad = array(
			'loginName'				=> 	$dbResult['username'],
			'user_avatar'			=> 	$dbResult['user_avatar'],
			'user_avatar_type'		=> 	$dbResult['user_avatar_type'],
			'user_avatar_width'		=> 	$dbResult['user_avatar_width'], 
			'user_avatar_height'	=> 	$dbResult['user_avatar_height'],
			'email'					=> 	$dbResult['user_email'],
			'group'					=> 	(isset($phpbbForum->lang['G_' . $dbResult['group_name']])) ? $phpbbForum->lang['G_' . $dbResult['group_name']] : $dbResult['group_name'],
			'numposts'				=> 	(int)$dbResult['user_posts'],
			'regdate'				=> 	$user->format_date($dbResult['user_regdate']),
			'lastvisit'				=> 	(!empty($dbResult['user_lastvisit'])) ? $user->format_date($dbResult['user_lastvisit']) : __('n/a', 'wp-united')
		);
		
		$arrToLoad['rank'] = $arrToLoad['rank_image'] = $arrToLoad['rank_image_src'] = '';
		// fills the last three variables
		get_user_rank($dbResult['user_rank'], $arrToLoad['numposts'], $arrToLoad['rank'], $arrToLoad['rank_image'], $arrToLoad['rank_image_src']);
		$arrToLoad['rank'] = (isset($phpbbForum->lang[$arrToLoad['rank']])) ? $phpbbForum->lang[$arrToLoad['rank']] : $arrToLoad['rank'];
		$arrToLoad['rank'] = (empty($arrToLoad['rank'])) ? __('n/a', 'wp-united') : $arrToLoad['rank'];
		
		$phpbbForum->restore_state($fStateChanged);
		
		return $arrToLoad;
	}
	
	/**
	 * Generates the phpBB SQL for finding users
	 * @access private
	 */
	private function phpbb_userlist_sql($arrUsers = false, $showLike = false, $countOnly = false) {
		global $db, $phpbbForum;
		
		$fStateChanged = $phpbbForum->foreground();
		
		$where = '';
		if(!empty($arrUsers)) {
			$where = ' AND (' . $db->sql_in_set('u.user_wpuint_id', (array)$arrUsers) . ') ';
		} else if(!empty($showLike)) {
			$where = " AND (UCASE(u.username) LIKE '%" . $db->sql_escape(strtoupper($showLike)) . "%') ";
		} 
		
		// apply filters
		if(!empty($this->showOnlyInt)) {
			$where = ' AND (u.user_wpuint_id > 0) ' . $where;
		} else if(!empty($this->showOnlyUnInt)){
			$where = ' AND ((u.user_wpuint_id = 0) OR (u.user_wpuint_id = \'\') OR (u.user_wpuint_id IS NULL))  ' . $where;
		} else if(!empty($this->showOnlyPosts)) {
			$where = ' AND (u.user_posts > 0) ' . $where;
		} else if(!empty($this->showOnlyNoPosts)) {
			$where = ' AND (u.user_posts = 0) ' . $where;
		}				



		 $sqlArray = array();
		 
		 $sqlArray['FROM']  = array();
		 $sqlArray['FROM'][USERS_TABLE] = 'u';
		 
		 $sqlArray['WHERE'] = '(u.user_type = ' . USER_NORMAL . ' OR u.user_type = ' . USER_FOUNDER . ') ' . 
											$where;		 

		 if($countOnly) {
			 $sqlArray['SELECT'] = 'COUNT(*) AS numusers';
		} else {
			 $sqlArray['SELECT'] = 'u.user_wpuint_id, u.username, u.user_id, u.user_email, u.user_rank, u.user_posts, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, u.user_regdate, u.user_lastvisit, g.group_name';
			 $sqlArray['FROM'][GROUPS_TABLE] = 'g';

			$sqlArray['WHERE'] .= ' AND g.group_id = u.group_id';
			$sqlArray['ORDER_BY'] = 'u.username ASC';
		}
		
		
		$sql = $db->sql_build_query('SELECT', $sqlArray);
		
		$phpbbForum->background($fStateChanged);
		
		return $sql;
		
	}
	
	/**
	 * Sends a JSON string to the browser with enough information to create e.g. an autocomplete dropdown
	 */
	
	public function send_json() {
		
		header('Content-type: application/json; charset=utf-8');
		
		$json = array();
		foreach($this->users as $user) {
			$statusCode = ($user->is_integrated()) ? 0 : 1;
			$status = ($statusCode == 0) ? __('Already integrated', 'wp-united') : __('Available', 'wp-united');
			
			$data = '{' .
				'"value": ' . $user->userID . ',' . 
				'"label": "' . $user->get_username() . '",' . 
				'"desc": "' . $user->get_email() . '",' . 
				'"status": "' . $status . '",' . 
				'"statuscode": ' . $statusCode . ',' . 
				'"avatar": "' . str_replace('"', "'", $user->get_avatar()) . '"' . 
				'}';
				
				$json[] = $data;
		}
		if(sizeof($json)) {
			die('[' . implode($json, ',') . ']');
		} else {
			die('{}');
		}
	}
	
	/**
	 * Returns the number of users that would exist for this query (including filters) if it were not paged
	 */
	public function num_users() {
		return $this->numUsers;
	}
}

// That's all. Done.