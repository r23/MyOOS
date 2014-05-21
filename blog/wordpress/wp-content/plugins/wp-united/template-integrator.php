<?php
/** 
*
* WP-United Main Integration  -- template portion
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
* 
* Here we modify and integrate templates as necessary. 
* 
*/

function wpu_integrate_templates() {
	global $wpuNoHead, $wpUnited, $wpUnited, $wpuSetWPSignature;

	$wpuCache = WPU_Cache::getInstance();

	$wpuSetWPSignature = '';
	
	$bodyClass = '';
	$bodyDetails = '';
		

	if ( $wpuCache->use_template_cache() || $wpUnited->ran_patched_wordpress() ) {
		wpu_get_wordpress();
	}


	/**
	 *  Just output WordPress if $wpuNoHead or if an ajax call, etc.
	 */
	 $content = $wpUnited->get_wp_content();
	 if(!stristr($content, '<body')) {
		$wpuNoHead = true;
	}

	if($wpuNoHead) {
		wpu_output_page($content);
	}

	if ( !$wpUnited->should_do_action('template-p-in-w') && ($wpUnited->get_setting('showHdrFtr') != 'FWD') ) {
		return;
	}

	/**
	 * Get phpBB header/footer
	 */
	if ($wpUnited->should_do_action('template-w-in-p') && !$wpuNoHead) {
		
		wpu_process_phpbb();
	}


	wpu_fix_wp_template();

	$wpUnited->add_boilerplate();


	/** 
	 * Make modifications to inner content, and extract items for interleaving into outer content <head>
	 */
	if ( $wpUnited->should_do_action('template-p-in-w') || ($wpUnited->get_setting('showHdrFtr') == 'FWD') )  { // phpBB is inner:


		

		//Get ltr, rtl & bgcolor, etc, from the body tag
		preg_match('/<body[^>]+>/i', $wpUnited->get_inner_content(), $pfBodyMatches);

		if(isset($pfBodyMatches[0])) {
			$bodyDetails = trim(str_replace(array('<body', '>'), '', $pfBodyMatches[0]));
			preg_match('/class\s*=\s*"[^"]+"/', $bodyDetails, $bodyClass);
			if(isset($bodyClass[0])) {
				$bodyDetails = str_replace($bodyClass[0], "", $bodyDetails);
				$bodyClass = trim(str_replace(array('class', '=', '"'), '', $bodyClass[0]));
			}
		}
		
		// process_remove_head removes the <head> during the process, leaving us with an insertable body (hehe).
		$wpUnited->set_inner_headinfo(process_remove_head($wpUnited->get_inner_content()));
		
		$innerContent = $wpUnited->get_inner_content();
		
		// get any signature added by WordPress after /html, e.g. WP_CUSTOMIZER_SIGNATURE  (... ffs)
		if($wpUnited->should_do_action('template-w-in-p')) {
			preg_match('/<\/html>(.*)/i', $innerContent, $sigs);
			if(is_array($sigs) && isset($sigs[1])) {
				$wpuSetWPSignature = $sigs[1];
				$innerContent = str_replace('</html>' . $sigs[1], '</html>', $innerContent);
			}
		}

		
		$wpUnited->set_inner_content(process_body($innerContent));
	} 

	if ($wpUnited->should_do_action('template-p-in-w')) { 
		
		
		//  Now we modify parts of the outer head -- changing the <html> tag and the title
		
		$outerContent = $wpUnited->get_outer_content();
		
		// First look for lang and direction attributes
		preg_match('/<html[^>]+>/i', $outerContent, $outerHtmlTag);
		if(isset($outerHrmlTag[0]) && $outerHtmlTag[0]) {
				$repl = '';
				global $user;
				if(stristr($outerHtmlTag[0], 'lang=') === false) {
					$repl = 'lang="' . $user->lang['USER_LANG'] . '" ';
				}
				if(stristr($outerHtmlTag[0], 'dir=') === false) {
					$repl = 'dir="' . $user->lang['DIRECTION'] . '" ';
				}
				// This only works on PHP 5:
				$outerContent = str_ireplace('<html', '<html ' . $repl, $outerContent);
				
		} 
				
		// Now we replace the outer title with phpBB title
		$wpUnited->set_outer_content(preg_replace('/<title>[^<]*<\/title>/i', '<title><!--[**PAGE_TITLE**]--></title>', $outerContent));
	}


	// So, we generate the phpBB outer page if required, then we're all set.



	if ($wpUnited->get_setting('cssMagic')) { 

		require($wpUnited->get_plugin_path() . 'css-magic.php');
		require($wpUnited->get_plugin_path() . 'functions-css-magic.php');
		
		wpu_css_magic();
	}



	//Wrap inner content in CSS Magic, padding, etc.
	$padding = '';
	if ($wpUnited->should_do_action('template-p-in-w') && ($wpUnited->get_setting('phpbbPadding') != 'NOT_SET')) {
		$pad = explode('-', $wpUnited->get_setting('phpbbPadding'));
		$padding = 'padding: ' . (int)$pad[0] . 'px ' .(int)$pad[1] . 'px ' .(int)$pad[2] . 'px ' .(int)$pad[3] . 'px;';
	}
	if ($wpUnited->get_setting('cssMagic')) {
		$wpuOutputPreStr = '<div id="wpucssmagic" style="' . $padding . 'margin: 0;"><div class="wpucssmagic"><div class="' . $bodyClass . '" ' . $bodyDetails . '>';
		$wpuOutputPostStr = '</div></div></div>';
	} else {
		$wpuOutputPreStr = '<div style="'. $padding .' margin: 0px;" class="' . $bodyClass . '" ' . $bodyDetails . '>';
		$wpuOutputPostStr = '</div>';
	}

	// If the WP theme didn't set the head marker, do it now
	if (!DISABLE_PHPBB_CSS) {
		$headMarker = '<!--[**HEAD_MARKER**]-->';
		if( PHPBB_CSS_FIRST) {
			$wpUnited->set_outer_content(str_replace('</title>', '</title>' . "\n\n" . $headMarker . "\n\n", $wpUnited->get_outer_content()));
		} else if(strstr($wpUnited->get_outer_content(), $headMarker) === false)  {
			$headMarker = '</head>';
			$wpUnited->set_inner_headinfo($wpUnited->get_inner_headinfo() . "\n\n</head>");	
		}
		$wpUnited->set_outer_content(str_replace($headMarker, $wpUnited->get_inner_headinfo(), $wpUnited->get_outer_content())); 
	}


	$wpUnited->set_outer_content(str_replace('<!--[**INNER_CONTENT**]-->', $wpuOutputPreStr . $wpUnited->get_inner_content() . $wpuOutputPostStr, $wpUnited->get_outer_content())); 
	
	$wpUnited->clear_inner_content();
	
	
	wpu_output_page($wpUnited->get_outer_content()); 
	$wpUnited->clear_outer_content();
	
	
	
}
	
	



