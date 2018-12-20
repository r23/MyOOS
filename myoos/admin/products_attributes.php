<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
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
  require 'includes/main.php';
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
      case 'add_product_options':
        for ($i = 0, $n = count($languages); $i < $n; $i ++) {
          $option_name = $_POST['option_name'];
          $option_type = $_POST['option_type'];

          $products_optionstable = $oostable['products_options'];
          $dbconn->Execute("INSERT INTO $products_optionstable (products_options_id, products_options_name, products_options_languages_id,products_options_type) VALUES ('" . $_POST['products_options_id'] . "', '" . $option_name[$languages[$i]['id']] . "', '" . $languages[$i]['id'] . "', '" . $option_type . "')");
        }
        switch ($option_type) {
          case PRODUCTS_OPTIONS_TYPE_TEXT:
          case PRODUCTS_OPTIONS_TYPE_FILE:

            $products_options_values_to_products_optionstable = $oostable['products_options_values_to_products_options'];
            $dbconn->Execute("INSERT INTO $products_options_values_to_products_optionstable (products_options_values_id, products_options_id) values ('" . PRODUCTS_OPTIONS_VALUES_TEXT_ID .  "', '" .  (int)$products_options_id .  "')");
            break;
        }
        oos_redirect_admin(oos_href_link_admin($aContents['products_attributes'], $page_info));
        break;

      case 'add_product_option_values':
        for ($i = 0, $n = count($languages); $i < $n; $i ++) {
          $value_name = $_POST['value_name'];

          $products_options_valuestable = $oostable['products_options_values'];
          $dbconn->Execute("INSERT INTO $products_options_valuestable (products_options_values_id, products_options_values_languages_id, products_options_values_name) VALUES ('" . $_POST['value_id'] . "', '" . $languages[$i]['id'] . "', '" . $value_name[$languages[$i]['id']] . "')");
        }

        $products_options_values_to_products_optionstable = $oostable['products_options_values_to_products_options'];
        $dbconn->Execute("INSERT INTO $products_options_values_to_products_optionstable (products_options_id, products_options_values_id) VALUES ('" . $_POST['option_id'] . "', '" . $_POST['value_id'] . "')");
        oos_redirect_admin(oos_href_link_admin($aContents['products_attributes'], $page_info));
        break;

      case 'add_product_attributes':
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
        oos_redirect_admin(oos_href_link_admin($aContents['products_attributes'], $page_info));
        break;

      case 'update_option_name':
        for ($i = 0, $n = count($languages); $i < $n; $i ++) {
          $option_name = $_POST['option_name'];
          $option_type = $_POST['option_type'];
          $products_optionstable = $oostable['products_options'];
          $dbconn->Execute("UPDATE $products_optionstable SET products_options_name = '" . $option_name[$languages[$i]['id']] . "', products_options_type = '" . $option_type . "' WHERE products_options_id = '" . $_POST['option_id'] . "' AND products_options_languages_id = '" . $languages[$i]['id'] . "'");
        }
        switch ($option_type) {
          case PRODUCTS_OPTIONS_TYPE_TEXT:
          case PRODUCTS_OPTIONS_TYPE_FILE:
            $products_options_values_to_products_optionstable = $oostable['products_options_values_to_products_options'];
            $dbconn->Execute("INSERT INTO $products_options_values_to_products_optionstable VALUES (NULL, '" . $_POST['option_id'] . "', '" . PRODUCTS_OPTIONS_VALUES_TEXT_ID . "')");
            break;
          default:
            $products_options_values_to_products_optionstable = $oostable['products_options_values_to_products_options'];
            $dbconn->Execute("DELETE FROM $products_options_values_to_products_optionstable WHERE products_options_values_id = '" . PRODUCTS_OPTIONS_VALUES_TEXT_ID . "'");
        }
        oos_redirect_admin(oos_href_link_admin($aContents['products_attributes'], $page_info));
        break;

      case 'update_value':
        for ($i = 0, $n = count($languages); $i < $n; $i ++) {
          $value_name = $_POST['value_name'];

          $products_options_valuestable = $oostable['products_options_values'];
          $dbconn->Execute("UPDATE $products_options_valuestable SET products_options_values_name = '" . $value_name[$languages[$i]['id']] . "' WHERE products_options_values_id = '" . $_POST['value_id'] . "' AND  products_options_values_languages_id= '" . $languages[$i]['id'] . "'");
        }

        $products_options_values_to_products_optionstable = $oostable['products_options_values_to_products_options'];
        // $dbconn->Execute("UPDATE $products_options_values_to_products_optionstable SET products_options_id = '" . $_POST['option_id'] . "', products_options_values_id = '" . $_POST['value_id'] . "'  WHERE products_options_values_to_products_options_id = '" . $_POST['value_id'] . "'");
        $dbconn->Execute("UPDATE $products_options_values_to_products_optionstable SET products_options_id = '" . $_POST['option_id'] . "' WHERE products_options_values_id = '" . $_POST['value_id'] . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['products_attributes'], $page_info));
        break;

      case 'update_product_attribute':

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
        oos_redirect_admin(oos_href_link_admin($aContents['products_attributes'], $page_info));
        break;
      case 'delete_option':
        $products_optionstable = $oostable['products_options'];
        $dbconn->Execute("DELETE FROM $products_optionstable WHERE products_options_id = '" . $_GET['option_id'] . "'");

        $products_options_values_to_products_optionstable = $oostable['products_options_values_to_products_options'];
        $dbconn->Execute("DELETE FROM $products_options_values_to_products_optionstable WHERE products_options_id = '" . (int)$option_id . "' AND products_options_values_id = '" . PRODUCTS_OPTIONS_VALUES_TEXT_ID . "'");
        oos_redirect_admin(oos_href_link_admin($aContents['products_attributes'], $page_info));
        break;
      case 'delete_value':
        $products_options_valuestable = $oostable['products_options_values'];
        $dbconn->Execute("DELETE FROM $products_options_valuestable WHERE products_options_values_id = '" . $_GET['value_id'] . "'");
        $products_options_values_to_products_optionstable = $oostable['products_options_values_to_products_options'];
        $dbconn->Execute("DELETE FROM $products_options_values_to_products_optionstable WHERE products_options_values_id = '" . $_GET['value_id'] . "'");
        oos_redirect_admin(oos_href_link_admin($aContents['products_attributes'], $page_info));
        break;
      case 'delete_attribute':
        $products_attributestable = $oostable['products_attributes'];
        $dbconn->Execute("DELETE FROM $products_attributestable WHERE products_attributes_id = '" . $_GET['attribute_id'] . "'");
        $products_attributes_downloadtable = $oostable['products_attributes_download'];
        $dbconn->Execute("DELETE FROM $products_attributes_downloadtable WHERE products_attributes_id = '" . $_GET['attribute_id'] . "'");
        oos_redirect_admin(oos_href_link_admin($aContents['products_attributes'], $page_info));
        break;
    }
  }


  $products_options_types_list = array();
  $products_options_typestable = $oostable['products_options_types'];
  $products_options_types_sql = "SELECT products_options_types_id, products_options_types_name 
                                 FROM $products_options_typestable
                                 WHERE products_options_types_languages_id = '" . intval($_SESSION['language_id']) . "' 
                                 ORDER BY products_options_types_id";
  $products_options_types_result = $dbconn->Execute($products_options_types_sql);
  while ($products_options_type_array = $products_options_types_result->fields) {
    $products_options_types_list[$products_options_type_array['products_options_types_id']] = $products_options_type_array['products_options_types_name'];

    // Move that ADOdb pointer!
    $products_options_types_result->MoveNext();
  }

  if (!isset($value_page)) $value_page = 1;
  if (!isset($attribute_page)) $attribute_page = 1;

  
  require 'includes/header.php';
