<?php
/* ----------------------------------------------------------------------
   $Id: campaigns.php,v 1.1 2007/06/08 17:14:40 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: campaigns.php,v 1.19 2003/02/06 17:37:09 thomasamoulton 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/main.php';

 /**
  * Return Campaigns Name
  *
  * @param $campaigns_id
  * @param $language
  * @return string
  */
  function oos_get_campaigns_name($campaigns_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $campaignstable = $oostable['campaigns'];
    $query = "SELECT campaigns_name
              FROM $campaignstable
              WHERE campaigns_id = '" . intval($campaigns_id) . "'
                AND campaigns_languages_id = '" . intval($lang_id) . "'";
    $campaigns_name = $dbconn->GetOne($query);

    return $campaigns_name;
  }


 /**
  * Return campaigns
  *
  * @param $campaigns_id
  * @param $language
  * @return array
  */
  function oos_get_campaigns() {

    $campaigns_array = array();

    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $campaignstable = $oostable['campaigns'];
    $campaigns_sql = "SELECT campaigns_id, campaigns_name
                      FROM $campaignstable
                      WHERE campaigns_languages_id = '" . intval($_SESSION['language_id']) . "'
                      ORDER BY campaigns_id";
    $campaigns_result = $dbconn->Execute($campaigns_sql);
    while ($campaigns = $campaigns_result->fields) {
      $campaigns_array[] = array('id' => $campaigns['campaigns_id'],
                                 'text' => $campaigns['campaigns_name']);

      // Move that ADOdb pointer!
      $campaigns_result->MoveNext();
    }

    return $campaigns_array;
  }


  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        $campaigns_id = oos_db_prepare_input($_GET['cID']);

        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $campaigns_name_array = $_POST['campaigns_name'];
          $lang_id = $languages[$i]['id'];

          $sql_data_array = array('campaigns_name' => oos_db_prepare_input($campaigns_name_array[$lang_id]));

          if ($action == 'insert') {
            if (oos_empty($campaigns_id)) {
              $campaignstable = $oostable['campaigns'];
              $next_id_result = $dbconn->Execute("SELECT max(campaigns_id) as campaigns_id FROM $campaignstable");
              $next_id = $next_id_result->fields;
              $campaigns_id = $next_id['campaigns_id'] + 1;
            }

            $insert_sql_data = array('campaigns_id' => $campaigns_id,
                                     'campaigns_languages_id' => $lang_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            oos_db_perform($oostable['campaigns'], $sql_data_array);
          } elseif ($action == 'save') {
            oos_db_perform($oostable['campaigns'], $sql_data_array, 'update', "campaigns_id = '" . oos_db_input($campaigns_id) . "' and campaigns_languages_id = '" . intval($lang_id) . "'");
          }
        }

        if (isset($_POST['default']) && ($_POST['default'] == 'on')) {
          $configurationtable = $oostable['configuration'];
          $dbconn->Execute("UPDATE $configurationtable SET configuration_value = '" . oos_db_input($campaigns_id) . "' WHERE configuration_key = 'DEFAULT_CAMPAIGNS_ID'");
        }

        oos_redirect_admin(oos_href_link_admin($aContents['campaigns'], 'page=' . $_GET['page'] . '&cID=' . $campaigns_id));
        break;

    case 'deleteconfirm':
        $cID = oos_db_prepare_input($_GET['cID']);

        $configurationtable = $oostable['configuration'];
        $campaigns_result = $dbconn->Execute("SELECT configuration_value FROM $configurationtable WHERE configuration_key = 'DEFAULT_CAMPAIGNS_ID'");
        $campaigns = $campaigns_result->fields;
        if ($campaigns['configuration_value'] == $cID) {
          $dbconn->Execute("UPDATE " . $oostable['configuration'] . " SET configuration_value = '' WHERE configuration_key = 'DEFAULT_CAMPAIGNS_ID'");
        }

        $campaignstable = $oostable['campaigns'];
        $dbconn->Execute("DELETE FROM $campaignstable WHERE campaigns_id = '" . oos_db_input($cID) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['campaigns'], 'page=' . $_GET['page']));
        break;

    case 'delete':
        $cID = oos_db_prepare_input($_GET['cID']);

        $remove_status = true;
        if ($cID == DEFAULT_CAMPAIGNS_ID) {
          $remove_status = false;
          $messageStack->add(ERROR_REMOVE_DEFAULT_CAMPAIGNS, 'error');
        }
        break;
    }
  }
  require 'includes/header.php';
