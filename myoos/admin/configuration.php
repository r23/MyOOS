<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2022 by the MyOOS Development Team.
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
$cID = (isset($_GET['cID']) ? intval($_GET['cID']) : 0);


if (!empty($action)) {
    switch ($action) {
      case 'save':
        $configuration_value = oos_db_prepare_input($_POST['configuration_value']);

        $dbconn->Execute("UPDATE " . $oostable['configuration'] . " SET configuration_value = '" . oos_db_input($configuration_value) . "', last_modified = now() WHERE configuration_id = '" . intval($cID) . "'");


        if ($cID == 2 || $cID == 3 || $cID == 4) {
            require 'includes/classes/class_upload.php';


            // Logo
            if ($cID == 2) {
                $options = array(
                    'image_versions' => array(
                        // The empty image version key defines options for the original image.
                        // Keep in mind: these image manipulations are inherited by all other image versions from this point onwards.
                        // Also note that the property 'no_cache' is not inherited, since it's not a manipulation.
                        '' => array(
                            // Automatically rotate images based on EXIF meta data:
                            'auto_orient' => true
                        ),
                        'medium' => array(
                            // 'auto_orient' => TRUE,
                            // 'crop' => TRUE,
                            // 'jpeg_quality' => 82,
                            // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                            // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                            'max_width' => 320, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                            'max_height' => 150 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
                        ),
                        'checkout' => array(
                            // 'auto_orient' => TRUE,
                            // 'crop' => TRUE,
                            // 'jpeg_quality' => 82,
                            // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                            // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                            'max_width' => 190, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                            'max_height' => 60 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
                        ),
                        'small' => array(
                            // 'auto_orient' => TRUE,
                            // 'crop' => TRUE,
                            // 'jpeg_quality' => 82,
                            // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                            // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                            'max_width' => 128, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                            'max_height' => 60 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
                        ),
                    ),
                );
                $dir_fs_catalog_images = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'logo/';
            } elseif ($cID == 3) {
                // Site Icons
                $options = array(
                    'image_versions' => array(
                        // The empty image version key defines options for the original image.
                        // Keep in mind: these image manipulations are inherited by all other image versions from this point onwards.
                        // Also note that the property 'no_cache' is not inherited, since it's not a manipulation.
                        '' => array(
                            // Automatically rotate images based on EXIF meta data:
                            'auto_orient' => true
                        ),
                        '180x180' => array(
                            // 'auto_orient' => TRUE,
                            // 'crop' => TRUE,
                            // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                            // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                            'max_width' => 180, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                            'max_height' => 180 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
                        ),
                        '144x144' => array(
                            // 'auto_orient' => TRUE,
                            // 'crop' => TRUE,
                            // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                            // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                            'max_width' => 144, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                            'max_height' => 144 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
                        ),
                        '114x114' => array(
                            // 'auto_orient' => TRUE,
                            // 'crop' => TRUE,
                            // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                            // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                            'max_width' => 114, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                            'max_height' => 114 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
                        ),
                        '72x72' => array(
                            // 'auto_orient' => TRUE,
                            // 'crop' => TRUE,
                            // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                            // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                            'max_width' => 72, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                            'max_height' => 72 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
                        ),
                        '96x96' => array(
                            // 'auto_orient' => TRUE,
                            // 'crop' => TRUE,
                            // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                            // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                            'max_width' => 96, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                            'max_height' => 96 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
                        ),
                        '32x32' => array(
                            // 'auto_orient' => TRUE,
                            // 'crop' => TRUE,
                            // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                            // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                            'max_width' => 32, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                            'max_height' => 32 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
                        ),

                    ),
                );
                $dir_fs_catalog_images = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'ico/';
            } elseif ($cID == 4) {
                // OpenGraph Thumbnail
                $options = array(
                    'image_versions' => array(
                        // The empty image version key defines options for the original image.
                        // Keep in mind: these image manipulations are inherited by all other image versions from this point onwards.
                        // Also note that the property 'no_cache' is not inherited, since it's not a manipulation.
                        '' => array(
                            // Automatically rotate images based on EXIF meta data:
                            'auto_orient' => true
                        ),
                        '1200x630' => array(
                            // 'auto_orient' => TRUE,
                            // 'crop' => TRUE,
                            // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                            // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                            'max_width' => 1200, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                            'max_height' => 630 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
                        ),
                        'medium' => array(
                            // 'auto_orient' => TRUE,
                            // 'crop' => TRUE,
                            // 'jpeg_quality' => 82,
                            // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                            // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                            'max_width' => 320, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                            'max_height' => 168 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
                        ),
                    ),
                );
                $dir_fs_catalog_images = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'og/';
            }


            $oLogo = new upload('site_image', $options);
            $oLogo->set_destination($dir_fs_catalog_images);
            if ($oLogo->parse() && oos_is_not_null($oLogo->filename)) {
                $configurationtable =  $oostable['configuration'];
                $dbconn->Execute("UPDATE $configurationtable
                            SET configuration_value = '" . oos_db_input($oLogo->filename) . "', 
							last_modified = now()				
                            WHERE configuration_id = '" . intval($cID) . "'");
            }
        }

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
  $configuration_result = $dbconn->Execute("SELECT configuration_id, configuration_key, configuration_value, use_function FROM " . $oostable['configuration'] . " WHERE configuration_group_id = '" . intval($_GET['gID']) . "' ORDER BY sort_order");

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
              if (function_exists($use_function)) {
                  $cfgValue = oos_call_function($use_function, $configuration['configuration_value']);
              }
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


      if (isset($cInfo) && is_object($cInfo) && ($configuration['configuration_id'] == $cInfo->configuration_id)) {
          echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['configuration'], 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=edit') . '\'">' . "\n";
      } else {
          echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['configuration'], 'gID=' . $_GET['gID'] . '&cID=' . $configuration['configuration_id']) . '\'">' . "\n";
      } ?>
                <td><?php echo constant(strtoupper($configuration['configuration_key'] . '_TITLE')); ?></td>
                <td><?php echo htmlspecialchars((string)$cfgValue, ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-right"><?php if (isset($cInfo) && is_object($cInfo) && ($configuration['configuration_id'] == $cInfo->configuration_id)) {
          echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
      } else {
          echo '<a href="' . oos_href_link_admin($aContents['configuration'], 'gID=' . $_GET['gID'] . '&cID=' . $configuration['configuration_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
      } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $configuration_result->MoveNext();
  }
?>
            </table></td>
<?php
  $heading = [];
  $contents = [];

  switch ($action) {
    case 'edit':
      $heading[] = array('text' => '<b>' . constant(strtoupper($cInfo->configuration_key . '_TITLE')) . '</b>');

        if ($cID == 2 || $cID == 3 || $cID == 4) {
            $value_field = oos_draw_file_field('site_image') . '<br>' . $cInfo->configuration_value;
        } else {
            if ($cInfo->set_function) {
				
				// Allowed values for $cInfo->set_function
				$whitelist = ['oos_cfg_select_option', 'oos_cfg_pull_down_order_statuses', 'oos_cfg_get_order_status_name', 'oos_cfg_pull_down_zone_classes', 'pull_down_country_list'];

				// Check if $cInfo->set_function is in the whitelist
				if (in_array ($cInfo->set_function, $whitelist)) {
					// Evaluation of the code
					eval('$value_field = ' . $cInfo->set_function . '"' . htmlspecialchars((string)$cInfo->configuration_value, ENT_QUOTES, 'UTF-8') . '");');
				} else {
					die ('Invalid value for $cInfo->set_function: '.$cInfo->set_function);
				}				
            } else {
                $value_field = oos_draw_input_field('configuration_value', $cInfo->configuration_value);
            }
        }

      $contents = array('form' => oos_draw_form('id', 'configuration', $aContents['configuration'], 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=save', 'post', false, 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br><b>' . constant(strtoupper($cInfo->configuration_key . '_TITLE')) . '</b><br>' . constant(strtoupper($cInfo->configuration_key . '_DESC')) . '<br>' . $value_field);
      $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_UPDATE) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['configuration'], 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');
      break;

   default:
      if (isset($cInfo) && is_object($cInfo)) {
          $heading[] = array('text' => '<b>' . constant(strtoupper($cInfo->configuration_key . '_TITLE')) . '</b>');
          $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['configuration'], 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a>');
          if ($cID == 2) {
              $contents[] = array('text' => '<br>' . oos_info_image('logo/medium/' . $cInfo->configuration_value, $cInfo->configuration_value));
          } elseif ($cID == 3) {
              $contents[] = array('text' => '<br>' . oos_info_image('ico/180x180/' . $cInfo->configuration_value, $cInfo->configuration_value));
          } elseif ($cID == 4) {
              $contents[] = array('text' => '<br>' . oos_info_image('og/medium/' . $cInfo->configuration_value, $cInfo->configuration_value));
          }
          $contents[] = array('text' => '<br>' . constant(strtoupper($cInfo->configuration_key . '_DESC')));
          $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . oos_date_short($cInfo->date_added));
          if (oos_is_not_null($cInfo->last_modified)) {
              $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' ' . oos_date_short($cInfo->last_modified));
          }
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