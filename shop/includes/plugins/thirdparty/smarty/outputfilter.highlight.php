<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     outputfilter.highlight.php
 * Type:     outputfilter
 * Name:     highlight
 * Version:  $Revision: 1.1 $
 * Date:     $Date: 2007/06/08 13:34:16 $
 * changed   $Author: r23 $
 * Purpose:  Adds Google-cache-like highlighting for terms in a
 *           template after its rendered. This can be used 
 *           easily integrated with the wiki search functionality
 *           to provide highlighted search terms.
 * Install:  Drop into the plugin directory, call 
 *           $smarty->load_filter('output','highlight');
 *           from application.
 * Author:   Greg Hinkle <ghinkl@users.sourceforge.net>
 *           patched by mose <mose@feu.org>
 *           Modified by r23 <info@r23.de> for OSIS Online Shop
 * -------------------------------------------------------------
 */
 function smarty_outputfilter_highlight($source, &$smarty) {

    $highlight = $_REQUEST['highlight']; 

    if (isset($_GET['keywords'])) {
      $highlight .= oos_var_prep_for_os($_GET['keywords']);
    }

    $highlight = strip_tags($highlight);
    $sStrSize = strlen($highlight);

    if ($sStrSize <= 5) {
      return $source;
    }

    if (eregi(oos_server_get_var('HTTP_HOST'), oos_server_get_var('HTTP_REFERER'))) {
      if (!isset($highlight) || empty($highlight)) {
        return $source;
      }
    } else {
      require_once('includes/classes/class_referrer.php');
      $referrer = new referrer();
      $highlight .= $referrer->getKeywords();
    }

    $words = $highlight;
    if (!isset($highlight) || empty($highlight)) {
      return $source;
    }

    // Pull out the script blocks
    preg_match_all("!<script[^>]+>.*?</script>!is", $source, $match);
    $_script_blocks = $match[0];
    $source = preg_replace("!<script[^>]+>.*?</script>!is", '@@@=====@@@', $source);

    preg_match_all("!<a onmouseo[^>]+>.*?>!is", $source, $match);
    $_onmouse_block = $match[0];
    $source = preg_replace("!<a onmouseo[^>]+>.*?>!is", '@@@#=====#@@@', $source);

    // pull out all html tags
    preg_match_all("'<[\/\!]*?[^<>]*?>'si", $source, $match);
    $_tag_blocks = $match[0];
    $source = preg_replace("'<[\/\!]*?[^<>]*?>'si", '@@@:=====:@@@', $source);


    // This array is used to choose colors for supplied highlight terms
    $colorArr = array('#ffff66','#ff9999','#A0FFFF','#ff66ff','#99ff99');

    // Wrap all the highlight words with tags bolding them and changing
    // their background colors
    $wordArr = split(" ",addslashes($words));
    $i = 0;
    foreach($wordArr as $word) {
                        $word = preg_quote($word);
                        $source = preg_replace('~('.$word.')~si', '<span style="color:black;background-color:'.$colorArr[$i].';">$1</span>', $source); 
                        $i++;
    }

    // replace script blocks
    foreach($_script_blocks as $curr_block) {
        $source = preg_replace("!@@@=====@@@!",$curr_block,$source,1);
    }

    foreach($_onmouse_block as $curr_block) {
        $source = preg_replace("!@@@#=====#@@@!",$curr_block,$source,1);
    }

    foreach($_tag_blocks as $curr_block) {
        $source = preg_replace("!@@@:=====:@@@!",$curr_block,$source,1);
    }

    return $source;
 }
?>