function wpu_process_phpbb() {
	global $wpUnited, $template, $user, $phpbbForum, $cache, $db, $phpbb_root_path;
	
	//export header styles to template - before or after phpBB's CSS depending on settings.
	// Since we might want to do operations on the head info, 
	//we just insert a marker, which we will substitute out later
	$wpStyleLoc = ( PHPBB_CSS_FIRST ) ? 'WP_HEADERINFO_LATE' : 'WP_HEADERINFO_EARLY';
	
		//set the DTD marker if we're doing DTD switching
		if ( $wpUnited->get_setting('dtdSwitch') ) {
			$template->assign_var('WP_DTD', '<!--[**WP_DTD**]-->'); 
		}

	
	
	$template->assign_vars(array(
		$wpStyleLoc => '<!--[**HEAD_MARKER**]-->',
		'S_SHOW_HDR_FTR' => TRUE,
		// We need to set the base HREF correctly, so that images and links in the phpBB header and footer work properly
		'PHPBB_BASE' =>  $phpbbForum->get_board_url()
	));
	
	
	// If the user wants CSS magic, we will need to inspect the phpBB Head, so we buffer the output 
	ob_start();
	page_header('<!--[**PAGE_TITLE**]-->');
	
	
	$template->assign_vars(array(
		'WORDPRESS_BODY' => '<!--[**INNER_CONTENT**]-->',
		'WPU_CREDIT' => sprintf($user->lang['WPU_Credit'], '<a href="http://www.wp-united.com">', '</a>')
	)); 
	
	//Stop phpBB from exiting
	define('PHPBB_EXIT_DISABLED', true);

	$template->set_filenames(array( 'body' => 'blog.html') ); 
	page_footer();
	
	//restore the DB connection that phpBB tried to close
	global $bckDB, $bckCache;
	if(isset($bckDB) && isset($bckCache)) {
		$db = $bckDB;
		$cache = $bckCache;
	}

	
	$wpUnited->set_outer_content(ob_get_contents());
	ob_end_clean();
	
		
	//kill absolute paths that should be URIs
	$wpUnited->set_outer_content(str_replace($phpbb_root_path, $phpbbForum->get_board_url(), $wpUnited->get_outer_content()));
	
	
}




