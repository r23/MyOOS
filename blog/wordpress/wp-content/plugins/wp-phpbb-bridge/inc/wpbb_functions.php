<?php

/**
 * Calculate the diference between today and a past date
 */
function dateDiff($time1, $time2, $precision = 6)
{
    if(!is_int($time1))
    {
        $time1 = strtotime($time1);
    }
    
    if(!is_int($time2))
    {
        $time2 = strtotime($time2);
    }
    
    if($time1 > $time2)
    {
        $ttime = $time1;
        $time1 = $time2;
        $time2 = $ttime;
    }
    
    $intervals = array('year','month','day','hour','minute','second');
    $diffs = array();
    
    foreach($intervals as $interval)
    {
        $diffs[$interval] = 0;
        $ttime = strtotime("+1 " . $interval, $time1);
        
        while($time2 >= $ttime)
        {
            $time1 = $ttime;
            $diffs[$interval]++;
            $ttime = strtotime("+1 " . $interval, $time1);
        }
    }

    return $diffs;
}

/**
 * Generating random hash codes
 */
function hash_generator()
{
    $s = sha1(time() . rand(0, 100000) . '$FA$F$#RFTGBRFWE' . md5(date('s', time())) . rand(5, 15000));
    apply_filters('wpbb_hash_generation', $s);
    return substr($s, 8, 7);
}

/**
 * Check if the Web Page exists
 */
function page_exists($url)
{
    $file_headers = @get_headers($url);
    
    if($file_headers[0] == 'HTTP/1.1 404 Not Found')
    {
        return false;
    }
    else
    {
        return true;
    }
}

/**
 * Return the administration link for phpBB
 */
function wpbb_get_admin_link()
{
	global $auth, $user, $phpbb_root_path, $phpEx;

	if(is_object($auth) && method_exists($auth, 'acl_get') && $auth->acl_get('a_') && $user->data['is_registered'])
	{
		$forum_url = str_replace("/ucp.php", "/", trim(get_option('wpbb_ucp_path')));
		return $forum_url . 'adm/index.php?sid=' . $user->session_id;
	}
	else
	{
		return '';
	}
}

/**
 * Return the user avatar for WordPress from phpBB
 */
function wpbb_get_avatar($ua = "", $tp = null)
{
	global $config, $user;
    
    do_action('wpbb_before_get_avatar');
    
    if(wpbb_is_active())
	{
        $forum_url = str_replace("/ucp.php", "/", trim(get_option('wpbb_ucp_path')));
        
        if($ua == "" && $tp == null)
        {
    		switch($user->data['user_avatar_type'])
    		{
    			case 1:
    				return $forum_url . 'download/file.php?avatar=' . $user->data['user_avatar'];
    				break;
    			case 2:
    				return $user->data['user_avatar'];
    				break;
    			case 3:
    				return $forum_url . $config['avatar_gallery_path'] . '/' . $user->data['user_avatar'];
    				break;
    			default:
    				return '';
    				break;
    		}
        }
        else
        {
    		switch($tp)
    		{
    			case 1:
    				return $forum_url . 'download/file.php?avatar=' . $ua;
    				break;
    			case 2:
    				return $ua;
    				break;
    			case 3:
    				return $forum_url . $config['avatar_gallery_path'] . '/' . $ua;
    				break;
    			default:
    				return '';
    				break;
    		}
        }
	}

    do_action('wpbb_after_get_avatar');

	return '';
}

/**
 * Getting the avatar from phpBB for WordPress internal use
 */
function get_forum_avatar($avatar, $comment, $size)
{   
    global $user;

    if($comment->comment_author_email == '' || $comment->user_id == 0 || is_admin())
    {
        return $avatar;
    }
    else
    {
        $config_php = trim(get_option('wpbb_config_path'));
        
        require($config_php);
                
        $cn = mysql_pconnect($dbhost . ":" . $dbport, $dbuser, $dbpasswd);
        @mysql_set_charset(get_option('wpbb_dbms_charset', 'utf8'), $cn);
                
        if($cn)
        {
            if(@mysql_select_db($dbname))
            {
                $qr = "SELECT u.`user_avatar`, u.`user_avatar_type` FROM `" . $table_prefix  . "users` u WHERE u.`user_email` = '" . $comment->comment_author_email . "'";
                $rs = @mysql_query($qr, $cn);
            }
            
            while($i = mysql_fetch_assoc($rs))
            {
                $avatar = '<img 
                    alt="" 
                    src="' . wpbb_get_avatar($i['user_avatar'], $i['user_avatar_type']) . '" 
                    class="avatar avatar-' . $size . ' photo" 
                    height="' . $size . '" 
                    width="' . $size . '"
                />';
            }
        }
    }
    
    return $avatar;
}

