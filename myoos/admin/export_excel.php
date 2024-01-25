<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

switch ($action) {
    case 'make_file_now':
        $excel_file = 'db_export-' . date('YmdHis') . '.cvs';
        $fp = fopen(OOS_EXPORT_PATH . $excel_file, 'w');

        $schema = '';
        $schema .= 'id | Model | Name | tax_class_id | Status |  Net Price | Gross Price ' .  "\n";

        $nLanguageID = intval($_SESSION['language_id']);

        $productstable = $oostable['products'];
        $products_descriptiontable = $oostable['products_description'];
        $sql = "SELECT p.products_id, p.products_model, p.products_price, p.products_tax_class_id, p.products_status, pd.products_name
			FROM $productstable p,
				 $products_descriptiontable pd
			WHERE p.products_id = pd.products_id
			  AND pd.products_languages_id = '" . intval($nLanguageID) . "'";
        $products_result = $dbconn->Execute($sql);

        $rows = 0;
        while ($products = $products_result->fields) {
            $rows++;

            $name = $products['products_name'];
            $name = str_replace('|', ' ', (string) $name);
            $name = strip_tags($name);

            $price = $products['products_price'];
            $tax = (100 + oos_get_tax_rate($products['products_tax_class_id'])) / 100;
            $price = number_format(oos_round($price * $tax, 2), 2, '.', '');

            $schema .= $products['products_id']. '|'  . $products['products_model'] . '|' . $name . '|' . $products['products_tax_class_id'] . '|' . $products['products_status'] . '|' . $products['products_price'] . '|' . $price . "\n";

            // Move that ADOdb pointer!
            $products_result->MoveNext();
        }


        fputs($fp, $schema);
        fclose($fp);

        if (isset($_POST['download']) && ($_POST['download'] == 'yes')) {
            # todo
            /*
                switch ($_POST['compress']) {
                case 'gzip':
                  exec(LOCAL_EXE_GZIP . ' ' . OOS_EXPORT_PATH . $excel_file);
                  $excel_file .= '.gz';
                  break;

                case 'zip':
                  exec(LOCAL_EXE_ZIP . ' -j ' . OOS_EXPORT_PATH . $excel_file . '.zip ' . OOS_EXPORT_PATH . $excel_file);
                  @unlink(OOS_EXPORT_PATH . $excel_file);
                  $excel_file .= '.zip';
              }
            */
            header('Content-type: application/x-octet-stream');
            header('Content-disposition: attachment; filename=' . $excel_file);

            readfile(OOS_EXPORT_PATH . $excel_file);
            @unlink(OOS_EXPORT_PATH . $excel_file);

            exit;
        } else {
            # todo
            /*
            switch ($_POST['compress']) {
            case 'gzip':
              exec(LOCAL_EXE_GZIP . ' ' . $excel_file);
              break;

            case 'zip':
              exec(LOCAL_EXE_ZIP . ' -j ' . $excel_file . '.zip ' . $excel_file);
              unlink(OOS_EXPORT_PATH . $excel_file);
      }
                  */
            $messageStack->add_session(SUCCESS_DATABASE_SAVED, 'success');
        }
        oos_redirect_admin(oos_href_link_admin($aContents['export_excel']));
        break;

    case 'download':
        $sFile = oos_db_prepare_input($_GET['file']);
        $extension = substr((string) $_GET['file'], -3);
        if (($extension == 'zip') || ($extension == '.gz') || ($extension == 'cvs')) {
            if ($fp = fopen(OOS_EXPORT_PATH . $sFile, 'rb')) {
                $buffer = fread($fp, filesize(OOS_EXPORT_PATH . $sFile));
                fclose($fp);
                header('Content-type: application/x-octet-stream');
                header('Content-disposition: attachment; filename=' . $sFile);
                echo $buffer;
                exit;
            }
        } else {
            $messageStack->add(ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE, 'error');
        }
        break;
    case 'deleteconfirm':
        if (strstr((string) $_GET['file'], '..')) {
            oos_redirect_admin(oos_href_link_admin($aContents['export_excel']));
        }

        oos_remove(OOS_EXPORT_PATH . '/' . oos_db_prepare_input($_GET['file']));
        if (!$oos_remove_error) {
            $messageStack->add_session(SUCCESS_EXPORT_DELETED, 'success');
            oos_redirect_admin(oos_href_link_admin($aContents['export_excel']));
        }
        break;
}