function wpu_fix_wp_template() {
	global $wpUnited, $phpEx;
	
	//wpu_modify_loginout_links();
	
	
	// Some trailing slashes are hard-coded into the WP templates. We don't want 'em.
	$wpUnited->set_wp_content(str_replace(".php/?",  ".$phpEx?", $wpUnited->get_wp_content()));
	$wpUnited->set_wp_content(str_replace(".$phpEx/\"",  ".$phpEx\"", $wpUnited->get_wp_content()));


}


/**
 * This is deprecated, we no longer use it
 */
function wpu_modify_loginout_links() {
	global $wpUnited, $phpEx, $phpbbForum;
	
	// re-point unintegrated login/out links
	if ( $wpUnited->get_setting('integrateLogin') ) {
	
		$login_link = append_sid('ucp.'.$phpEx.'?mode=login') . '&amp;redirect=';
		$logout_link = append_sid('ucp.'.$phpEx.'?mode=logout') . '&amp;redirect=';
		
		$wpUnited->set_wp_content(str_replace("{$wpUnited->get_wp_base_url()}/wp-login.php?redirect_to=", $phpbbForum->get_board_url() . $login_link, $wpUnited->get_wp_content()));
		$wpUnited->set_wp_content(str_replace("{$wpUnited->get_wp_base_url()}/wp-login.php?redirect_to=", $phpbbForum->get_board_url() . $login_link, $wpUnited->get_wp_content()));
		$wpUnited->set_wp_content(str_replace("{$wpUnited->get_wp_base_url()}/wp-login.php?action=logout", $phpbbForum->get_board_url() . $logout_link, $wpUnited->get_wp_content()));
	}
}

/*
 * Processes the page head, returns header info to be inserted into the WP or phpBB page head.
 * Removes the head from the rest of the page.
 * @param string $retWpInc The page content for modification, must be passed by reference.
 * @return string the page <HEAD>
 * TODO: Remove global variables
 */
function process_remove_head($retWpInc, $loc = 'inner') {
	global $wpUnited, $wpu_dtd, $wpu_page_title;
	
	//Locate where the WordPress <body> begins, and snip off everything above and including the statement
	$bodyLocStart = strpos($retWpInc, "<body");
	$bodyLoc = strpos($retWpInc, ">", $bodyLocStart);
	$wpHead = substr($retWpInc, 0, $bodyLoc + 1);
	$retWpInc = substr_replace($retWpInc, '', 0, $bodyLoc + 1);

	//grab the page title
	$begTitleLoc = strpos($wpHead, "<title>");
	$titleLen = strpos($wpHead, "</title>") - $begTitleLoc;
	$wpTitleStr = substr($wpHead, $begTitleLoc +7, $titleLen - 7);

	// set page title 
	$wpu_page_title = trim($wpTitleStr); 
	

	//get anything inportant from the WP or phpBB <head> and integrate it into our phpBB page...
	$header_info = '';

	$findItems = array(
		'<!--[if' => '<![endif]-->',
		'<meta ' => '/>',
		'<script ' => '</script>',
		'<link ' => '/>',
		'<style ' => '</style>',

		'<!-- wpu-debug -->' => '<!-- /wpu-debug -->'
	);
	$header_info = head_snip($wpHead, $findItems);
	
		//get the DTD if we're doing DTD switching
		if ( ($wpUnited->get_setting('dtdSwitch')) && !$wpUnited->should_do_action('template-p-in-w') ) {
			$wpu_dtd = head_snip($wpHead, array('<!DOCTYPE' => '>'));
			
		}

	//fix font sizes coded in pixels  by phpBB -- un-comment this line if WordPress text looks too small
	//$wpHdrInfo .= "<style type=\"text/css\" media=\"screen\"> body { font-size: 62.5% !important;} </style>";


	if($loc == 'outer') {
		$wpUnited->set_outer_content($retWpInc);
	} else {
		$wpUnited->set_inner_content($retWpInc);
	}
	
	return $header_info;
}