?>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require 'includes/oos_blocks.php'; ?>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CAMPAIGNS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $campaignstable = $oostable['campaigns'];
  $campaigns_result_raw = "SELECT campaigns_id, campaigns_name
                           FROM $campaignstable
                           WHERE campaigns_languages_id = '" . intval($_SESSION['language_id']) . "'
                           ORDER BY campaigns_id";
  $campaigns_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $campaigns_result_raw, $campaigns_result_numrows);
  $campaigns_result = $dbconn->Execute($campaigns_result_raw);
  while ($campaigns = $campaigns_result->fields) {
    if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $campaigns['campaigns_id']))) && !isset($oInfo) && (substr($action, 0, 3) != 'new')) {
      $oInfo = new objectInfo($campaigns);
    }

    if (isset($oInfo) && is_object($oInfo) && ($campaigns['campaigns_id'] == $oInfo->campaigns_id)) {
      echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['campaigns'], 'page=' . $_GET['page'] . '&cID=' . $oInfo->campaigns_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['campaigns'], 'page=' . $_GET['page'] . '&cID=' . $campaigns['campaigns_id']) . '\'">' . "\n";
    }

    if (DEFAULT_CAMPAIGNS_ID == $campaigns['campaigns_id']) {
      echo '                <td class="dataTableContent"><b>' . $campaigns['campaigns_name'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
      echo '                <td class="dataTableContent">' . $campaigns['campaigns_name'] . '</td>' . "\n";
    }
?>

                <td class="dataTableContent" align="right"><?php if (isset($oInfo) && is_object($oInfo) && ($campaigns['campaigns_id'] == $oInfo->campaigns_id)) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aContents['campaigns'], 'page=' . $_GET['page'] . '&cID=' . $campaigns['campaigns_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $campaigns_result->MoveNext();
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $campaigns_split->display_count($campaigns_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CAMPAIGNS); ?></td>
                    <td class="smallText" align="right"><?php echo $campaigns_split->display_links($campaigns_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
    if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['campaigns'], 'page=' . $_GET['page'] . '&action=new') . '">' . oos_image_swap_button('insert','insert_off.gif', IMAGE_INSERT) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php

  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CAMPAIGNS . '</b>');

      $contents = array('form' => oos_draw_form('status', $aContents['campaigns'], 'page=' . $_GET['page'] . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);

      $campaigns_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $campaigns_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('campaigns_name[' . $languages[$i]['id'] . ']');
      }

      $contents[] = array('text' => '<br />' . TEXT_INFO_CAMPAIGNS_NAME . $campaigns_inputs_string);
      $contents[] = array('text' => '<br />' . oos_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('insert','insert_off.gif', IMAGE_INSERT) . ' <a href="' . oos_href_link_admin($aContents['campaigns'], 'page=' . $_GET['page']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CAMPAIGNS . '</b>');

      $contents = array('form' => oos_draw_form('status', $aContents['campaigns'], 'page=' . $_GET['page'] . '&cID=' . $oInfo->campaigns_id  . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);

      $campaigns_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $campaigns_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('campaigns_name[' . $languages[$i]['id'] . ']', oos_get_campaigns_name($oInfo->campaigns_id, $languages[$i]['id']));
      }

      $contents[] = array('text' => '<br />' . TEXT_INFO_CAMPAIGNS_NAME . $campaigns_inputs_string);
      if (DEFAULT_CAMPAIGNS_ID != $oInfo->campaigns_id) $contents[] = array('text' => '<br />' . oos_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('update','update_off.gif', IMAGE_UPDATE) . ' <a href="' . oos_href_link_admin($aContents['campaigns'], 'page=' . $_GET['page'] . '&cID=' . $oInfo->campaigns_id) . '">' . oos_image_swap_button('cancel', 'cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CAMPAIGNS . '</b>');

      $contents = array('form' => oos_draw_form('status', $aContents['campaigns'], 'page=' . $_GET['page'] . '&cID=' . $oInfo->campaigns_id  . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $oInfo->campaigns_name . '</b>');
      if ($remove_status) $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete', 'delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aContents['campaigns'], 'page=' . $_GET['page'] . '&cID=' . $oInfo->campaigns_id) . '">' . oos_image_swap_button('cancel', 'cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
     if (isset($oInfo) && is_object($oInfo)) {
        $heading[] = array('text' => '<b>' . $oInfo->campaigns_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['campaigns'], 'page=' . $_GET['page'] . '&cID=' . $oInfo->campaigns_id . '&action=edit') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['campaigns'], 'page=' . $_GET['page'] . '&cID=' . $oInfo->campaigns_id . '&action=delete') . '">' . oos_image_swap_button('delete', 'delete_off.gif', IMAGE_DELETE) . '</a>');

        $campaigns_inputs_string = '';
        $languages = oos_get_languages();

        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $campaigns_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_get_campaigns_name($oInfo->campaigns_id, $languages[$i]['id']);
        }

        $contents[] = array('text' => $campaigns_inputs_string);
      }
      break;
  }

  if ( (oos_is_not_null($heading)) && (oos_is_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<?php require 'includes/footer.php'; ?>
<br />
</body>
</html>
<?php require 'includes/nice_exit.php'; ?>