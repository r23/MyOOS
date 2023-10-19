<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
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

require 'includes/classes/class_currencies.php';
require 'includes/functions/function_modules.php';

$currencies = new currencies();

$set = ($_GET['set'] ?? '');

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


$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

switch ($action) {
    case 'save':

        $aKeys = [];

        if (defined('MODULE_SHIPPING_TABLE_STATUS') && (MODULE_SHIPPING_TABLE_STATUS == 'true')) {
            $aKeys[] = 'MODULE_SHIPPING_TABLE_HANDLING';
        }

        if (defined('MODULE_SHIPPING_WEIGHT_STATUS') && (MODULE_SHIPPING_WEIGHT_STATUS == 'true')) {
            $aKeys[] = 'MODULE_SHIPPING_WEIGHT_HANDLING';
        }

        if (defined('MODULE_SHIPPING_ZONES_STATUS') && (MODULE_SHIPPING_ZONES_STATUS == 'true')) {
            $num_zones = (defined('MODULE_SHIPPING_ZONES_NUM_ZONES') ? MODULE_SHIPPING_ZONES_NUM_ZONES : 2);
            for ($i = 1; $i <= $num_zones; $i++) {
                $aKeys[] = 'MODULE_SHIPPING_ZONES_HANDLING_' . $i;
            }
        }

        foreach ($_POST['configuration'] as $key => $value) {
            $configurationtable = $oostable['configuration'];

            // todo
            if (in_array($key, $aKeys)) {
                $value = oos_tofloat($value);
            }

            $dbconn->Execute("UPDATE $configurationtable SET configuration_value = '" . oos_db_input($value) . "' WHERE configuration_key = '" . oos_db_input($key) . "'");
        }

        if (isset($_POST['default']) && ($_POST['default'] == 'on')) {
            $code = oos_db_prepare_input($_POST['code']);
            $dbconn->Execute("UPDATE " . $oostable['configuration'] . " SET configuration_value = '" . oos_db_input($code) . "' WHERE configuration_key = 'DEFAULT_SHIPPING_METHOD'");
        }


        oos_redirect_admin(oos_href_link_admin($aContents['modules'], 'set=' . $_GET['set'] . '&module=' . $_GET['module']));
        break;

    case 'install':
    case 'remove':
        $php_self = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
        $file_extension = oos_db_prepare_input(substr($php_self, strrpos($php_self, '.')));
        $class = oos_db_prepare_input(basename((string) $_GET['module']));

        if (file_exists($module_directory . $class . $file_extension)) {
            include OOS_ABSOLUTE_PATH . 'includes/languages/' . $_SESSION['language'] . '/modules/' . $module_type . '/' . $class . $file_extension;
            include $module_directory . $class . $file_extension;

            $module = new $class();
            if ($action == 'install') {
                $module->install();
            } elseif ($action == 'remove') {
                if ($class == DEFAULT_SHIPPING_METHOD) {
                    $messageStack->add_session(ERROR_REMOVE_DEFAULT_SHIPPING, 'error');
                } else {
                    $module->remove();
                }
            }
        }
        oos_redirect_admin(oos_href_link_admin($aContents['modules'], 'set=' . $set . '&module=' . $class));
        break;
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
							<?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
						</li>
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['modules'], 'selected_box=modules&set=payment') . '">' . BOX_HEADING_MODULES . '</a>'; ?>
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
<div class="table-responsive">
	<table class="table w-100">
          <tr>
            <td valign="top">
			
				<table class="table table-striped table-hover w-100">
					<thead class="thead-dark">
						<tr>
							<th><?php echo TABLE_HEADING_MODULES; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_SORT_ORDER; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_STATUS; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>
<?php
    $php_self = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
$file_extension = substr($php_self, strrpos($php_self, '.'));
$directory_array = [];
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

$installed_modules = [];
for ($i = 0, $n = count($directory_array); $i < $n; $i++) {
    $file = $directory_array[$i];

    include OOS_ABSOLUTE_PATH . 'includes/languages/' . $_SESSION['language'] . '/modules/' . $module_type . '/' . $file;
    include $module_directory . $file;

    $class = substr($file, 0, strrpos($file, '.'));
    if (oos_class_exits($class)) {
        $module = new $class();
        if ($module->check() > 0) {
            if ($module->sort_order > 0) {
                $installed_modules[$module->sort_order] = $file;
            } else {
                $installed_modules[] = $file;
            }
        }

        if ((!isset($_GET['module']) || (isset($_GET['module']) && ($_GET['module'] == $class))) && !isset($mInfo)) {
            $module_info = ['code' => $module->code, 'title' => $module->title, 'description' => $module->description, 'status' => $module->check()];

            $module_keys = $module->keys();

            $keys_extra = [];
            for ($j = 0, $k = is_countable($module_keys) ? count($module_keys) : 0; $j < $k; $j++) {
                $key_value_result = $dbconn->Execute("SELECT configuration_value, use_function, set_function FROM " . $oostable['configuration'] . " WHERE configuration_key = '" . $module_keys[$j] . "'");
                $key_value = $key_value_result->fields;

                $keys_extra[$module_keys[$j]]['title'] = constant(strtoupper($module_keys[$j] . '_TITLE'));
                $keys_extra[$module_keys[$j]]['value'] = $key_value['configuration_value'] ?? '';
                $keys_extra[$module_keys[$j]]['description'] = constant(strtoupper($module_keys[$j] . '_DESC'));
                $keys_extra[$module_keys[$j]]['use_function'] = $key_value['use_function'] ?? '';
                $keys_extra[$module_keys[$j]]['set_function'] = $key_value['set_function'] ?? '';
            }

            $module_info['keys'] = $keys_extra;

            $mInfo = new objectInfo($module_info);
        }

        if (isset($mInfo) && is_object($mInfo) && ($class == $mInfo->code)) {
            if ($module->check() > 0) {
                echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['modules'], 'set=' . $set . '&module=' . $class . '&action=edit') . '\'">' . "\n";
            } else {
                echo '              <tr class="dataTableRowSelected">' . "\n";
            }
        } else {
            echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['modules'], 'set=' . $set . '&module=' . $class) . '\'">' . "\n";
        }

        if (DEFAULT_SHIPPING_METHOD == $module->code) {
            echo '                <td><b>' . $module->title . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
        } else {
            echo '                <td>' . $module->title . '</td>' . "\n";
        } ?>
                <td class="text-right"><?php if (is_numeric($module->sort_order)) {
                    echo $module->sort_order;
                } ?></td>
                <td class="text-right">