if(trim(get_option('wpbb_avatars', 'no')) == 'yes')
{
    add_filter('get_avatar', 'get_forum_avatar', 1, 3);
}

/**
 * Generate Moderator Control Panel Link
 */
function wpbb_get_mcp_link()
{
	global $auth, $user, $phpbb_root_path, $phpEx;

	if(is_object($auth) && method_exists($auth, 'acl_get') && $auth->acl_get('m_') && $user->data['is_registered'] && page_exists(trim(get_option('wpbb_ucp_path'))))
	{
		$forum_url = str_replace("/ucp.php", "/", trim(get_option('wpbb_ucp_path')));
		return $forum_url . 'mcp.php?i=main&amp;mode=front&amp;sid=' . $user->session_id;
	}
	else
	{
		return '';
	}
}

/**
 * Generate restore permissions link
 */
function wpbb_get_restore_permissions_link()
{
	global $user, $auth;

	if($user->data['user_perm_from'] && is_object($auth) && method_exists($auth, 'acl_get') && $auth->acl_get('a_switchperm') && page_exists(trim(get_option('wpbb_ucp_path'))))
	{
		return trim(get_option('wpbb_ucp_path')) . '?mode=restore_perm&amp;sid=' . $user->session_id;
	}
	else
	{
		return '';
	}
}

/**
 * Reterrning the session id
 */
function wpbb_get_sessionid()
{
	global $user;
	return $user->session_id;
}

/**
 * Return current time
 */
function wpbb_get_time_current()
{
	global $user;

	if(wpbb_is_active())
	{
		return sprintf($user->lang['CURRENT_TIME'], $user->format_date(time(), false, true));
	}

	return '';
}

/**
 * Return the last visited time
 */
function wpbb_get_time_last_visit()
{
	global $user;

	if (wpbb_is_active() && wpbb_is_user_logged_in())
	{
		return sprintf($user->lang['YOU_LAST_VISIT'], $user->format_date($user->data['session_last_visit']));
	}

	return '';
}

function is_200($url = "")
{
    if($url == "")
    {
        return false;
    }
    
    $options['http'] = array(
        'method' => "HEAD",
        'ignore_errors' => 1,
        'max_redirects' => 0
    );
    
    $body = file_get_contents($url, NULL, stream_context_create($options));
    sscanf($http_response_header[0], 'HTTP/%*d.%*d %d', $code);
    
    return $code === 200;
}

/**
 * Check if plugin is active
 */
function wpbb_is_active()
{
	if(strtolower(trim(get_option('wpbb_activate', 'no'))) == 'yes')
	{
		$config_php = trim(get_option('wpbb_config_path'));
        $ucp_php = trim(get_option('wpbb_ucp_path'));
        
        $file_exists = is_file($config_php);
        $url_exists = page_exists($ucp_php);
        
        $files_exists = $file_exists && $url_exists;

		if(!$files_exists)
		{
			update_option('wpbb_activate', 'no');
			return false;
		}

		return true;
	}
    
	return false;
}

/**
 * Check if the user is logged in 
 */
function wpbb_is_user_logged_in()
{
	global $user;

	if(wpbb_is_active() && $user->data['user_id'] == ANONYMOUS)
	{
		return false;
	}

	return is_user_logged_in();
}

function aasort(&$array, $key, $r = false)
{
    $sorter = array();
    $ret = array();
    reset($array);
    
    foreach($array as $ii => $va)
    {
        $sorter[$ii] = $va[$key];
    }
    
    $r == true ? arsort($sorter) : asort($sorter);  
    
    foreach($sorter as $ii => $va)
    {
        $ret[$ii] = $array[$ii];
    }
    
    $array=$ret;
}

