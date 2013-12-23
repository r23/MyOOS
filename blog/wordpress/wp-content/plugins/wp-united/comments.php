<?php
/** 
*
* @package WP-United
* @version $Id: 0.9.2.0  2012/01/07 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
* The main class for retrieving cross-posted comments.
* Access is via an access layer that pulls and caches comments as necessary.
* 
*/

/**
*
*/
if ( !defined('IN_PHPBB') && !defined('ABSPATH') ) exit;


/**

	TODO:
	- right now grouped count and full count queries have different fingerprints (as former has no WP data passed in)... make them identical!!
	- call in here for main xposting details too, and rename file to generic xposting layer.


*/



class WPU_XPost_Query_Store {
	
	private 
		
		$queries,
		$currentQuery,
		$maxLimit,
		$currentProvidedLimit,
		$links,
		$doingQuery,
		$primeCacheKey,
		$orderFieldsMap,
		$idOffset;
		
	/**
	 * Class initialisation
	 */
	public function __construct() {
		
		$this->maxLimit = 10000;
		$this->idOffset = 100000000;
		$this->queries = array();
		$this->links = array(
			'view'		=> array(),
			'edit'		=> array(),
			'delete'	=> array(),
			'approve'	=> array()
		);
		$this->currentQuery = array();
		$this->primeCacheKey = '';
		$this->doingQuery = false;
		

		
		// variables we could sort our query by.
		// Commented items are criteria WordPress could send which we will drop.
		$this->orderFieldsMap = array(
			//'comment_agent'			=> 	'', // same for all phpBB comments,
			'comment_approved'		=>	'p.post_approved',
			'comment_author'		=>	'u.username',
			'comment_author_email'	=>	'u.user_email',
			'comment_author_IP'		=> 	'u.user_ip',
			'comment_author_url'	=>	'u.user_website',
			'comment_content'		=>	'p.post_text',
			'comment_date'			=>	'p.post_time',
			'comment_date_gmt'		=>	'p.post_time',
			'comment_ID'			=>	'p.post_id',
			//'comment_karma'		=>	'', // n/a
			'comment_parent'		=>	'p.post_wpu_xpost_parent', 
			'comment_post_ID'		=>	't.topic_id',
			//'comment_type'			=> 	'',		// only interested in 'comments'
			'user_id'				=>	'u.user_wpuint_id',
		);
		

	}	
	
	public function get_id_offset() {
		return $this->idOffset;
	}
	
	private function init_defaults() {
	
		$this->currentQuery = array(
			'passedResult'	=> array(),
			'phpbbOnly'	=> array(),
			'signature' 	=> '',
			'postID'		=> 0,
			'userID' 		=> '',
			'userEmail' 	=> '',
			'topicUser' 	=> '',
			'limit' 		=> 25,
			'offset' 		=> 0,
			'parentID'		=> 0,
			'realLimit'		=> 0,
			'realOffset'	=> 0,			
			'count' 		=> false,
			'groupByStatus' => false,
			'hideOtherUnapproved'	=> false,
			'status' 		=> 'all',
			'order' 		=> 'DESC',
			'phpbbOrderBy' 	=> '',
			'finalOrderBy' 	=> array()
		);
		
		$this->currentProvidedLimit = 0;

	}
	