/**
 * snips content out of a given string, and inserts it into a second string that is returned. 
 * @param string $haystack the page to be modified -- or <head> -- or whatever -- to find items and snip them out. 
 * @param array $findItems stuff to be found, provided as an array of starting_token => ending_token.
 */
function head_snip($haystack,$findItems) {
	$wpHdrInfo = '';
	foreach ( $findItems as $startToken => $endToken ) {
		$foundStyle = 1;
		$searchOffset = 0;
		$numLoops = 0; 	
		$styleLen = 0;
		$begStyleLoc = false;
		while (($foundStyle == 1) && ($numLoops <=200)) { //If we find more than 200 of one needle, something's probably wrong
		   $numLoops++; 
		   $begStyleLoc = strpos($haystack, $startToken, $searchOffset);
		   if (!($begStyleLoc === false)) { 
		      $styleLen = strpos($haystack, $endToken, $begStyleLoc) - $begStyleLoc;
		      if ($styleLen > 0) {
		        $foundPart = substr($haystack, $begStyleLoc, $styleLen + strlen($endToken));
				$haystack = str_replace($foundPart, '', $haystack);
				$wpHdrInfo .= $foundPart . "\n";
		        $foundStyle = 1;
		        $searchOffset = $begStyleLoc;
		      } else {
		         $searchOffset = $begStyleLoc;
		      }
		   } else {
		     $foundStyle = 0;
		   }
		}
	}
	return $wpHdrInfo;
}

/**
 * Process the <body> section of the integrated page
 * @param string $pageContent The page to be processed and modified. Must be passed by ref.
 */
function process_body($pageContent) {	
	//Process the body section for integrated page

	// With our Base HREF set, any relative links will point to the wrong location. Let's fix them.
	if(defined('WPU_BLOG_PAGE')) {
		$fullWpURL = strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strpos($_SERVER['SERVER_PROTOCOL'], '/'))) . '://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'];
		$pageContent = str_replace('a href="#', "a href=\"$fullWpURL#", $pageContent);
	}
	
	//cut out any </body> and </html> tags
	$pageContent = str_replace('</body>', '', $pageContent);
	$pageContent = str_replace('</html>', '', $pageContent);
	
	
	return $pageContent;
	
// End of processing of integrated page. 
}

/**
 * Does final clean-up of the integrated page, and sends it to the browser.
 * @param string $content The fully integrated page.
 */
function wpu_output_page($content) {
	global $wpuNoHead, $wpu_page_title, $wpu_dtd, $wpuSetWPSignature;
	
	//Add title back
	$content = str_replace("<!--[**PAGE_TITLE**]-->", $wpu_page_title, $content);

	//Add DTD if needed
	if(isset($wpu_dtd)) {
		$content = str_replace("<!--[**WP_DTD**]-->", $wpu_dtd, $content);
	}

	
	global $wpuDebug;
	
	// Add login debugging if requested
	if ( defined('WPU_DEBUG') && WPU_DEBUG && !$wpuNoHead ) {
		$content = $wpuDebug->add_debug_box($content, 'login');
	}

	// Add stats if requested
	if(defined('WPU_SHOW_STATS') && WPU_SHOW_STATS && !$wpuNoHead) {
		$content = $wpuDebug->add_stats_box($content);
	}
	


	echo $content . $wpuSetWPSignature; 
	// Finally -- clean up
	define('WPU_FINISHED', true);
	garbage_collection();
	exit_handler();
}

// That's all. Done!