function print_forum($forums = null)
{
    global $wpbb_categories;
    global $forums_categories;
    $forums_categories = (array)$forums_categories;
    
    static $b = 0;
    ++$b;
    
    static $times = 1;
    static $current_parent = 0;
    $current_parent = $forums['PARENT'];
    
    ?>
        <tr id="forum_<?php echo $forums['ID']; ?>" class="<?php echo $b % 2 == 0 ? "alternate " : "" ?>format-default" valign="top">
            <th scope="row" class="<?php echo $forums['TYPE'] == 0 ? "wpbb_category" : "wpbb_forum" ?>">
                <?php 
                    echo $forums['TYPE'] == 0 ? "" : '<div class="wpbb_display_categories wpbb_display_open"></div>'; 
                    echo ($forums['TYPE'] == 0 ? '' : str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $times)) . $forums['NAME'];
                    
                    if($forums['TYPE'] == 1)
                    {
                    ?>
                    <div class="wpbb_categories">
                        <br />
                        <br />
                        <?php
                            $val = "";
                            
                            foreach($forums_categories as $fc)
                            {
                                if($fc['forum'] == $forums['ID'])
                                {
                                    $c_counter = 0;
                                    
                                    foreach($fc['categories'] as $c)
                                    {
                                        ++$c_counter;
                                        
                                        if($c_counter == 1)
                                        {
                                            $val = $c;
                                        }
                                        else
                                        {
                                            $val .= ", " . $c;
                                        }
                                    }
                                }
                            }
                        ?>
                        <input type="hidden" name="forum_categories" value="<?php echo $val; ?>" />
                        <input type="hidden" name="forum_id" value="<?php echo $forums['ID']; ?>" />
                        <?php
                            global $wpbb_w;
                            print_array($wpbb_w, $forums['ID']);
                        ?>
                        <br />
                    </div>
                    <?php
                    }
                    
                    foreach($forums as &$forum)
                    {
                        if(is_array($forum))
                        {
                            if($current_parent == 0)
                            {
                                $times = 1;
                            }
                            elseif($current_parent < $forum['PARENT'])
                            {
                                $times++;
                            }
                            elseif($current_parent > $forum['PARENT'])
                            {
                                $times--;
                            }
                                            
                            print_forum($forum);
                        }
                    }
                ?>
            </th>
        </tr>
    <?php
}

function print_array($array, $forum_id, $level = 0)
{
    if(!is_array($array)) return;
    
    $printed = false;

    foreach($array as $key => $value)
    {
        if(is_array($value))
        {
            print_array($value, $forum_id, $level);
    	}
        else
        {
            if($printed){continue;}
            
            global $forums_categories;
            
            foreach($forums_categories as $fc)
            {
                if($fc['forum'] == $forum_id)
                {
                    if(in_array($array['term_id'], $fc['categories']))
                    {
                        $ch = 'checked="checked"';
                    }
                }
            }
            
            $printed = true;
            $level++;
    	    echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
    ?>
        <label>
            <input type="checkbox" value="<?php echo $array['term_id']; ?>" <?php echo $ch; ?> /> <?php echo $array['name']; ?>
        </label>
        <br />
    <?php
        }
    }
}

function print_category_tree($c, $forum_id)
{
    global $forums_categories;
    
    static $times = 0;
    static $last_id = 0;
    
    foreach($c as $cat)
    {
        ?>
        <label>
            <input type="checkbox" value="<?php echo $cat['term_id']; ?>" <?php echo $ch; ?> /> <?php echo $cat['name']; ?>
        </label>
        <br />
        <?php
        
        foreach($cat as $categ)
        {
            if(is_array($categ))
            {
                $f = fopen(ABSPATH . '/log.txt');
                print_category_tree($categ, $forum_id);
            }
        }
    }
}

/**
 * Adding new post into Forum
 */