	public function get($query, $comments, $count = false, $prefetch = false) {
		
		global $wpuDebug;
		
		if(!$this->can_handle_request($query, $prefetch) || $this->doingQuery) {
			return $comments;
		}
		// this must be set before set_current_query as we sometimes 
		// need to pull from WP
		$this->doingQuery = true;
	
		$this->set_current_query($query, $comments, $count, $prefetch);
	
		$this->create_request_signature();
		
		$sig =  $this->currentQuery['signature']; 
		
		if(!isset($this->queries[$sig])) {
			$wpuDebug->add('New XPost query created for query ' . $sig);
			$this->queries[$sig] = new WPU_XPost_Query($this->idOffset);
		} else {
			$wpuDebug->add('Re-using XPost query from store for query ' . $sig);
		}
		
		$result = $this->queries[$sig]->get_result($this->currentQuery);
		
		$newLinks = array();
		foreach($this->links as $linkGroup => $links) {
			$newLinks[$linkGroup] = array_merge($links, $this->queries[$sig]->links[$linkGroup]);
		}
		$this->links = $newLinks;
		
		$this->doingQuery = false;
		
		if(!empty($this->primeCacheKey)) {
			$last_changed = wp_cache_get( 'last_changed', 'comment' );
			$cache_key = "get_comments:{$this->primeCacheKey}:$last_changed";
			wp_cache_add( $cache_key, $result, 'comment' );
		}
		
		return $result;
	
	}
		
	
	/**
	 * We can only handle certain types of comment queries right now.
	 * This is used to reject queries that we either can't understand or make no sense in a cross-posted
	 * context.
	 *
	 * Not to be called for integer queries -- only for full query objects!.
	 * @pram WP_Comment_Query $query query object
	 * @return bool true if we can handle it!
	 */
	private function can_handle_request(&$query, $prefetch) {
		
		if(!is_object($query)) {
			return true;
		}

		if( // temp; the comments page needs offsets to work
			//preg_match("/\/edit-comments\.php/", $_SERVER['REQUEST_URI'])	||
			
			// we have no karma
			!empty($query->query_vars['karma'])							||
			
			// We can deal with threaded comments, but if they don't sit in phpBB then there's not much point
			!(
				empty($query->query_vars['parent'])			||
				($query->query_vars['parent'] > $this->idOffset)
			)															||
			
			// TODO: Allow comment filtering by topic title
			!empty($query->query_vars['post_name'])						||
			
			// TODO: Allow comment filtering by WP post parent
			!empty($query->query_vars['post_parent'])					||
			
			// we only understand published, cross-posted posts
			!(	
				empty($query->query_vars['post_status']) 	||
				($query->query_vars['post_status'] == 'publish')
			)															||
			
			// we only understand approved, pending and all comments.
			(
				($query->query_vars['status'] == 'spam') 	||
				($query->query_vars['status'] == 'trash')
			)															||
			
			// ignore attachments or anything other than posts
			!(
				empty($query->query_vars['post_type'])					||
				($query->query_vars['post_type'] == 'post')
			)															||
			
			// ignore trackbacks, pingbacks or anything other than comments
			!(
				empty($query->query_vars['type'])			||
				($query->query_vars['type'] == 'wpuxpostonly') ||
				($query->query_vars['type'] == 'wpunoxpost') 
			)															||
			
			//no need to do anything for non-xpost comments only
			(isset($query->query_vars['wpu_not_xposted']))				||
			
			// cannot translate meta-queries to phpBB
			!empty($query->query_vars['meta_key'])						||
			!empty($query->query_vars['meta_value'])					||
			!empty($query->query_vars['meta_query'])
        ) {
			return false;
		}
		
		if($prefetch) {
			/**
			 * if the query has an offset, modify the query. It gets passed back
			 * by reference, so we can modify the offset and plant some markers for
			 * us later.
			 */
			if(!empty($query->query_vars['offset'])) {
				if(!isset($query->query_vars['wpu-real-offset'])) {
					if(($query->query_vars['offset'] + $query->query_vars['number']) <= $this->maxLimit) {
						$query->query_vars['wpu-real-offset'] = $query->query_vars['offset'];
						$query->query_vars['wpu-real-limit'] = $query->query_vars['number'];
						if(!empty($query->query_vars['number'])) {
							$query->query_vars['number'] = $query->query_vars['number'] + $query->query_vars['offset'];
						}
						$query->query_vars['offset'] = 0;
					}
				}
				
				// now send the query back and let wordpress send us the results later.
				return false;
			}
			
			//if this is for "anything but cross-posts", we just change the comment type 
			// and send it back to WordPress for handling
			if($query->query_vars['type'] == 'wpunoxpost') {
				$query->query_vars['type'] = '';
				$query->query_vars['wpu_not_xposted'] = true;
				return false;
			}			
			
			
			// for other prefetch requests, we only handle counts. Everything else gets sent back
			if(empty($query->query_vars['count'])) {
				return false;
			}
		}
		

		

		return true;

	}
	
