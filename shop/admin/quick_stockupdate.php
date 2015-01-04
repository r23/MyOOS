<?php
/* ----------------------------------------------------------------------
   $Id: quick_stockupdate.php,v 1.1 2007/06/08 17:14:41 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: quick_stockupdate.php v1.1 by Tomorn Kaewtong / http://www.phpthailand.com
         MODIFIED quick_stockupdate.php v2.4 by Dominic Stein
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

  require 'includes/functions/function_categories.php';
  require '../includes/classes/class_currencies.php';
  $currencies = new currencies();

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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
        </td>
      </tr>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top">
                <table border="0" width="100%" cellspacing="0" cellpadding="1">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent"><?php echo oos_draw_separator('trans.gif', '100%', '1'); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo HEADING_INTRO; ?></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td>
<?php
  if (isset($_POST['stock_update']) && !empty($_POST['stock_update'])) {

    foreach ($_POST['stock_update'] as $key => $items) {

      // update the quantity in stock
      $productstable = $oostable['products'];
      $sql = "UPDATE $productstable SET products_quantity = '" . $items['stock'] . "', products_model = '" . $items['model'] . "', products_price = '" . $items['price'] . "', products_weight = '" . $items['weight'] . "' WHERE products_id = '" . $key . "'";
      $dbconn->Execute($sql);
      $stock_i++;

      // we're de-re-activating the selected products
      if (isset($_POST['update_status']) && !empty($_POST['update_status'])) {
        if ($items['stock'] >= 1 ) {
          $productstable = $oostable['products'];
          $dbconn->Execute("UPDATE $productstable SET products_status = '3' WHERE products_id = '" . $key . "'");
          $status_a++;
        } else {
          $productstable = $oostable['products'];
          $dbconn->Execute("UPDATE $productstable SET products_status = '0' WHERE products_id = '" . $key . "'");
          $status_d++;
        }
      }
    }
  }
?>
<?php
  $tree = oos_get_category_tree();
  $dropdown = oos_draw_pull_down_menu('cat_id', $tree, $_POST['cat_id'], 'onChange="this.form.submit();"');
?>
<br />
            <table border="0">
              <tr><?php echo oos_draw_form('stockupdate', $aContents['quick_stockupdate']); ?>
              <th class="smallText" align="left" valign="top">Categories:<br /><?php echo $dropdown; ?></form></th>
              </tr>
            </table>
            </td>
          </tr>
        </table>
<br />
<?php
  // see if there is a category ID:
  if (isset($_POST['cat_id']) && !empty($_POST['cat_id'])) {
    // start the table
    echo oos_draw_form('stockupdate', $aContents['quick_stockupdate']);
    echo '            <table width="100%" border="0" cellspacing="2" cellpadding="2">';

    // get all active prods in that specific category
    $productstable = $oostable['products'];
    $products_to_categoriestable = $oostable['products_to_categories'];
    $products_descriptiontable = $oostable['products_description'];
    $sql2 = "SELECT p.products_tax_class_id, p.products_model, p.products_id, p.products_quantity, p.products_status, p.products_weight, p.products_price, pd.products_name
               FROM $productstable p,
                    $products_to_categoriestable ptc,
                    $products_descriptiontable pd
              WHERE p.products_id = ptc.products_id AND
                    p.products_id = pd.products_id AND
                    pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                    ptc.categories_id = '" . $_POST['cat_id'] . "'
              ORDER BY pd.products_name";
    $result2 = $dbconn->Execute($sql2);
    echo '<tr class="dataTableHeadingRow"><td class="dataTableContent" align="left"><b>' . TABLE_HEADING_MODEL . '</b></td><td class="dataTableContent" align="left"><b>'. TABLE_HEADING_ID .'</b></td><td class="dataTableContent" align="left"><b>' . TABLE_HEADING_NAME . '</b></td><td class="dataTableContent" align="left"><b>' . TABLE_HEADING_WEIGHT . '</b></td><td class="dataTableContent" align="left"><b>' . TABLE_HEADING_PRICE . '</b></td><td class="dataTableContent" align="left"><b>' . TABLE_HEADING_STOCK . '</b></td></tr>';

    while ($product = $result2->fields) {
      echo '<tr class="dataTableRow"><td class="dataTableContent" align="left"><input type="text" size="16" name="stock_update[' . $product['products_id'] . '][model]" value="' . $product['products_model'] . '"><i>';
      echo '</td><td class="dataTableContent" align="left">' . $product['products_id'] . '</td><td class="dataTableContent" align="left">' . $product['products_name'];
      echo '</td><td class="dataTableContent" align="left"><input type="text" size="3" name="stock_update[' . $product['products_id'] . '][weight]" value="' . $product['products_weight'] . '"><i>';

      $oosPrice = $product['products_price'];
      echo '</td><td class="dataTableContent" align="left"><input type="text" size="5" name="stock_update[' . $product['products_id'] . '][price]" value="' . $oosPrice . '">';
      echo '<input type="hidden" name="stock_update[' . $product['products_id'] . '][tax_class_id]" value="' . $product['products_tax_class_id'] . '">';
      echo '</td><td class="dataTableContent" align="left"><input type="text" size="4" name="stock_update[' . $product['products_id'] . '][stock]" value="' . $product['products_quantity'] . '"><i>';
      echo (($product['products_status'] != 3) ? '<font color="ff0000"><b>not active</b></font>' : '<font color="009933"><b>active</b></font>');
      echo '</i></td></tr>';

      // Move that ADOdb pointer!
      $result2->MoveNext();
    }
    // Close result set
    $result2->Close();

    echo '</table><table border="0" width="100%" cellspacing=2 cellpadding=2><tr>';
    echo '<input type="hidden" name="cat_id" value="' . $_POST['cat_id'] . '">';
    echo '</tr><br /><td align="center" colspan="10" class="smallText">';
    echo '<input type="checkbox" name="update_status">';
    echo TEXT_INFO_UPDATE_STATUS;
    echo '<input type="submit" value="Update"></td></tr></form>';
  } 
?>
    </tr></table>
  </td>
</tr></table>
    </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->


<?php require 'includes/oos_footer.php'; ?>
<br />
</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>