function add_post($id, $post)
{
    global $user, $phpbb_root_path, $phpEx, $wpdb;
    
    $current_user = wp_get_current_user();              // Get current user info
    
    // If the user cannot create posts on forum then return.
    // This info is comming from WordPress administration panel
    if(!in_array($current_user->data->ID, (array)unserialize(get_option('wpbb_users_posting'))) && $post->post_status == "publish")
    {
        return;
    }
    
    // Check if that status of the current posts is publish
    if($post->post_status == "publish")
    {
        // Get the categories are assigned to that post
        $post_categories = wp_get_post_categories($id);
        // Get the full list of allowed forums to be posted
        $forum_categories = unserialize(get_option('wpbb_forums_categories'));
        // Array that will hold the allowed forums IDs that will be created new post
        $add_to_forum = array();
        
        // Loop through the forums
        foreach($forum_categories as $set)
        {
            // Loop through the categories
            foreach($post_categories as $category)
            {
                // If category is into forums lists
                if(in_array($category, $set['categories']))
                {
                    // Check if the category already exists into the $add_to_forum
                    if(!in_array($set['forum'], $add_to_forum))
                    {
                        // Add the forum id into $add_to_forum
                        $add_to_forum[] = $set['forum'];
                    }
                }
            }
        }
        
        // Create definition in order that is not already defined
        if(!defined('IN_PHPBB'))
        {
            // Creating the definition
            define('IN_PHPBB', true);
        }
        
        // Import the utf tools from phpBB
        require($phpbb_root_path . 'includes/utf/utf_tools.php');
        
        // Check if seo version exists, if phpbb_seo class not already exists or if not $phpbb_seo is set
        if(file_exists($phpbb_root_path . 'phpbb_seo/phpbb_seo_class.php') && class_exists('phpbb_seo') == false && isset($phpbb_seo) == false)
        {
            global $phpbb_seo;                                                  // Create global variable $phpbb_set
            require($phpbb_root_path . 'phpbb_seo/phpbb_seo_class.php');        // Load phpbb seo version class
            $phpbb_seo = new phpbb_seo();                                       // Initiate a new phpbb_seo() object
        }
        
        // Import functions_posting.php
        require($phpbb_root_path . 'includes/functions_posting.php');
        
        $post_content = $post->post_content;                                    // Getting post content
        $post_title = $post->post_title;                                        // Getting post title
        $uid = $bitfield = $options = '';                                       // Set post options
        
        wp_generate_text_for_storage($post_content, $uid, $bitfield, $options, true, true, true);       // Process post content
        wp_generate_text_for_storage($post_title, $uid, $bitfield, $options, true, true, true);         // Process post title
        
        $poll = null;               // There is no poll
        
        $p = get_post($id);
        $current_title = $p->post_title;
        $current_hash = md5($p->post_content);
        unset($p);
        
        $post_exists_sql = "SELECT
          `p`.`topic_id` AS `TOPIC`,
          `p`.`forum_id` AS `FORUM`
        FROM
          `" . POSTS_TABLE . "` `p`
        WHERE
          `p`.`post_subject` = '" . $current_title . "'
        OR
          `p`.`post_checksum` = '" . $current_hash . "'";
          
        $post_exists = $wpdb->get_results($post_exists_sql);
                        
        // Loop through the allowed forums
        foreach($add_to_forum as $forum_id)
        {
            $topicId = 0;
            
            foreach($post_exists as $post_e)
            {
                if($forum_id == $post_e->FORUM)
                {
                    continue 2;
                    $topicId = $post_e->TOPIC;
                }
            }
            
            $data = array(
                'forum_id' => $forum_id,                    // Forum ID
                'topic_id' => $topicId,                     // 0 Create new post, seted ID updates the existing topic
                'icon_id' => false,                         // Set no icon
                'enable_bbcode' => true,                    // Allow bbCode
                'enable_smilies' => true,                   // Allow smilies
                'enable_urls' => true,                      // Allow urls
                'enable_sig' => true,                       // Allow SIG
                'message' => $post_content,                 // Set the post message
                'message_md5' => md5($post_content),        // Set the post hash
                'bbcode_bitfield' => $bitfield,             // Set the bitfield
                'bbcode_uid' => $uid,                       // Set the uid
                'post_edit_locked' => 0,                    // Set the post to unlocked
                'topic_title' => $post_title,               // Set the post title
                'notify_set' => false,                      // Set notify set to false
                'notify' => false,                          // Set notify to false
                'post_time' => 0,                           // Set post time to 0
                'forum_name' => '',                         // Set the name of forum
                'enable_indexing' => true,                  // Set indexing to true
                'force_approved_state' => true              // Set the posts as approved
            );
            
            // Submit the data here
            submit_post(
                'post',
                $post_title,
                $user->data['username'],
                POST_NORMAL,
                $poll,
                $data
            );
        }
    }
}