	/**
	 * Translate query vars from WordPress format to our standardised / phpBB format
	 * Not to be called for integer queries.
	 * @param WP_Comment_Query $query our query object
	 * @return void
	 */
	private function set_current_query($query, $comments, $count, $prefetch) {
	
		$this->init_defaults();
		
		$this->currentQuery['passedResult'] = $comments;
	

		if(!is_object($query)) {
			$this->currentQuery['postID'] = ((int)$query > 0) ? $query : false;
			$this->currentQuery['limit'] = $this->maxLimit;
			$this->currentQuery['offset'] = 0;
			$this->currentQuery['realLimit'] = $this->maxLimit;
			$this->currentQuery['realOffset'] = 0;
			$this->currentQuery['hideOtherUnapproved'] = true;
		
			if($count) { 
				$this->currentQuery['count'] = true;
				$this->currentQuery['groupByStatus'] = true;
				$this->currentQuery['hideOtherUnapproved'] = false;
			}
		} else {
			
			$this->currentProvidedLimit = $query->query_vars['number'];
			
			$this->currentQuery['hideOtherUnapproved'] = (is_admin()) ? false : true;
			$this->currentQuery['postID'] = $query->query_vars['post_id'];
			$this->currentQuery['limit'] = ((int)$query->query_vars['number'] > 0) ? $query->query_vars['number'] : $this->maxLimit;
			$this->currentQuery['offset'] = $query->query_vars['offset'];
			$this->currentQuery['count'] = $query->query_vars['count'];
			
			// set up vars for status clause
			if(!empty($query->query_vars['status'])) {
				if($query->query_vars['status'] == 'hold') {
					$this->currentQuery['status'] = 'unapproved';
				}
				else if($query->query_vars['status'] != 'approve') {
					$this->currentQuery['status'] = 'approved';
				}
			}
			
			// set up vars for user clause
			if(!empty($query->query_vars['user_id'])) {
				$this->currentQuery['userID'] = $query->query_vars['user_id'];
			}
			
			// set up vars for e-mail clause
			if(!empty($query->query_vars['author_email'])) {
				$this->currentQuery['userEmail'] = $query->query_vars['author_email'];
			}
			
			// set up vars for topic author ID clause
			if(!empty($query->query_vars['post_author'])) {
				$this->currentQuery['topicUser'] = $query->query_vars['post_author'];
			}
			
			// set up vars for comment parent clause
			if(!empty($query->query_vars['parent'])) {
				$this->currentQuery['parentID'] = $query->query_vars['parent'];
			}			
			
			/**
			 * if this is a modified query that has had its offset 
			 * and limit modified by us, then set up the params
			 */
			if(isset($query->query_vars['wpu-real-offset'])) {
				$this->currentQuery['realOffset'] = (int)$query->query_vars['wpu-real-offset'];
				$this->currentQuery['realLimit'] = ((int)$query->query_vars['wpu-real-limit'] > 0) ? (int)$query->query_vars['wpu-real-limit'] : $this->maxLimit;
			} else {
				$this->currentQuery['realOffset'] = (int)$this->currentQuery['offset'];
				$this->currentQuery['realLimit'] = (int)$this->currentQuery['limit'];
			}	
			
			// If this is a count request that comes from get_comments,
			// it is never filtered and we never get to add the WP count.
			// So we get that now. We then have to prime the cache with the
			// result, as there is no useable filter.
			
			if(!empty($query->query_vars['count']) && ($this->currentQuery['passedResult'] === false) && $prefetch) {
				$this->currentQuery['passedResult'] = get_comments($query->query_vars);
				$query->query_vars['karma'] = 'WP-United Count Shortcut';
				$this->primeCacheKey = md5(serialize((array)($query->query_vars)));
			}
			
			if($query->query_vars['type'] == 'wpuxpostonly') {
				$this->currentQuery['phpbbOnly'] = true;
			}
			
			
		}
		
		$this->setup_sort_vars($query);
		
		return;
	}
	
	/**
	*	Sets up the class sorting parameters. Sorting happens at two levels:
	*	- in the SQL when fetching results from the database (using $this->phpbbOrderBy)
	* 	- when mixing together the WordPress and phpBB comments (using $this->finalOrderBy)
	*/
	private function setup_sort_vars($query) {
		
		if($this->currentQuery['count']) {
			$this->currentQuery['order'] = '';
			return;
		}
		
		if(!is_object($query)) {
			// for loop queries, order is ASC by default
			$this->currentQuery['order'] = ('DESC' == strtoupper(get_option('comment_order'))) ? 'DESC' : 'ASC';
			$this->currentQuery['phpbbOrderBy'] = 'p.post_time';
			$this->currentQuery['finalOrderBy'] = array('comment_date_gmt');
			return;
		} 
			
		
		// set up vars for ordering clauses
		if (!empty($query->query_vars['orderby'])) {
			$ordersBy = is_array($query->query_vars['orderby']) ? $query->query_vars['orderby'] : preg_split('/[,\s]/', $query->query_vars['orderby']);
		
			
			$ordersBy = array_intersect($ordersBy, array_keys($this->orderFieldsMap));
			
			foreach($ordersBy as $orderBy) {
				if(!empty($this->currentQuery['phpbbOrderBy'])) {
					$this->currentQuery['phpbbOrderBy'] .= ', ';
				}
				$this->currentQuery['phpbbOrderBy'] .= $this->orderFieldsMap[$orderBy];
				$this->currentQuery['finalOrderBy'][] = $orderBy;
			}
			
		}
		
		$this->currentQuery['phpbbOrderBy'] = empty($this->currentQuery['phpbbOrderBy']) ? 'p.post_time' : $this->currentQuery['phpbbOrderBy'];
		
		if(!sizeof($this->currentQuery['finalOrderBy'])) {
			$this->currentQuery['finalOrderBy'] = array('comment_date_gmt');
		}
		
		// for non-loop queries, order is DESC by default
		if(empty($query->query_vars['order'])) {
			$this->currentQuery['order'] = 'DESC';
		} else {
			$this->currentQuery['order'] = ('ASC' == strtoupper($query->query_vars['order'])) ? 'ASC' : 'DESC';
		}
			
	}
	
	
	
