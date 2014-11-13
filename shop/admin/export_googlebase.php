<?php
/* ----------------------------------------------------------------------
   $Id: export_googlebase.php,v 1.2 2007/10/27 21:29:39 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   Edgeio.com and googlebase feed - v. 1.0 (May '06)
   by Andrew Yasinsky (andrew@edgeio.com)


   Ported to CRELoaded by maestro
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/*
http://base.google.de/base/help/attributes.html
*/


/*
  error_reporting(E_ALL & ~E_NOTICE);
  ini_set('display_errors',1);
*/

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';


//CHANGE PARAMETERS BELOW///
  $feed_title = ' '; //Feed Title
  $feed_description = ''; //Feed Description

 # $location_address = 'Straße und Hausnummer, Postleitzahl und Ort, Land'; //Die Adresse sollte folgendes Format haben: Straße und Hausnummer, Postleitzahl und Ort, Land. Jedes Standortelement sollte durch ein Komma getrennt werden. Orte werden über Google Maps  validiert. 
  $location_address = ''; //Die Adresse sollte folgendes Format haben: Straße und Hausnummer, Postleitzahl und Ort, Land. Jedes Standortelement sollte durch ein Komma getrennt werden. Orte werden über Google Maps  validiert. 

  $location_city = '';
  $location_state = '';
  $location_zip = '';
  $location_country = '';

  $ttl = 30; //Time to expiration in days minimum 30 max 90
  $payment_accepted = array('Bar', 'Scheck', 'Überweisung'); //  Zulässige Werte:  Bargeld ,  Scheck ,  GoogleCheckout ,  Visa ,  MasterCard ,  AmericanExpress ,  Lastschrift  und  Überweisung .