?>
<script language="javascript"><!--
function go_option() {
  if (document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value != "none") {
    location = "<?php echo oos_href_link_admin($aContents['products_attributes'], 'option_page=' . ($_GET['option_page'] ? $_GET['option_page'] : 1)); ?>&option_order_by="+document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value;
  }
}
//--></script>

<!-- body //-->
<div class="wrapper">
	<!-- Header //-->
	<header class="topnavbar-wrapper">
		<!-- Top Navbar //-->
		<?php require 'includes/menue.php'; ?>
	</header>
	<!-- END Header //-->
	<aside class="aside">
		<!-- Sidebar //-->
		<div class="aside-inner">
			<?php require 'includes/blocks.php'; ?>
		</div>
		<!-- END Sidebar (left) //-->
	</aside>
	
	<!-- Main section //-->
	<section>
		<!-- Page content //-->
		<div class="content-wrapper">
		
				<div class="row">
					<div class="col-lg-12">
<!-- body_text //-->

    <table border="0" width="100%" cellspacing="0" cellpadding="0">
<!-- options and values//-->
      <tr>
        <td width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
<!-- options //-->
<?php
  if ($action == 'delete_product_option') { // delete product option
    $products_optionstable = $oostable['products_options'];
    $options = $dbconn->Execute("SELECT products_options_id, products_options_name FROM $products_optionstable WHERE products_options_id = '" . $_GET['option_id'] . "' AND products_options_languages_id = '" . intval($_SESSION['language_id']) . "'");
    $options_values = $options->fields;
?>
              <tr>
                <td class="pageHeading">&nbsp;<?php echo $options_values['products_options_name']; ?>&nbsp;</td>
                <td>&nbsp;&nbsp;</td>
              </tr>
              <tr>
                <td>

				<table class="table table-striped w-100">	
<?php
    $productstable = $oostable['products'];
    $products_options_valuestable = $oostable['products_options_values'];
    $products_attributestable = $oostable['products_attributes'];
    $products_descriptiontable = $oostable['products_description'];
    $products = $dbconn->Execute("SELECT p.products_id, pd.products_name, pov.products_options_values_name FROM $productstable p, $products_options_valuestable pov, $products_attributestable pa, $products_descriptiontable pd WHERE pd.products_id = p.products_id AND pov.products_options_values_languages_id = '" . intval($_SESSION['language_id']) . "' AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND pa.products_id = p.products_id AND pa.options_id='" . $_GET['option_id'] . "' AND pov.products_options_values_id = pa.options_values_id ORDER BY pd.products_name");
    if ($products->RecordCount()) {
?>
					<thead class="thead-dark">
						<tr>	
							<th class="text-center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</th>
							<th>&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</th>
							<th>&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</th>
						</tr>
					</thead>
<?php
      $rows = 0;
      while ($products_values = $products->fields) {
        $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_name']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_options_values_name']; ?>&nbsp;</td>
                  </tr>
<?php
         // Move that ADOdb pointer!
        $products->MoveNext();
      }

      // Close result set
      $products->Close();
?>
                  <tr>
                    <td colspan="3"><?php echo oos_black_line(); ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="main"><br /><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="3" class="main"><br /><?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'value_page=' . $value_page . '&attribute_page=' . $attribute_page) . '">'; ?><?php echo oos_button('cancel', BUTTON_CANCEL); ?></a>&nbsp;</td>
                  </tr>