	/**
	 * Get the signature of a request to determine if we can re-use the 
	 * results, of if we need to run another query.
	 * 
	 * Even slightly varying requests could be served from the same 
	 * query results.
	 * 
	 */
	private function create_request_signature() {
		
		// order is of no significance if no limit or offset is set
		$order = (empty($this->currentProvidedLimit)) ? 0 : $this->currentQuery['order'];
		$orderBy = (empty($this->currentProvidedLimit)) ? 'na' : $this->currentQuery['phpbbOrderBy'];
		
		$count = 0;
		$group = 0;
		
		// if we are pulling for a specific post, a count request can be fingerprinted almost the same as a normal request.
		if($this->currentQuery['count']) {
			if(empty($this->currentQuery['postID'])) {
				$count = (int)$this->currentQuery['count'];
				$group = (int)$this->currentQuery['groupByStatus'];
			} else {
				
				// TODO: Right now the group-by for individual posts must be fingerprinted, as data is provided for counts
				// but not for group-by requests... TODO: Make the data pulls more generic.
				$group = (int)$this->currentQuery['groupByStatus'];
				
			}
		}
			
		$phpbbOnly = (empty($this->currentQuery['offset'])) ? '' : $phpbbOnly;
				
		
		$this->currentQuery['signature'] = '[' . implode(',', array(
			(int)$this->currentQuery['postID'], 
			$this->currentQuery['userID'], 
			$this->currentQuery['userEmail'], 
			$this->currentQuery['topicUser'], 
			(int)$phpbbOnly,
			(int)$this->currentQuery['parentID'], 
			(int)$this->currentQuery['limit'], 
			(int)$this->currentQuery['offset'], 
			(int)$this->currentQuery['hideOtherUnapproved'], 
			$count,
			$group,
			(int)$this->currentQuery['status'], 
			(int)$order,
			$orderBy
		)) . ']';
		
	}
	
	
	/**
	* Returns a link for this cross-posted comment.
	* @param mixed $commentID an identifier for our cross-posted comment
	* @return StdObject comment object, or false if no link exists.
	*/
	public function get_comment_action($type, $commentID) {
		
		if(isset($this->links[$type])) {
			if(isset($this->links[$type]['comment' . $commentID])) { 
				return $this->links[$type]['comment' . $commentID];
			} else {
				return 0;
			}
		}	
		return false;
	}

	
	
}





/**
 * A comment object to store cross-posted comment results, and retrieve various other info.
 * Each WPU_Comments object is a query result.
 */
class WPU_XPost_Query {
	
	private 
		$success,
		$result,
		$usingPhpBBComments,
		$queryExecuted,
		
		$lastQueryArgs,
		$currQueryArgs,
		
		$passedResult,
		$phpbbOnly,
		$signature,
		$postID,
		$userID,
		$userEmail,
		$topicUser,
		$parentID,
		$limit,
		$offset,
		$count,
		$groupByStatus,
		$status,
		$order,
		$hideOtherUnapproved,
		$phpbbOrderBy,
		$finalOrderBy,
		
		$realLimit,
		$realOffset,
		
		$idOffset;
		

	public $links;
	

	/**
	 * Class initialisation
	 */
	public function __construct($idOffset) {
		
		$this->success = false;
		$this->reset_results();
		
		$this->links = array(
			'view'		=> array(),
			'edit'		=> array(),
			'delete'	=> array(),
			'approve'	=> array()
		);
		
		$this->currQueryArgs = false;
		$this->lastQueryArgs = array();
		
		// we set an unfeasibly large base offset for comment IDs, as otherwise we could merge phpBB and WordPress comments
		// that have colliding IDs. phpBB IDs have the offset added.
		$this->idOffset = $idOffset;

	}
	
