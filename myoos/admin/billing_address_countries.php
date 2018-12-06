<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: countries.php,v 1.25 2002/03/17 17:34:47 harley_vb 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce

   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';
  
$nPage = (!isset($_GET['page']) || !is_numeric($_GET['page'])) ? 1 : intval($_GET['page']); 
$action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'insert':
        $dbconn->Execute("INSERT INTO " . $oostable['billing_address_countries'] . " (countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES ('" . oos_db_input($countries_name) . "', '" . oos_db_input($countries_iso_code_2) . "', '" . oos_db_input($countries_iso_code_3) . "', '" . oos_db_input($address_format_id) . "')");
        oos_redirect_admin(oos_href_link_admin($aContents['billing_address_countries']));
        break;

      case 'save':
        $countries_id = oos_db_prepare_input($_GET['cID']);

        $dbconn->Execute("UPDATE " . $oostable['billing_address_countries'] . " SET countries_name = '" . oos_db_input($countries_name) . "', countries_iso_code_2 = '" . oos_db_input($countries_iso_code_2) . "', countries_iso_code_3 = '" . oos_db_input($countries_iso_code_3) . "', address_format_id = '" . oos_db_input($address_format_id) . "' WHERE countries_id = '" . oos_db_input($countries_id) . "'");
        oos_redirect_admin(oos_href_link_admin($aContents['billing_address_countries'], 'page=' . $nPage . '&cID=' . $countries_id));
        break;

      case 'deleteconfirm':
        $countries_id = oos_db_prepare_input($_GET['cID']);

        $dbconn->Execute("DELETE FROM " . $oostable['billing_address_countries'] . " WHERE countries_id = '" . oos_db_input($countries_id) . "'");
        oos_redirect_admin(oos_href_link_admin($aContents['billing_address_countries'], 'page=' . $nPage));
        break;
    }
  }
  require 'includes/header.php'; 
?>
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

			<!-- Breadcrumbs //-->
			<div class="content-heading">
				<div class="col-lg-12">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="<?php echo oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
						</li>
						<li class="breadcrumb-item">
							<a href="<?php echo oos_href_link_admin($aContents['billing_address_countries'], 'selected_box=taxes') . '">' . BOX_HEADING_LOCATION_AND_TAXES . '</a>'; ?>
						</li>
						<li class="breadcrumb-item active">
							<strong><?php echo HEADING_TITLE; ?></strong>
						</li>
					</ol>
				</div>
			</div>
			<!-- END Breadcrumbs //-->
			
			<div class="wrapper wrapper-content">
				<div class="row">
					<div class="col-lg-12">				
<!-- body_text //-->
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_COUNTRY_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center" colspan="2"><?php echo TABLE_HEADING_COUNTRY_CODES; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $countries_result_raw = "SELECT countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id 
                          FROM " . $oostable['billing_address_countries'] . " 
                          ORDER BY countries_name";
  $countries_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $countries_result_raw, $countries_result_numrows);
  $countries_result = $dbconn->Execute($countries_result_raw);
  while ($countries = $countries_result->fields) {
    if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $countries['countries_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $cInfo = new objectInfo($countries);
    }

    if (isset($cInfo) && is_object($cInfo) && ($countries['countries_id'] == $cInfo->countries_id) ) {
      echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['billing_address_countries'], 'page=' . $nPage . '&cID=' . $cInfo->countries_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['billing_address_countries'], 'page=' . $nPage . '&cID=' . $countries['countries_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $countries['countries_name']; ?></td>
                <td class="dataTableContent" align="center" width="40"><?php echo $countries['countries_iso_code_2']; ?></td>
                <td class="dataTableContent" align="center" width="40"><?php echo $countries['countries_iso_code_3']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($countries['countries_id'] == $cInfo->countries_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['billing_address_countries'], 'page=' . $nPage . '&cID=' . $countries['countries_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $countries_result->MoveNext();
  }

?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $countries_split->display_count($countries_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></td>
                    <td class="smallText" align="right"><?php echo $countries_split->display_links($countries_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['billing_address_countries'], 'page=' . $nPage . '&action=new') . '">' . oos_button('new_country', IMAGE_NEW_COUNTRY) . '</a>'; ?></td>
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
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_COUNTRY . '</b>');

      $contents = array('form' => oos_draw_form('id', 'countries', $aContents['billing_address_countries'], 'page=' . $nPage . '&action=insert', 'post', FALSE));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_NAME . '<br />' . oos_draw_input_field('countries_name'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_CODE_2 . '<br />' . oos_draw_input_field('countries_iso_code_2'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_CODE_3 . '<br />' . oos_draw_input_field('countries_iso_code_3'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_ADDRESS_FORMAT . '<br />' . oos_draw_input_field('address_format_id'));
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('insert', BUTTON_INSERT) . '&nbsp;<a href="' . oos_href_link_admin($aContents['billing_address_countries'], 'page=' . $nPage) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_COUNTRY . '</b>');

      $contents = array('form' => oos_draw_form('id', 'countries', $aContents['billing_address_countries'], 'page=' . $nPage . '&cID=' . $cInfo->countries_id . '&action=save', 'post', FALSE));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_NAME . '<br />' . oos_draw_input_field('countries_name', $cInfo->countries_name));
      $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_CODE_2 . '<br />' . oos_draw_input_field('countries_iso_code_2', $cInfo->countries_iso_code_2));
      $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_CODE_3 . '<br />' . oos_draw_input_field('countries_iso_code_3', $cInfo->countries_iso_code_3));
      $contents[] = array('text' => '<br />' . TEXT_INFO_ADDRESS_FORMAT . '<br />' . oos_draw_input_field('address_format_id', $cInfo->address_format_id));
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('update', IMAGE_UPDATE) . '&nbsp;<a href="' . oos_href_link_admin($aContents['billing_address_countries'], 'page=' . $nPage . '&cID=' . $cInfo->countries_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_COUNTRY . '</b>');

      $contents = array('form' => oos_draw_form('id', 'countries', $aContents['billing_address_countries'], 'page=' . $nPage . '&cID=' . $cInfo->countries_id . '&action=deleteconfirm', 'post', FALSE));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $cInfo->countries_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('delete', IMAGE_UPDATE) . '&nbsp;<a href="' . oos_href_link_admin($aContents['billing_address_countries'], 'page=' . $nPage . '&cID=' . $cInfo->countries_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->countries_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['billing_address_countries'], 'page=' . $nPage . '&cID=' . $cInfo->countries_id . '&action=edit') . '">' . oos_button('edit', BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['billing_address_countries'], 'page=' . $nPage . '&cID=' . $cInfo->countries_id . '&action=delete') . '">' . oos_button('delete',  BUTTON_DELETE) . '</a>');
        $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_NAME . '<br />' . $cInfo->countries_name);
        $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_CODE_2 . ' ' . $cInfo->countries_iso_code_2);
        $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_CODE_3 . ' ' . $cInfo->countries_iso_code_3);
        $contents[] = array('text' => '<br />' . TEXT_INFO_ADDRESS_FORMAT . ' ' . $cInfo->address_format_id);
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
    </table>
<!-- body_text_eof //-->

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