<?php
    } else {
?>
                  <tr>
                    <td class="main" colspan="3"><br /><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br /><?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'action=delete_option&option_id=' . $_GET['option_id']) . '">'; ?><?php echo oos_button('delete', BUTTON_DELETE); ?></a>&nbsp;&nbsp;&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], '&order_by=' . $order_by . '&page=' . $_GET['page']) . '">'; ?><?php echo oos_button('cancel', BUTTON_CANCEL); ?></a>&nbsp;</td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
<?php
  } else {
    if (isset($_GET['option_order_by'])) {
      $option_order_by = $_GET['option_order_by'];
    } else {
      $option_order_by = 'products_options_id';
    }
?>
              <tr>
                <td colspan="3" class="pageHeading">&nbsp;<?php echo HEADING_TITLE_OPT; ?>&nbsp;</td>
                <td class="text-right"><br /><form name="option_order_by" action="<?php echo $aContents['products_attributes']; ?>"><select name="selected" onChange="go_option()"><option value="products_options_id"<?php if ($option_order_by == 'products_options_id') { echo ' SELECTED'; } ?>><?php echo TEXT_OPTION_ID; ?></option><option value="products_options_name"<?php if ($option_order_by == 'products_options_name') { echo ' SELECTED'; } ?>><?php echo TEXT_OPTION_NAME; ?></option></select></form></td>
              </tr>
              <tr>
                <td colspan="4" class="smallText">
<?php
    $per_page = MAX_ROW_LISTS_OPTIONS;
    $products_optionstable = $oostable['products_options'];
    $options = "SELECT * FROM $products_optionstable WHERE products_options_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY " . $option_order_by;
    if (!isset($option_page)) {
      $option_page = 1;
    }
    $prev_option_page = $option_page - 1;
    $next_option_page = $option_page + 1;

    $option_result = $dbconn->Execute($options);

    $option_page_start = ($per_page * $option_page) - $per_page;
    $num_rows = $option_result->RecordCount();

    if ($num_rows <= $per_page) {
      $num_pages = 1;
    } elseif(($num_rows % $per_page) == 0) {
      $num_pages = ($num_rows / $per_page);
    } else {
      $num_pages = ($num_rows / $per_page) + 1;
    }
    $num_pages = (int) $num_pages;

    $options = $options . " LIMIT $option_page_start, $per_page";

    // Previous
    if ($prev_option_page)  {
      echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'option_page=' . $prev_option_page) . '"> &lt;&lt; </a> | ';
    }

    for ($i = 1; $i <= $num_pages; $i++) {
      if ($i != $option_page) {
        echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'option_page=' . $i) . '">' . $i . '</a> | ';
      } else {
        echo '<b><font color=red>' . $i . '</font></b> | ';
      }
    }

    // Next
    if ($option_page != $num_pages) {
      echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'option_page=' . $next_option_page) . '"> &gt;&gt; </a>';
    }
