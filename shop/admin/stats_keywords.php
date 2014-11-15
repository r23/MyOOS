<?php
/* ----------------------------------------------------------------------
   $Id: stats_keywords.php,v 1.1 2007/06/08 17:14:42 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: stats_keywords.php,v 0.90 10/03/2002 03:15:00  
   ----------------------------------------------------------------------
   by Cheng	

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

  if (isset($_GET['txtWord']) && $_GET['txtWord'] != '' && isset($_GET['txtReplacement']) && $_GET['txtReplacement'] != '' && !isset($_GET['updateword'])){
    $searchword_swaptable = $oostable['searchword_swap'];
    $newword_sql = "INSERT INTO $searchword_swaptable (sws_word, sws_replacement)VALUES('" . addslashes($_GET['txtWord']) . "', '" . addslashes($_GET['txtReplacement']) . "' )";
    $result = $dbconn->Execute($newword_sql);
    oos_redirect_admin(oos_href_link_admin($aContents['stats_keywords'], 'action=' . BUTTON_VIEW_WORD_LIST . ''));
  }

  if (isset($_GET['removeword']) && isset($_GET['delete'])){
    $searchword_swaptable = $oostable['searchword_swap'];
    $word_delete_sql = "DELETE FROM $searchword_swaptable WHERE sws_id = " . $_GET['delete'];
    $result = $dbconn->Execute($word_delete_sql);
    oos_redirect_admin(oos_href_link_admin($aContents['stats_keywords'], 'action=' . BUTTON_VIEW_WORD_LIST . ''));	
  }

  if (isset($_GET['editword']) && isset($_GET['link'])){
    $searchword_swaptable = $oostable['searchword_swap'];
    $word_select_sql = "SELECT * FROM $searchword_swaptable WHERE sws_id = " . $_GET['edit'];
    $result = $dbconn->Execute($word_select_sql);
    $word_select_result = $result->fields;
  }

  if (isset($_GET['editword']) && isset($_GET['updateword'])){
    $searchword_swaptable = $oostable['searchword_swap'];
    $word_update_sql = "UPDATE $searchword_swaptable SET sws_word= '" . addslashes($_GET['txtWord']) . "', sws_replacement = '" . addslashes($_GET['txtReplacement']) . "' WHERE  sws_id = " . $_GET['id'];
    $result = $dbconn->Execute($word_update_sql);
    oos_redirect_admin(oos_href_link_admin($aContents['stats_keywords'], 'action=' . BUTTON_VIEW_WORD_LIST . ''));
  }

  $no_js_general = true;
  require 'includes/oos_header.php';
?>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require 'includes/oos_blocks.php'; ?>
        </table></td>
<!-- body_text //-->
    <td valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="pageHeading"><?php echo HEADING_TITLE ?></td>
    <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
  </tr><tr>
    <td class="main" colspan="2">
<?php
  if (isset($_GET['action']) && ($_GET['action'] == 'delete')) {
    $search_queries_sortedtable = $oostable['search_queries_sorted'];
    $dbconn->Execute("DELETE FROM $search_queries_sortedtable");
  }

  if (isset($_GET['update']) && ($_GET['update'] == BUTTON_UPDATE_WORD_LIST)) {
    $search_queriestable = $oostable['search_queries'];
    $sql_q = $dbconn->Execute("SELECT DISTINCT search_text, COUNT(*) AS ct FROM $search_queriestable GROUP BY search_text");

    while ($sql_q_result = $sql_q->fields) {
      $search_queries_sortedtable = $oostable['search_queries_sorted'];
      $update_q = $dbconn->Execute("SELECT search_text, search_count FROM $search_queries_sortedtable WHERE search_text = '" . $sql_q_result['search_text'] . "'");
      $update_q_result = $update_q->fields;
      $count = $sql_q_result['ct'] + $update_q_result['search_count'];

      if ($update_q_result['search_count'] != '') {
        $search_queries_sortedtable = $oostable['search_queries_sorted'];
        $dbconn->Execute("UPDATE $search_queries_sortedtable SET search_count = '" . $count . "' WHERE search_text = '" . $sql_q_result['search_text'] . "'");
      } else {
        $search_queries_sortedtable = $oostable['search_queries_sorted'];
        $dbconn->Execute("INSERT INTO $search_queries_sortedtable (search_text, search_count) VALUES ('" . $sql_q_result['search_text'] . "'," . $count . ")");
      }
      $search_queriestable = $oostable['search_queries'];

      $dbconn->Execute("DELETE FROM $search_queriestable");

      // Move that ADOdb pointer!
      $sql_q->MoveNext();
    }
    // Close result set
    $sql_q->Close();
  }

  if (isset($_GET['action']) && ($_GET['action'] == BUTTON_UPDATE_WORD_LIST)) {
    echo oos_draw_form('addwords', $aContents['stats_keywords'], '', 'get');
?>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<?php
    if (isset($_GET['add'])) {
?>
<tr><td colspan="4">
<table border="1" cellpadding="0" cellspacing="1" width="100%" bgcolour="gray"><tr><td>
  <table border="0" cellpadding="2" cellspacing="0" width="100%"><tr class="dataTableRow">
    <td class="main" nowrap><br /><?php echo WORD_ENTRY_ORIGINAL ?> 
    <input type="text" name="txtWord" value="<?php if(isset($word_select_result['sws_word'])){echo stripslashes($word_select_result['sws_word']);} ?>" size="12">&nbsp;
<?php echo WORD_ENTRY_REPLACEMENT ?>
    <input type="text" name="txtReplacement" value="<?php if(isset($word_select_result['sws_replacement'])){echo stripslashes($word_select_result['sws_replacement']);} ?>" size="12"></td>
<?php if(isset($word_select_result['sws_id'])){echo '<input type="hidden" name="id" value="' . $word_select_result['sws_id'] . '">';} ?>
  </tr>
  <tr class="dataTableRow">
    <td class="main">
<?php
       if (isset($_GET['editword']) && isset($_GET['link'])) {
?>
    <input type="submit" name="editword" value="<?php echo BUTTON_EDIT_WORD ?>">
    <input type="hidden" name="updateword" value="1">
    <br /><br />
<?php
       } else {
?>
    <input type="submit" name="newword" value="<?php echo BUTTON_ADD_WORD ?>"><br /><br />
<?php
       }
?>
    </td>
  </tr>
  </table></td></tr></table>
  </d></tr>
<?php
    }
?>
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent" width="40%"><?php echo WORD_ENTRY_ORIGINAL ?></td>
    <td class="dataTableHeadingContent" colspan="3"><?php echo WORD_ENTRY_REPLACEMENT ?></td>
  </tr>
<?php
    $searchword_swaptable = $oostable['searchword_swap'];
    $pw_word_sql = "SELECT * FROM $searchword_swaptable ORDER BY sws_word ASC" ;
    $pw_words = $dbconn->Execute($pw_word_sql);
    while ($pw_words_result = $pw_words->fields) {
?>
  <tr class="dataTableRow">
    <td class="dataTableContent"><?php echo stripslashes($pw_words_result['sws_word']); ?></td>
    <td class="dataTableContent"><?php echo stripslashes($pw_words_result['sws_replacement']); ?></td>
    <td class="dataTableHeadingContent"><a href="<?php echo oos_href_link_admin($aContents['stats_keywords'], 'editword=1&link=1&add=1&action=' . BUTTON_VIEW_WORD_LIST . '&edit=' . $pw_words_result['sws_id']); ?>"><u><?php echo LINK_EDIT ?></u></a></td>
    <td class="dataTableHeadingContent"><a href="<?php echo oos_href_link_admin($aContents['stats_keywords'], 'removeword=1&delete=' . $pw_words_result['sws_id']); ?>"><u><?php echo LINK_DELETE ?></u></a></td>
  </tr>
<?php
      // Move that ADOdb pointer!
      $pw_words->MoveNext();
    }
    // Close result set
    $pw_words->Close();
?>
  <tr>
    <td colspan="4" class="main" align="right"><br /><input type="submit" value="New Entry" name="add" method="post" />
    <input type="hidden" name="action" value="<?php echo BUTTON_VIEW_WORD_LIST ?>"></td>
  </tr>
</table></form>
<?php
  }

  if(!isset($_GET['action']) && $_GET['action'] != BUTTON_VIEW_WORD_LIST){
?>
    	<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent" width="40%"><?php echo KEYWORD_TITLE ?></td>
    <td class="dataTableHeadingContent"><?php echo KEYWORD_TITLE2 ?></td>
  </tr>
<?php

switch($_GET['sortorder']){
  case BUTTON_SORT_NAME:
    $search_queries_sortedtable = $oostable['search_queries_sorted'];
    $pw_sql = "SELECT search_text, search_count FROM $search_queries_sortedtable ORDER BY search_text ASC" ;
  break;

  case BUTTON_SORT_TOTAL:
    $search_queries_sortedtable = $oostable['search_queries_sorted'];
    $pw_sql = "SELECT search_text, search_count FROM $search_queries_sortedtable ORDER BY search_count DESC" ;
  break;

  default:
    $search_queries_sortedtable = $oostable['search_queries_sorted'];
    $pw_sql = "SELECT search_text, search_count FROM $search_queries_sortedtable ORDER BY search_text ASC" ;
  break;
}

   $result = $dbconn->Execute($pw_sql);
   while ($sql_q_result = $result->fields) {
?>
  <tr class="dataTableRow"  onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'" onclick="document.location.href='<?php echo oos_catalog_link($oosCatalogFilename['advanced_search_result'], 'keywords=' . urlencode($sql_q_result['search_text']). '&search_in_description=1' ); ?>'" >
    <td class="dataTableContent"><a target="_blank" href="<?php echo oos_catalog_link($oosCatalogFilename['advanced_search_result'], 'keywords=' . urlencode($sql_q_result['search_text']). '&search_in_description=1' ); ?>"><?php echo $sql_q_result['search_text']; ?></a></td>  
    <td class="dataTableContent"><?php echo $sql_q_result['search_count']; ?></td>
  </tr>
<?php
      // Move that ADOdb pointer!
     $result->MoveNext();
   }

  // Close result set
  $result->Close();
?>
    </td></tr></table>

<?php } ?>
    </td>
  </tr>
 </table>
    </td>
<!-- body_eof //-->
<!-- right_column_bof //-->
<td valign="top" width="25%">
<?php echo oos_draw_form('delete', $aContents['stats_keywords'], '', 'get'); ?>
<table border="0" cellspacing="0" cellpadding="2" width="100%">
  <tr>
    <td class="pageHeading" align="right">&nbsp;</td>
  </tr><tr>
    <td>
<?php
    $heading = array();
    $contents = array();

    $heading[]  = array('text'  => '<b>' . SIDEBAR_HEADING . '</b>');

    $contents[] = array('text'  => '<br />' . SIDEBAR_INFO_1);
    $contents[] = array('text'  => '<input type="submit" name="update" value="' . BUTTON_UPDATE_WORD_LIST . '">');
    $contents[] = array('text'  =>  oos_draw_separator());
    $contents[] = array('text'  => '<br /><input type="submit" name="sortorder" value="' . BUTTON_SORT_NAME . '"><br /><input type="submit" name="sortorder" value="' . BUTTON_SORT_TOTAL . '">');
    $contents[] = array('text'  =>  oos_draw_separator());
    $contents[] = array('text'  => '<br />' . SIDEBAR_INFO_2);
    $contents[] = array('text'  => '<input type="submit" value="' . BUTTON_DELETE . '" name="action">');
    $contents[] = array('text'  =>  oos_draw_separator());
    $contents[] = array('text'  => SIDEBAR_INFO_3);
    $contents[] = array('text'  => '<input type="submit" name="action" value="' . BUTTON_VIEW_WORD_LIST . '">');

  if ( (oos_is_not_null($heading)) && (oos_is_not_null($contents)) ) {

    $box = new box;
    echo $box->infoBox($heading, $contents);
  }
?>
</td></tr></table></form>
</td>
  </tr>
</table>
<!-- right_column_eof //-->

<?php require 'includes/oos_footer.php'; ?>
</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>