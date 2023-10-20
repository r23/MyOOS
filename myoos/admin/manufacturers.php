<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: manufacturers.php,v 1.51 2003/01/29 23:21:48 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

require 'includes/classes/class_upload.php';

function oos_get_manufacturer_url($manufacturer_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $manufacturers_infotable = $oostable['manufacturers_info'];
    $manufacturer = $dbconn->Execute("SELECT manufacturers_url FROM $manufacturers_infotable WHERE manufacturers_id = '" . intval($manufacturer_id) . "' AND manufacturers_languages_id = '" . intval($language_id) . "'");

    return $manufacturer->fields['manufacturers_url'];
}

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

switch ($action) {
    case 'insert':
    case 'save':
        if (isset($_GET['mID'])) {
            $manufacturers_id = oos_db_prepare_input($_GET['mID']);
        }
        $manufacturers_name = oos_db_prepare_input($_POST['manufacturers_name']);

        $sql_data_array = ['manufacturers_name' => $manufacturers_name];

        if ($action == 'insert') {
            $insert_sql_data = ['date_added' => 'now()'];

            $sql_data_array = [...$sql_data_array, ...$insert_sql_data];

            oos_db_perform($oostable['manufacturers'], $sql_data_array);
            $manufacturers_id = $dbconn->Insert_ID();
        } elseif ($action == 'save') {
            $update_sql_data = ['last_modified' => 'now()'];

            $sql_data_array = [...$sql_data_array, ...$update_sql_data];

            oos_db_perform($oostable['manufacturers'], $sql_data_array, 'UPDATE', "manufacturers_id = '" . intval($manufacturers_id) . "'");
        }


        $aLanguages = oos_get_languages();
        $nLanguages = is_countable($aLanguages) ? count($aLanguages) : 0;

        for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
            $manufacturers_url_array = oos_db_prepare_input($_POST['manufacturers_url']);
            $language_id = $aLanguages[$i]['id'];

            $sql_data_array = ['manufacturers_url' => oos_db_prepare_input($manufacturers_url_array[$language_id])];

            if ($action == 'insert') {
                $insert_sql_data = ['manufacturers_id' => intval($manufacturers_id), 'manufacturers_languages_id' => intval($language_id)];

                $sql_data_array = [...$sql_data_array, ...$insert_sql_data];

                oos_db_perform($oostable['manufacturers_info'], $sql_data_array);
            } elseif ($action == 'save') {
                oos_db_perform($oostable['manufacturers_info'], $sql_data_array, 'UPDATE', "manufacturers_id = '" . intval($manufacturers_id) . "' AND manufacturers_languages_id = '" . intval($language_id) . "'");
            }
        }

        $options = ['image_versions' => [
            // The empty image version key defines options for the original image.
            // Keep in mind: these image manipulations are inherited by all other image versions from this point onwards.
            // Also note that the property 'no_cache' is not inherited, since it's not a manipulation.
            '' => [
                // Automatically rotate images based on EXIF meta data:
                'auto_orient' => true,
            ],
            'medium' => [
                // 'auto_orient' => TRUE,
                // 'crop' => TRUE,
                // 'jpeg_quality' => 82,
                // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                'max_width' => 300,
                // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                'max_height' => 300,
            ],
            'small' => [
                // 'auto_orient' => TRUE,
                // 'crop' => TRUE,
                // 'jpeg_quality' => 82,
                // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                'max_width' => 150,
                // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                'max_height' => 150,
            ],
        ]];

        $oManufacturersImage = new upload('manufacturers_image', $options);

        $dir_fs_catalog_images = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'brands/';
        $oManufacturersImage->set_destination($dir_fs_catalog_images);
        $oManufacturersImage->parse();

        if (oos_is_not_null($oManufacturersImage->filename)) {
            $manufacturerstable = $oostable['manufacturers'];
            $dbconn->Execute(
                "UPDATE $manufacturerstable
                            SET manufacturers_image = '" . oos_db_input($oManufacturersImage->filename) . "'
                            WHERE manufacturers_id = '" . intval($manufacturers_id) . "'"
            );
        }


        oos_redirect_admin(oos_href_link_admin($aContents['manufacturers'], 'page=' . $nPage . '&mID=' . $manufacturers_id));
        break;

    case 'deleteconfirm':
        $manufacturers_id = oos_db_prepare_input($_GET['mID']);

        if (isset($_POST['delete_image']) && ($_POST['delete_image'] == 'on')) {
            $manufacturerstable = $oostable['manufacturers'];
            $manufacturer_result = $dbconn->Execute("SELECT manufacturers_image FROM $manufacturerstable WHERE manufacturers_id = '" . intval($manufacturers_id) . "'");
            $manufacturer = $manufacturer_result->fields;

            $image_location_originals = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'brands/originals/'. $manufacturer['manufacturers_image'];
            if (file_exists($image_location_originals)) {
                @unlink($image_location_originals);
            }

            $image_location_medium = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'brands/medium/'. $manufacturer['manufacturers_image'];
            if (file_exists($image_location_medium)) {
                @unlink($image_location_medium);
            }

            $image_location_small = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'brands/small/'. $manufacturer['manufacturers_image'];
            if (file_exists($image_location_small)) {
                @unlink($image_location_small);
            }
        }

        $manufacturerstable = $oostable['manufacturers'];
        $dbconn->Execute("DELETE FROM $manufacturerstable WHERE manufacturers_id = '" . intval($manufacturers_id) . "'");

        $manufacturers_infotable = $oostable['manufacturers_info'];
        $dbconn->Execute("DELETE FROM $manufacturers_infotable WHERE manufacturers_id = '" . intval($manufacturers_id) . "'");

        if (isset($_POST['delete_products']) && ($_POST['delete_products'] == 'on')) {
            $productstable = $oostable['products'];
            $products_result = $dbconn->Execute("SELECT products_id FROM $productstable WHERE manufacturers_id = '" . intval($manufacturers_id) . "'");
            while ($products = $products_result->fields) {
                oos_remove_product($products['products_id']);

                // Move that ADOdb pointer!
                $products_result->MoveNext();
            }
        } else {
            $productstable = $oostable['products'];
            $dbconn->Execute("UPDATE $productstable SET manufacturers_id = '' WHERE manufacturers_id = '" . intval($manufacturers_id) . "'");
        }

        oos_redirect_admin(oos_href_link_admin($aContents['manufacturers'], 'page=' . $nPage));
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
                            <?php echo '<a href="' . oos_href_link_admin($aContents['categories'], 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG . '</a>'; ?>
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
                            <th><?php echo TABLE_HEADING_MANUFACTURERS; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                        </tr>    
                    </thead>
<?php
$rows = 0;
$aDocument = [];
$manufacturerstable = $oostable['manufacturers'];
$manufacturers_result_raw = "SELECT manufacturers_id, manufacturers_name, manufacturers_image, date_added, last_modified
                               FROM $manufacturerstable
                               ORDER BY manufacturers_name";
$manufacturers_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $manufacturers_result_raw, $manufacturers_result_numrows);
$manufacturers_result = $dbconn->Execute($manufacturers_result_raw);
while ($manufacturers = $manufacturers_result->fields) {
    $rows++;
    if ((!isset($_GET['mID']) || (isset($_GET['mID']) && ($_GET['mID'] == $manufacturers['manufacturers_id']))) && !isset($mInfo) && (!str_starts_with((string) $action, 'new'))) {
        $manufacturer_products_result = $dbconn->Execute("SELECT COUNT(*) AS products_count FROM " . $oostable['products'] . " WHERE manufacturers_id = '" . $manufacturers['manufacturers_id'] . "'");
        $manufacturer_products = $manufacturer_products_result->fields;

        $mInfo_array = array_merge($manufacturers, $manufacturer_products);
        $mInfo = new objectInfo($mInfo_array);
    }

    if (isset($mInfo) && is_object($mInfo) && ($manufacturers['manufacturers_id'] == $mInfo->manufacturers_id)) {
        $aDocument[] = ['id' => $rows,
                        'link' => oos_href_link_admin($aContents['manufacturers'], 'page=' . $nPage . '&mID=' . $manufacturers['manufacturers_id'] . '&action=edit')];
        echo '                  <tr id="row-' . $rows .'">' . "\n";
    } else {
        $aDocument[] = ['id' => $rows,
                        'link' => oos_href_link_admin($aContents['manufacturers'], 'page=' . $nPage . '&mID=' . $manufacturers['manufacturers_id'])];
        echo '                  <tr id="row-' . $rows .'">' . "\n";
    } ?>
                <td><?php echo $manufacturers['manufacturers_name']; ?></td>
                <td class="text-right"><?php if (isset($mInfo) && is_object($mInfo) && ($manufacturers['manufacturers_id'] == $mInfo->manufacturers_id)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['manufacturers'], 'page=' . $nPage . '&mID=' . $manufacturers['manufacturers_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
                } ?>&nbsp;</td>
              </tr>
    <?php
                // Move that ADOdb pointer!
                $manufacturers_result->MoveNext();
}
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $manufacturers_split->display_count($manufacturers_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS); ?></td>
                    <td class="smallText" align="right"><?php echo $manufacturers_split->display_links($manufacturers_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
 if ($action == 'default') {
     ?>
              <tr>
                <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . oos_href_link_admin($aContents['manufacturers'], 'page=' . $nPage . '&mID=' . ($mInfo->manufacturers_id ?? '') . '&action=new') . '">' . oos_button(BUTTON_INSERT) . '</a>'; ?></td>
              </tr>
    <?php
 }
?>
            </table></td>
<?php
$heading = [];
$contents = [];

switch ($action) {
    case 'new':
        $heading[] = ['text' => '<b>' . TEXT_HEADING_NEW_MANUFACTURER . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'manufacturers', $aContents['manufacturers'], 'action=insert', 'post', false, 'enctype="multipart/form-data"')];
        $contents[] = ['text' => TEXT_NEW_INTRO];
        $contents[] = ['text' => '<br>' . TEXT_MANUFACTURERS_NAME . '<br>' . oos_draw_input_field('manufacturers_name')];
        $contents[] = ['text' => '<br>' . TEXT_MANUFACTURERS_IMAGE . '<br>' . oos_draw_file_field('manufacturers_image')];



        $aLanguages = oos_get_languages();
        $nLanguages = is_countable($aLanguages) ? count($aLanguages) : 0;

        $manufacturer_inputs_string = '';
        for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
            $manufacturer_inputs_string .= '<br>' . oos_flag_icon($aLanguages[$i]) . '&nbsp;' . oos_draw_input_field('manufacturers_url[' . $aLanguages[$i]['id'] . ']');
        }

        $contents[] = ['text' => '<br>' . TEXT_MANUFACTURERS_URL . $manufacturer_inputs_string];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_SAVE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['manufacturers'], 'page=' . $nPage . '&mID=' . $_GET['mID']) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
        break;

    case 'edit':
        $heading[] = ['text' => '<b>' . TEXT_HEADING_EDIT_MANUFACTURER . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'manufacturers', $aContents['manufacturers'], 'page=' . $nPage . '&mID=' . $mInfo->manufacturers_id . '&action=save', 'post', false, 'enctype="multipart/form-data"')];
        $contents[] = ['text' => TEXT_EDIT_INTRO];
        $contents[] = ['text' => '<br>' . TEXT_MANUFACTURERS_NAME . '<br>' . oos_draw_input_field('manufacturers_name', $mInfo->manufacturers_name)];

        $contents[] = ['text' => '<br>' . TEXT_MANUFACTURERS_IMAGE . '<br>' . oos_draw_file_field('manufacturers_image') . '<br>' . $mInfo->manufacturers_image];

        $aLanguages = oos_get_languages();
        $nLanguages = is_countable($aLanguages) ? count($aLanguages) : 0;

        $manufacturer_inputs_string = '';
        for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
            $manufacturer_inputs_string .= '<br>' . oos_flag_icon($aLanguages[$i]) . '&nbsp;' . oos_draw_input_field('manufacturers_url[' . $aLanguages[$i]['id'] . ']', oos_get_manufacturer_url($mInfo->manufacturers_id, $aLanguages[$i]['id']));
        }

        $contents[] = ['text' => '<br>' . TEXT_MANUFACTURERS_URL . $manufacturer_inputs_string];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_SAVE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['manufacturers'], 'page=' . $nPage . '&mID=' . $mInfo->manufacturers_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
        break;

    case 'delete':
        $heading[] = ['text' => '<b>' . TEXT_HEADING_DELETE_MANUFACTURER . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'manufacturers', $aContents['manufacturers'], 'page=' . $nPage . '&mID=' . $mInfo->manufacturers_id . '&action=deleteconfirm', 'post', false)];
        $contents[] = ['text' => TEXT_DELETE_INTRO];
        $contents[] = ['text' => '<br><b>' . $mInfo->manufacturers_name . '</b>'];
        $contents[] = ['text' => '<br>' . oos_draw_checkbox_field('delete_image', '', true) . ' ' . TEXT_DELETE_IMAGE];

        if ($mInfo->products_count > 0) {
            $contents[] = ['text' => '<br>' . oos_draw_checkbox_field('delete_products') . ' ' . TEXT_DELETE_PRODUCTS];
            $contents[] = ['text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $mInfo->products_count)];
        }

        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['manufacturers'], 'page=' . $nPage . '&mID=' . $mInfo->manufacturers_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
        break;

    default:
        if (isset($mInfo) && is_object($mInfo)) {
            $heading[] = ['text' => '<b>' . $mInfo->manufacturers_name . '</b>'];

            $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['manufacturers'], 'page=' . $nPage . '&mID=' . $mInfo->manufacturers_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['manufacturers'], 'page=' . $nPage . '&mID=' . $mInfo->manufacturers_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>'];
            $contents[] = ['text' => '<br>' . TEXT_DATE_ADDED . ' ' . oos_date_short($mInfo->date_added)];
            if (oos_is_not_null($mInfo->last_modified)) {
                $contents[] = ['text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($mInfo->last_modified)];
            }
            $manufacturers_image = 'brands/medium/' . $mInfo->manufacturers_image;
            $contents[] = ['text' => '<br>' . oos_info_image($manufacturers_image, $mInfo->manufacturers_name)];
            $contents[] = ['text' => '<br>' . TEXT_PRODUCTS . ' ' . $mInfo->products_count];
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

if (isset($aDocument) || !empty($aDocument)) {
    echo '<script nonce="' . NONCE . '">' . "\n";
    $nDocument = is_countable($aDocument) ? count($aDocument) : 0;
    for ($i = 0, $n = $nDocument; $i < $n; $i++) {
        echo 'document.getElementById(\'row-'. $aDocument[$i]['id'] . '\').addEventListener(\'click\', function() { ' . "\n";
        echo 'document.location.href = "' . $aDocument[$i]['link'] . '";' . "\n";
        echo '});' . "\n";
    }
    echo '</script>' . "\n";
}

?>
<script nonce="<?php echo NONCE; ?>">
// Add an event listener to the select element
document.getElementById('page').addEventListener('change', function() { 
	// Submit the form 
	this.form.submit(); 
}); 
</script>
<?php
require 'includes/nice_exit.php';