?>
                </td>
              </tr>
					<thead class="thead-dark">
						<tr>
							<th>&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</th>
							<th>&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</th>
							<th>&nbsp;<?php echo TABLE_HEADING_OPT_TYPE; ?>&nbsp;</th>
							<th class="text-center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>
<?php
    $next_id = 1;
    $rows = 0;
    $options = $dbconn->Execute($options);
    while ($options_values = $options->fields) {
      $rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      if (($action == 'update_option') && ($_GET['option_id'] == $options_values['products_options_id'])) {
        echo '<form name="option" action="' . oos_href_link_admin($aContents['products_attributes'], 'action=update_option_name') . '" method="post">';
        $inputs = '';
        for ($i = 0, $n = count($languages); $i < $n; $i ++) {
          $option_name = $dbconn->Execute("SELECT products_options_name FROM " . $oostable['products_options'] . " WHERE products_options_id = '" . $options_values['products_options_id'] . "' AND  products_options_languages_id = '" . $languages[$i]['id'] . "'");
          $option_name = $option_name->fields;
          $inputs .= $languages[$i]['id'] . ':&nbsp;<input type="text" name="option_name[' . $languages[$i]['id'] . ']" size="20" value="' . $option_name['products_options_name'] . '">&nbsp;<br />';
        }
?>
                <td align="center" class="smallText">&nbsp;<?php echo $options_values['products_options_id']; ?><input type="hidden" name="option_id" value="<?php echo $options_values['products_options_id']; ?>">&nbsp;</td>
                <td class="smallText"><?php echo $inputs; ?></td>
                <td class="smallText"><?php echo oos_draw_option_type_pull_down_menu('option_type', $options_values['products_options_type']); ?>&nbsp;</td>
                <td class="smallText"><?php echo oos_submit_button('update', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], '') . '">'; ?><?php echo oos_button('cancel', BUTTON_CANCEL); ?></a>&nbsp;</td>
<?php
        echo '</form>' . "\n";
      } else {
?>
                <td align="center" class="smallText">&nbsp;<?php echo $options_values['products_options_id']; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo $options_values['products_options_name']; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo oos_options_type_name($options_values['products_options_type']); ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'action=update_option&option_id=' . $options_values['products_options_id'] . '&option_order_by=' . $option_order_by . '&option_page=' . $option_page) . '">'; ?><?php echo oos_button('edit', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'action=delete_product_option&option_id=' . $options_values['products_options_id']) , '">'; ?><?php echo oos_button('delete', BUTTON_DELETE); ?></a>&nbsp;</td>
<?php
      }
?>
              </tr>
