<?php
/* ----------------------------------------------------------------------
   $Id: products_attributes_add.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: products_attributes.php,v 1.52 2003/07/10 20:46:01 dgw_
         products_attributes.php,v 1.48 2002/11/22 14:45:49 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';
  require 'includes/functions/function_products_attributes.php';

  $languages = oos_get_languages();

  $page_info = '';
  if (isset($_GET['option_page'])) {
    $option_page = intval($_GET['option_page']);
    $page_info .= 'option_page=' . $option_page . '&';
  }
  if (isset($_GET['value_page'])) {
    $value_page =  intval($_GET['value_page']);
    $page_info .= 'value_page=' . $value_page . '&';
  }
  if (isset($_GET['attribute_page'])) {
    $attribute_page = intval($_GET['attribute_page']);
    $page_info .= 'attribute_page=' . $attribute_page . '&';
  }
  if (oos_is_not_null($page_info)) {
    $page_info = substr($page_info, 0, -1);
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'add_product_attributes':
        if (OOS_PRICE_IS_BRUTTO == 'true'){
          $tax_ratestable = $oostable['tax_rates'];
          $productstable = $oostable['products'];
          $sql = "SELECT tr.tax_rate
                  FROM $tax_ratestable tr,
                       $productstable p
                  WHERE tr.tax_class_id = p.products_tax_class_id
                    AND p.products_id = '".$_POST['products_id']."' ";
          $tax_result = $dbconn->Execute($sql);
          $tax = $tax_result->fields;
          $_POST['value_price'] = ($_POST['value_price']/($tax[tax_rate]+100)*100);
        }

        $products_optionstable = $oostable['products_options'];
        $products_options_result = $dbconn->Execute("SELECT products_options_type FROM $products_optionstable WHERE products_options_id = '" . $_POST['options_id'] . "'");
        $products_options_array = $products_options_result->fields;
        $values_id = (($products_options_array['products_options_type'] == PRODUCTS_OPTIONS_TYPE_TEXT) or ($products_options_array['products_options_type'] == PRODUCTS_OPTIONS_TYPE_FILE)) ? PRODUCTS_OPTIONS_VALUE_TEXT_ID : $_POST['values_id'];

        $products_attributestable = $oostable['products_attributes'];
        $dbconn->Execute("INSERT INTO $products_attributestable VALUES ('', '" . $_POST['products_id'] . "', '" . $_POST['options_id'] . "', '" . $_POST['values_id'] . "', '" . $_POST['value_price'] . "', '" . $_POST['price_prefix'] . "', '" . $_POST['sort_order'] . "')");
        $products_attributes_id = $dbconn->Insert_ID();
        if ((DOWNLOAD_ENABLED == 'true') && $_POST['products_attributes_filename'] != '') {
          $products_attributes_downloadtable = $oostable['products_attributes_download'];
          $dbconn->Execute("INSERT INTO $products_attributes_downloadtable VALUES (" . $products_attributes_id . ", '" . $_POST['products_attributes_filename'] . "', '" . $_POST['products_attributes_maxdays'] . "', '" . $_POST['products_attributes_maxcount'] . "')");
        }
        oos_redirect_admin(oos_href_link_admin($aFilename['products_attributes'], $page_info));
        break;

      case 'update_product_attribute':
        if (OOS_PRICE_IS_BRUTTO == 'true'){
          $tax_ratestable = $oostable['tax_rates'];
          $productstable = $oostable['products'];
          $sql = "SELECT tr.tax_rate
                   FROM $tax_ratestable tr,
                        $productstable p
                  WHERE tr.tax_class_id = p.products_tax_class_id 
                    AND p.products_id = '".$_POST['products_id']."'";
          $tax_result = $dbconn->Execute($sql);
          $tax = $tax_result->fields;
          $_POST['value_price'] = ($_POST['value_price']/($tax[tax_rate]+100)*100);
        }

        $products_optionstable = $oostable['products_options'];
        $products_options_result = $dbconn->Execute("SELECT products_options_type FROM $products_optionstable WHERE products_options_id = '" . $_POST['options_id'] . "'");
        $products_options_array = $products_options_result->fields;
        switch ($products_options_array['products_options_type']) {
          case PRODUCTS_OPTIONS_TYPE_TEXT:
          case PRODUCTS_OPTIONS_TYPE_FILE:
            $values_id = PRODUCTS_OPTIONS_VALUE_TEXT_ID;
            break;
          default:
            $values_id = $_POST['values_id'];
        }

        $products_attributestable = $oostable['products_attributes'];
        $dbconn->Execute("UPDATE $products_attributestable SET products_id = '" . $_POST['products_id'] . "', options_id = '" . $_POST['options_id'] . "', options_values_id = '" . $_POST['values_id'] . "', options_values_price = '" . $_POST['value_price'] . "', price_prefix = '" . $_POST['price_prefix'] . "', options_sort_order = '" . $_POST['sort_order'] . "' WHERE products_attributes_id = '" . $_POST['attribute_id'] . "'");

        if ((DOWNLOAD_ENABLED == 'true') && $_POST['products_attributes_filename'] != '') {
          $products_attributes_downloadtable = $oostable['products_attributes_download'];
          $dbconn->Execute("UPDATE $products_attributes_downloadtable
                        SET products_attributes_filename='" . $_POST['products_attributes_filename'] . "',
                            products_attributes_maxdays='" . $_POST['products_attributes_maxdays'] . "',
                            products_attributes_maxcount='" . $_POST['products_attributes_maxcount'] . "'
                        WHERE products_attributes_id = '" . $_POST['attribute_id'] . "'");
        }
        oos_redirect_admin(oos_href_link_admin($aFilename['products_attributes'], $page_info));
        break;

      case 'delete_attribute':
        $products_attributestable = $oostable['products_attributes'];
        $dbconn->Execute("DELETE FROM $products_attributestable WHERE products_attributes_id = '" . $_GET['attribute_id'] . "'");
        $products_attributes_downloadtable = $oostable['products_attributes_download'];
        $dbconn->Execute("DELETE FROM $products_attributes_downloadtable WHERE products_attributes_id = '" . $_GET['attribute_id'] . "'");
        oos_redirect_admin(oos_href_link_admin($aFilename['products_attributes'], $page_info));
        break;
    }
  }


  if (!isset($value_page)) $value_page = 1;
  if (!isset($attribute_page)) $attribute_page = 1;

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?> - Administration [OOS]</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function go_option() {
  if (document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value != "none") {
    location = "<?php echo oos_href_link_admin($aFilename['products_attributes'], 'option_page=' . ($_GET['option_page'] ? $_GET['option_page'] : 1)); ?>&option_order_by="+document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value;
  }
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<?php
  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }
?>
<script language="javascript" src="includes/menu.js"></script>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td></td>
    <td align="right"><?php echo '<a href="http://www.oos-shop.de/" target="_blank">' . oos_image(OOS_IMAGES . 'support.gif', HEADER_TITLE_SUPPORT_SITE, '50', '50') . '</a>&nbsp;&nbsp;<a href="' . oos_catalog_link($aCatalogFilename['default']) . '">' . oos_image(OOS_IMAGES . 'checkout.gif', HEADER_TITLE_ONLINE_CATALOG, '53', '50') . '</a>&nbsp;&nbsp;<a href="' . oos_href_link_admin($aFilename['default'], '', 'NONSSL') . '">' . oos_image(OOS_IMAGES . 'administration.gif', HEADER_TITLE_ADMINISTRATION, '50', '50') . '</a>'; ?>&nbsp;&nbsp;</td>
  </tr>
  <tr class="headerBar">
    <td class="headerBarContent">&nbsp;&nbsp;<?php if (isset($_SESSION['login_id'])) { echo '<a href="' . oos_href_link_admin($aFilename['admin_account'], '', 'SSL') . '" class="headerLink">' . HEADER_TITLE_ACCOUNT . '</a> | <a href="' . oos_href_link_admin($aFilename['logoff'], '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_LOGOFF . '</a>'; } else { echo '<a href="' . oos_href_link_admin($aFilename['default'], '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_TOP . '</a>'; }?></td>
    <td class="headerBarContent" align="right"><?php echo '<a href="http://www.oos-shop.de/" class="headerLink">' . HEADER_TITLE_SUPPORT_SITE . '</a> &nbsp;|&nbsp; <a href="' . oos_catalog_link($aCatalogFilename['default']) . '" class="headerLink">' . HEADER_TITLE_ONLINE_CATALOG . '</a>&nbsp;|&nbsp; <a href="' . oos_href_link_admin($aFilename['default'], '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_ADMINISTRATION . '</a>'; ?>&nbsp;&nbsp;</td>
  </tr>
</table>

<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require 'includes/oos_blocks.php'; ?>
    </table></td>
<!-- body_text //-->

    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<!-- products_attributes //-->
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE_ATRIB; ?>&nbsp;</td>
            <td>&nbsp;<?php echo oos_image(OOS_IMAGES . 'trans.gif', '', '1', '53'); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
<?php
    if ($action == 'update_attribute') {
      $form_action = 'update_product_attribute';
    } else {
      $form_action = 'add_product_attributes';
    }
?>
        <td><form name="attributes" action="<?php echo oos_href_link_admin($aFilename['products_attributes'], 'action=' . $form_action . '&option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page); ?>" method="post"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="8" class="smallText">
<?php
  $per_page = MAX_ROW_LISTS_OPTIONS;
  $products_attributestable = $oostable['products_attributes'];
  $products_descriptiontable = $oostable['products_description'];
  $attributes = "SELECT pa.* FROM $products_attributestable pa left join $products_descriptiontable pd on pa.products_id = pd.products_id AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY pd.products_name";

  $prev_attribute_page = $attribute_page - 1;
  $next_attribute_page = $attribute_page + 1;

  $attribute_result = $dbconn->Execute($attributes);

  $attribute_page_start = ($per_page * $attribute_page) - $per_page;
  $num_rows = $attribute_result->RecordCount();

  if ($num_rows <= $per_page) {
     $num_pages = 1;
  } else if (($num_rows % $per_page) == 0) {
     $num_pages = ($num_rows / $per_page);
  } else {
     $num_pages = ($num_rows / $per_page) + 1;
  }
  $num_pages = (int) $num_pages;

  $attributes = $attributes . " LIMIT $attribute_page_start, $per_page";

  // Previous
  if ($prev_attribute_page) {
    echo '<a href="' . oos_href_link_admin($aFilename['products_attributes'], 'attribute_page=' . $prev_attribute_page) . '"> &lt;&lt; </a> | ';
  }

  for ($i = 1; $i <= $num_pages; $i++) {
    if ($i != $attribute_page) {
      echo '<a href="' . oos_href_link_admin($aFilename['products_attributes'], 'attribute_page=' . $i) . '">' . $i . '</a> | ';
    } else {
      echo '<b><font color="red">' . $i . '</font></b> | ';
    }
  }

  // Next
  if ($attribute_page != $num_pages) {
    echo '<a href="' . oos_href_link_admin($aFilename['products_attributes'], 'attribute_page=' . $next_attribute_page) . '"> &gt;&gt; </a>';
  }
?>
            </td>
          </tr>
          <tr>
            <td colspan="8"><?php echo oos_black_line(); ?></td>
          </tr>
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_SORT_ORDER_VALUE; ?>&nbsp;</td>
            <td class="dataTableHeadingContent" align="right">&nbsp;<?php echo TABLE_HEADING_OPT_PRICE; ?>&nbsp;</td>
            <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_OPT_PRICE_PREFIX; ?>&nbsp;</td>
            <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="8"><?php echo oos_black_line(); ?></td>
          </tr>
<?php
  $next_id = 1;
  $rows = 0;
  $attributes = $dbconn->Execute($attributes);
  while ($attributes_values = $attributes->fields) {
    $products_name_only = oos_get_products_name($attributes_values['products_id']);
    $options_name = oos_options_name($attributes_values['options_id']);
    $values_name = oos_values_name($attributes_values['options_values_id']);
    $rows++;
?>
          <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
    if (($action == 'update_attribute') && ($_GET['attribute_id'] == $attributes_values['products_attributes_id'])) {
?>
            <td class="smallText">&nbsp;<?php echo $attributes_values['products_attributes_id']; ?><input type="hidden" name="attribute_id" value="<?php echo $attributes_values['products_attributes_id']; ?>">&nbsp;</td>
            <td class="smallText">&nbsp;<select name="products_id">
<?php
      $productstable = $oostable['products'];
      $products_descriptiontable = $oostable['products_description'];
      $products = $dbconn->Execute("SELECT p.products_id, pd.products_name FROM $productstable p, $products_descriptiontable pd WHERE pd.products_id = p.products_id AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY pd.products_name");
      while($products_values = $products->fields) {
        if ($attributes_values['products_id'] == $products_values['products_id']) {
          echo "\n" . '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '" selected="selected">' . $products_values['products_name'] . '</option>';
        } else {
          echo "\n" . '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';
        }

        // Move that ADOdb pointer!
        $products->MoveNext();
      }

      // Close result set
      $products->Close();
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="options_id">
<?php
      $products_optionstable = $oostable['products_options'];
      $options = $dbconn->Execute("SELECT * FROM $products_optionstable WHERE products_options_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_options_name");
      while($options_values = $options->fields) {
        if ($attributes_values['options_id'] == $options_values['products_options_id']) {
          echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '" selected="selected">' . $options_values['products_options_name'] . '</option>';
        } else {
          echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
        }

        // Move that ADOdb pointer!
        $options->MoveNext();
      }

      // Close result set
      $options->Close();
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="values_id">
<?php
      $products_options_valuestable = $oostable['products_options_values'];
      $values = $dbconn->Execute("SELECT * FROM $products_options_valuestable WHERE products_options_values_languages_id='" . intval($_SESSION['language_id']) . "' ORDER BY products_options_values_name");
      while($values_values = $values->fields) {
        if ($attributes_values['options_values_id'] == $values_values['products_options_values_id']) {
          echo "\n" . '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '" selected="selected">' . $values_values['products_options_values_name'] . '</option>';
        } else {
          echo "\n" . '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '">' . $values_values['products_options_values_name'] . '</option>';
        }

         // Move that ADOdb pointer!
        $values->MoveNext();
      }

      // Close result set
      $values->Close();
?>
            </select>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<input type="text" name="sort_order" value="<?php echo $attributes_values['options_sort_order']; ?>" size="4">&nbsp;</td>

<?php
      $in_price= $attributes_values['options_values_price'];
      if (OOS_PRICE_IS_BRUTTO == 'true') {
        $in_price_netto = round($in_price, TAX_DECIMAL_PLACES);
        $tax_ratestable = $oostable['tax_rates'];
        $productstable = $oostable['products'];
        $sql = "SELECT tr.tax_rate  FROM  $tax_ratestable tr,  $productstable p  WHERE  tr.tax_class_id = p.products_tax_class_id  AND  p.products_id = '". $attributes_values['products_id'] . "'";
        $tax_result = $dbconn->Execute($sql);
        $tax = $tax_result->fields;
        $in_price= ($in_price*($tax[tax_rate]+100)/100);  
      }
      $in_price = round ($in_price,TAX_DECIMAL_PLACES);
?>
            <td align="right" class="smallText">&nbsp;<input type="text" name="value_price" value="<?php echo $in_price; ?>" size="6">
<?php
      if (OOS_PRICE_IS_BRUTTO == 'true') echo " - " . TEXT_TAX_INFO . $in_price_netto;
      echo '&nbsp;</td>';
?>
            <td align="center" class="smallText">&nbsp;<input type="text" name="price_prefix" value="<?php echo $attributes_values['price_prefix']; ?>" size="2">&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo oos_image_swap_submits('update', 'update_off.gif', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . oos_href_link_admin($aFilename['products_attributes'], '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo oos_image_swap_button('cancel', 'cancel_off.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>
<?php
      if (DOWNLOAD_ENABLED == 'true') {
        $products_attributes_downloadtable = $oostable['products_attributes_download'];
        $download_result_raw ="SELECT products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount 
                              FROM $products_attributes_downloadtable
                              WHERE products_attributes_id = '" . $attributes_values['products_attributes_id'] . "'";
        $download_result = $dbconn->Execute($download_result_raw);
        if ($download_result->RecordCount() > 0) {
          $download = $download_result->fields;
          $products_attributes_filename = $download['products_attributes_filename'];
          $products_attributes_maxdays  = $download['products_attributes_maxdays'];
          $products_attributes_maxcount = $download['products_attributes_maxcount'];
        }
?>
          <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
            <td>&nbsp;</td>
            <td colspan="5">
              <table>
                <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DOWNLOAD; ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_FILENAME; ?></td>
                  <td class="smallText"><?php echo oos_draw_input_field('products_attributes_filename', $products_attributes_filename, 'size="15"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_DAYS; ?></td>
                  <td class="smallText"><?php echo oos_draw_input_field('products_attributes_maxdays', $products_attributes_maxdays, 'size="5"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_COUNT; ?></td>
                  <td class="smallText"><?php echo oos_draw_input_field('products_attributes_maxcount', $products_attributes_maxcount, 'size="5"'); ?>&nbsp;</td>
                </tr>
              </table>
            </td>
            <td>&nbsp;</td>
          </tr>
<?php
      }
    } elseif (($action == 'delete_product_attribute') && ($_GET['attribute_id'] == $attributes_values['products_attributes_id'])) {
?>
            <td class="smallText">&nbsp;<b><?php echo $attributes_values["products_attributes_id"]; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $products_name_only; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $options_name; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $values_name; ?></b>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<b><?php echo $attributes_values["options_sort_order"]; ?></td>
            <td align="right" class="smallText">&nbsp;<b><?php echo $attributes_values["options_values_price"]; ?></b>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<b><?php echo $attributes_values["price_prefix"]; ?></b>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<b><?php echo '<a href="' . oos_href_link_admin($aFilename['products_attributes'], 'action=delete_attribute&attribute_id=' . $_GET['attribute_id']) . '">'; ?><?php echo oos_image_swap_button('confirm', 'confirm_off.gif', IMAGE_CONFIRM); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . oos_href_link_admin($aFilename['products_attributes'], '&option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo oos_image_swap_button('cancel', 'cancel_off.gif', IMAGE_CANCEL); ?></a>&nbsp;</b></td>
<?php
    } else {
?>
            <td class="smallText">&nbsp;<?php echo $attributes_values["products_attributes_id"]; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $products_name_only; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $options_name; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $values_name; ?>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<b><?php echo $attributes_values["options_sort_order"]; ?></td>
<?php
      $in_price= $attributes_values['options_values_price'];
      if (OOS_PRICE_IS_BRUTTO == 'true') {
        $in_price_netto = round($in_price,TAX_DECIMAL_PLACES);
        $tax_ratestable = $oostable['tax_rates'];
        $productstable = $oostable['products'];
        $sql = "SELECT tr.tax_rate FROM $tax_ratestable tr, $productstable p  WHERE tr.tax_class_id = p.products_tax_class_id  AND p.products_id = '". $attributes_values['products_id'] . "' ";
        $tax_result = $dbconn->Execute($sql);
        $tax = $tax_result->fields;
        $in_price = ($in_price*($tax[tax_rate]+100)/100); 
      }
      $in_price= round($in_price,TAX_DECIMAL_PLACES);
?>
            <td align="right" class="smallText">&nbsp;
<?php
        echo $in_price;
        if (OOS_PRICE_IS_BRUTTO == 'true') echo " - ". TEXT_TAX_INFO . $in_price_netto;
?>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo $attributes_values["price_prefix"]; ?>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . oos_href_link_admin($aFilename['products_attributes'], 'action=update_attribute&attribute_id=' . $attributes_values['products_attributes_id'] . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo oos_image_swap_button('edit', 'edit_off.gif', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . oos_href_link_admin($aFilename['products_attributes'], 'action=delete_product_attribute&attribute_id=' . $attributes_values['products_attributes_id'] . '&attribute_page=' . $attribute_page, 'NONSSL') , '">'; ?><?php echo oos_image_swap_button('delete', 'delete_off.gif', IMAGE_DELETE); ?></a>&nbsp;</td>
<?php
    }
    $products_attributestable = $oostable['products_attributes'];
    $max_attributes_id_result = $dbconn->Execute("SELECT max(products_attributes_id) + 1 as next_id FROM $products_attributestable");
    $max_attributes_id_values = $max_attributes_id_result->fields;
    $next_id = $max_attributes_id_values['next_id'];
?>
          </tr>
<?php
    // Move that ADOdb pointer!
    $attributes->MoveNext();
  }

  // Close result set
  $attributes->Close();

  if ($action != 'update_attribute') {
?>
          <tr>
            <td colspan="8"><?php echo oos_black_line(); ?></td>
          </tr>
          <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
            <td class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="products_id">
<?php
    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $products = $dbconn->Execute("SELECT p.products_id, pd.products_name FROM $productstable p, $products_descriptiontable pd WHERE pd.products_id = p.products_id AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY pd.products_name");
    while ($products_values = $products->fields) {
      echo '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';

      // Move that ADOdb pointer!
      $products->MoveNext();
    }
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="options_id">
<?php
    $products_optionstable = $oostable['products_options'];
    $options = $dbconn->Execute("SELECT * FROM $products_optionstable WHERE products_options_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_options_name");
    while ($options_values = $options->fields) {
      echo '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';

      // Move that ADOdb pointer!
      $options->MoveNext();
    }

    // Close result set
    $options->Close();

?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="values_id">
<?php
    $products_options_valuestable = $oostable['products_options_values'];
    $values = $dbconn->Execute("SELECT * FROM $products_options_valuestable WHERE products_options_values_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_options_values_name");
    while ($values_values = $values->fields) {
      echo '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '">' . $values_values['products_options_values_name'] . '</option>';

      // Move that ADOdb pointer!
      $values->MoveNext();
    }

    // Close result set
    $values->Close();
?>
            </select>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<input type="text" name="sort_order" value="<?php echo $attributes_values['options_sort_order']; ?>" size="4">&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<input type="text" name="value_price" size="6">&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<input type="text" name="price_prefix" size="2" value="+">&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo oos_image_swap_submits('insert', 'insert_off.gif', IMAGE_INSERT); ?>&nbsp;</td>
          </tr>
<?php
      if (DOWNLOAD_ENABLED == 'true') {
        $products_attributes_maxdays  = DOWNLOAD_MAX_DAYS;
        $products_attributes_maxcount = DOWNLOAD_MAX_COUNT;
?>
          <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
            <td>&nbsp;</td>
            <td colspan="6">
              <table>
                <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DOWNLOAD; ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_FILENAME; ?></td>
                  <td class="smallText"><?php echo oos_draw_input_field('products_attributes_filename', $products_attributes_filename, 'size="15"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_DAYS; ?></td>
                  <td class="smallText"><?php echo oos_draw_input_field('products_attributes_maxdays', $products_attributes_maxdays, 'size="5"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_COUNT; ?></td>
                  <td class="smallText"><?php echo oos_draw_input_field('products_attributes_maxcount', $products_attributes_maxcount, 'size="5"'); ?>&nbsp;</td>
                  <td class="smallText">&nbsp;</td>
                </tr>
              </table>
            </td>
            <td>&nbsp;</td>
          </tr>
<?php
      }
?>
<?php
  }
?>
          <tr>
            <td colspan="8"><?php echo oos_black_line(); ?></td>
          </tr>
        </table></form></td>
      </tr>
    </table></td>
<!-- products_attributes_eof //-->
  </tr>
</table>
<!-- body_text_eof //-->

<?php require 'includes/oos_footer.php'; ?>
<br />
</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>
