<?php
/* ----------------------------------------------------------------------
   $Id: specials.php,v 1.1 2007/06/08 17:14:42 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: specials.php,v 1.38 2002/05/16 15:32:22 hpdl 
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
  * Output a form pull down menu
  *
  * @param $name
  * @param $parameters
  * @param $exclude
  * @return string
  */
  function oos_draw_products_pull_down($name, $parameters = '', $exclude = '') {
    global $currencies;

    if ($exclude == '') {
      $exclude = array();
    }
    $select_string = '<select name="' . $name . '"';
    if ($parameters) {
      $select_string .= ' ' . $parameters;
    }
    $select_string .= '>';

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $products_result = $dbconn->Execute("SELECT p.products_id, pd.products_name, p.products_price FROM $productstable p, $products_descriptiontable pd WHERE p.products_id = pd.products_id AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_name");
    while ($products = $products_result->fields) {
      if (!oos_in_array($products['products_id'], $exclude)) {
        $select_string .= '<option value="' . $products['products_id'] . '">' . $products['products_name'] . ' (' . $currencies->format($products['products_price']) . ')</option>';
      }

      // Move that ADOdb pointer!
      $products_result->MoveNext();
    }
    $select_string .= '</select>';

    return $select_string;
  }


 /**
  * Sets the status of a special
  *
  * @param $specials_id
  * @param $status
  * @return boolan
  */
  function oos_set_specials_status($specials_id, $status) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if ($status == '1') {
      $specialstable = $oostable['specials'];
      return $dbconn->Execute("UPDATE $specialstable SET status = '1', expires_date = NULL, date_status_change = NULL WHERE specials_id = '" . $specials_id . "'");
    } elseif ($status == '0') {
      $specialstable = $oostable['specials'];
      return $dbconn->Execute("UPDATE $specialstable SET status = '0', date_status_change = now() WHERE specials_id = '" . $specials_id . "'");
    } else {
      return -1;
    }
  }

  require 'includes/classes/class_currencies.php';
  $currencies = new currencies();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'setflag':
        oos_set_specials_status($_GET['id'], $_GET['flag']);
        oos_redirect_admin(oos_href_link_admin($aContents['specials'], '', 'NONSSL'));
        break;

      case 'insert':
        // insert a product on special
        if (substr($_POST['specials_price'], -1) == '%') {
          $productstable = $oostable['products'];
          $new_special_insert_result = $dbconn->Execute("SELECT products_id, products_price FROM $productstable WHERE products_id = '" . $_POST['products_id'] . "'");
          $new_special_insert = $new_special_insert_result->fields;
          $_POST['products_price'] = $new_special_insert['products_price'];
          $_POST['specials_price'] = ($_POST['products_price'] - (($_POST['specials_price'] / 100) * $_POST['products_price']));
        } 

        $expires_date = '';
        if ($_POST['day'] && $_POST['month'] && $_POST['year']) {
          $expires_date = $_POST['year'];
          $expires_date .= (strlen($_POST['month']) == 1) ? '0' . $_POST['month'] : $_POST['month'];
          $expires_date .= (strlen($_POST['day']) == 1) ? '0' . $_POST['day'] : $_POST['day'];
        }
        $dbconn->Execute("INSERT INTO " . $oostable['specials'] . " (products_id, specials_new_products_price, specials_date_added, expires_date, status) VALUES ('" . $_POST['products_id'] . "', '" . $_POST['specials_price'] . "', now(), '" . $expires_date . "', '1')");
        oos_redirect_admin(oos_href_link_admin($aContents['specials'], 'page=' . $_GET['page']));
        break;

      case 'update':
        // update a product on special
        if (substr($_POST['specials_price'], -1) == '%') {
          $_POST['specials_price'] = ($_POST['products_price'] - (($_POST['specials_price'] / 100) * $_POST['products_price']));
        } 
        $expires_date = '';
        if ($_POST['day'] && $_POST['month'] && $_POST['year']) {
          $expires_date = $_POST['year'];
          $expires_date .= (strlen($_POST['month']) == 1) ? '0' . $_POST['month'] : $_POST['month'];
          $expires_date .= (strlen($_POST['day']) == 1) ? '0' . $_POST['day'] : $_POST['day'];
        }

        $dbconn->Execute("UPDATE " . $oostable['specials'] . " SET specials_new_products_price = '" . $_POST['specials_price'] . "', specials_last_modified = now(), expires_date = '" . $expires_date . "' WHERE specials_id = '" . $_POST['specials_id'] . "'");
        oos_redirect_admin(oos_href_link_admin($aContents['specials'], 'page=' . $_GET['page'] . '&sID=' . $specials_id));
        break;

      case 'deleteconfirm':
        $specials_id = oos_db_prepare_input($_GET['sID']);

        $specialstable = $oostable['specials'];
        $dbconn->Execute("DELETE FROM $specialstable WHERE specials_id = '" . oos_db_input($specials_id) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['specials'], 'page=' . $_GET['page']));
        break;
    }
  }
  require 'includes/header.php';

  if ( ($action == 'new') || ($action == 'edit') ) {
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
<?php
  }