<?php
      $products_optionstable = $oostable['products_options'];
      $max_options_id_result = $dbconn->Execute("SELECT max(products_options_id) + 1 as next_id FROM $products_optionstable");
      $max_options_id_values = $max_options_id_result->fields;
      $next_id = $max_options_id_values['next_id'];

      // Move that ADOdb pointer!
      $options->MoveNext();
    }

    // Close result set
    $options->Close();
?>
              <tr>
                <td colspan="4"><?php echo oos_black_line(); ?></td>
              </tr>
<?php
    if ($action != 'update_option') {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      echo '<form name="options" action="' . oos_href_link_admin($aContents['products_attributes'], 'action=add_product_options&option_page=' . $option_page) . '" method="post"><input type="hidden" name="products_options_id" value="' . $next_id . '">';
      $inputs = '';
      for ($i = 0, $n = count($languages); $i < $n; $i ++) {
        $inputs .= $languages[$i]['id'] . ':&nbsp;<input type="text" name="option_name[' . $languages[$i]['id'] . ']" size="20">&nbsp;<br />';
      }
?>
                <td align="center" class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
                <td class="smallText"><?php echo $inputs; ?></td>
                <td class="smallText"><?php echo oos_draw_option_type_pull_down_menu('option_type'); ?></td>
                <td class="smallText">&nbsp;<?php echo oos_submit_button('insert', BUTTON_INSERT); ?>&nbsp;</td>
<?php
      echo '</form>';
?>
              </tr>
              <tr>
                <td colspan="4"><?php echo oos_black_line(); ?></td>
              </tr>
<?php
    }
  }
?>
            </table></td>
<!-- options eof //-->
            <td valign="top" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
<!-- value //-->
<?php
  if ($action == 'delete_option_value') { // delete product option value
    $products_options_valuestable = $oostable['products_options_values'];
    $values = $dbconn->Execute("SELECT products_options_values_id, products_options_values_name FROM $products_options_valuestable WHERE products_options_values_id = '" . $_GET['value_id'] . "' AND products_options_values_languages_id = '" . intval($_SESSION['language_id']) . "'");
    $values_values = $values->fields;
?>
              <tr>
                <td colspan="3" class="pageHeading">&nbsp;<?php echo $values_values['products_options_values_name']; ?>&nbsp;</td>
                <td>&nbsp;&nbsp;</td>
              </tr>
              <tr>
                <td>
				
				<table class="table table-striped w-100">			
<?php
    $productstable = $oostable['products'];
    $products_attributestable = $oostable['products_attributes'];
    $products_optionstable = $oostable['products_options'];
    $products_descriptiontable = $oostable['products_description'];
    $products = $dbconn->Execute("SELECT p.products_id, pd.products_name, po.products_options_name FROM $productstable p, $products_attributestable pa, $products_optionstable po, $products_descriptiontable pd WHERE pd.products_id = p.products_id AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND po.products_options_languages_id = '" . intval($_SESSION['language_id']) . "' AND pa.products_id = p.products_id AND pa.options_values_id='" . $_GET['value_id'] . "' AND po.products_options_id = pa.options_id ORDER BY pd.products_name");
    if ($products->RecordCount()) {
?>
					<thead class="thead-dark">
						<tr>
							<th class="text-center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</th>
							<th>&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</th>
							<th>&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</th>
						</tr>	
					</thead>
<?php
      $rows = 0;
      while ($products_values = $products->fields) {
        $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_name']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_options_name']; ?>&nbsp;</td>
                  </tr>
<?php
        // Move that ADOdb pointer!
        $products->MoveNext();
      }

      // Close result set
      $products->Close();
?>
                  <tr>
                    <td colspan="3"><?php echo oos_black_line(); ?></td>
                  </tr>
                  <tr>
                    <td class="main" colspan="3"><br /><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br /><?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'value_page=' . $value_page . '&attribute_page=' . $attribute_page) . '">'; ?><?php echo oos_button('cancel', BUTTON_CANCEL); ?></a>&nbsp;</td>
                  </tr>
<?php
    } else {
?>
                  <tr>
                    <td class="main" colspan="3"><br /><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br /><?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'action=delete_value&value_id=' . $_GET['value_id']) . '">'; ?><?php echo oos_button('delete', BUTTON_DELETE); ?></a>&nbsp;&nbsp;&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], '&option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page) . '">'; ?><?php echo oos_button('cancel', BUTTON_CANCEL); ?></a>&nbsp;</td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