	private function reset_results() {
		$this->result = array(
			'has-xposts'			=> false,
			'has-xposted-comments'	=> false,
			'xposts'				=> array(),
			'comments'				=> array(),
			'count'					=> 0,
			'count-grouped'			=> (object)array(
				'moderated'			=> 0,
				'approved'			=> 0,
				'spam'				=> 0,
				'trash'				=> 0,
				'post-trashed'		=> 0,
				'total_comments'	=> 0
			),
			'count-phpbb'			=> 0,
			'count-wp'				=> 0
		);
		
		$this->queryExecuted = false;
	}
	

	public function get_result($queryArgs) {
	
		$this->populate_vars($queryArgs);
	
		if(!$this->count && $this->lastQueryArgs['count']) {
			// The last query was a count request, we need to re-run the query
			$this->reset_results();
		}
	
		if(!$this->queryExecuted) {
			$this->queryExecuted = true;
			$this->success = $this->perform_phpbb_comment_query();
			
			if($this->success && !$this->count && is_array($this->passedResult) && sizeof($this->passedResult) && !$this->phpbbOnly) {
				$this->add_wp_comments($this->passedResult);
			}
		}
		
		if(!$this->success) { 
			return false;
		}
		
		if(!$this->count) {
			if($this->result['has-xposted-comments']) {
				$this->sort();
			}
			if(is_array($this->result['comments'])) {
				return array_slice($this->result['comments'], $this->realOffset, $this->realLimit);
			}
		}
		
		if($this->groupByStatus) {
			return $this->result['count-grouped'];
		} else {
			return $this->result['count'];
		}
	
		return false;
		
	}

	private function populate_vars($queryArgs) {
		foreach($queryArgs as $arg => $value) {
			$this->$arg = $value;
		}
		$this->lastQueryArgs = $this->currQueryArgs;
		$this->currQueryArgs = $queryArgs;
	}
	
	
	
	/**
	* Adds the WordPress comments to the class
	* @param array $comments the WordPress comments
	* @return void
	*/
	private function add_wp_comments($comments) {
		$this->result['comments'] = array_merge($comments, $this->result['comments']);
		
		// add to totals
		foreach($comments as $comment) {
			switch($comment->comment_approved) {
				case 0:
					$this->result['count-grouped']->moderated++;
					$this->result['count-grouped']->total_comments++;
					$this->result['count_wp']++;
				case 1:
					$this->result['count-grouped']->approved++;
					$this->result['count-grouped']->total_comments++;
					$this->result['count_wp']++;
				case 'spam':
					$this->result['count-grouped']->spam++;
					$this->result['count-grouped']->total_comments++;
					$this->result['count_wp']++;
				case 'trash':
					$this->result['count-grouped']->trash++;
				break;
				case 'post-trashed':
					$this->result['count-grouped']->{'post-trashed'}++;
				break;
				default:
					$this->result['count-grouped']->total_comments++;
					$this->result['count_wp']++;
				break;
			}
		}	
		$this->result['count'] = $this->result['count-grouped']->total_comments;
	}
	