?>
<div id="popupcalendar" class="text"></div>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require 'includes/oos_blocks.php'; ?>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
    $form_action = 'insert';
    if ( ($action == 'edit') && isset($_GET['sID']) ) {
      $form_action = 'update';

      $productstable = $oostable['products'];
      $products_descriptiontable = $oostable['products_description'];
      $specialstable = $oostable['specials'];
      $sql = "SELECT p.products_tax_class_id, p.products_id, pd.products_name, p.products_price,
                    s.specials_new_products_price, s.expires_date
              FROM $productstable p,
                   $products_descriptiontable pd,
                   $specialstable s
              WHERE p.products_id = pd.products_id AND
                  pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                  p.products_id = s.products_id AND
                  s.specials_id = '" . $_GET['sID'] . "'";
      $product_result = $dbconn->Execute($sql);
      // END IN-SOLUTION	
      $product = $product_result->fields;

      $sInfo = new objectInfo($product);
    } elseif ( ($action == 'new') && isset($_GET['pID']) ) {
      $productstable = $oostable['products'];
      $products_descriptiontable = $oostable['products_description'];
      $sql = "SELECT p.products_tax_class_id, p.products_id, pd.products_name, p.products_price
              FROM $productstable p,
                   $products_descriptiontable pd
              WHERE p.products_id = pd.products_id AND
                    pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                    p.products_id = '" . $_GET['pID'] . "'";
      $product_result = $dbconn->Execute($sql);
      $product = $product_result->fields;

      $sInfo = new objectInfo($product);
    } else {
      $sInfo = new objectInfo(array());

// create an array of products on special, which will be excluded from the pull down menu of products
// (when creating a new product on special)
      $specials_array = array();
      $productstable = $oostable['products'];
      $specialstable = $oostable['specials'];
      $specials_result = $dbconn->Execute("SELECT p.products_id FROM $productstable p, $specialstable s WHERE s.products_id = p.products_id");
      while ($specials = $specials_result->fields) {
        $specials_array[] = $specials['products_id'];

        // Move that ADOdb pointer!
        $specials_result->MoveNext();
      }

      // Close result set
      $specials_result->Close();
    }