<?php
  } else {
?>
              <tr>
                <td colspan="3" class="pageHeading">&nbsp;<?php echo HEADING_TITLE_VAL; ?>&nbsp;</td>
                <td>&nbsp;&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4" class="smallText">
<?php
    $per_page = MAX_ROW_LISTS_OPTIONS;
    $products_options_valuestable = $oostable['products_options_values'];
    $products_options_values_to_products_optionstable = $oostable['products_options_values_to_products_options'];
    $values = "SELECT pov.products_options_values_id, pov.products_options_values_name, pov2po.products_options_id FROM $products_options_valuestable pov left join $products_options_values_to_products_optionstable pov2po on pov.products_options_values_id = pov2po.products_options_values_id WHERE pov.products_options_values_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY pov.products_options_values_id";

    if (!isset($value_page)) {
      $value_page = 1;
    }
    $prev_value_page = $value_page - 1;
    $next_value_page = $value_page + 1;

    $value_result = $dbconn->Execute($values);

    $value_page_start = ($per_page * $value_page) - $per_page;
    $num_rows = $value_result->RecordCount();

    if ($num_rows <= $per_page) {
      $num_pages = 1;
    } elseif(($num_rows % $per_page) == 0) {
      $num_pages = ($num_rows / $per_page);
    } else {
      $num_pages = ($num_rows / $per_page) + 1;
    }
    $num_pages = (int) $num_pages;

    $values = $values . " LIMIT $value_page_start, $per_page";

    // Previous
    if ($prev_value_page)  {
      echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'option_order_by=' . $option_order_by . '&value_page=' . $prev_value_page) . '"> &lt;&lt; </a> | ';
    }

    for ($i = 1; $i <= $num_pages; $i++) {
      if ($i != $value_page) {
         echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'option_order_by=' . $option_order_by . '&value_page=' . $i) . '">' . $i . '</a> | ';
      } else {
         echo '<b><font color=red>' . $i . '</font></b> | ';
      }
    }

    // Next
    if ($value_page != $num_pages) {
      echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'option_order_by=' . $option_order_by . '&value_page=' . $next_value_page) . '"> &gt;&gt;</a> ';
    }
?>
                </td>
              </tr>
			  
					<thead class="thead-dark">
						<tr>
							<th>&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</th>
							<th>&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</th>
							<th>&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</th>
							<th class="text-center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>
<?php
    $next_id = 1;
    $rows = 0;
    $values = $dbconn->Execute($values);
    while ($values_values = $values->fields) {
      $options_name = oos_options_name($values_values['products_options_id']);
      $option_id = $values_values['products_options_id'];
      $values_name = $values_values['products_options_values_name'];
      $rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      if (($action == 'update_option_value') && ($_GET['value_id'] == $values_values['products_options_values_id'])) {
        echo '<form name="values" action="' . oos_href_link_admin($aContents['products_attributes'], 'action=update_value') . '" method="post">';
        $inputs = '';
        for ($i = 0, $n = count($languages); $i < $n; $i ++) {
          $products_options_valuestable = $oostable['products_options_values'];
          $value_name = $dbconn->Execute("SELECT products_options_values_name FROM $products_options_valuestable WHERE products_options_values_id = '" . $values_values['products_options_values_id'] . "' AND products_options_values_languages_id= '" . $languages[$i]['id'] . "'");
          $value_name = $value_name->fields;
          $inputs .= $languages[$i]['id'] . ':&nbsp;<input type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15" value="' . $value_name['products_options_values_name'] . '">&nbsp;<br />';
        }
?>
                <td align="center" class="smallText">&nbsp;<?php echo $values_values['products_options_values_id']; ?><input type="hidden" name="value_id" value="<?php echo $values_values['products_options_values_id']; ?>">&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo "\n"; ?><select name="option_id">
<?php
        $products_optionstable = $oostable['products_options'];
        $options = $dbconn->Execute("SELECT products_options_id, products_options_name FROM $products_optionstable WHERE products_options_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_options_name");
        while ($options_values = $options->fields) {
          echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '"';
          if ($values_values['products_options_id'] == $options_values['products_options_id']) { 
            echo ' selected';
          }
          echo '>' . $options_values['products_options_name'] . '</option>';

          // Move that ADOdb pointer!
          $options->MoveNext();
        }

        // Close result set
        $options->Close();
?>
                </select>&nbsp;</td>
                <td class="smallText"><?php echo $inputs; ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo oos_submit_button('update', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], '') . '">'; ?><?php echo oos_button('cancel', BUTTON_CANCEL); ?></a>&nbsp;</td>
<?php
        echo '</form>';
      } else {
?>
                <td align="center" class="smallText">&nbsp;<?php echo $values_values["products_options_values_id"]; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo $options_name; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo $values_name; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'action=update_option_value&value_id=' . $values_values['products_options_values_id'] . '&value_page=' . $value_page) . '">'; ?><?php echo oos_button('edit', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'action=delete_option_value&value_id=' . $values_values['products_options_values_id'] . '&option_id=' . $option_id) , '">'; ?><?php echo oos_button('delete', BUTTON_DELETE); ?></a>&nbsp;</td>

