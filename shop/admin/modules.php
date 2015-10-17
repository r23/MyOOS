<?php
/* ----------------------------------------------------------------------
   $Id: modules.php,v 1.1 2007/06/08 17:14:41 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: modules.php,v 1.44 2002/11/22 18:58:29 dgw_
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

  $set = (isset($_GET['set']) ? $_GET['set'] : '');

  if (oos_is_not_null($set)) {
    switch ($set) {
      case 'shipping':
        $module_type = 'shipping';
        $module_directory = OOS_ABSOLUTE_PATH . 'includes/modules/shipping/';
        $module_key = 'MODULE_SHIPPING_INSTALLED';
        define('HEADING_TITLE', HEADING_TITLE_MODULES_SHIPPING);
        break;

      case 'ordertotal':
        $module_type = 'order_total';
        $module_directory = OOS_ABSOLUTE_PATH . 'includes/modules/order_total/';
        $module_key = 'MODULE_ORDER_TOTAL_INSTALLED';
        define('HEADING_TITLE', HEADING_TITLE_MODULES_ORDER_TOTAL);
        break;

      case 'payment':
      default:
        $module_type = 'payment';
        $module_directory = OOS_ABSOLUTE_PATH . 'includes/modules/payment/';
        $module_key = 'MODULE_PAYMENT_INSTALLED';
        define('HEADING_TITLE', HEADING_TITLE_MODULES_PAYMENT);
        break;

    }
  }


  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'save':
        while (list($key, $value) = each($_POST['configuration'])) {
          $configurationtable = $oostable['configuration'];
          $dbconn->Execute("UPDATE $configurationtable SET configuration_value = '" . $value . "' WHERE configuration_key = '" . $key . "'");
        }
        oos_redirect_admin(oos_href_link_admin($aContents['modules'], 'set=' . $_GET['set'] . '&module=' . $_GET['module']));
        break;

      case 'install':
      case 'remove':
        $file_extension = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
        $class = basename($_GET['module']);
        if (file_exists($module_directory . $class . $file_extension)) {
          include $module_directory . $class . $file_extension;
          $module = new $class;
          if ($action == 'install') {
            $module->install();
          } elseif ($action == 'remove') {
            $module->remove();
          }
        }
        oos_redirect_admin(oos_href_link_admin($aContents['modules'], 'set=' . $_GET['set'] . '&module=' . $class));
        break;
    }
  }

  require 'includes/header.php';
?>
<div id="wrapper">
	<?php require 'includes/blocks.php'; ?>
		<div id="page-wrapper" class="gray-bg">
			<div class="row border-bottom">
			<?php require 'includes/menue.php'; ?>
			</div>

			<!-- Breadcrumbs  -->
			<div class="row wrapper white-bg page-heading">
				<div class="col-lg-10">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['default']); ?>">Home</a>
						</li>
						<li>
							<a href="<?php echo oos_href_link_admin(oos_selected_file('modules.php'), 'selected_box=modules&set=payment') . '">' . BOX_HEADING_MODULES . '</a>'; ?>
						</li>
						<li class="active">
							<strong><?php echo HEADING_TITLE; ?></strong>
						</li>
					</ol>
				</div>
				<div class="col-lg-2">

				</div>
			</div><!--/ End Breadcrumbs -->
			
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
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MODULES; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SORT_ORDER; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $file_extension = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
  $directory_array = array();
  if ($oDir = @dir($module_directory)) {
    while ($file = $oDir->read()) {
      if (!is_dir($module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $directory_array[] = $file;
        }
      }
    }
    sort($directory_array);
    $oDir->close();
  }

  $installed_modules = array();
  for ($i = 0, $n = count($directory_array); $i < $n; $i++) {
    $file = $directory_array[$i];

    include OOS_ABSOLUTE_PATH . 'includes/languages/' . $_SESSION['language'] . '/modules/' . $module_type . '/' . $file;
    include $module_directory . $file;

    $class = substr($file, 0, strrpos($file, '.'));
    if (oos_class_exits($class)) {
      $module = new $class;
      if ($module->check() > 0) {
        if ($module->sort_order > 0) {
          $installed_modules[$module->sort_order] = $file;
        } else {
          $installed_modules[] = $file;
        }
      }

      if ((!isset($_GET['module']) || (isset($_GET['module']) && ($_GET['module'] == $class))) && !isset($mInfo)) {
        $module_info = array('code' => $module->code,
                             'title' => $module->title,
                             'description' => $module->description,
                             'status' => $module->check());

        $module_keys = $module->keys();

        $keys_extra = array();
        for ($j = 0, $k = count($module_keys); $j < $k; $j++) {
          $key_value_result = $dbconn->Execute("SELECT configuration_value, use_function, set_function FROM " . $oostable['configuration'] . " WHERE configuration_key = '" . $module_keys[$j] . "'");
          $key_value = $key_value_result->fields;

          $keys_extra[$module_keys[$j]]['title'] = constant(strtoupper($module_keys[$j] . '_TITLE'));
          $keys_extra[$module_keys[$j]]['value'] = $key_value['configuration_value'];
          $keys_extra[$module_keys[$j]]['description'] = constant(strtoupper($module_keys[$j] . '_DESC'));
          $keys_extra[$module_keys[$j]]['use_function'] = $key_value['use_function'];
          $keys_extra[$module_keys[$j]]['set_function'] = $key_value['set_function'];
        }

        $module_info['keys'] = $keys_extra;

        $mInfo = new objectInfo($module_info);
      }
      if (isset($mInfo) && is_object($mInfo) && ($class == $mInfo->code) ) {
        if ($module->check() > 0) {
          echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['modules'], 'set=' . $_GET['set'] . '&module=' . $class . '&action=edit') . '\'">' . "\n";
        } else {
          echo '              <tr class="dataTableRowSelected">' . "\n";
        }
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['modules'], 'set=' . $_GET['set'] . '&module=' . $class) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $module->title; ?></td>
                <td class="dataTableContent" align="right"><?php if (is_numeric($module->sort_order)) echo $module->sort_order; ?></td>
                <td class="dataTableContent" align="right">
<?php
  if ($module->check() > 0) {
    echo '<a href="' . oos_href_link_admin($aContents['modules'], 'set=' . $_GET['set'] . '&module=' . $class . '&action=remove') . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
  } else {
    echo '<a href="' . oos_href_link_admin($aContents['modules'], 'set=' . $_GET['set'] . '&module=' . $class . '&action=install') . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
  }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($mInfo) && is_object($mInfo) && ($class == $mInfo->code) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['modules'], 'set=' . $_GET['set'] . '&module=' . $class) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
  }

  ksort($installed_modules);
  $configurationtable = $oostable['configuration'];
  $check_result = $dbconn->Execute("SELECT configuration_value FROM $configurationtable WHERE configuration_key = '" . $module_key . "'");
  if ($check_result->RecordCount()) {
    $check = $check_result->fields;
    if ($check['configuration_value'] != implode(';', $installed_modules)) {
      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("UPDATE $configurationtable SET configuration_value = '" . implode(';', $installed_modules) . "', last_modified = now() WHERE configuration_key = '" . $module_key . "'");
    }
  } else {
    $configurationtable = $oostable['configuration'];
    $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('" . $module_key . "', '" . implode(';', $installed_modules) . "', '6', '0', now())");
  }
?>
              <tr>
                <td colspan="4" class="smallText"><?php echo TEXT_MODULE_DIRECTORY . ' ' . $module_directory; ?></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'edit':
      $keys = '';
      reset($mInfo->keys);
      while (list($key, $value) = each($mInfo->keys)) {
        $keys .= '<b>' . $value['title'] . '</b><br />' . $value['description'] . '<br />';

        if ($value['set_function']) {
          eval('$keys .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
        } else {
          $keys .= oos_draw_input_field('configuration[' . $key . ']', $value['value']);
        }
        $keys .= '<br /><br />';
      }
      $keys = substr($keys, 0, strrpos($keys, '<br /><br />'));

      $heading[] = array('text' => '<b>' . $mInfo->title . '</b>');

      $contents = array('form' => oos_draw_form('modules', $aContents['modules'], 'set=' . $_GET['set'] . '&module=' . $_GET['module'] . '&action=save', 'post', FALSE));
      $contents[] = array('text' => $keys);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('update', IMAGE_UPDATE) . ' <a href="' . oos_href_link_admin($aContents['modules'], 'set=' . $_GET['set'] . '&module=' . $_GET['module']) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    default:
      $heading[] = array('text' => '<b>' . $mInfo->title . '</b>');

      if ($mInfo->status == '1') {
        $keys = '';
        reset($mInfo->keys);
        while (list(, $value) = each($mInfo->keys)) {
          $keys .= '<b>' . $value['title'] . '</b><br />';
          if ($value['use_function']) {
            $use_function = $value['use_function'];
            if (preg_match('/->/', $use_function)) {
              $class_method = explode('->', $use_function);
              if (!is_object(${$class_method[0]})) {
                include 'includes/classes/class_'. $class_method[0] . '.php';
                ${$class_method[0]} = new $class_method[0]();
              }
              $keys .= oos_call_function($class_method[1], $value['value'], ${$class_method[0]});
            } else {
              $keys .= oos_call_function($use_function, $value['value']);
            }
          } else {
            $keys .= $value['value'];
          }
          $keys .= '<br /><br />';
        }
        $keys = substr($keys, 0, strrpos($keys, '<br /><br />'));

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['modules'], 'set=' . $_GET['set'] . '&module=' . $_GET['module'] . '&action=edit') . '">' . oos_button('edit', BUTTON_EDIT) . '</a>');
        $contents[] = array('text' => '<br />' . $mInfo->description);
        $contents[] = array('text' => '<br />' . $keys);
      } else {
        $contents[] = array('text' => $mInfo->description);
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
</div>


<?php 
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>