function wp_generate_text_for_storage(&$text, &$uid, &$bitfield, &$flags, $allow_bbcode = false, $allow_urls = false, $allow_smilies = false)
{
    global $phpbb_root_path, $phpEx;

	$uid = $bitfield = '';
	$flags = (($allow_bbcode) ? OPTION_FLAG_BBCODE : 0) + (($allow_smilies) ? OPTION_FLAG_SMILIES : 0) + (($allow_urls) ? OPTION_FLAG_LINKS : 0);

	if (!$text)
	{
		return;
	}

	if(!class_exists('parse_message'))
	{
		include($phpbb_root_path . 'includes/message_parser.' . $phpEx);
	}

	$message_parser = new parse_message($text);
	$message_parser->parse($allow_bbcode, $allow_urls, $allow_smilies);

	$text = $message_parser->message;
	$uid = $message_parser->bbcode_uid;

	// If the bbcode_bitfield is empty, there is no need for the uid to be stored.
	if (!$message_parser->bbcode_bitfield)
	{
		$uid = '';
	}

	$bitfield = $message_parser->bbcode_bitfield;

	return;
}

if(!function_exists('truncate_string'))
{
    function truncate_string($string, $max_length = 60, $max_store_length = 255, $allow_reply = false, $append = '')
    {
    	$chars = array();
    
    	$strip_reply = false;
    	$stripped = false;
    	if ($allow_reply && strpos($string, 'Re: ') === 0)
    	{
    		$strip_reply = true;
    		$string = substr($string, 4);
    	}
    
    	$_chars = utf8_str_split(htmlspecialchars_decode($string));
    	$chars = array_map('utf8_htmlspecialchars', $_chars);
    
    	// Now check the length ;)
    	if (sizeof($chars) > $max_length)
    	{
    		// Cut off the last elements from the array
    		$string = implode('', array_slice($chars, 0, $max_length - utf8_strlen($append)));
    		$stripped = true;
    	}
    
    	// Due to specialchars, we may not be able to store the string...
    	if (utf8_strlen($string) > $max_store_length)
    	{
    		// let's split again, we do not want half-baked strings where entities are split
    		$_chars = utf8_str_split(htmlspecialchars_decode($string));
    		$chars = array_map('utf8_htmlspecialchars', $_chars);
    
    		do
    		{
    			array_pop($chars);
    			$string = implode('', $chars);
    		}
    		while (!empty($chars) && utf8_strlen($string) > $max_store_length);
    	}
    
    	if ($strip_reply)
    	{
    		$string = 'Re: ' . $string;
    	}
    
    	if ($append != '' && $stripped)
    	{
    		$string = $string . $append;
    	}
    
    	return $string;
    }
}

if(!function_exists('censor_text'))
{
    function censor_text($text)
    {
    	static $censors;
    
    	// Nothing to do?
    	if ($text === '')
    	{
    		return '';
    	}
    
    	// We moved the word censor checks in here because we call this function quite often - and then only need to do the check once
    	if (!isset($censors) || !is_array($censors))
    	{
    		global $config, $user, $auth, $cache;
    
    		// We check here if the user is having viewing censors disabled (and also allowed to do so).
    		if (!$user->optionget('viewcensors') && $config['allow_nocensors'] && $auth->acl_get('u_chgcensors'))
    		{
    			$censors = array();
    		}
    		else
    		{
    			$censors = $cache->obtain_word_list();
    		}
    	}
    
    	if (sizeof($censors))
    	{
    		return preg_replace($censors['match'], $censors['replace'], $text);
    	}
    
    	return $text;
    }
}

?>