<?php
      }
      $products_options_valuestable = $oostable['products_options_values'];
      $max_values_id_result = $dbconn->Execute("SELECT max(products_options_values_id) + 1 as next_id FROM $products_options_valuestable");
      $max_values_id_values = $max_values_id_result->fields;
      $next_id = $max_values_id_values['next_id'];

      // Move that ADOdb pointer!
      $values->MoveNext();
    }

?>
              </tr>
              <tr>
                <td colspan="4"><?php echo oos_black_line(); ?></td>
              </tr>
<?php
    if ($action != 'update_option_value') {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      echo '<form name="values" action="' . oos_href_link_admin($aContents['products_attributes'], 'action=add_product_option_values&value_page=' . $value_page) . '" method="post">';
?>
                <td align="center" class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<select name="option_id">
<?php
      $products_optionstable = $oostable['products_options'];
      $options = $dbconn->Execute("SELECT products_options_id, products_options_name FROM $products_optionstable WHERE products_options_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_options_name");
      while ($options_values = $options->fields) {
        echo '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';

        // Move that ADOdb pointer!
        $options->MoveNext();
      }

      // Close result set
      $options->Close();

      $inputs = '';
      for ($i = 0, $n = count($languages); $i < $n; $i ++) {
        $inputs .= $languages[$i]['id'] . ':&nbsp;<input type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15">&nbsp;<br />';
      }
?>
                </select>&nbsp;</td>
                <td class="smallText"><input type="hidden" name="value_id" value="<?php echo $next_id; ?>"><?php echo $inputs; ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo oos_submit_button('insert', BUTTON_INSERT); ?>&nbsp;</td>
<?php
      echo '</form>';
?>
              </tr>
              <tr>
                <td colspan="4"><?php echo oos_black_line(); ?></td>
              </tr>
<?php
    }
  }
?>
            </table></td>
          </tr>
        </table></td>
<!-- option value eof //-->
      </tr> 
<!-- products_attributes //-->
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE_ATRIB; ?>&nbsp;</td>
            <td>&nbsp;&nbsp;</td>
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
        <td><form name="attributes" action="<?php echo oos_href_link_admin($aContents['products_attributes'], 'action=' . $form_action . '&option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page); ?>" method="post"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="8" class="smallText">
<?php
  $per_page = MAX_ROW_LISTS_OPTIONS;
  $products_attributestable = $oostable['products_attributes'];
  $products_descriptiontable = $oostable['products_description'];
  $attributes = "SELECT pa.* FROM $products_attributestable pa left join $products_descriptiontable pd on pa.products_id = pd.products_id AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY pd.products_name";

  if (!isset($attribute_page)) $attribute_page=1;
  $prev_attribute_page = $attribute_page - 1;
  $next_attribute_page = $attribute_page + 1;

  $attribute_result = $dbconn->Execute($attributes);

  $attribute_page_start = ($per_page * $attribute_page) - $per_page;
  $num_rows = $attribute_result->RecordCount();

  if ($num_rows <= $per_page) {
     $num_pages = 1;
  } elseif(($num_rows % $per_page) == 0) {
     $num_pages = ($num_rows / $per_page);
  } else {
     $num_pages = ($num_rows / $per_page) + 1;
  }
  $num_pages = (int) $num_pages;

  $attributes = $attributes . " LIMIT $attribute_page_start, $per_page";

  // Previous
  if ($prev_attribute_page) {
    echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'attribute_page=' . $prev_attribute_page) . '"> &lt;&lt; </a> | ';
  }

  for ($i = 1; $i <= $num_pages; $i++) {
    if ($i != $attribute_page) {
      echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'attribute_page=' . $i) . '">' . $i . '</a> | ';
    } else {
      echo '<b><font color="red">' . $i . '</font></b> | ';
    }
  }

  // Next
  if ($attribute_page != $num_pages) {
    echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'attribute_page=' . $next_attribute_page) . '"> &gt;&gt; </a>';
  }
