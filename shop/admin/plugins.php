<?php
/* ----------------------------------------------------------------------
   $Id: plugins.php,v 1.1 2007/06/08 17:14:41 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: services.php,v 1.1 2004/04/13 08:19:16 hpdl Exp $
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';
  require 'includes/functions/function_modules.php';

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $installed = explode(';', MODULE_PLUGIN_EVENT_INSTALLED);

  if (!empty($action)) {
    switch ($action) {
      case 'save':
        while (list($key, $value) = each($_POST['configuration'])) {
          $configurationtable = $oostable['configuration'];
          $dbconn->Execute("UPDATE $configurationtable SET configuration_value = '" . $value . "' WHERE configuration_key = '" . $key . "'");
        }
        oos_redirect_admin(oos_href_link_admin($aFilename['plugins'], 'plugin=' . $_GET['plugin']));
        break;

      case 'remove':
        $sInstance = oos_db_prepare_input($_GET['plugin']);

        if (($key = array_search($sInstance, $installed)) !== false) {
          include OOS_ABSOLUTE_PATH . 'includes/plugins/' . 'oos_event_' . $sInstance . '/oos_event_' . $sInstance . '.php';
          $class = 'oos_event_' . $sInstance;
          $oPlugin = new $class;

          if ($oPlugin->uninstallable) {
            $oPlugin->remove();

            unset($installed[$key]);

            $configurationtable = $oostable['configuration'];
            $dbconn->Execute("UPDATE $configurationtable SET configuration_value = '" . implode(';', $installed) . "' WHERE configuration_key = 'MODULE_PLUGIN_EVENT_INSTALLED'");
          }
        }

        oos_redirect_admin(oos_href_link_admin($aFilename['plugins'], 'plugin=' . $_GET['plugin']));
        break;

      case 'install':
        $sInstance = oos_db_prepare_input($_GET['plugin']);

        if (array_search($sInstance, $installed) === false) {
          include OOS_ABSOLUTE_PATH . 'includes/plugins/' . 'oos_event_' . $sInstance . '/oos_event_' . $sInstance . '.php';
          $class = 'oos_event_' . $sInstance;
          $oPlugin = new $class;

          $bInstall = $oPlugin->install();
          if ($bInstall) {
            if (isset($oPlugin->depends)) {
              if (is_string($oPlugin->depends) && (($key = array_search($oPlugin->depends, $installed)) !== false)) {
                if (isset($installed[$key+1])) {
                  array_splice($installed, $key+1, 0, $sInstance);
                } else {
                  $installed[] = $sInstance;
                }
              } elseif (is_array($oPlugin->depends)) {
                foreach ($oPlugin->depends as $depends_module) {
                  if (($key = array_search($depends_module, $installed)) !== false) {
                    if (!isset($array_position) || ($key > $array_position)) {
                      $array_position = $key;
                    }
                  }
                }

                if (isset($array_position)) {
                  array_splice($installed, $array_position+1, 0, $sInstance);
                } else {
                  $installed[] = $sInstance;
                }
              }
            } elseif (isset($oPlugin->preceeds)) {
              if (is_string($oPlugin->preceeds)) {
                if ((($key = array_search($oPlugin->preceeds, $installed)) !== false)) {
                  array_splice($installed, $key, 0, $sInstance);
                } else {
                  $installed[] = $sInstance;
                }
              } elseif (is_array($oPlugin->preceeds)) {
                foreach ($oPlugin->preceeds as $preceeds_module) {
                  if (($key = array_search($preceeds_module, $installed)) !== false) {
                    if (!isset($array_position) || ($key < $array_position)) {
                      $array_position = $key;
                    }
                  }
                }

                if (isset($array_position)) {
                  array_splice($installed, $array_position, 0, $sInstance);
                } else {
                  $installed[] = $sInstance;
                }
              }
            } else {
              $installed[] = $sInstance;
            }

            $configurationtable = $oostable['configuration'];
            $dbconn->Execute("UPDATE $configurationtable SET configuration_value = '" . implode(';', $installed) . "' WHERE configuration_key = 'MODULE_PLUGIN_EVENT_INSTALLED'");

          }
        }
        oos_redirect_admin(oos_href_link_admin($aFilename['plugins'], 'plugin=' . $_GET['plugin']));
        break;
    }
  }

  $sLocaleDir = OOS_ABSOLUTE_PATH . 'includes/plugins/';
  $aDirectory = array();

  if (is_dir($sLocaleDir)) {
    if ($dh = opendir($sLocaleDir)) {
      while (($file = readdir($dh)) !== false) {
        if ($file == '.' || $file == '..' || $file == 'CVS' || $file == 'thirdparty' || $file == 'default' || filetype($sLocaleDir . $file) == 'file' ) continue;
        if (filetype(realpath($sLocaleDir . $file)) == 'dir') {
          $aDirectory[] = $file;
        }
      }
      closedir($dh);
    }
  }

  sort($aDirectory);

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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PLUGINS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  foreach ($aDirectory as $sName) {

    if (isset($_SESSION['language']) &&  file_exists(OOS_ABSOLUTE_PATH . 'includes/plugins/' . $sName . '/lang/' . $_SESSION['language'] . '.php')) {
      include OOS_ABSOLUTE_PATH . 'includes/plugins/' .  $sName . '/lang/' . $_SESSION['language'] . '.php';
    } elseif (file_exists(OOS_ABSOLUTE_PATH . 'includes/plugins/' .  $sName . '/lang/' . DEFAULT_LANGUAGE . '.php')) {
      include  OOS_ABSOLUTE_PATH . 'includes/plugins/' .  $sName . '/lang/' . DEFAULT_LANGUAGE . '.php';
    }
    include OOS_ABSOLUTE_PATH . 'includes/plugins/' .  $sName . '/' . $sName . '.php';

    $sInstance = strstr($sName, '_event_');
    $sInstance = substr($sInstance, 7);

    $class = $sName;
    $oPlugin = new $class;

    if ((!isset($_GET['plugin']) || (isset($_GET['plugin']) && ($_GET['plugin'] == $sInstance))) && !isset($pInfo)) {
      $plugin_info = array('instance' => $sInstance,
                           'name' => $oPlugin->name,
                           'description' => $oPlugin->description,
                           'status' => in_array($sInstance, $installed),
                           'uninstallable' => $oPlugin->uninstallable,
                           'preceeds' => $oPlugin->preceeds,
                           'preceeds' => $oPlugin->preceeds);

      $plugin_keys = $oPlugin->config_item();
      $keys_extra = array();

      if (is_array($plugin_keys) && (sizeof($plugin_keys) > 0)) {
        foreach ($plugin_keys as $key) {
          $configurationtable = $oostable['configuration'];
          $key_value_result = $dbconn->Execute("SELECT configuration_value, use_function, set_function FROM $configurationtable WHERE configuration_key = '" . $key . "'");
          $key_value = $key_value_result->fields;

          $keys_extra[$key]['title'] = constant(strtoupper($key . '_TITLE'));
          $keys_extra[$key]['value'] = $key_value['configuration_value'];
          $keys_extra[$key]['description'] = constant(strtoupper($key . '_DESC'));
          $keys_extra[$key]['use_function'] = $key_value['use_function'];
          $keys_extra[$key]['set_function'] = $key_value['set_function'];
        }
      }

      $plugin_info['keys'] = $keys_extra;

      $pInfo = new objectInfo($plugin_info);
    }

    if (isset($pInfo) && is_object($pInfo) && ($sInstance == $pInfo->instance) ) {
      echo '              <tr class="dataTableRowSelected">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['plugins'], 'plugin=' . $sInstance) . '\'">' . "\n";
    }
?>

                <td class="dataTableContent"><?php echo $oPlugin->name; ?></td>
                <td class="dataTableContent" align="right">
<?php
   if (in_array($sInstance, $installed)) {
     if ($oPlugin->uninstallable) {
       echo '<a href="' . oos_href_link_admin($aFilename['plugins'], 'plugin=' . $sInstance . '&action=remove') . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
     } else {
       echo oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10);
     }
   } else {
      echo '<a href="' . oos_href_link_admin($aFilename['plugins'], 'plugin=' . $sInstance . '&action=install') . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>'; 
   }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($sInstance == $pInfo->instance) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . oos_href_link_admin($aFilename['plugins'], 'plugin=' . $sInstance ) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>

<?php
  }
?>
              <tr>
                <td colspan="4" class="smallText"><?php echo TEXT_PLUGINS_DIRECTORY . ' ' . $sLocaleDir; ?></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'edit':
      $keys = '';
      foreach ($pInfo->keys as $key => $value) {
        $keys .= '<b>' . $value['title'] . '</b><br />' . $value['description'] . '<br />';

        if ($value['set_function']) {
          eval('$keys .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
        } else {
          $keys .= oos_draw_input_field('configuration[' . $key . ']', $value['value']);
        }
        $keys .= '<br /><br />';
      }
      $keys = substr($keys, 0, strrpos($keys, '<br /><br />'));

      $heading[] = array('text' => '<b>' . $pInfo->name . '</b>');

      $contents = array('form' => oos_draw_form('plugins', $aFilename['plugins'], 'plugin=' . $_GET['plugin'] . '&action=save'));
      $contents[] = array('text' => $keys);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('update','update_off.gif', IMAGE_UPDATE) . ' <a href="' . oos_href_link_admin($aFilename['plugins'], 'plugin=' . $_GET['plugin']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;


    default:

      if (isset($pInfo) && is_object($pInfo)) {
        $heading[] = array('text' => '<b>' . $pInfo->name . '</b>');

        $keys = '';
        if ($pInfo->uninstallable || (sizeof($pInfo->keys > 0))) {
          if ($pInfo->status) {
            $contents[] = array('align' => 'center', 'text' => ($pInfo->uninstallable ? '<a href="' . oos_href_link_admin($aFilename['plugins'], 'plugin=' . $pInfo->instance . '&action=remove') . '">' . oos_image_swap_button('modules_remove','module_remove_off.gif', IMAGE_PLUGINS_REMOVE) . '</a>' : '') . ((sizeof($pInfo->keys) > 0) ? ' <a href="' . oos_href_link_admin($aFilename['plugins'], 'plugin=' . $pInfo->instance . '&action=edit') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a>' : ''));

            if (sizeof($pInfo->config_item) > 0) {
              $keys = '<br />';

              foreach ($pInfo->config_item as $value) {
                $keys .= '<b>' . $value['title'] . '</b><br />';

                if ($value['use_function']) {
                  $use_function = $value['use_function'];

                  if (ereg('->', $use_function)) {
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
            }
          } else {
            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['plugins'], 'plugin=' . $pInfo->instance . '&action=install') . '">' . oos_image_swap_button('module_install','module_install_off.gif', IMAGE_PLUGINS_INSTALL) . '</a>');
          }
        }

        $contents[] = array('text' => $pInfo->description);

        if (!empty($pInfo->preceeds)) {
          $preceeds_string = '<u>Preceeds</u><br />';

          if (is_string($pInfo->preceeds)) {
            $preceeds_string .= $pInfo->preceeds;
          } else {
            foreach ($pInfo->preceeds as $preceeds) {
              $preceeds_string .= $preceeds . '<br />';
            }
          }

          $contents[] = array('text' => $preceeds_string);
        }

        $contents[] = array('text' => $keys);
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


<?php require 'includes/oos_footer.php'; ?>
<br />
</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>