?>
      <tr><form name="new_special" <?php echo 'action="' . oos_href_link_admin($aContents['specials'], oos_get_all_get_params(array('action', 'info', 'sID')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post"><?php if ($form_action == 'update') echo oos_draw_hidden_field('specials_id', $_GET['sID']); ?>
        <td><br /><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_SPECIALS_PRODUCT; echo ($sInfo->products_name) ? "" :  '('.TEXT_TAX_INFO.')'; ?>&nbsp;</td>
<?php
    $in_price = $sInfo->products_price; 
    $in_new_price = $sInfo->specials_new_products_price;
    $in_price=round($in_price,TAX_DECIMAL_PLACES);
    $in_new_price=round($in_new_price,TAX_DECIMAL_PLACES);

    if (isset($_GET['pID']) ) {
      echo '<input type="hidden" name="products_id" value="' . $sInfo->products_id . '">';
    } else {
      echo '<input type="hidden" name="products_up_id" value="' . $sInfo->products_id . '">';
    }
?>
            <td class="main"><?php echo ($sInfo->products_name) ? $sInfo->products_name . ' <small>(' . $currencies->format($in_price) . ' - ' . TEXT_TAX_INFO . $currencies->format($in_price_netto) . ')</small>' : oos_draw_products_pull_down('products_id', 'style="font-size:10px"', $specials_array); echo oos_draw_hidden_field('products_price', $sInfo->products_price); ?></td>

          </tr>
          <tr>
            <td class="main"><?php echo TEXT_SPECIALS_SPECIAL_PRICE; ?>&nbsp;</td>
            <td class="main"><?php echo oos_draw_input_field('specials_price', $in_new_price); echo '  ' . TEXT_TAX_INFO . $in_new_price_netto; ?> </td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_SPECIALS_EXPIRES_DATE; ?>&nbsp;</td>
            <td class="main"><?php echo oos_draw_input_field('day', substr($sInfo->expires_date, 8, 2), 'size="2" maxlength="2" class="cal-TextBox"') . oos_draw_input_field('month', substr($sInfo->expires_date, 5, 2), 'size="2" maxlength="2" class="cal-TextBox"') . oos_draw_input_field('year', substr($sInfo->expires_date, 0, 4), 'size="4" maxlength="4" class="cal-TextBox"'); ?><a class="so-BtnLink" href="javascript:calClick();return false;" onmouseover="calSwapImg('BTN_date', 'img_Date_OVER',true);" onmouseout="calSwapImg('BTN_date', 'img_Date_UP',true);" onclick="calSwapImg('BTN_date', 'img_Date_DOWN');showCalendar('new_special','dteWhen','BTN_date');return false;"><?php echo oos_image(OOS_IMAGES . 'cal_date_up.gif', 'Calendar', '22', '17', 'align="absmiddle" name="BTN_date"'); ?></a></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><br /><?php echo TEXT_SPECIALS_PRICE_TIP; ?></td>
            <td class="main" align="right" valign="top"><br /><?php echo (($form_action == 'insert') ? oos_image_swap_submits('insert','insert_off.gif', IMAGE_INSERT) : oos_image_swap_submits('update','update_off.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;&nbsp;<a href="' . oos_href_link_admin($aContents['specials'], 'page=' . $_GET['page'] . '&sID=' . $_GET['sID']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRODUCTS_PRICE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $specialstable = $oostable['specials'];
    $specials_sql_raw = "SELECT p.products_tax_class_id, p.products_id, pd.products_name, p.products_price,
                               s.specials_id, s.specials_new_products_price, s.specials_date_added,
                               s.specials_last_modified, s.expires_date, s.date_status_change, s.status
                           FROM $productstable p,
                                $specialstable s,
                                $products_descriptiontable pd
                           WHERE p.products_id = pd.products_id AND
                                 pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                                 p.products_id = s.products_id
                           ORDER BY pd.products_name";
    $specials_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $specials_sql_raw, $specials_numrows);
    $specials_result = $dbconn->Execute($specials_sql_raw);
    while ($specials = $specials_result->fields) {
      if ((!isset($_GET['sID']) || (isset($_GET['sID']) && ($_GET['sID'] == $specials['specials_id']))) && !isset($sInfo)) {
        $productstable = $oostable['products'];
        $products_result = $dbconn->Execute("SELECT products_image FROM $productstable WHERE products_id = '" . $specials['products_id'] . "'");
        $products = $products_result->fields;
        $sInfo_array = array_merge($specials, $products);
        $sInfo = new objectInfo($sInfo_array);
      }

      if (isset($sInfo) && is_object($sInfo) && ($specials['specials_id'] == $sInfo->specials_id) ) {
        echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['specials'], 'page=' . $_GET['page'] . '&sID=' . $sInfo->specials_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['specials'], 'page=' . $_GET['page'] . '&sID=' . $specials['specials_id']) . '\'">' . "\n";
      }

      $in_price = $sInfo->products_price; 
      $in_new_price = $sInfo->specials_new_products_price;
?>
                <td  class="dataTableContent"><?php echo $specials['products_name']; ?></td>
                <td  class="dataTableContent" align="right"><span class="oldPrice"><?php echo $currencies->format($specials['products_price']); ?></span> <span class="specialPrice"><?php echo $currencies->format($specials['specials_new_products_price']); ?></span></td>
                <td  class="dataTableContent" align="right">
<?php
      if ($specials['status'] == '1') {
        echo '<a href="' . oos_href_link_admin($aContents['specials'], 'action=setflag&flag=0&id=' . $specials['specials_id'], 'NONSSL') . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . oos_href_link_admin($aContents['specials'], 'action=setflag&flag=1&id=' . $specials['specials_id'], 'NONSSL') . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($sInfo) && is_object($sInfo) && ($specials['specials_id'] == $sInfo->specials_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aContents['specials'], 'page=' . $_GET['page'] . '&sID=' . $specials['specials_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
      </tr>
<?php
      // Move that ADOdb pointer!
      $specials_result->MoveNext();
    }

    // Close result set
    $specials_result->Close();
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $specials_split->display_count($specials_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?></td>
                    <td class="smallText" align="right"><?php echo $specials_split->display_links($specials_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr> 
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['specials'], 'page=' . $_GET['page'] . '&action=new') . '">' . oos_image_swap_button('new_product','new_product_off.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?></td>
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
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_SPECIALS . '</b>');

      $contents = array('form' => oos_draw_form('specials', $aContents['specials'], 'page=' . $_GET['page'] . '&sID=' . $sInfo->specials_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $sInfo->products_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . '&nbsp;<a href="' . oos_href_link_admin($aContents['specials'], 'page=' . $_GET['page'] . '&sID=' . $sInfo->specials_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (isset($sInfo) && is_object($sInfo)) {
        $heading[] = array('text' => '<b>' . $sInfo->products_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['specials'], 'page=' . $_GET['page'] . '&sID=' . $sInfo->specials_id . '&action=edit') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['specials'], 'page=' . $_GET['page'] . '&sID=' . $sInfo->specials_id . '&action=delete') . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br />' . TEXT_INFO_DATE_ADDED . ' ' . oos_date_short($sInfo->specials_date_added));
        $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . oos_date_short($sInfo->specials_last_modified));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_info_image($sInfo->products_image, $sInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));


        $in_price = $sInfo->products_price; 
        $in_new_price = $sInfo->specials_new_products_price;
        $in_price=round($in_price,TAX_DECIMAL_PLACES);
        $in_new_price=round($in_new_price,TAX_DECIMAL_PLACES);
        $contents[] = array('text' => '<br />' . TEXT_INFO_ORIGINAL_PRICE . ' ' . $currencies->format($in_price) . ' - ' . TEXT_TAX_INFO . $currencies->format($in_price_netto));
        $contents[] = array('text' => '' . TEXT_INFO_NEW_PRICE . ' ' . $currencies->format($in_new_price) . ' - ' . TEXT_TAX_INFO . $currencies->format($in_new_price_netto) );
        $contents[] = array('text' => '' . TEXT_INFO_PERCENTAGE . ' ' . number_format(100 - (($sInfo->specials_new_products_price / $sInfo->products_price) * 100)) . '%');

        if (date('Y-m-d') < $sInfo->expires_date) $contents[] = array('text' => '<br />' . TEXT_INFO_EXPIRES_DATE . ' <b>' . oos_date_short($sInfo->expires_date) . '</b>');
        if (oos_is_not_null($sInfo->date_status_change)) $contents[] = array('text' => '' . TEXT_INFO_STATUS_CHANGE . ' ' . oos_date_short($sInfo->date_status_change));
      }
      break;
  }
  if ( (oos_is_not_null($heading)) && (oos_is_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
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

<?php require 'includes/oos_footer.php'; ?>
</body>
</html>
<?php require 'includes/nice_exit.php'; ?>