?>
            </td>
          </tr>  
					<thead class="thead-dark">
						<tr>
							<th>&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</th>
							<th>&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</th>
							<th>&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</th>
							<th>&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</th>
							<th>&nbsp;<?php echo TABLE_HEADING_SORT_ORDER_VALUE; ?>&nbsp;</th>
							<th class="text-right">&nbsp;<?php echo TABLE_HEADING_OPT_PRICE; ?>&nbsp;</th>
							<th class="text-center">&nbsp;<?php echo TABLE_HEADING_OPT_PRICE_PREFIX; ?>&nbsp;</th>
							<th class="text-center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>
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
      $in_price = $attributes_values['options_values_price'];
      $in_price = round ($in_price,TAX_DECIMAL_PLACES);
?>
            <td align="right" class="smallText">&nbsp;<input type="text" name="value_price" value="<?php echo $in_price; ?>" size="6"><?php echo $in_price; ?>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<input type="text" name="price_prefix" value="<?php echo $attributes_values['price_prefix']; ?>" size="2">&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo oos_submit_button('update', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], '&attribute_page=' . $attribute_page) . '">'; ?><?php echo oos_button('cancel', BUTTON_CANCEL); ?></a>&nbsp;</td>
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
                  <td><?php echo TABLE_HEADING_DOWNLOAD; ?>&nbsp;</td>
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
            <td align="center" class="smallText">&nbsp;<b><?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'action=delete_attribute&attribute_id=' . $_GET['attribute_id']) . '">'; ?><?php echo oos_button('confirm', IMAGE_CONFIRM); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], '&option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page) . '">'; ?><?php echo oos_button('cancel', BUTTON_CANCEL); ?></a>&nbsp;</b></td>
<?php
    } else {
?>
            <td class="smallText">&nbsp;<?php echo $attributes_values["products_attributes_id"]; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $products_name_only; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $options_name; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $values_name; ?>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<b><?php echo $attributes_values["options_sort_order"]; ?></td>
<?php
      $in_price = $attributes_values['options_values_price'];
      $in_price = round($in_price,TAX_DECIMAL_PLACES);
?>
            <td align="right" class="smallText">&nbsp;
<?php 
        echo $in_price;
?>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo $attributes_values["price_prefix"]; ?>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'action=update_attribute&attribute_id=' . $attributes_values['products_attributes_id'] . '&attribute_page=' . $attribute_page) . '">'; ?><?php echo oos_button('edit', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['products_attributes'], 'action=delete_product_attribute&attribute_id=' . $attributes_values['products_attributes_id'] . '&attribute_page=' . $attribute_page) , '">'; ?><?php echo oos_button('delete', BUTTON_DELETE); ?></a>&nbsp;</td>
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
            <td align="center" class="smallText">&nbsp;<?php echo oos_submit_button('insert', BUTTON_INSERT); ?>&nbsp;</td>
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
                  <td><?php echo TABLE_HEADING_DOWNLOAD; ?>&nbsp;</td>
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
      } // end of DOWNLOAD_ENABLED section
?>
<?php
  }
?>
          <tr>
            <td colspan="8"><?php echo oos_black_line(); ?></td>
          </tr>
        </table></form></td>
      </tr>
    </table>
<!-- products_attributes_eof //-->
				</div>
			</div>
        </div>

		</div>
	</section>
	<!-- Page footer //-->
	<footer>
		<span>&copy; 2018 - <a href="https://www.oos-shop.de" target="_blank" rel="noopener">MyOOS [Shopsystem]</a></span>
	</footer>
</div>

<?php 
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>