<?php
                if ($module->check() > 0) {
                    echo '<a href="' . oos_href_link_admin($aContents['modules'], 'set=' . $set . '&module=' . $class . '&action=remove') . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10) . '</a>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['modules'], 'set=' . $set . '&module=' . $class . '&action=install') . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10) . '</a>';
                } ?></td>
                <td class="text-right"><?php if (isset($mInfo) && is_object($mInfo) && ($class == $mInfo->code)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['modules'], 'set=' . $set . '&module=' . $class) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
                } ?>&nbsp;</td>
              </tr>
<?php
    }
}

ksort($installed_modules);
$configurationtable = $oostable['configuration'];
$check_result = $dbconn->Execute("SELECT configuration_value FROM $configurationtable WHERE configuration_key = '" . oos_db_input($module_key) . "'");
if ($check_result->RecordCount()) {
    $check = $check_result->fields;
    if ($check['configuration_value'] != implode(';', $installed_modules)) {
        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("UPDATE $configurationtable SET configuration_value = '" . oos_db_input(implode(';', $installed_modules)) . "', last_modified = now() WHERE configuration_key = '" . oos_db_input($module_key). "'");
    }
} else {
    $configurationtable = $oostable['configuration'];
    $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('" . oos_db_input($module_key) . "', '" . oos_db_input(implode(';', $installed_modules)) . "', '6', '0', now())");
}
?>
              <tr>
                <td colspan="4" class="smallText"><?php echo TEXT_MODULE_DIRECTORY . ' ' . $module_directory; ?></td>
              </tr>
            </table></td>
<?php
$heading = [];
$contents = [];

switch ($action) {
    case 'edit':
        $keys = '';
        reset($mInfo->keys);
        foreach ($mInfo->keys as $key => $value) {
            $keys .= '<b>' . $value['title'] . '</b><br>' . $value['description'] . '<br>';
            if ($value['set_function']) {
                eval('$keys .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
            } else {
                $keys .= oos_draw_input_field('configuration[' . $key . ']', $value['value']);
            }
            $keys .= '<br><br>';
        }
        $keys = substr($keys, 0, strrpos($keys, '<br><br>'));
        $heading[] = ['text' => '<b>' . $mInfo->title . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'modules', $aContents['modules'], 'set=' . $set . '&module=' . $_GET['module'] . '&action=save', 'post', false)];
        $contents[] = ['text' => $keys];

        if ($set == 'shipping') {
            if (DEFAULT_SHIPPING_METHOD != $mInfo->code) {
                $contents[] = ['text' => '<br>' . oos_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT . oos_draw_hidden_field('code', $mInfo->code)];
            }
        }

        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_UPDATE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['modules'], 'set=' . $set . '&module=' . $_GET['module']) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
        break;

    default:
        $heading[] = ['text' => '<b>' . $mInfo->title . '</b>'];

        if ($mInfo->status == '1') {
            $keys = '';
            reset($mInfo->keys);
            foreach ($mInfo->keys as $value) {
                $keys .= '<b>' . $value['title'] . '</b><br>';
                if ($value['use_function']) {
                    $use_function = $value['use_function'];
                    if (preg_match('/->/', (string) $use_function)) {
                        $class_method = explode('->', (string) $use_function);
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
                $keys .= '<br><br>';
            }
            $keys = substr($keys, 0, strrpos($keys, '<br><br>'));

            $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['modules'], 'set=' . $set . '&module=' . $mInfo->code . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a>'];
            $contents[] = ['text' => '<br>' . $mInfo->description];
            $contents[] = ['text' => '<br>' . $keys];
        } else {
            $contents[] = ['text' => $mInfo->description];
        }
        break;
}

if ((oos_is_not_null($heading)) && (oos_is_not_null($contents))) {
    ?>
	<td class="w-25" valign="top">
		<table class="table table-striped">
<?php
    $box = new box();
    echo $box->infoBox($heading, $contents); ?>
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

		</div>
	</section>
	<!-- Page footer //-->
	<footer>
		<span>&copy; <?php echo date('Y'); ?> - <a href="https://www.oos-shop.de" target="_blank" rel="noopener">MyOOS [Shopsystem]</a></span>
	</footer>
</div>

<?php
    require 'includes/bottom.php';
require 'includes/nice_exit.php';
?>