//GOOGLE SPECIFIC Settings
  $dryrun = false; //whether or not execute actual upload to both or stop at file generation

  $ftp_destination_file = "google_feed.xml";  //the upload file name specified on google base
  $ftp_server = "uploads.google.com" ; //google ftp server
  $ftp_user_name = ""; //ftp user name
  $ftp_password = ""; //ftp password
  $ftp_directory = ""; // leave blank

  $site_url = OOS_HTTP_SERVER . OOS_SHOP; //Site URL
  $image_url = OOS_HTTP_SERVER . OOS_SHOP . OOS_IMAGES . OOS_POPUP_IMAGES; //Base URL for images
  $product_url = OOS_HTTP_SERVER . OOS_SHOP . 'index.php?mp=' . $oosModules['products'] . '&file=' . $oosCatalogFilename['product_info'] . '&products_id=';

  $currency = 'EUR'; //  ISO 4217. http://www.iso.org/iso/en/prods-services/popstds/currencycodeslist.html
  $file_google = "google_feed.xml";


  function xmlentities($sStr) {
     return str_replace ( array ( '&', '"', "'", '<', '>', '?' ), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;', '&apos;' ), $sStr );
  }

  function get_iso_8601_date($int_date) {
     //$int_date: current date in UNIX timestamp
     $date_mod = date('Y-m-d\TH:i:s', $int_date);
     $pre_timezone = date('O', $int_date);
     $time_zone = substr($pre_timezone, 0, 3).":".substr($pre_timezone, 3, 2);
     $date_mod .= $time_zone;

     return $date_mod;
  }


  //Start FTP to Google Base
  function ftp_file( $ftp_server, $ftp_user_name, $ftp_password, $ftpsourcefile, $ftp_directory, $ftp_destination_file ) {
    // set up basic connection
    $conn_id = ftp_connect($ftp_server);

    if ( $conn_id == false ) {
      echo "FTP open connection failed to $ftp_server <BR>\n" ;
      return false;
    }

    // login with username and password
    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_password);

    // check connection
    if ((!$conn_id) || (!$login_result)) {
      echo "FTP connection has failed!<BR>\n";
      echo "Attempted to connect to " . $ftp_server . " for user " . $ftp_user_name . "<BR>\n";
      return false;
    } else {
      echo "Connected to " . $ftp_server . ", for user " . $ftp_user_name . "<BR>\n";
    }

    if ( strlen( $ftp_directory ) > 0 ) {
      if (ftp_chdir($conn_id, $ftp_directory )) {
        echo "Current directory is now: " . ftp_pwd($conn_id) . "<BR>\n";
      } else {
        echo "Couldn't change directory on $ftp_server<BR>\n";
        return false;
      }
    }

    ftp_pasv ( $conn_id, true ) ;
    // upload the file
    $upload = ftp_put( $conn_id, $ftp_destination_file, $ftpsourcefile, FTP_ASCII );

    // check upload status
    if (!$upload) {
      echo "$ftp_server: FTP upload has failed!<BR>\n";
      return false;
    } else {
      echo "Uploaded " . $ftpsourcefile . " to " . $ftp_server . " as " . $ftp_destination_file . "<BR>\n";
    }

    // close the FTP stream
    ftp_close($conn_id);

    return true;
  }





  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {

      case 'make_file_now':

        $output='<?xml version="1.0" encoding="UTF-8" ?>'."\n";
        $output.='<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">'."\n";
        $output.='<channel>'."\n";
        $output.='<title>' . $feed_title.'</title>'."\n";
        $output.='<description>' . $feed_description.'</description>'."\n";
        $output.='<link>' . $site_url.'</link>'."\n";

        $limit = 0;
        $limit_step = 1000;

        do {
          $nLanguageID = intval($_SESSION['language_id']);

          $productstable = $oostable['products'];
          $products_descriptiontable = $oostable['products_description'];

          $sql = "SELECT p.products_id, p.products_model, p.products_image, p.products_price,
                         p.products_tax_class_id, p.products_quantity, pd.products_name, pd.products_description
                    FROM $productstable p,
                         $products_descriptiontable pd
                   WHERE p.products_id = pd.products_id
                     AND p.products_status >= '1'
                     AND pd.products_languages_id = '" . intval($nLanguageID) . "'";

           $products_result = $dbconn->SelectLimit($sql, $limit_step, $limit);
           $count = $products_result->RecordCount();

           if ($count > 0) {
             while ($products = $products_result->fields) {

               if ($specialprice = oos_get_products_special_price($products['products_id'])){
                 $price = $specialprice;
               } else {
                 $price = $products['products_price'];
               }

               if ( $price > 0) {
                 $tax = (100+oos_get_tax_rate($products['products_tax_class_id']))/100;
                 $price = number_format($price*$tax,2,".","");

                 $products_description = $products['products_description'];
                 $products_description = strip_tags($products_description);
                 $products_description = utf8_encode($products_description);

                 $output.='      <item>'."\n";
                 $output.='            <title>' . xmlentities(utf8_encode(strip_tags($products['products_name']))) . '</title>'."\n";
                 $output.='            <beschreibung>' . xmlentities($products_description) . '</beschreibung>'."\n";
                 $output.='            <link>' .  xmlentities($product_url . $products['products_id']) . '</link>'."\n";
                 if ($products['products_image'] != '') {
                   $output.='            <g:bild_url>' . $image_url . $products['products_image'] . '</g:bild_url>'."\n";
                 }
                 $output.='            <g:bild_url>' . $image_url . $products['products_image'] . '</g:bild_url>'."\n";
                 $output.='            <g:währung>' . $currency . '</g:währung>'."\n";
                 $output.='            <g:preis>' . $price . '</g:preis>'."\n";
                 $output.='            <g:modellnummer>' . $products['products_model'] . '</g:modellnummer>'."\n";
                 $output.='            <g:menge>' . $products['products_quantity'].'</g:menge>'."\n";
                 foreach($payment_accepted as $key=>$value){
                   $output.='            <g:zahlungsmethode>'.$value.'</g:zahlungsmethode>'."\n";
                 }
                 $output.='            <g:zahlungsrichtlinien>Lokale Bestellungen nur gegen Barzahlung</g:zahlungsrichtlinien>'."\n";
                 $output.='            <g:standort>'.xmlentities($location_address).'</g:standort>'."\n";
                 $output.='      </item>'."\n";
               }
               $products_result->MoveNext();
             }
           }
           $limit += $limit_step;
           flush();
           sleep(2);
         } while ($count > 0);

         $output.='</channel>'."\n";
         $output.='</rss>'."\n";

         if ( file_exists( OOS_FEEDS_EXPORT_PATH . $file_google ) ) {
            unlink( OOS_FEEDS_EXPORT_PATH . $file_google );
         }
         $fp = fopen( OOS_FEEDS_EXPORT_PATH . $file_google , "w" );
         $fout = fwrite( $fp , $output );
         fclose( $fp );


         if (!$dryrun){
           $result = ftp_file( $ftp_server, $ftp_user_name, $ftp_password, OOS_FEEDS_EXPORT_PATH . $file_google, $ftp_directory, $ftp_destination_file);
         }

         $messageStack->add(SUCCESS_EXPORT_DATABASE_SAVED, 'success');
        break;

      case 'deleteconfirm':
        if (strstr($_GET['file'], '..')) oos_redirect_admin(oos_href_link_admin($aFilename['export_googlebase']));

        oos_remove(OOS_FEEDS_EXPORT_PATH . '/' . $_GET['file']);
        if (!$oos_remove_error) {
          $messageStack->add_session(SUCCESS_EXPORT_DELETED, 'success');
          oos_redirect_admin(oos_href_link_admin($aFilename['export_googlebase']));
        }
        break;
    }
  }