// check if the backup directory exists
$dir_ok = false;
if (is_dir(oos_get_local_path(OOS_EXPORT_PATH))) {
    if (is_writeable(oos_get_local_path(OOS_EXPORT_PATH))) {
        $dir_ok = true;
    } else {
        $messageStack->add(ERROR_EXPORT_DIRECTORY_NOT_WRITEABLE, 'error');
    }
} else {
    $messageStack->add(ERROR_EXPORT_DIRECTORY_DOES_NOT_EXIST, 'error');
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
							<th><?php echo TABLE_HEADING_TITLE; ?></th>
							<th class="text-center"><?php echo TABLE_HEADING_FILE_DATE; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_FILE_SIZE; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>
<?php
if ($dir_ok) {
    $dir = dir(OOS_EXPORT_PATH);
    $contents = [];
    while ($file = $dir->read()) {
        if (($file != '.') && ($file != '..') && ($file != '.htaccess')) {
            if (!is_dir(OOS_EXPORT_PATH . $file)) {
                $contents[] = $file;
            }
        }
    }
    rsort($contents);

    $rows = 0;
    $aDocument = [];

    for ($files = 0, $count = count($contents); $files < $count; $files++) {
        $rows = $files;
        $entry = $contents[$files];

        $check = 0;

        if ((!isset($_GET['file']) || (isset($_GET['file']) && ($_GET['file'] == $entry))) && !isset($buInfo) && ($action != 'backup')) {
            $file_array['file'] = $entry;
            $file_array['date'] = date(PHP_DATE_TIME_FORMAT, filemtime(OOS_EXPORT_PATH . $entry));
            $file_array['size'] = number_format(filesize(OOS_EXPORT_PATH . $entry)) . ' bytes';

            $file_array['compression'] = match (substr($entry, -3)) {
                'zip' => 'ZIP',
                '.gz' => 'GZIP',
                default => TEXT_NO_EXTENSION,
            };

            $buInfo = new objectInfo($file_array);
        }

        $onclick_link = 'file=' . $entry;
        if (isset($buInfo) && is_object($buInfo) && ($entry == $buInfo->file)) {
            echo '              <tr id="row-' . $rows .'">' . "\n";
        } else {
            $aDocument[] = ['id' => $rows,
                            'link' => oos_href_link_admin($aContents['export_excel'], $onclick_link)];
            echo '              <tr id="row-' . $rows .'">' . "\n";
        }
        ?>
                <td><?php echo '<a href="' . oos_href_link_admin($aContents['export_excel'], 'action=download&file=' . $entry) . '"><button class="btn btn-default" type="button"><i class="fa fa-download" title="' . ICON_FILE_DOWNLOAD . '" aria-hidden="true"></i></button></a>&nbsp;' . $entry; ?></td>
                <td align="center"><?php echo date(PHP_DATE_TIME_FORMAT, filemtime(OOS_EXPORT_PATH . $entry)); ?></td>
                <td align="right"><?php echo number_format(filesize(OOS_EXPORT_PATH . $entry)); ?> bytes</td>
                <td class="text-right"><?php if (isset($buInfo) && is_object($buInfo) && ($entry == $buInfo->file)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['export_excel'], 'file=' . $entry) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></button></a>';
                } ?>&nbsp;</td>
              </tr>
<?php
    }
    $dir->close();
}
?>
              <tr>
                <td class="smallText" colspan="3"><?php echo TEXT_EXPORT_DIRECTORY . ' ' . OOS_EXPORT_PATH; ?></td>
                <td align="right" class="smallText"><?php if ($action != 'backup') {
                    echo '<a href="' . oos_href_link_admin($aContents['export_excel'], 'action=backup') . '">' . oos_button(BUTTON_EXPORT) . '</a>';
                } ?></td>
             </tr>
            </table></td>
<?php
                  $heading = [];
$contents = [];

switch ($action) {
    case 'backup':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_NEW_EXPORT . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'backup', $aContents['export_excel'], 'action=make_file_now', 'post', false)];
        $contents[] = ['text' => TEXT_INFO_NEW_EXPORT];

        # todo
        #if (file_exists(LOCAL_EXE_ZIP)) {
        #$contents[] = array('text' => oos_draw_radio_field('compress', 'zip') . ' ' . TEXT_INFO_USE_ZIP);
        #}

        if ($dir_ok == true) {
            $contents[] = ['text' => '<br>' . oos_draw_checkbox_field('download', 'yes') . ' ' . TEXT_INFO_DOWNLOAD_ONLY . '*<br><br>*' . TEXT_INFO_BEST_THROUGH_HTTPS];
        } else {
            $contents[] = ['text' => '<br>' . oos_draw_radio_field('download', 'yes', true) . ' ' . TEXT_INFO_DOWNLOAD_ONLY . '*<br><br>*' . TEXT_INFO_BEST_THROUGH_HTTPS];
        }

        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_EXPORT) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['export_excel']) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

        break;

    case 'delete':
        $heading[] = ['text' => '<b>' . $buInfo->date . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'delete', $aContents['export_excel'], 'file=' . $buInfo->file . '&action=deleteconfirm', 'post', false)];
        $contents[] = ['text' => TEXT_DELETE_INTRO];
        $contents[] = ['text' => '<br><b>' . $buInfo->file . '</b>'];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['export_excel'], 'file=' . $buInfo->file) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

        break;

    default:
        if (isset($buInfo) && is_object($buInfo)) {
            $heading[] = ['text' => '<b>' . $buInfo->date . '</b>'];

            $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['export_excel'], 'file=' . $buInfo->file . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>'];
            $contents[] = ['text' => '<br>' . TEXT_INFO_DATE . ' ' . $buInfo->date];
            $contents[] = ['text' => TEXT_INFO_SIZE . ' ' . $buInfo->size];
            $contents[] = ['text' => '<br>' . TEXT_INFO_COMPRESSION . ' ' . $buInfo->compression];
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

require 'includes/nice_exit.php';
