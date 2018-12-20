<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: configuration.php,v 1.40 2002/12/29 16:55:01 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/main.php';
  require 'includes/functions/function_modules.php';

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'save':
        $configuration_value = oos_db_prepare_input($_POST['configuration_value']);
        $cID = oos_db_prepare_input($_GET['cID']);

        $dbconn->Execute("UPDATE " . $oostable['configuration'] . " SET configuration_value = '" . oos_db_input($configuration_value) . "', last_modified = now() WHERE configuration_id = '" . oos_db_input($cID) . "'");
        oos_redirect_admin(oos_href_link_admin($aContents['configuration'], 'gID=' . $_GET['gID'] . '&cID=' . $cID));
        break;
    }
  }
  
  $sHeaderTitle = constant(strtoupper((int)$_GET['gID'] . '_TITLE')); 
  define('HEADING_TITLE', $sHeaderTitle);
  
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
					<h2><?php echo $sHeaderTitle; ?></h2>
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
						</li>
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['configuration'], 'selected_box=configuration&gID=1') . '">' . BOX_HEADING_CONFIGURATION . '</a>'; ?>
						</li>
						<li class="breadcrumb-item active">
							<strong><?php echo $sHeaderTitle; ?></strong>
						</li>
					</ol>
				</div>
			</div>
			<!-- END Breadcrumbs //-->	
			
			<div class="row">
				<div class="col-lg-12">	
				
<!-- body_text //-->
	<div class="table-responsive">
		<table class="table w-100">
          <tr>
            <td valign="top">
				<table class="table table-striped table-hover w-100">
					<thead class="thead-dark">
						<tr>
							<th><?php echo TABLE_HEADING_CONFIGURATION_TITLE; ?></th>
							<th><?php echo TABLE_HEADING_CONFIGURATION_VALUE; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>
<?php
  $configuration_result = $dbconn->Execute("SELECT configuration_id, configuration_key, configuration_value, use_function FROM " . $oostable['configuration'] . " WHERE configuration_group_id = '" . $_GET['gID'] . "' ORDER BY sort_order");

  while ($configuration = $configuration_result->fields) {
    if (oos_is_not_null($configuration['use_function'])) {
      $use_function = $configuration['use_function'];
      if (preg_match('/->/', $use_function)) {
        $class_method = explode('->', $use_function);
        if (!is_object(${$class_method[0]})) {
          include 'includes/classes/class_'. $class_method[0] . '.php';
          ${$class_method[0]} = new $class_method[0]();
        }
        $cfgValue = oos_call_function($class_method[1], $configuration['configuration_value'], ${$class_method[0]});
      } else {
        $cfgValue = oos_call_function($use_function, $configuration['configuration_value']);
      }
    } else {
      $cfgValue = $configuration['configuration_value'];
    }

    if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $configuration['configuration_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $cfg_extra_result = $dbconn->Execute("SELECT configuration_key, date_added, last_modified, use_function, set_function FROM " . $oostable['configuration'] . " WHERE configuration_id = '" . $configuration['configuration_id'] . "'");
      $cfg_extra = $cfg_extra_result->fields;

      $cInfo_array = array_merge($configuration, $cfg_extra);
      $cInfo = new objectInfo($cInfo_array);
    }


    if (isset($cInfo) && is_object($cInfo) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) {
      echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['configuration'], 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['configuration'], 'gID=' . $_GET['gID'] . '&cID=' . $configuration['configuration_id']) . '\'">' . "\n";
    }
?>
                <td><?php echo constant(strtoupper($configuration['configuration_key'] . '_TITLE')); ?></td>
                <td><?php echo htmlspecialchars($cfgValue); ?></td>
                <td class="text-right"><?php if (isset($cInfo) && is_object($cInfo) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['configuration'], 'gID=' . $_GET['gID'] . '&cID=' . $configuration['configuration_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $configuration_result->MoveNext();
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'edit':
      $heading[] = array('text' => '<b>' . constant(strtoupper($cInfo->configuration_key . '_TITLE')) . '</b>');

      if ($cInfo->set_function) {
        eval('$value_field = ' . $cInfo->set_function . '"' . htmlspecialchars($cInfo->configuration_value) . '");');
      } else {
        $value_field = oos_draw_input_field('configuration_value', $cInfo->configuration_value);
      }


      $contents = array('form' => oos_draw_form('id', 'configuration', $aContents['configuration'], 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=save', 'post', FALSE));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br /><b>' . constant(strtoupper($cInfo->configuration_key . '_TITLE')) . '</b><br />' . constant(strtoupper($cInfo->configuration_key . '_DESC')) . '<br />' . $value_field);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('update', IMAGE_UPDATE) . '&nbsp;<a href="' . oos_href_link_admin($aContents['configuration'], 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

   default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . constant(strtoupper($cInfo->configuration_key . '_TITLE')) . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['configuration'], 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=edit') . '">' . oos_button('edit', BUTTON_EDIT) . '</a>');
        $contents[] = array('text' => '<br />' . constant(strtoupper($cInfo->configuration_key . '_DESC')));
        $contents[] = array('text' => '<br />' . TEXT_INFO_DATE_ADDED . ' ' . oos_date_short($cInfo->date_added));
        if (oos_is_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' ' . oos_date_short($cInfo->last_modified));
      }
      break;
  }


    if ( (oos_is_not_null($heading)) && (oos_is_not_null($contents)) ) {
?>
	<td class="w-25">
		<table class="table table-striped">
<?php
		$box = new box;
		echo $box->infoBox($heading, $contents);  
?>
		</table> 
	</td> 
<?php
  }
?>
          </tr>
        </table>
	</div>
<!-- body_text_eof //-->

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