// check if the backup directory exists
  $dir_ok = false;
  if (is_dir(oos_get_local_path(OOS_FEEDS_EXPORT_PATH))) {
    if (is_writeable(oos_get_local_path(OOS_FEEDS_EXPORT_PATH))) {
      $dir_ok = true;
    } else {
      $messageStack->add(ERROR_EXPORT_DIRECTORY_NOT_WRITEABLE, 'error');
    }
  } else {
    $messageStack->add(ERROR_EXPORT_DIRECTORY_DOES_NOT_EXIST, 'error');
  }


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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TITLE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_FILE_DATE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_FILE_SIZE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  if ($dir_ok) {
    $dir = dir(OOS_FEEDS_EXPORT_PATH);
    $contents = array();
    while ($file = $dir->read()) {
      if (!is_dir(OOS_FEEDS_EXPORT_PATH . $file)) {
        $contents[] = $file;
      }
    }
    sort($contents);

    for ($files = 0, $count = count($contents); $files < $count; $files++) {
      $entry = $contents[$files];

      $check = 0;

      if (((!$_GET['file']) || ($_GET['file'] == $entry)) && (!$buInfo) && ($action != 'make_file')) {
        $file_array['file'] = $entry;
        $file_array['date'] = date(PHP_DATE_TIME_FORMAT, filemtime(OOS_FEEDS_EXPORT_PATH . $entry));
        $file_array['size'] = number_format(filesize(OOS_FEEDS_EXPORT_PATH . $entry)) . ' bytes';

        $buInfo = new objectInfo($file_array);
      }

      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'">' . "\n";
      $onclick_link = 'file=' . $entry;
?>
                <td class="dataTableContent" ><?php echo '&nbsp;' . $entry; ?></td>
                <td class="dataTableContent" align="center" onclick="document.location.href='<?php echo oos_href_link_admin($aFilename['export_googlebase'], $onclick_link); ?>'"><?php echo date(PHP_DATE_TIME_FORMAT, filemtime(OOS_FEEDS_EXPORT_PATH . $entry)); ?></td>
                <td class="dataTableContent" align="right" onclick="document.location.href='<?php echo oos_href_link_admin($aFilename['export_googlebase'], $onclick_link); ?>'"><?php echo number_format(filesize(OOS_FEEDS_EXPORT_PATH . $entry)); ?> bytes</td>
                <td class="dataTableContent" align="right"><?php if (isset($buInfo) && is_object($buInfo) && ($entry == $buInfo->file) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aFilename['export_googlebase'], 'file=' . $entry) . '">' . oos_image(OOS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
    $dir->close();
  }
?>
              <tr>
                <td class="smallText" colspan="3"><?php echo TEXT_EXPORT_DIRECTORY . ' ' . OOS_FEEDS_EXPORT_PATH; ?></td>
                <td align="right" class="smallText"><?php if ( ($action != 'make_file') ) echo '<a href="' . oos_href_link_admin($aFilename['export_googlebase'], 'action=make_file') . '">' . oos_image_swap_button('make_file', 'backup_off.gif', IMAGE_BACKUP) . '</a>'; ?></td>
             </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {

    case 'make_file':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW .' </b>');

      $contents = array('form' => oos_draw_form('make_file_now', $aFilename['export_googlebase'], 'action=make_file_now'));
      $contents[] = array('text' => TEXT_INFO_NEW_INFO);
      $contents[] = array('text' => '<br />');
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('backup', 'backup_off.gif', IMAGE_BACKUP) . ' <a href="' . oos_href_link_admin($aFilename['export_googlebase'], '') . '">' . oos_image_swap_button('cancel', 'cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;


    case 'delete':
      $heading[] = array('text' => '<b>' . $buInfo->date . '</b>');

      $contents = array('form' => oos_draw_form('delete', $aFilename['export_googlebase'], 'file=' . $buInfo->file . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $buInfo->file . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete', 'delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aFilename['export_googlebase'], 'file=' . $buInfo->file) . '">' . oos_image_swap_button('cancel', 'cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (isset($buInfo) && is_object($buInfo)) {
        $heading[] = array('text' => '<b>' . $buInfo->date . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['export_googlebase'], 'file=' . $buInfo->file . '&action=delete') . '">' . oos_image_swap_button('delete', 'delete_off.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br />' . TEXT_INFO_DATE . ' ' . $buInfo->date);
        $contents[] = array('text' => TEXT_INFO_SIZE . ' ' . $buInfo->size);
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