	/**
	* Populates the class with phpBB comments or a count result, according to the query requirements.
	* the query must already have been processed.
	* @return bool true if it is possible to read cross-posted comments here, even if there are none.
	*/
	private function perform_phpbb_comment_query() {
		
		global $wpuDebug, $phpbbForum, $auth, $db, $phpEx, $user, $phpbb_root_path;

		$fStateChanged = $phpbbForum->foreground();

		
		// pull some permissions
		$permissions = array(
			'read_forum'	=> array_unique(array_keys($auth->acl_getf('f_read', true))),
			'edit_own'		=> array(),
			'delete_own'	=> array(),
			'edit_forum'	=> array(),
			'delete_forum'	=> array(),
			'approve_forum'	=> array()
		);
		
		// user can't read any forums -- don't bother proceding unless this is a count request.
		// TODO: WordPress sometimes prepares a count based on pulling all records. We may need to move check until later
		if(!sizeof($permissions['read_forum'])) {
			if(!$this->count) {
				$phpbbForum->restore_state($fStateChanged);
				return false;
			}
		} else {
			
			//Add global topics
			$permissions['read_forum'][] = 0;
		
			$permissions['edit_own'] 		= array_unique(array_keys($auth->acl_getf('f_edit')));
			$permissions['delete_own']		= array_unique(array_keys($auth->acl_getf('f_delete')));
			$permissions['edit_forum']		= array_unique(array_keys($auth->acl_getf('m_edit')));
			$permissions['delete_forum'] 	= array_unique(array_keys($auth->acl_getf('m_delete')));
			if($this->status != 'approved') {
				$permissions['approve_forum'] 	= array_unique(array_keys($auth->acl_getf('m_approve')));
			}
		
		}
		
	
		// What are the user's edit permissions?
		// edit own posts
		$canEditIn = array_unique(array_keys($auth->acl_getf('f_edit', true))); 
		// edit others
		$canEditOthersIn = array_unique(array_keys($auth->acl_getf('m_edit', true))); 
		
		$phpbbID = $phpbbForum->get_userdata('user_id');


		// Now, time to build the query.... It's a many-faceted one but can be done in one go....
		$where = array();
		
		if($this->count) { 
			if($this->groupByStatus) {
				$query = array(
					'SELECT' 	=> 'p.post_approved, COUNT(p.post_id) AS num_total',
					'GROUP_BY'	=> 'p.post_approved'
				);
			} else { 
				$query = array(
					'SELECT' 	=> 'COUNT(p.post_id) AS num_total'
				);
			}
			
		} else {
			
			$query = array(
				'SELECT'	=> 'p.post_id, p.poster_id, p.poster_ip, p.post_time, 
								p.post_approved, p.enable_bbcode, p.enable_smilies, p.enable_magic_url, 
								p.enable_sig, p.post_username, p.post_subject, 
								p.post_text, p.bbcode_bitfield, p.bbcode_uid, p.post_edit_locked,
								p.post_wpu_xpost_parent, p.post_wpu_xpost_meta1, p.post_wpu_xpost_meta2,
								t.topic_approved, t.topic_wpu_xpost, t.topic_first_post_id, t.topic_type, 
								t.forum_id, t.topic_id, t.topic_status, t.topic_replies AS all_replies, t.topic_replies_real AS replies, 
								u.user_id, u.username, u.user_wpuint_id, u.user_email',

				'ORDER_BY'	=> $this->phpbbOrderBy . ' ' . $this->order
			);
			
			$where[] = '(' . $db->sql_in_set('t.forum_id', $permissions['read_forum']) . ')';
		}
		
		$query['FROM'] = array(
			TOPICS_TABLE 	=> 	't',
			POSTS_TABLE		=> 	'p',
			USERS_TABLE		=>	'u'
		);
		
		
		if($this->postID) {
			$where[] = sprintf('(t.topic_wpu_xpost = %d)', $this->postID);
		} else {
			$where[] = '(t.topic_wpu_xpost > 0)';
		}
		
		if($this->userID) {
			$where[] = sprintf('(u.user_wpuint_id = %d)', $this->userID);
		}
		
		if($this->userEmail) {
			$string = esc_sql(like_escape($this->userEmail));
			$where[] = "(u.user_email LIKE '%" . $db->sql_escape($string) . "%')";
		}
		
		if($this->topicUser) {
			$where[] = sprintf("(t.topic_poster = %d)", wpu_get_integrated_phpbbuser($this->topicUser));
		}
		
		if($this->parentID) {
			$where[] = sprintf("(p.post_wpu_xpost_parent = %d)");
		}
		
		$canViewUnapproved = (sizeof($permissions['approve_forum'])) ? $db->sql_in_set('t.forum_id', $permissions['approve_forum']) . ' OR ' : '';
		
		if($this->status == 'unapproved') {
			$where[] = '(p.post_approved = 0 AND (' .
				$canViewUnapproved . ' 
				u.user_id = ' . $phpbbID . ' 
				))';
		} else if($this->status == 'approved') {
			$where[] = '(p.post_approved = 1)';
		} else {
			$where[] = '(p.post_approved = 1 OR ( 
				p.post_approved = 0 AND (' .
				$canViewUnapproved . ' 
				u.user_id = ' . $phpbbID . '
				)))';
		}
			
		
		$where[] = '
			((p.poster_id = u.user_id) AND 
			(p.topic_id = t.topic_id) AND 
			(t.topic_first_post_id <> p.post_id) AND 
			(t.topic_replies > 0)) ';
			
		$query['WHERE'] = implode(' AND ', $where);
					
		// Phew. Done. Now run it.			
		$sql = $db->sql_build_query('SELECT', $query);

		$wpuDebug->add('Performing cross-post query: ' . htmlentities(str_replace(array("\n", "\t"), '', $sql . ' LIMIT ' . $this->limit . ' OFFSET ' . $this->offset . ' [real limit: (' . $this->realLimit . ', ' . $this->realOffset . ')]')));
		
		if(!($result = $db->sql_query_limit($sql, $this->limit, $this->offset))) {
			$db->sql_freeresult($result);
			$phpbbForum->restore_state($fStateChanged);
			return false;
		}

		if($this->count) {
			if($this->groupByStatus) {
				// start with inited object
				$stats = $this->result['count-grouped'];
				while ($stat = $db->sql_fetchrow($result)) {
					if($stat['post_approved'] == 0) {
						$stats->moderated = $stat['num_total'];
					} else {
						$stats->approved = $stat['num_total'];
					}
					$stats->total_comments = $stats->total_comments + $stat['num_total'];
					
				}
				$this->result['count-phpbb'] = $stats->total_comments;
				$db->sql_freeresult($result);

				// Now we fetch the native WP count
				$phpbbForum->background();
				$wpCount = wp_count_comments($this->postID);
				if(is_object($wpCount)) {
					$stats->{'post-trashed'}	= $wpCount->{'post-trashed'};
					$stats->trash 			= $wpCount->trash;
					$stats->spam 			= $wpCount->spam;
					$stats->moderated 		= $stats->moderated			+ $wpCount->moderated;
					$stats->approved	 	= $stats->approved 			+ $wpCount->approved;
					$stats->total_comments	= $stats->total_comments 	+ $wpCount->total_comments;
				}
				$this->result['count-grouped'] = $stats;
				$this->result['count'] = $this->result['count-grouped']->total_comments;
				$this->result['count-wp'] = $wpCount->total_comments;
				
			} else {
				$countRow = $db->sql_fetchrow($result);
				$count = $countRow['num_total'];
				$this->result['count'] = (int)$count + (int)$this->passedResult;
				$this->result['count-phpbb'] = (int)$count;
				$this->result['count-wp'] = (int)$count = (int)$this->passedResult;
				$db->sql_freeresult($result);
			}
			
			
			$phpbbForum->restore_state($fStateChanged);
			return true;
		}
		
		$currentCommenter = wp_get_current_commenter();
		$pUsername = $phpbbForum->get_username();
		
		// Now fill the comments and links arrays
		while ($row = $db->sql_fetchrow($result)) {
		
			$row['bbcode_options'] = (($row['enable_bbcode']) ? OPTION_FLAG_BBCODE : 0) +
				(($row['enable_smilies']) ? OPTION_FLAG_SMILIES : 0) + 
				(($row['enable_magic_url']) ? OPTION_FLAG_LINKS : 0);
			
			// TODO: THIS IS DISABLED, AS COUNTS/LIMITS WERE UNRELIABLE!!!
			if($row['topic_first_post_id'] == $row['post_id']) {
				// this is a cross-post, not a comment.
				
				$forumName = $row['forum_id'];
				if($row['topic_type'] == POST_GLOBAL) {
					$forumName = $phpbbForum->lang['VIEW_TOPIC_GLOBAL'];
				}
				
				$args = array(
					'topic_id'		=> $row['topic_id'],
					'post_id'		=> $row['post_id'],
					'subject'		=> $row['post_subject'],
					'forum_id'		=> $row['forum_id'],
					'user_id'		=> $row['poster_id'],
					'replies'		=> $row['replies'],
					'time'			=> $user->format_date($row['post_time'], "Y-m-d H:i:s"), 
					'approved'		=> $row['topic_approved'],
					'type'			=> $row['topic_type'],
					'status'		=> $row['topic_status'],
					'forum_name'	=> $forumName
				);
			
				$this->result['xposts'][] = (object) $args;
				
				$this->result['has_xposts'] = true;
				
			} else {
				
				if(($row['user_id'] == ANONYMOUS) && (!empty($row['post_username']))) {
					$username = $row['post_username'];
				} else {
					$username = $row['username'];
				}
				if(($row['user_id'] == ANONYMOUS) && (!empty($row['post_wpu_xpost_meta1']))) {
					$website = $row['post_wpu_xpost_meta1'];
				} else {
					$website = $phpbbForum->get_board_url() . "memberlist.$phpEx?mode=viewprofile&amp;u=" . $row['poster_id'];
				}
				if(($row['user_id'] == ANONYMOUS) && (!empty($row['post_wpu_xpost_meta2']))) {
					$email = $row['post_wpu_xpost_meta2'];
				} else {
					$email = $row['user_email'];
				}	
				
				if(!$row['post_approved'] && $this->hideOtherUnapproved) {
					if(($row['user_id'] == ANONYMOUS) && (empty($currentCommenter['comment_author']) || ($currentCommenter['comment_author'] != $username))) {
						continue;
					} else if(($row['user_id'] != ANONYMOUS) && (empty($pUsername) || ($pUsername != $username))) {
						continue;
					}
				}			
				
				$parentPost = (empty($this->postID)) ? $row['topic_wpu_xpost'] : $this->postID;
				$commentID = $this->idOffset + $row['post_id'];
				
				$args = array(
					'comment_ID' => $commentID,
					'comment_post_ID' => $parentPost,
					'comment_author' => $username,
					'comment_author_email' => $email,
					'comment_author_url' => $website,
					'comment_author_IP' => $row['poster_ip'],
					'comment_date' => $user->format_date($row['post_time'], "Y-m-d H:i:s"), //Convert phpBB timestamp to mySQL datestamp
					'comment_date_gmt' =>  $user->format_date($row['post_time'] - ($user->timezone + $user->dst), "Y-m-d H:i:s"), 
					'comment_content' => generate_text_for_display($row['post_text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']),
					'comment_karma' => 0,
					'comment_approved' => $row['post_approved'],
					'comment_agent' => 'phpBB forum',
					'comment_type' => '',
					'comment_parent' => (int)$row['post_wpu_xpost_parent'],
					'user_id' => $row['user_wpuint_id'],
					'phpbb_id' => $row['poster_id'],
				);


				// Fix relative paths in comment text
				$pathsToFix = array('src="' . $phpbb_root_path, 'href="' . $phpbb_root_path);
				$pathsFixed = array('src="' . $phpbbForum->get_board_url(), 'href="' . $phpbbForum->get_board_url());
				$args['comment_content'] = str_replace($pathsToFix, $pathsFixed, $args['comment_content']);

				$this->result['comments'][] = (object) $args;
				$this->result['has-xposted-comments'] = true;
				
				// calculate counts anyway, even though this wasn't an explicit count request.
				if($row['post_approved'] == 0) {
					$this->result['count-grouped']->moderated++;
				} else {
					$this->result['count-grouped']->approved++;
				}
				$this->result['count-grouped']->total_comments++;
				
				
				
				
				// prepare links
				//don't use numerical keys to avoid renumbering on array_merge
				$cID = 'comment' . $commentID;
				$r = $phpbbForum->get_board_url();
				
				foreach(array('view', 'edit', 'delete', 'approve') as $linkType) {
					$this->links[$linkType][$cID] = false;
				}
								
				$this->links['view'][$cID] = $r . (($phpbbForum->seo) ? "post{$row['post_id']}.html#p{$row['post_id']}" : "viewtopic.{$phpEx}?f={$row['forum_id']}&amp;t={$row['topic_id']}&amp;p={$row['post_id']}#p{$row['post_id']}");
				if( 
					((in_array($row['forum_id'], $permissions['edit_own'])) && ($row['poster_id'] == $phpbbID)) ||
					(in_array($row['forum_id'], $permissions['edit_forum']))
				) {
					$this->links['edit'][$cID] = $r. $phpbbForum->append_sid("posting.{$phpEx}?mode=edit&amp;f={$row['forum_id']}&amp;p={$row['post_id']}#start_here");
				}
				if( 
					((in_array($row['forum_id'], $permissions['delete_own'])) && ($row['poster_id'] == $phpbbID)) ||
					(in_array($row['forum_id'], $permissions['delete_forum']))
				) {
					$this->links['delete'][$cID] = $r . $phpbbForum->append_sid("posting.{$phpEx}?mode=delete&amp;f={$row['forum_id']}&amp;p={$row['post_id']}");
				}
				if(in_array($row['forum_id'], $permissions['approve_forum'])) {	
					$this->links['approve'][$cID] = $r . $phpbbForum->append_sid("mcp.{$phpEx}?i=queue&amp;mode=approve_details&amp;f={$row['forum_id']}&amp;p={$row['post_id']}#start_here");
				}
			
			
			}
			
			$this->result['count'] = $this->result['count-grouped']->total_comments;
		}
		
		$db->sql_freeresult($result);
		
		$phpbbForum->restore_state($fStateChanged);

		return true;
	
	}
	

	/**
	* Sorts the comments, even when there are mixed WordPress and phpBB comments.
	* @return void
	*/
	private function sort() {
		usort($this->result['comments'], array($this, 'comment_sort_callback'));
	}
	
	// internal callback for the sort function.
	private function comment_sort_callback($a, $b){
		
		$criteriaCounter = 0;
		$criterion = $this->finalOrderBy[$criteriaCounter];

		if(!empty($criterion)) {
			while(($a->$criterion == $b->$criterion) && ($criteriaCounter < (sizeof($this->finalOrderBy) - 1)) ) {
				$criteriaCounter++;
				$criterion = $this->finalOrderBy[$criteriaCounter];
			}
			$result = strcmp((string)$a->$criterion, (string)$b->$criterion);

			if($this->order == 'ASC') {
				return ($result == 0) ? 0 : (($result > 0) ? 1 : -1);
			} else {
				return ($result == 0) ? 0 : (($result < 0) ? 1 : -1);
			}
		}

		return 0;
	}
	

}

// End the comments file with a comment: Done.