<?php
/* ----------------------------------------------------------------------
   $Id: easypopulate.php,v 1.1 2007/06/08 17:14:41 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: easypopulate.php,v 2.75 2005/04/05 AL Exp 
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
  require 'includes/functions/function_easypopulate.php';

  if (!defined('OOS_EP_VERSION')) {
    // Current EP Version
    define('OOS_EP_VERSION', '2.76-OOS');
  }

  // vx anti register globals
  if (isset($_GET['split']) && !empty($_GET['split'])) {
    $split = $_GET['split'];
  }

  if (isset($_FILES['usrfl']) && !empty($_FILES['usrfl'])) {
    $usrfl = $_FILES['usrfl'];
  }

  if (isset($_POST['localfile']) && !empty($_POST['localfile'])) {
    $localfile = $_POST['localfile'];
  }

  if (isset($_GET['download']) && !empty($_GET['download'])) {
    $download = $_GET['download'];
  }

  if (isset($_GET['dltype']) && !empty($_GET['dltype'])) {
    $dltype = $_GET['dltype'];
  }
  // eof vx anti register globals

 /**
  * C O N F I G U R A T I O N    V A R I A B L E S
  */

 /**
  * Temp directory 
  * if you changed your directory structure from stock and 
  * do not have /catalog/temp/, then you'll need to change this accordingly.
  */
  $tempdir = "tmp/";

 /**
  * File Splitting Configuration
  * we attempt to set the timeout limit longer for this script 
  * to avoid having to split the files
  *
  * NOTE:  If your server is running in safe mode, this setting 
  * cannot override the timeout set in php.ini
  * uncomment this if you are not on a safe mode server and you are getting timeouts
  */
  if (strlen(ini_get("safe_mode"))< 1) {
    @set_time_limit(330); // VJ changed
  }


 /**
  * if you are splitting files, this will set the maximum number of records to put in each file.
  * if you set your php.ini to a long time, you can make this number bigger
  */
  GLOBAL $maxrecs;
  $maxrecs = 300; // default, seems to work for most people.  Reduce if you hit timeouts
  //$maxrecs = 4; // for testing


 /**
  * Image Defaulting
  */
  GLOBAL $default_images, $default_image_manufacturer, $default_image_product, $default_image_category;

 /**
  * set them to your own default "We don't have any picture".gif
  */
  //$default_image_manufacturer = 'no_image_manufacturer.gif';
  //$default_image_product = 'no_picture.gif';
  //$default_image_category = 'no_image_category.gif';

  // or let them get set to nothing
  $default_image_manufacturer = '';
  $default_image_product = '';
  $default_image_category = '';

 /**
  * Status Field Setting 
  * Set the v_status field to "Inactive" if you want the status=0 in the system
  * Set the v_status field to "Delete" if you want to remove the item from the system <- THIS IS NOT WORKING YET!
  * If zero_qty_inactive is true, then items with zero qty will automatically be inactive in the store.
  */
  GLOBAL $ps_instock, $ps_outstock, $ps_soon, $ps_notav, $zero_qty_inactive, $deleteit;

  //$active = 'Active'; vexoid
  //$inactive = 'Inactive'; vexoid
  
  $ps_instock = 'Active';
  $ps_outstock = 'Out of Stock';
  $ps_soon = 'Soon';
  $ps_notav = 'Inactive';
  //$deleteit = 'Delete'; // not functional yet
  $zero_qty_inactive = true;

 /**
  * Size of products_model in products table 
  * set this to the size of your model number field in the db. 
  * We check to make sure all models are no longer than this value.
  * this prevents the database from getting fubared.  
  * Just making this number bigger won't help your database! 
  * They must match!
  */
  GLOBAL $modelsize;
  $modelsize = 35;

 /**
  * Price includes tax? 
  * Set the v_price_with_tax to
  * 0 if you want the price without the tax included
  * 1 if you want the price to be defined for import & export including tax.
  */
  GLOBAL $price_with_tax;
  $price_with_tax = 1;

 /**
  * Quote -> Escape character conversion 
  * If you have extensive html in your descriptions and it's getting 
  * mangled on upload, turn this off
  * set to 1 = replace quotes with escape characters
  * set to 0 = no quote replacement
  */
  GLOBAL $replace_quotes;
  $replace_quotes = true;

 /**
  * Field Separator 
  * change this if you can't use the default of tabs
  * Tab is the default, comma and semicolon are commonly supported by various progs
  * Remember, if your descriptions contain this character, you will confuse EP!
  */
  GLOBAL $separator;
  $separator = "\t"; // tab is default
  //$separator = ","; // comma
  //$separator = ";"; // semi-colon
  //$separator = "~"; // tilde
  //$separator = "-"; // dash
  //$separator = "*"; // splat

 /**
  * Max Category Levels 
  * change this if you need more or fewer categories
  */
  GLOBAL $max_categories;
  $max_categories = 3; // 7 is default

 /**
  * VJ product attributes begin
  *
  * Product Attributes 
  * change this to false, if do not want to download product attributes
  */
  global $products_with_attributes;
  $products_with_attributes = true; 

  // change this to true, if you use QTYpro and want to set attributes stock with EP.
  global $products_attributes_stock;
  $products_attributes_stock = false; 


 /* change this if you want to download selected product options
  * this might be handy, if you have a lot of product options, and your 
  * output file exceeds 256 columns (which is the max. limit MS Excel is able to handle)
  */
  GLOBAL $attribute_options_select;
  //$attribute_options_select = array('Size', 'Model'); // uncomment and fill with product options name you wish to download // comment this line, if you wish to download all product options
  // VJ product attributes end


 /**
  * Froogle configuration variables
  * YOU MUST CONFIGURE THIS!
  * IT WON'T WORK OUT OF THE BOX!
  */

 /**
  * Froogle product info page path 
  * We can't use the tep functions to create the link, 
  * because the links will point to the admin, 
  * since that's where we're at.
  * so put the entire path to your product_info.php page here
  */
  GLOBAL $froogle_product_info_path;
   //$froogle_product_info_path = "http://www.your-domain.com/shop/index.php?mp=products&file=info";
   $froogle_product_info_path = OOS_HTTP_SERVER . OOS_SHOP . 'index.php?mp=' . $oosModules['products'] . '&file=' . $oosCatalogFilename['product_info'];

 /**
  * Froogle product image path 
  * Set this to the path to your images directory
  */
  GLOBAL $froogle_image_path;
   //$froogle_image_path = "http://www.your-domain.com/shop/images/";
   $froogle_image_path = OOS_HTTP_SERVER . OOS_IMAGES . $products['products_image'];


  // VJ product attributes begin
  GLOBAL $attribute_options_array;
  $attribute_options_array = array();
  if ($products_with_attributes == true) {
    if (is_array($attribute_options_select) && (count($attribute_options_select) > 0)) {
      foreach ($attribute_options_select as $value) {
        $attribute_options_result = "SELECT distinct products_options_id FROM " . $oostable['products_options'] . " WHERE products_options_name = '" . $value . "'";

        $attribute_options_values = $dbconn->Execute($attribute_options_result);

        if ($attribute_options = $attribute_options_values->fields){
          $attribute_options_array[] = array('products_options_id' => $attribute_options['products_options_id']);
        }
      }
    } else {
      $attribute_options_result = "SELECT distinct products_options_id FROM " . $oostable['products_options'] . " order by products_options_id";

      $attribute_options_values = $dbconn->Execute($attribute_options_result);

      while ($attribute_options = $attribute_options_values->fields){
        $attribute_options_array[] = array('products_options_id' => $attribute_options['products_options_id']);

        // Move that ADOdb pointer!
        $attribute_options_values->MoveNext();
      }
    }
  }
  // VJ product attributes end

  GLOBAL $filelayout, $filelayout_count, $filelayout_sql, $langcode, $fileheaders;

  // these are the fields that will be defaulted to the current values in the database if they are not found in the incoming file
  GLOBAL $default_these;
  $default_these = array('v_products_image',
                         #'v_products_mimage',
                         #'v_products_bimage',
                         #'v_products_subimage1',
                         #'v_products_bsubimage1',
                         #'v_products_subimage2',
                         #'v_products_bsubimage2',
                         #'v_products_subimage3',
                         #'v_products_bsubimage3',
                         'v_categories_id',
                         'v_products_price',
                         'v_products_quantity',
                         'v_products_weight',
                         'v_date_avail',
                         'v_instock',
                         'v_tax_class_title',
                         'v_manufacturers_name',
                         'v_manufacturers_id',
                         'v_products_dim_type',
                         'v_products_length',
                         'v_products_width',
                         'v_products_height',
                         'v_products_upc'
                        );


  $langcode = ep_get_languages();

  if ($dltype != ''){
    // if dltype is set, then create the filelayout.  Otherwise it gets read from the uploaded file
    ep_create_filelayout($dltype); // get the right filelayout for this download
  }




  if ($download == 'stream' or  $download == 'tempfile'){

    $sFile = ''; // this holds the csv file we want to download
    $result = $dbconn->Execute($filelayout_sql);

    // Here we need to allow for the mapping of internal field names to external field names
    // default to all headers named like the internal ones
    // the field mapping array only needs to cover those fields that need to have their name changed
    if ( count($fileheaders) != 0 ){
      $filelayout_header = $fileheaders; // if they gave us fileheaders for the dl, then use them
    } else {
      $filelayout_header = $filelayout; // if no mapping was spec'd use the internal field names for header names
    }
    //We prepare the table heading with layout values
    foreach( $filelayout_header as $key => $value ) {
      $sFile .= $key . $separator;
    }
    // now lop off the trailing tab
    $sFile = substr($sFile, 0, strlen($sFile)-1);

    // set the type
    if ( $dltype == 'froogle' ){
      $endofrow = "\n";
    } else {
      // default to normal end of row
      $endofrow = $separator . 'EOREOR' . "\n";
    }
    $sFile .= $endofrow;

    $num_of_langs = count($langcode);
    while ($row = $result->fields){
      // if the filelayout says we need a products_name, get it
      // build the long full froogle image path
      $row['v_products_fullpath_image'] = $froogle_image_path . $row['v_products_image'];
      // Other froogle defaults go here for now
      $row['v_froogle_instock']         = 'Y';
      $row['v_froogle_shipping']        = '';

      $row['v_froogle_upc']             = '';
      $row['v_froogle_color']           = '';
      $row['v_froogle_size']            = '';
      $row['v_froogle_quantitylevel']   = '';
      $row['v_froogle_manufacturer_id'] = '';

      $row['v_froogle_exp_date']        = '';
      $row['v_froogle_product_type']    = 'Sonstige';
      $row['v_froogle_delete']          = '';
      $row['v_froogle_currency']        = 'EUR';
      $row['v_froogle_offer_id']        = $row['v_products_model'];
      $row['v_froogle_product_id']      = $row['v_products_model'];

      // names and descriptions require that we loop thru all languages that are turned on in the store
      foreach ($langcode as $key => $lang){
        $lid = $lang['id'];

        // for each language, get the description and set the vals
        $sql2 = "SELECT *
                 FROM " . $oostable['products_description'] . "
                 WHERE products_id = " . $row['v_products_id'] . "
                   AND products_languages_id = '" . $lid . "'";
        $result2 = $dbconn->Execute($sql2);
        $row2 = $result2->fields;


          // if ($num_of_langs == 1) {
          $row['v_froogle_products_url_'] = $froogle_product_info_path . '&products_id=' . $row['v_products_id'];
          // } else {
          //   $row['v_froogle_products_url_' . $lid] = $froogle_product_info_path . '&products_id=' . $row['v_products_id'] . '&language=' . $lid;
          // }
        //}

        $row['v_products_name_' . $lid]         = $row2['products_name'];
        $row['v_products_description_' . $lid]  = $row2['products_description'];
        $row['v_products_url_' . $lid]          = $row2['products_url'];

        // froogle advanced format needs the quotes around the name and desc
        $row['v_froogle_products_name_' . $lid] = '"' . strip_tags(str_replace('"','""',$row2['products_name'])) . '"';
        $row['v_froogle_products_description_' . $lid] = '"' . strip_tags(str_replace('"','""',$row2['products_description'])) . '"';

        // support for Linda's Header Controller 2.0 here
        if (isset($filelayout['v_products_head_title_tag_' . $lid])) {
          $row['v_products_head_title_tag_' . $lid]   = $row2['products_head_title_tag'];
          $row['v_products_head_desc_tag_' . $lid]  = $row2['products_head_desc_tag'];
          $row['v_products_head_keywords_tag_' . $lid]  = $row2['products_head_keywords_tag'];
        }
      }

      // for the categories, we need to keep looping until we find the root category

      // start with v_categories_id
      // Get the category description
      // set the appropriate variable name
      // if parent_id is not null, then follow it up.
      // we'll populate an aray first, then decide where it goes in the
      $thecategory_id = $row['v_categories_id'];
      $fullcategory = ''; // this will have the entire category stack for froogle
      for ( $categorylevel=1; $categorylevel<$max_categories+1; $categorylevel++){
        if ($thecategory_id){
          $sql2 = "SELECT categories_name
                   FROM " . $oostable['categories_description'] . "
                   WHERE categories_id = " . $thecategory_id . " 
                     AND categories_languages_id = '" . intval($_SESSION['language_id']) ."'";
          $result2 = $dbconn->Execute($sql2);
          $row2 = $result2->fields;
          // only set it if we found something
          $temprow['v_categories_name_' . $categorylevel] = $row2['categories_name'];
          // now get the parent ID if there was one
          $sql3 = "SELECT parent_id
                   FROM " . $oostable['categories'] . "
                   WHERE categories_id = " . $thecategory_id;
          $result3 = $dbconn->Execute($sql3);
          $row3 = $result3->fields;
          $theparent_id = $row3['parent_id'];
          if ($theparent_id != ''){
            // there was a parent ID, lets set thecategoryid to get the next level
            $thecategory_id = $theparent_id;
          } else {
            // we have found the top level category for this item,
            $thecategory_id = false;
          }
          //$fullcategory .= " > " . $row2['categories_name'];
          $fullcategory = $row2['categories_name'] . " > " . $fullcategory;
        } else {
          $temprow['v_categories_name_' . $categorylevel] = '';
        }
      }
      // now trim off the last ">" from the category stack
      $row['v_category_fullpath'] = substr($fullcategory,0,strlen($fullcategory)-3);

      // temprow has the old style low to high level categories.
      $newlevel = 1;
      // let's turn them into high to low level categories
      for( $categorylevel=6; $categorylevel>0; $categorylevel--){
        if ($temprow['v_categories_name_' . $categorylevel] != ''){
          $row['v_categories_name_' . $newlevel++] = $temprow['v_categories_name_' . $categorylevel];
        }
      }
      // if the filelayout says we need a manufacturers name, get it
      if (isset($filelayout['v_manufacturers_name'])){
        if ($row['v_manufacturers_id'] != ''){
          $sql2 = "SELECT manufacturers_name
                   FROM " . $oostable['manufacturers'] . "
                   WHERE manufacturers_id = " . $row['v_manufacturers_id'];
          $result2 = $dbconn->Execute($sql2);
          $row2 = $result2->fields;
          $row['v_manufacturers_name'] = $row2['manufacturers_name'];
        }
      }

      // If you have other modules that need to be available, put them here

    // VJ product attribs begin
    if (isset($filelayout['v_attribute_options_id_1'])){
      $languages = oos_get_languages();

      $attribute_options_count = 1;
      foreach ($attribute_options_array as $attribute_options) {
        $row['v_attribute_options_id_' . $attribute_options_count]  = $attribute_options['products_options_id'];

        for ($i=0, $n = count($languages); $i<$n; $i++) {
          $lid = $languages[$i]['id'];

          $attribute_options_languages_result = "SELECT products_options_name FROM " . $oostable['products_options'] . " WHERE products_options_id = '" . (int)$attribute_options['products_options_id'] . "' and products_options_languages_id = '" . (int)$lid . "'";

          $attribute_options_languages_values = $dbconn->Execute($attribute_options_languages_result);

          $attribute_options_languages = $attribute_options_languages_values->fields;

          $row['v_attribute_options_name_' . $attribute_options_count . '_' . $lid] = $attribute_options_languages['products_options_name'];
        }

        $attribute_values_result = "SELECT products_options_values_id FROM " . $oostable['products_options_values_to_products_options'] . " WHERE products_options_id = '" . (int)$attribute_options['products_options_id'] . "' order by products_options_values_id";

        $attribute_values_values = $dbconn->Execute($attribute_values_result);

        $attribute_values_count = 1;
        while ($attribute_values = $attribute_values_values->fields) {
          $row['v_attribute_values_id_' . $attribute_options_count . '_' . $attribute_values_count]   = $attribute_values['products_options_values_id'];
          $attribute_values_price_result = "SELECT options_values_price, price_prefix FROM " . $oostable['products_attributes'] . " WHERE products_id = '" . (int)$row['v_products_id'] . "' and options_id = '" . (int)$attribute_options['products_options_id'] . "' and options_values_id = '" . (int)$attribute_values['products_options_values_id'] . "'";
          $attribute_values_price_values = $dbconn->Execute($attribute_values_price_result);
          $attribute_values_price = $attribute_values_price_values->fields;
          $row['v_attribute_values_price_' . $attribute_options_count . '_' . $attribute_values_count]  = $attribute_values_price['price_prefix'] . $attribute_values_price['options_values_price'];


          // attributes stock add start
/*
          if ( $products_attributes_stock == true ) {
            $stock_attributes = $attribute_options['products_options_id'].'-'.$attribute_values['products_options_values_id'];
            $stock_quantity_result = $dbconn->Execute("SELECT products_stock_quantity FROM " . $oostable['PRODUCTS_STOCK'] . " WHERE products_id = '" . (int)$row['v_products_id'] . "' and products_stock_attributes = '" . $stock_attributes . "'");
            $stock_quantity = $stock_quantity_result->fields;

            $row['v_attribute_values_stock_' . $attribute_options_count . '_' . $attribute_values_count] = $stock_quantity['products_stock_quantity'];
          }
*/
          // attributes stock add end


          for ($i=0, $n = count($languages); $i<$n; $i++) {
            $lid = $languages[$i]['id'];
            $attribute_values_languages_result = "SELECT products_options_values_name FROM " . $oostable['products_options_values'] . " WHERE products_options_values_id = '" . (int)$attribute_values['products_options_values_id'] . "' and products_options_values_languages_id = '" . (int)$lid . "'";
            $attribute_values_languages_values = $dbconn->Execute($attribute_values_languages_result);
            $attribute_values_languages = $attribute_values_languages_values->fields;
            $row['v_attribute_values_name_' . $attribute_options_count . '_' . $attribute_values_count . '_' . $lid] = $attribute_values_languages['products_options_values_name'];
          }

          $attribute_values_count++;

          // Move that ADOdb pointer!
          $attribute_values_values->MoveNext();
        }

        $attribute_options_count++;
      }
    }

    // this is for the separate price per customer module
    if (isset($filelayout['v_customer_price_1'])){
      $sql2 = "SELECT customers_group_price, customers_group_id 
               FROM " . $oostable['products_groups'] . "
               WHERE products_id = " . $row['v_products_id'] . "
               ORDER BY customers_group_id";
      $result2 = $dbconn->Execute($sql2);
      $ll = 1;
      while($row2 = $result2->fields){
        $row['v_customer_group_id_' . $ll]  = $row2['customers_group_id'];
        $row['v_customer_price_' . $ll]   = $row2['customers_group_price'];

        // Move that ADOdb pointer!
        $result2->MoveNext();
        $ll++;
      }
    }
    if ($dltype == 'froogle'){
      // For froogle, we check the specials prices for any applicable specials, and use that price
      // by grabbing the specials id descending, we always get the most recently added special price
      // I'm checking status because I think you can turn off specials
      $sql2 = "SELECT specials_new_products_price
               FROM " . $oostable['specials'] . "
               WHERE products_id = " . $row['v_products_id'] . "
                 AND status = 1 
                 AND expires_date < CURRENT_TIMESTAMP
               ORDER BY specials_id DESC";
      $result2 = $dbconn->Execute($sql2);
      $ll = 1;
      $row2 = $result2->fields;
      if( $row2 ){
        // reset the products price to our special price if there is one for this product
        $row['v_products_price']  = $row2['specials_new_products_price'];
      }
    }

    //We check the value of tax class and title instead of the id
    //Then we add the tax to price if $price_with_tax is set to 1
    $row_tax_multiplier = oos_get_tax_class_rate($row['v_tax_class_id']); # ! ! !
    $row['v_tax_class_title']   = oos_cfg_get_tax_class_title($row['v_tax_class_id']);
    $row['v_products_price']  = round($row['v_products_price'] + ($price_with_tax * $row['v_products_price'] * $row_tax_multiplier / 100),2);


    // Now set the status to a word the user specd in the config vars
    if ( $row['v_status'] == '1' ){
      $row['v_status'] = $ps_outstock;
      // $row['v_status'] = $active; vexoid
    } elseif ( $row['v_status'] == '2' ) {
      $row['v_status'] = $ps_soon;
    } elseif ( $row['v_status'] == '3' ) {
      $row['v_status'] = $ps_instock;
    } else {
      $row['v_status'] = $ps_notav;
      // $row['v_status'] = $inactive; vexoid
    }

    // remove any bad things in the texts that could confuse EasyPopulate
    $therow = '';
    foreach( $filelayout as $key => $value ){
      //echo "The field was $key<br />";

      $thetext = $row[$key];
      // kill the carriage returns and tabs in the descriptions, they're killing me!
      $thetext = str_replace("\r",' ',$thetext);
      $thetext = str_replace("\n",' ',$thetext);
      $thetext = str_replace("\t",' ',$thetext);
      // and put the text into the output separated by tabs
      $therow .= $thetext . $separator;
    }

    // lop off the trailing tab, then append the end of row indicator
    $therow = substr($therow,0,strlen($therow)-1) . $endofrow;

    $sFile .= $therow;

    // Move that ADOdb pointer!
    $result->MoveNext();
  }

  // $sExportTime=time();
  $sExportTime = date('YmdHis');
  if ($dltype=="froogle"){
    $sExportTime = "FroogleEP" . $sExportTime;
  } else {
    $sExportTime = "EP" . $sExportTime;
  }

  // now either stream it to them or put it in the temp directory
  if ($download == 'stream'){
    // STREAM FILE
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition: attachment; filename=$sExportTime.txt");
    // Changed if using SSL, helps prevent program delay/timeout (add to backup.php also)
    // header("Pragma: no-cache");
    if ($request_type == 'NONSSL'){
      header("Pragma: no-cache");
    } else {
      header("Pragma: ");
    }
    header("Expires: 0");
    echo $sFile;
    die();
  } else {
    // PUT FILE IN TEMP DIR
    $tmpfname = OOS_ABSOLUTE_PATH . $tempdir . $sExportTime . '.txt';
    $fp = fopen( $tmpfname, "w+");
    fwrite($fp, $sFile);
    fclose($fp);
    echo 'You can get your file in the Tools/Files under ' . $tempdir . 'EP' . $sExportTime . '.txt';
    die();
  }
}

  // *** END *** download section

  require 'includes/oos_header.php';
?>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top" height="27"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require 'includes/oos_blocks.php'; ?>
    </table></td>
<!-- body_text //-->
      <td class="pageHeading" valign="top">
<?php
  echo  EP . " ". OOS_EP_VERSION . ' ' . DEFAULT_LANGUAGE . ' : ' .  $epdlanguage_name . '(' . $_SESSION['language'] .')' ;
?>

<p class="smallText">

<?php
  if ($localfile || (is_uploaded_file($usrfl['tmp_name']) && $split==0)) {


    if ($usrfl){
      // move the file to where we can work with it
      $file = oos_get_uploaded_file('usrfl');
      if (is_uploaded_file($file['tmp_name'])) {
        oos_get_copy_uploaded_file($file, OOS_ABSOLUTE_PATH . $tempdir);
      }

      echo "<p class=smallText>";
      echo FILE_UPLOADED .'. <br />';
      echo TEMPORARY_FILENAME . ': ' . $usrfl['tmp_name'] . '<br />';
      echo USER_FILENAME . ': ' . $usrfl['name'] . '<br />';
      echo SIZE . ': ' . $usrfl['size'] . '<br />';

      // get the entire file into an array
      $readed = file(OOS_ABSOLUTE_PATH . $tempdir . $usrfl['name']);
    }
    if ($localfile){
      // move the file to where we can work with it
      $file = oos_get_uploaded_file('usrfl');      $attribute_options_result = "SELECT distinct products_options_id FROM " . $oostable['products_options'] . " order by products_options_id";

        $attribute_options_values = $dbconn->Execute($attribute_options_result);

        $attribute_options_count = 1;
        //while ($attribute_options = $attribute_options_values->fields){
      if (is_uploaded_file($file['tmp_name'])) {
        oos_get_copy_uploaded_file($file, OOS_ABSOLUTE_PATH . $tempdir);
      }

      echo "<p class=smallText>";
      echo FILENAME . ': ' . $localfile . "<br />";

      // get the entire file into an array
      $readed = file(OOS_ABSOLUTE_PATH . $tempdir . $localfile);
    }

    // now we string the entire thing together in case there were carriage returns in the data
    $newreaded = "";
    foreach ($readed as $read){
      $newreaded .= $read;
    }

    // now newreaded has the entire file together without the carriage returns.
    // if for some reason excel put qoutes around our EOREOR, remove them then split into rows
    $newreaded = str_replace('"EOREOR"', 'EOREOR', $newreaded);
    $readed = explode( $separator . 'EOREOR',$newreaded);


    // Now we'll populate the filelayout based on the header row.
    $theheaders_array = explode( $separator, $readed[0] ); // explode the first row, it will be our filelayout
    $lll = 0;
    $filelayout = array();
    foreach( $theheaders_array as $header ){
      $cleanheader = str_replace( '"', '', $header);
      //  echo "Fileheader was $header<br /><br /><br />";
      $filelayout[ $cleanheader ] = $lll++; //
    }
    unset($readed[0]); //  we don't want to process the headers with the data

    // now we've got the array broken into parts by the expicit end-of-row marker.
    array_walk($readed, 'walk');

  }

  if (is_uploaded_file($usrfl) && $split==1) {

    // move the file to where we can work with it
    $file = oos_get_uploaded_file('usrfl');
    //echo "Trying to move file...";
    if (is_uploaded_file($file['tmp_name'])) {
      oos_get_copy_uploaded_file($file, OOS_ABSOLUTE_PATH . $tempdir);
    }

    $infp = fopen(OOS_ABSOLUTE_PATH . $tempdir . $usrfl['name'], "r");

    //toprow has the field headers
    $toprow = fgets($infp,32768);
 
    $filecount = 1;

    echo CREATING_FILE . "EP_Split" . $filecount . ".txt ...  ";
    $tmpfname = OOS_ABSOLUTE_PATH . $tempdir . "EP_Split" . $filecount . ".txt";
    $fp = fopen( $tmpfname, "w+");
    fwrite($fp, $toprow);

    $linecount = 0;
    $line = fgets($infp,32768);
    while ($line){
      // walking the entire file one row at a time
      // but a line is not necessarily a complete row, we need to split on rows that have "EOREOR" at the end
      $line = str_replace('"EOREOR"', 'EOREOR', $line);
      fwrite($fp, $line);
      if (strpos($line, 'EOREOR')){
        // we found the end of a line of data, store it
        $linecount++; // increment our line counter
        if ($linecount >= $maxrecs){
          echo ADDED . ' ' . $linecount  . RECORDS_AND_CLOSING_FILE . '... <br />';
          $linecount = 0; // reset our line counter
          // close the existing file and open another;
          fclose($fp);
          // increment filecount
          $filecount++;
          echo CREATING_FILE . "EP_Split" . $filecount . ".txt ...  ";
          $tmpfname = OOS_ABSOLUTE_PATH . $tempdir . "EP_Split" . $filecount . ".txt";
          //Open next file name
          $fp = fopen( $tmpfname, "w+");
          fwrite($fp, $toprow);
        }
      }
      $line=fgets($infp,32768);
    }
    echo ADDED . ' ' . $linecount . RECORDS_AND_CLOSING_FILE . '...<br /><br />';
    fclose($fp);
    fclose($infp);

    echo DOWNLOAD_TEXT;

  }
?>
      </p>

      <table width="75%" border="2">
        <tr>
          <td width="75%">
           <FORM ENCTYPE="multipart/form-data" ACTION="easypopulate.php?split=0" METHOD=POST>
              <p>
                <div align = "left">
                <p><b>Upload EP File</b></p>
                <p>
                  <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000000">
                  <p></p>
                  <input name="usrfl" type="file" size="50">
                  <input type="submit" name="buttoninsert" value="<?php echo INSERT_INTO_DB; ?>" ><br />
                </p>
              </div>

              </form>

           <FORM ENCTYPE="multipart/form-data" ACTION="easypopulate.php?split=1" METHOD=POST>
              <p>
                <div align = "left">
                <p><b>Split EP File</b></p>
                <p>
                  <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="1000000000">
                  <p></p>
                  <input name="usrfl" type="file" size="50">
                  <input type="submit" name="buttonsplit" value="<?php echo SPLIT_FILE; ?>"<br />
                </p>
              </div>

             </form>

           <FORM ENCTYPE="multipart/form-data" ACTION="easypopulate.php" METHOD=POST>
              <p>
                <div align = "left">
                <p><b>Import from Temp Dir (<?php echo $tempdir; ?>)</b></p>
    <p class="smallText">
    <INPUT TYPE="text" name="localfile" size="50">
                  <input type="submit" name="buttoninsert" value="<?php echo INSERT_INTO_DB; ?>" ><br />
                </p>
              </div>

             </form>



<?php
  echo '<p><b>' . DOWNLOAD_HEADING  . '</b></p>';

  echo '<a href="' . oos_href_link_admin($aFilename['easypopulate'], 'download=stream&dltype=full') . '">' . DOWNLOAD . '<b>' . COMPLETE . '</b>' . TAB_LIMITED_TEXT . '</a><br />';
  echo '<a href="' . oos_href_link_admin($aFilename['easypopulate'], 'download=stream&dltype=priceqty') . '">' . DOWNLOAD . '<b>' . MODEL_PRICE_QTY .  '</b>' . TAB_LIMITED_TEXT . '</a><br />';
  echo '<a href="' . oos_href_link_admin($aFilename['easypopulate'], 'download=stream&dltype=category') . '">' . DOWNLOAD . '<b>' . MODEL_CATEGORY . '</b>' . TAB_LIMITED_TEXT . '</a><br />';
  echo '<a href="' . oos_href_link_admin($aFilename['easypopulate'], 'download=stream&dltype=froogle') . '">' . DOWNLOAD . '<b>' . FROOGLE . '</b>' . TAB_LIMITED_TEXT . '</a><br />';

  if ($products_with_attributes == true) {
    echo '<a href="' . oos_href_link_admin($aFilename['easypopulate'], 'stream&dltype=attrib') . '">' . DOWNLOAD . '<b>' . MODEL_ATTRIBUTES . '</b>' . TAB_LIMITED_TEXT . '</a><br />';
  }

  echo '<p><b>' . TEMP_HEADING . ' (' . $tempdir . ')</b></p>';

  echo '<a href="' . oos_href_link_admin($aFilename['easypopulate'], 'download=tempfile&dltype=full') . '">' . CREATE . '<b>' . COMPLETE . '</b>' . TAB_LIMITED_TEMP_TEXT . '</a><br />';
  echo '<a href="' . oos_href_link_admin($aFilename['easypopulate'], 'download=tempfile&dltype=priceqty') . '">' . CREATE . '<b>' . MODEL_PRICE_QTY . '</b>' . TAB_LIMITED_TEMP_TEXT . '</a><br />';
  echo '<a href="' . oos_href_link_admin($aFilename['easypopulate'], 'download=tempfile&dltype=category') . '">' . CREATE . '<b>' . MODEL_CATEGORY . '</b>' . TAB_LIMITED_TEMP_TEXT . '</a><br />';
  echo '<a href="' . oos_href_link_admin($aFilename['easypopulate'], 'download=tempfile&dltype=froogle') . '">' . CREATE . '<b>' . FROOGLE . '</b>' . TAB_LIMITED_TEMP_TEXT . '</a><br />';
  echo '<a href="' . oos_href_link_admin($aFilename['easypopulate'], 'download=tempfile&dltype=attrib') . '">' . CREATE . '<b>' . MODEL_ATTRIBUTES . '</b>' . TAB_LIMITED_TEMP_TEXT . '</a><br />';
?>

    </td>
  </tr>
      </table>
    </td>
 </tr>
</table>

<?php require 'includes/oos_footer.php'; ?>
<br />
</body>
</html>


<?php
  function ep_create_filelayout($dltype){
    GLOBAL $filelayout, $filelayout_count, $filelayout_sql, $langcode, $fileheaders, $max_categories;

    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();


  // depending on the type of the download the user wanted, create a file layout for it.
    $fieldmap = array(); // default to no mapping to change internal field names to external.

    switch($dltype) {
        case 'full':
            // The file layout is dynamically made depending on the number of languages
            $iii = 0;
            $filelayout = array('v_products_model'    => $iii++,
                                'v_products_image'    => $iii++,);

            foreach ($langcode as $key => $lang){
              $l_id = $lang['id'];
              // uncomment the head_title, head_desc, and head_keywords to use
              // Linda's Header Tag Controller 2.0
              //echo $langcode['id'] . $langcode['code'];
              $filelayout  = array_merge($filelayout , array('v_products_name_' . $l_id        => $iii++,
                                                             'v_products_description_' . $l_id => $iii++,
                                                             'v_products_url_' . $l_id         => $iii++,
                                                             // 'v_products_head_title_tag_'.$l_id  => $iii++,
                                                             // 'v_products_head_desc_tag_'.$l_id => $iii++,
                                                             // 'v_products_head_keywords_tag_'.$l_id => $iii++,
                                                            ));
            }


            // uncomment the customer_price and customer_group to support multi-price per product contrib

            // VJ product attribs begin
            $header_array = array('v_products_price'    => $iii++,
                                  'v_products_weight'   => $iii++,
                                  'v_date_avail'        => $iii++,
                                  'v_date_added'        => $iii++,
                                  'v_products_quantity' => $iii++,
                                 );

            $languages = oos_get_languages();

            GLOBAL $attribute_options_array;

            $attribute_options_count = 1;
            foreach ($attribute_options_array as $attribute_options_values) {
              $key1 = 'v_attribute_options_id_' . $attribute_options_count;
              $header_array[$key1] = $iii++;

              for ($i=0, $n = count($languages); $i<$n; $i++) {
                $l_id = $languages[$i]['id'];

                $key2 = 'v_attribute_options_name_' . $attribute_options_count . '_' . $l_id;
                $header_array[$key2] = $iii++;
              }

              $attribute_values_result = "SELECT products_options_values_id  FROM " . $oostable['products_options_values_to_products_options'] . " WHERE products_options_id = '" . (int)$attribute_options_values['products_options_id'] . "' order by products_options_values_id";
              $attribute_values_values = $dbconn->Execute($attribute_values_result);

              $attribute_values_count = 1;
              while ($attribute_values = $attribute_values_values->fields) {
                $key3 = 'v_attribute_values_id_' . $attribute_options_count . '_' . $attribute_values_count;
                $header_array[$key3] = $iii++;

                for ($i=0, $n = count($languages); $i<$n; $i++) {
                  $l_id = $languages[$i]['id'];

                  $key4 = 'v_attribute_values_name_' . $attribute_options_count . '_' . $attribute_values_count . '_' . $l_id;
                  $header_array[$key4] = $iii++;
                }

                $key5 = 'v_attribute_values_price_' . $attribute_options_count . '_' . $attribute_values_count;
                $header_array[$key5] = $iii++;

                // attributes stock add start
                if ( $products_attributes_stock == true ) { 
                  $key6 = 'v_attribute_values_stock_' . $attribute_options_count . '_' . $attribute_values_count;
                  $header_array[$key6] = $iii++;
                }
                // attributes stock add end

                $attribute_values_count++;

                // Move that ADOdb pointer!
                $attribute_values_values->MoveNext();
              }

              $attribute_options_count++;
            }

            $header_array['v_manufacturers_name'] = $iii++;

            $filelayout = array_merge($filelayout, $header_array);
            // VJ product attribs end

            // build the categories name section of the array based on the number of categores the user wants to have
            for ($i=1;$i<$max_categories+1;$i++) {
              $filelayout = array_merge($filelayout, array('v_categories_name_' . $i => $iii++));
            }

            $filelayout = array_merge($filelayout, array('v_tax_class_title'   => $iii++,
                                                         'v_status'      => $iii++,
                                                         ));

            $filelayout_sql = "SELECT p.products_id as v_products_id, p.products_model as v_products_model,
                                      p.products_image as v_products_image, p.products_price as v_products_price,
                                      p.products_weight as v_products_weight, p.products_date_available as v_date_avail,
                                      p.products_date_added as v_date_added, p.products_tax_class_id as v_tax_class_id,
                                      p.products_quantity as v_products_quantity, p.manufacturers_id as v_manufacturers_id,
                                      subc.categories_id as v_categories_id, p.products_status as v_status
                               FROM " . $oostable['products'] . " as p,
                                    " . $oostable['categories'] . " as subc,
                                    " . $oostable['products_to_categories'] . " as ptoc
                               WHERE p.products_id = ptoc.products_id 
                               AND ptoc.categories_id = subc.categories_id";

            break;

        case 'priceqty':
            $iii = 0;
            // uncomment the customer_price and customer_group to support multi-price per product contrib
            $filelayout = array('v_products_model'     => $iii++,
                                'v_products_price'     => $iii++,
                                'v_products_quantity'  => $iii++,
                                #'v_customer_price_1'   => $iii++,
                                #'v_customer_group_id_1'    => $iii++,
                                #'v_customer_price_2'   => $iii++,
                                #'v_customer_group_id_2'    => $iii++,
                                #'v_customer_price_3'   => $iii++,
                                #'v_customer_group_id_3'    => $iii++,
                                #'v_customer_price_4'   => $iii++,
                                #'v_customer_group_id_4'    => $iii++,
                               );
            $filelayout_sql = "SELECT p.products_id as v_products_id, p.products_model as v_products_model,
                                      p.products_price as v_products_price, p.products_tax_class_id as v_tax_class_id,
                                      p.products_quantity as v_products_quantity 
                               FROM ".$oostable['products']." as p";

            break;

        case  'category':
            // The file layout is dynamically made depending on the number of languages
            $iii = 0;
            $filelayout = array('v_products_model'    => $iii++,);

            // build the categories name section of the array based on the number of categores the user wants to have
            for ($i=1; $i<$max_categories+1; $i++) {
             $filelayout = array_merge($filelayout, array('v_categories_name_' . $i => $iii++));
            }


            $filelayout_sql = "SELECT p.products_id as v_products_id, p.products_model as v_products_model,
                                      subc.categories_id as v_categories_id
                               FROM " . $oostable['products'] . " as p,
                                    " . $oostable['categories'] . " as subc,
                                    " . $oostable['products_to_categories']." as ptoc
                               WHERE p.products_id = ptoc.products_id 
                                 AND ptoc.categories_id = subc.categories_id";
            break;

        case 'froogle':
            // this is going to be a little interesting because we need
            // a way to map from internal names to external names
            //
            // Before it didn't matter, but with froogle needing particular headers,
            // The file layout is dynamically made depending on the number of languages
            $iii = 0;
            $filelayout = array('v_froogle_products_url_'      => $iii++,);

            foreach ($langcode as $key => $lang){
              $l_id = $lang['id'];
              $filelayout  = array_merge($filelayout, 
                                 array('v_froogle_products_name_' . $l_id    => $iii++,
                                       'v_froogle_products_description_' . $l_id => $iii++,));
            }
            $filelayout  = array_merge($filelayout , 
                            array('v_products_price'          => $iii++,
                                  'v_products_fullpath_image' => $iii++,
                                  'v_category_fullpath'       => $iii++,
                                  'v_froogle_offer_id'        => $iii++,
                                  'v_froogle_instock'         => $iii++,
                                  'v_froogle_shipping'        => $iii++,

                                  'v_manufacturers_name'      => $iii++,
                                  'v_froogle_upc'             => $iii++,
                                  'v_froogle_color'           => $iii++,
                                  'v_froogle_size'            => $iii++,
                                  'v_froogle_quantitylevel'   => $iii++,

                                  'v_froogle_product_id'      => $iii++,
                                  'v_froogle_manufacturer_id' => $iii++,
                                  'v_froogle_exp_date'        => $iii++,
                                  'v_froogle_product_type'    => $iii++,
                                  'v_froogle_delete'          => $iii++,
                                  'v_froogle_currency'        => $iii++,
                                 )
                              );
            $iii = 0;
            $fileheaders = array('product_url'     => $iii++,
                                 'name'            => $iii++,
                                 'description'     => $iii++,

                                 'price'           => $iii++,
                                 'image_url'       => $iii++,
                                 'category'        => $iii++,
                                 'offer_id'        => $iii++,
                                 'instock'         => $iii++,
                                 'shipping'        => $iii++,

                                 'brand'           => $iii++,
                                 'upc'             => $iii++,
                                 'color'           => $iii++,
                                 'size'            => $iii++,
                                 'quantity'        => $iii++,

                                 'product_id'      => $iii++,
                                 'manufacturer_id' => $iii++,
                                 'exp_date'        => $iii++,
                                 'product_type'    => $iii++,
                                 'delete'          => $iii++,
                                 'currency'        => $iii++,
                               );

            $filelayout_sql = "SELECT p.products_id as v_products_id, p.products_model as v_products_model,
                                      p.products_image as v_products_image,  p.products_price as v_products_price,
                                      p.products_weight as v_products_weight, p.products_date_added as v_date_avail,
                                      p.products_tax_class_id as v_tax_class_id, p.products_quantity as v_products_quantity,
                                      p.manufacturers_id as v_manufacturers_id, subc.categories_id as v_categories_id
                               FROM " . $oostable['products'] . " as p,
                                    " . $oostable['categories'] . " as subc,
                                    " . $oostable['products_to_categories'] . " as ptoc
                               WHERE p.products_status >= '1' 
                                 AND p.products_id = ptoc.products_id 
                                 AND ptoc.categories_id = subc.categories_id";
            break;

        case 'attrib':
            $iii = 0;
            $filelayout = array('v_products_model'    => $iii++ );

            $header_array = array();

            $languages = oos_get_languages();

            GLOBAL $attribute_options_array;

            $attribute_options_count = 1;
            foreach ($attribute_options_array as $attribute_options_values) {
              $key1 = 'v_attribute_options_id_' . $attribute_options_count;
              $header_array[$key1] = $iii++;

              for ($i=0, $n = count($languages); $i<$n; $i++) {
                $l_id = $languages[$i]['id'];

                $key2 = 'v_attribute_options_name_' . $attribute_options_count . '_' . $l_id;
                $header_array[$key2] = $iii++;
              }

              $attribute_values_result = "SELECT products_options_values_id  FROM " . $oostable['products_options_values_to_products_options'] . " WHERE products_options_id = '" . (int)$attribute_options_values['products_options_id'] . "' order by products_options_values_id";
              $attribute_values_values = $dbconn->Execute($attribute_values_result);

              $attribute_values_count = 1;
              while ($attribute_values = $attribute_values_values->fields) {
                $key3 = 'v_attribute_values_id_' . $attribute_options_count . '_' . $attribute_values_count;
                $header_array[$key3] = $iii++;

                for ($i=0, $n = count($languages); $i<$n; $i++) {
                  $l_id = $languages[$i]['id'];

                  $key4 = 'v_attribute_values_name_' . $attribute_options_count . '_' . $attribute_values_count . '_' . $l_id;
                  $header_array[$key4] = $iii++;
                }

                $key5 = 'v_attribute_values_price_' . $attribute_options_count . '_' . $attribute_values_count;
                $header_array[$key5] = $iii++;

                // attributes stock add start
                if ( $products_attributes_stock == true ) {
                  $key6 = 'v_attribute_values_stock_' . $attribute_options_count . '_' . $attribute_values_count;
                  $header_array[$key6] = $iii++;
                }
                // attributes stock add end

                $attribute_values_count++;

                // Move that ADOdb pointer!
                $attribute_values_values->MoveNext();
              }

              $attribute_options_count++;
           }

            $filelayout = array_merge($filelayout, $header_array);

            $filelayout_sql = "SELECT p.products_id as v_products_id,
                                      p.products_model as v_products_model
                               FROM ".$oostable['products']." as p";

            break;
       }

       $filelayout_count = count($filelayout);
  }


 /**
  *
  */ 
  function walk( $item1 ) {

    GLOBAL $filelayout, $filelayout_count, $modelsize;
    GLOBAL $ps_outstock, $ps_instock, $ps_soon, $ps_notav, $langcode, $default_these, $deleteit, $zero_qty_inactive;
    GLOBAL $price_with_tax, $replace_quotes;
    GLOBAL $default_images, $default_image_manufacturer, $default_image_product, $default_image_category;
    GLOBAL $separator, $max_categories;
  // first we clean up the row of data

        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();
  // chop blanks from each end
  $item1 = ltrim(rtrim($item1));

  // blow it into an array, splitting on the tabs
  $items = explode($separator, $item1);

  // make sure all non-set things are set to '';
  // and strip the quotes from the start and end of the stings.
  // escape any special chars for the database.
  foreach( $filelayout as $key=> $value){
    $i = $filelayout[$key];
    if (isset($items[$i]) == false) {
      $items[$i]='';
    } else {
      // Check to see if either of the magic_quotes are turned on or off;
      // And apply filtering accordingly.
      if (function_exists('ini_get')) {
        //echo "Getting ready to check magic quotes<br />";
        if (ini_get('magic_quotes_runtime') == 1){
          // The magic_quotes_runtime are on, so lets account for them
          // check if the last character is a quote;
          // if it is, chop off the quotes.
          if (substr($items[$i],-1) == '"'){
            $items[$i] = substr($items[$i],2,strlen($items[$i])-4);
          }
          // now any remaining doubled double quotes should be converted to one doublequote
          $items[$i] = str_replace('\"\"',"&#34",$items[$i]);
          if ($replace_quotes){
            $items[$i] = str_replace('\"',"&#34",$items[$i]);
            $items[$i] = str_replace("\'","&#39",$items[$i]);
          }
        } else { // no magic_quotes are on
          // check if the last character is a quote;
          // if it is, chop off the 1st and last character of the string.
          if (substr($items[$i],-1) == '"'){
            $items[$i] = substr($items[$i],1,strlen($items[$i])-2);
          }
          // now any remaining doubled double quotes should be converted to one doublequote
          $items[$i] = str_replace('""',"&#34",$items[$i]);
          if ($replace_quotes){
            $items[$i] = str_replace('"',"&#34",$items[$i]);
            $items[$i] = str_replace("'","&#39",$items[$i]);
          }
        }
      }
    }
  }
/*
  if ( $items['v_status'] == $deleteit ){
    // they want to delete this product.
    echo "Deleting product " . $items['v_products_model'] . " from the database<br />";
    // Get the ID
    // kill in the products_to_categories
    // Kill in the products table
    return; // we're done deleteing!
  }
*/
  // now do a query to get the record's current contents
  $sql = "SELECT
    p.products_id as v_products_id,
    p.products_model as v_products_model,
    p.products_image as v_products_image,
    p.products_price as v_products_price,
    p.products_weight as v_products_weight,
    p.products_date_added as v_date_avail,
    p.products_tax_class_id as v_tax_class_id,
    p.products_quantity as v_products_quantity,
    p.manufacturers_id as v_manufacturers_id,
    subc.categories_id as v_categories_id
    FROM
    ".$oostable['products']." as p,
    ".$oostable['categories']." as subc,
    ".$oostable['products_to_categories']." as ptoc
    WHERE
    p.products_id = ptoc.products_id AND
    p.products_model = '" . $items[$filelayout['v_products_model']] . "' AND
    ptoc.categories_id = subc.categories_id
    ";

  $result = $dbconn->Execute($sql);

  while ($row = $result->fields){
    // OK, since we got a row, the item already exists.
    // Let's get all the data we need and fill in all the fields that need to be defaulted to the current values
    // for each language, get the description and set the vals
    foreach ($langcode as $key => $lang){
      //echo "Inside defaulting loop";
      //echo "key is $key<br />";
      //echo "langid is " . $lang['id'] . "<br />";
//      $sql2 = "SELECT products_name, products_description
//        FROM ".$oostable['products_description']."
//        WHERE
//          products_id = " . $row['v_products_id'] . " AND
//          language_id = '" . $lang['id'] . "'
//        ";
      $sql2 = "SELECT *
        FROM ".$oostable['products_description']."
        WHERE
          products_id = " . $row['v_products_id'] . " AND
          products_languages_id = '" . $lang['id'] . "'
        ";
      $result2 = $dbconn->Execute($sql2);
      $row2 = $result2->fields;
                        // Need to report from ......_name_1 not ..._name_0
      $row['v_products_name_' . $lang['id']]    = $row2['products_name'];
      $row['v_products_description_' . $lang['id']]   = $row2['products_description'];
      $row['v_products_url_' . $lang['id']]     = $row2['products_url'];

/*
      // support for Linda's Header Controller 2.0 here
      if(isset($filelayout['v_products_head_title_tag_' . $lang['id'] ])){
        $row['v_products_head_title_tag_' . $lang['id']]  = $row2['products_head_title_tag'];
        $row['v_products_head_desc_tag_' . $lang['id']]   = $row2['products_head_desc_tag'];
        $row['v_products_head_keywords_tag_' . $lang['id']]   = $row2['products_head_keywords_tag'];
      }
      // end support for Header Controller 2.0
*/
    }

    // start with v_categories_id
    // Get the category description
    // set the appropriate variable name
    // if parent_id is not null, then follow it up.
    $thecategory_id = $row['v_categories_id'];

    for( $categorylevel=1; $categorylevel<$max_categories+1; $categorylevel++){
      if ($thecategory_id){
        $sql2 = "SELECT categories_name
          FROM ".$oostable['categories_description']."
          WHERE
            categories_id = " . $thecategory_id . " AND
            categories_languages_id = '" . intval($_SESSION['language_id']) . "'";

        $result2 = $dbconn->Execute($sql2);
        $row2 = $result2->fields;
        // only set it if we found something
        $temprow['v_categories_name_' . $categorylevel] = $row2['categories_name'];
        // now get the parent ID if there was one
        $sql3 = "SELECT parent_id
          FROM ".$oostable['categories']."
          WHERE
            categories_id = " . $thecategory_id;
        $result3 = $dbconn->Execute($sql3);
        $row3 = $result3->fields;
        $theparent_id = $row3['parent_id'];
        if ($theparent_id != ''){
          // there was a parent ID, lets set thecategoryid to get the next level
          $thecategory_id = $theparent_id;
        } else {
          // we have found the top level category for this item,
          $thecategory_id = false;
        }
      } else {
          $temprow['v_categories_name_' . $categorylevel] = '';
      }
    }
    // temprow has the old style low to high level categories.
    $newlevel = 1;
    // let's turn them into high to low level categories
    for( $categorylevel=$max_categories+1; $categorylevel>0; $categorylevel--){
      if ($temprow['v_categories_name_' . $categorylevel] != ''){
        $row['v_categories_name_' . $newlevel++] = $temprow['v_categories_name_' . $categorylevel];
      }
    }

    if ($row['v_manufacturers_id'] != ''){
      $sql2 = "SELECT manufacturers_name
        FROM ".$oostable['manufacturers']."
        WHERE
        manufacturers_id = " . $row['v_manufacturers_id']
        ;
      $result2 = $dbconn->Execute($sql2);
      $row2 = $result2->fields;
      $row['v_manufacturers_name'] = $row2['manufacturers_name'];
    }

    //elari -
    //We check the value of tax class and title instead of the id
    //Then we add the tax to price if $price_with_tax is set to true
    $row_tax_multiplier = oos_get_tax_class_rate($row['v_tax_class_id']); # ! ! !
    $row['v_tax_class_title'] = oos_cfg_get_tax_class_title($row['v_tax_class_id']);
    if ($price_with_tax){
      $row['v_products_price'] = round($row['v_products_price'] + ($row['v_products_price']* $row_tax_multiplier / 100),2);
    }

    // now create the internal variables that will be used
    // the $$thisvar is on purpose: it creates a variable named what ever was in $thisvar and sets the value
    foreach ($default_these as $thisvar){
      $$thisvar = $row[$thisvar];
    }

    // Move that ADOdb pointer!
    $result->MoveNext();
  }

  // this is an important loop.  What it does is go thru all the fields in the incoming file and set the internal vars.
  // Internal vars not set here are either set in the loop above for existing records, or not set at all (null values)
  // the array values are handled separatly, although they will set variables in this loop, we won't use them.
  foreach( $filelayout as $key => $value ){
    $$key = $items[ $value ];
  }

        // so how to handle these?  we shouldn't built the array unless it's been giving to us.
  // The assumption is that if you give us names and descriptions, then you give us name and description for all applicable languages
  foreach ($langcode as $lang){
    //echo "Langid is " . $lang['id'] . "<br />";
    $l_id = $lang['id'];
    if (isset($filelayout['v_products_name_' . $l_id ])){
      //we set dynamically the language values
      $v_products_name[$l_id]   = $items[$filelayout['v_products_name_' . $l_id]];
      $v_products_description[$l_id]  = $items[$filelayout['v_products_description_' . $l_id ]];
      $v_products_url[$l_id]    = $items[$filelayout['v_products_url_' . $l_id ]];
      // support for Linda's Header Controller 2.0 here
/*
      if(isset($filelayout['v_products_head_title_tag_' . $l_id])){
        $v_products_head_title_tag[$l_id]   = $items[$filelayout['v_products_head_title_tag_' . $l_id]];
        $v_products_head_desc_tag[$l_id]  = $items[$filelayout['v_products_head_desc_tag_' . $l_id]];
        $v_products_head_keywords_tag[$l_id]  = $items[$filelayout['v_products_head_keywords_tag_' . $l_id]];
      }
      // end support for Header Controller 2.0
*/
    }
  }
  //elari... we get the tax_clas_id from the tax_title
  //on screen will still be displayed the tax_class_title instead of the id....
  if ( isset( $v_tax_class_title) ){
    $v_tax_class_id = oos_get_tax_title_class_id($v_tax_class_title); # ! ! !
  }
  //we check the tax rate of this tax_class_id
   $row_tax_multiplier = oos_get_tax_class_rate($v_tax_class_id); # ! ! !

  //And we recalculate price without the included tax...
  //Since it seems display is made before, the displayed price will still include tax
  //This is same problem for the tax_clas_id that display tax_class_title
  if ($price_with_tax){
    $v_products_price = round( $v_products_price / (1 + ( $row_tax_multiplier * $price_with_tax/100) ), 4);
  }

  // if they give us one category, they give us all 6 categories
  unset ($v_categories_name); // default to not set.
  if ( isset( $filelayout['v_categories_name_1'] ) ){
    $newlevel = 1;
    for( $categorylevel=6; $categorylevel>0; $categorylevel--){
      if ( $items[$filelayout['v_categories_name_' . $categorylevel]] != ''){
        $v_categories_name[$newlevel++] = $items[$filelayout['v_categories_name_' . $categorylevel]];
      }
    }
    while( $newlevel < $max_categories+1){
      $v_categories_name[$newlevel++] = ''; // default the remaining items to nothing
    }
  }

  if (ltrim(rtrim($v_products_quantity)) == '') {
    $v_products_quantity = 1;
  }
  if ($v_date_avail == '') {
//    $v_date_avail = "CURRENT_TIMESTAMP";
    $v_date_avail = "NULL";
  } else {
    // we put the quotes around it here because we can't put them into the query, because sometimes
    //   we will use the "current_timestamp", which can't have quotes around it.
    $v_date_avail = '"' . $v_date_avail . '"';
  }

  if ($v_date_added == '') {
    $v_date_added = "CURRENT_TIMESTAMP";
  } else {
    // we put the quotes around it here because we can't put them into the query, because sometimes
    //   we will use the "current_timestamp", which can't have quotes around it.
    $v_date_added = '"' . $v_date_added . '"';
  }


  // default the stock if they spec'd it or if it's blank
  $v_db_status = '3'; // default to active
  if ($v_status == $ps_notav){
    $v_db_status = '0';
  } elseif ($v_status == $ps_outstock){
    $v_db_status = '1';
  } elseif ($v_status == $ps_soon){
    $v_db_status = '2';
  }
  if ($zero_qty_inactive && $v_products_quantity == 0) {
    // if they said that zero qty products should be deactivated, let's deactivate if the qty is zero
    $v_db_status = '0';
  }

  if ($v_manufacturer_id==''){
    $v_manufacturer_id="NULL";
  }

  if (trim($v_products_image)==''){
    $v_products_image = $default_image_product;
  }

  if (strlen($v_products_model) > $modelsize ){
    echo "<font color='red'>" . strlen($v_products_model) . $v_products_model . "... ERROR! - Too many characters in the model number.<br />
      12 is the maximum on a standard OSC install.<br />
      Your maximum product_model length is set to $modelsize<br />
      You can either shorten your model numbers or increase the size of the field in the database.</font>";
    die();
  }

  // OK, we need to convert the manufacturer's name into id's for the database
  if ( isset($v_manufacturers_name) && $v_manufacturers_name != '' ){
    $sql = "SELECT man.manufacturers_id
      FROM ".$oostable['manufacturers']." as man
      WHERE
        man.manufacturers_name = '" . $v_manufacturers_name . "'";
    $result = $dbconn->Execute($sql);
    $row = $result->fields;
    if ($row != ''){
      foreach($row as $item){ # ! ! !
        $v_manufacturer_id = $item;
      }
    } else {
      // to add, we need to put stuff in categories and categories_description
      $sql = "SELECT MAX( manufacturers_id) max FROM ".$oostable['manufacturers'];
      $result = $dbconn->Execute($sql);
      $row = $result->fields;
      $max_mfg_id = $row['max']+1;
      // default the id if there are no manufacturers yet
      if (!is_numeric($max_mfg_id) ){
        $max_mfg_id=1;
      }

      // Uncomment this query if you have an older 2.2 codebase
      /*
      $sql = "INSERT INTO ".$oostable['manufacturers']."(
        manufacturers_id,
        manufacturers_name,
        manufacturers_image
        ) VALUES (
        $max_mfg_id,
        '$v_manufacturers_name',
        '$default_image_manufacturer'
        )";
      */

      // Comment this query out if you have an older 2.2 codebase
      $sql = "INSERT INTO ".$oostable['manufacturers']."(
        manufacturers_id,
        manufacturers_name,
        manufacturers_image,
        date_added,
        last_modified
        ) VALUES (
        $max_mfg_id,
        '$v_manufacturers_name',
        '$default_image_manufacturer',
        CURRENT_TIMESTAMP,
        CURRENT_TIMESTAMP
        )";
      $result = $dbconn->Execute($sql);
      $v_manufacturer_id = $max_mfg_id;
    }
  }
  // if the categories names are set then try to update them
  if ( isset($v_categories_name_1)){
    // start from the highest possible category and work our way down from the parent
    $v_categories_id = 0;
    $theparent_id = 0;
    for ( $categorylevel=$max_categories+1; $categorylevel>0; $categorylevel-- ){
      $thiscategoryname = $v_categories_name[$categorylevel];
      if ( $thiscategoryname != ''){
        // we found a category name in this field

        // now the subcategory
        $sql = "SELECT cat.categories_id
          FROM ".$oostable['categories']." as cat, 
               ".$oostable['categories_description']." as des
          WHERE
            cat.categories_id = des.categories_id AND
            des.categories_languages_id = '" . intval($_SESSION['language_id']) . "' AND
            cat.parent_id = " . $theparent_id . " AND
            des.categories_name = '" . $thiscategoryname . "'";
        $result = $dbconn->Execute($sql);
        $row = $result->fields;
        if ($row != ''){
          foreach($row as $item){ # ! ! !
            $thiscategoryid = $item;
          }
        } else {
          // to add, we need to put stuff in categories and categories_description
          $sql = "SELECT MAX( categories_id) max FROM ".$oostable['categories'];
          $result = $dbconn->Execute($sql);
          $row = $result->fields;
          $max_category_id = $row['max']+1;
          if (!is_numeric($max_category_id) ){
            $max_category_id=1;
          }
          $sql = "INSERT INTO ".$oostable['categories']."(
            categories_id,
            categories_image,
            parent_id,
            sort_order,
            date_added,
            last_modified,
            categories_status
            ) VALUES (
            $max_category_id,
            '$default_image_category',
            $theparent_id,
            0,
            CURRENT_TIMESTAMP
            ,CURRENT_TIMESTAMP,
            1
            )";
          $result = $dbconn->Execute($sql);
          $sql = "INSERT INTO ".$oostable['categories_description']."(
              categories_id,
              categories_languages_id,
              categories_name
            ) VALUES (
              $max_category_id,
              '" . $_SESSION['language_id'] . "',
              '$thiscategoryname'
            )";
          $result = $dbconn->Execute($sql);
          $thiscategoryid = $max_category_id;
        }
        // the current catid is the next level's parent
        $theparent_id = $thiscategoryid;
        $v_categories_id = $thiscategoryid; // keep setting this, we need the lowest level category ID later
      }
    }
  }

  if ($v_products_model != "") {
    //   products_model exists!
    array_walk($items, 'print_el');

    // First we check to see if this is a product in the current db.
    $result = $dbconn->Execute("SELECT products_id FROM ".$oostable['products']." WHERE (products_model = '". $v_products_model . "')");

    if ($result->RecordCount() == 0)  {
      //   insert into products

      $sql = "SHOW TABLE STATUS LIKE '".$oostable['products']."'";
      $result = $dbconn->Execute($sql);
      $row = $result->fields;
      $max_product_id = $row['Auto_increment'];
      if (!is_numeric($max_product_id) ){
        $max_product_id=1;
      }
      $v_products_id = $max_product_id;
      echo "<font color='green'> !New Product!</font><br />";

      $query = "INSERT INTO ".$oostable['products']." (
          products_image,
          products_model,
          products_price,
          products_status,
          products_last_modified,
          products_date_added,
          products_date_available,
          products_tax_class_id,
          products_weight,
          products_quantity,
          manufacturers_id)
            VALUES (
              '$v_products_image',";

      // unmcomment these lines if you are running the image mods
      /*
        $query .=   . $v_products_mimage . '", "'
              . $v_products_bimage . '", "'
              . $v_products_subimage1 . '", "'
              . $v_products_bsubimage1 . '", "'
              . $v_products_subimage2 . '", "'
              . $v_products_bsubimage2 . '", "'
              . $v_products_subimage3 . '", "'
              . $v_products_bsubimage3 . '", "'
      */

      $query .="        '$v_products_model',
                '$v_products_price',
                '$v_db_status',
                CURRENT_TIMESTAMP,
                $v_date_added,
                $v_date_avail,
                '$v_tax_class_id',
                '$v_products_weight',
                '$v_products_quantity',
                '$v_manufacturer_id')
              ";
        $result = $dbconn->Execute($query);
    } else {
      // existing product, get the id from the query
      // and update the product data
      $row = $result->fields;
      $v_products_id = $row['products_id'];
      echo "<font color='black'> Updated</font><br />";
      $row = $result->fields; # ! ! !
      $query = 'UPDATE '.$oostable['products'].'
          SET
          products_price="'.$v_products_price.
          '" ,products_image="'.$v_products_image;

      // uncomment these lines if you are running the image mods
/*
        $query .=
          '" ,products_mimage="'.$v_products_mimage.
          '" ,products_bimage="'.$v_products_bimage.
          '" ,products_subimage1="'.$v_products_subimage1.
          '" ,products_bsubimage1="'.$v_products_bsubimage1.
          '" ,products_subimage2="'.$v_products_subimage2.
          '" ,products_bsubimage2="'.$v_products_bsubimage2.
          '" ,products_subimage3="'.$v_products_subimage3.
          '" ,products_bsubimage3="'.$v_products_bsubimage3;
*/

      $query .= '", products_weight="'.$v_products_weight .
          '", products_tax_class_id="'.$v_tax_class_id . 
          '", products_date_available= ' . $v_date_avail .
          ', products_date_added= ' . $v_date_added .
          ', products_last_modified=CURRENT_TIMESTAMP
          , products_quantity="' . $v_products_quantity .  
          '" ,manufacturers_id=' . $v_manufacturer_id . 
          ' , products_status=' . $v_db_status . '
          WHERE
            (products_id = "'. $v_products_id . '")';

      $result = $dbconn->Execute($query);
    }

    // the following is common in both the updating an existing product and creating a new product
                if ( isset($v_products_name)){
      foreach( $v_products_name as $key => $name){
              if ($name!=''){
          $sql = "SELECT * FROM ".$oostable['products_description']." WHERE
              products_id = $v_products_id AND
              products_languages_id = '" . $key . "'";
          $result = $dbconn->Execute($sql);
          if ($result->RecordCount() == 0) {
            // nope, this is a new product description
            $result = $dbconn->Execute($sql);
            $sql =
              "INSERT INTO ".$oostable['products_description']."
                (products_id,
                products_languages_id,
                products_name,
                products_description,
                products_url)
                VALUES (
                  '" . $v_products_id . "',
                  '" . $key . "',
                  '" . $name . "',
                  '" . $v_products_description[$key] . "',
                  '" . $v_products_url[$key] . "'
                  )";
            // support for Linda's Header Controller 2.0
            if (isset($v_products_head_title_tag)){
              // override the sql if we're using Linda's contrib
              $sql =
                "INSERT INTO ".$oostable['products_description']."
                  (products_id,
                  products_languages_id,
                  products_name,
                  products_description,
                  products_url,
                  products_head_title_tag,
                  products_head_desc_tag,
                  products_head_keywords_tag)
                  VALUES (
                    '" . $v_products_id . "',
                    '" . $key . "',
                    '" . $name . "',
                    '" . $v_products_description[$key] . "',
                    '" . $v_products_url[$key] . "',
                    '" . $v_products_head_title_tag[$key] . "',
                    '" . $v_products_head_desc_tag[$key] . "',
                    '" . $v_products_head_keywords_tag[$key] . "')";
            }
            // end support for Linda's Header Controller 2.0
            $result = $dbconn->Execute($sql);
          } else {
            // already in the description, let's just update it
            $sql =
              "UPDATE ".$oostable['products_description']." SET
                products_name='$name',
                products_description='".$v_products_description[$key] . "',
                products_url='" . $v_products_url[$key] . "'
              WHERE
                products_id = '$v_products_id' AND
                products_languages_id = '$key'";
            // support for Lindas Header Controller 2.0
            if (isset($v_products_head_title_tag)){
              // override the sql if we're using Linda's contrib
              $sql =
                "UPDATE ".$oostable['products_description']." SET
                  products_name = '$name',
                  products_description = '".$v_products_description[$key] . "',
                  products_url = '" . $v_products_url[$key] ."',
                  products_head_title_tag = '" . $v_products_head_title_tag[$key] ."',
                  products_head_desc_tag = '" . $v_products_head_desc_tag[$key] ."',
                  products_head_keywords_tag = '" . $v_products_head_keywords_tag[$key] ."'
                WHERE
                  products_id = '$v_products_id' AND
                  products_languages_id = '$key'";
            }
            // end support for Linda's Header Controller 2.0
            $result = $dbconn->Execute($sql);
          }
        }
      }
    }
    if (isset($v_categories_id)){
      //find out if this product is listed in the category given
      $result_incategory = $dbconn->Execute('SELECT
            '.$oostable['products_to_categories'].'.products_id,
            '.$oostable['products_to_categories'].'.categories_id
            FROM
              '.$oostable['products_to_categories'].'
            WHERE
            '.$oostable['products_to_categories'].'.products_id='.$v_products_id.' AND
            '.$oostable['products_to_categories'].'.categories_id='.$v_categories_id);

      if ($result_incategory->RecordCount() == 0) {
        // nope, this is a new category for this product
        $res1 = $dbconn->Execute('INSERT INTO '.$oostable['products_to_categories'].' (products_id, categories_id)
              VALUES ("' . $v_products_id . '", "' . $v_categories_id . '")');
      } else {
        // already in this category, nothing to do!
      }
    }
    // for the separate prices per customer module
    $ll=1;

    if (isset($v_customer_price_1)){
      if (($v_customer_group_id_1 == '') AND ($v_customer_price_1 != ''))  {
        echo "<font color=red>ERROR - v_customer_group_id and v_customer_price must occur in pairs</font>";
        die();
      }
      // they spec'd some prices, so clear all existing entries
      $result = $dbconn->Execute('DELETE FROM ' . $oostable['products_groups'] . ' WHERE products_id = ' . $v_products_id);
      // and insert the new record
      if ($v_customer_price_1 != ''){
        $result = $dbconn->Execute('
              INSERT INTO
                '.$oostable['products_groups'].'
              VALUES
              (
                ' . $v_customer_group_id_1 . ',
                ' . $v_customer_price_1 . ',
                ' . $v_products_id . ',
                ' . $v_products_price .'
                )'
              );
      }
      if ($v_customer_price_2 != ''){
        $result = $dbconn->Execute('
              INSERT INTO
                '.$oostable['products_groups'].'
              VALUES
              (
                ' . $v_customer_group_id_2 . ',
                ' . $v_customer_price_2 . ',
                ' . $v_products_id . ',
                ' . $v_products_price . '
                )'
              );
      }
      if ($v_customer_price_3 != ''){
        $result = $dbconn->Execute('
              INSERT INTO
                '.$oostable['products_groups'].'
              VALUES
              (
                ' . $v_customer_group_id_3 . ',
                ' . $v_customer_price_3 . ',
                ' . $v_products_id . ',
                ' . $v_products_price . '
                )'
              );
      }
      if ($v_customer_price_4 != ''){
        $result = $dbconn->Execute('
              INSERT INTO
                '.$oostable['products_groups'].'
              VALUES
              (
                ' . $v_customer_group_id_4 . ',
                ' . $v_customer_price_4 . ',
                ' . $v_products_id . ',
                ' . $v_products_price . '
                )'
              );
      }

    }

    // VJ product attribs begin
    if (isset($v_attribute_options_id_1)){
      $attribute_rows = 1; // master row count

      $languages = oos_get_languages();

      // product options count
      $attribute_options_count = 1;
      $v_attribute_options_id_var = 'v_attribute_options_id_' . $attribute_options_count;

      while (isset($$v_attribute_options_id_var) && !empty($$v_attribute_options_id_var)) {
        // remove product attribute options linked to this product before proceeding further
        // this is useful for removing attributes linked to a product
        $attributes_clean_result = "delete FROM " . $oostable['products_attributes'] . " WHERE products_id = '" . (int)$v_products_id . "' and options_id = '" . (int)$$v_attribute_options_id_var . "'";

        $dbconn->Execute($attributes_clean_result);

        $attribute_options_result = "SELECT products_options_name FROM " . $oostable['products_options'] . " WHERE products_options_id = '" . (int)$$v_attribute_options_id_var . "'";

        $attribute_options_values = $dbconn->Execute($attribute_options_result);

        // option table update begin
        if ($attribute_rows == 1) {
          // insert into options table if no option exists
          if ($attribute_options_values->RecordCount() <= 0) {
            for ($i=0, $n = count($languages); $i<$n; $i++) {
              $lid = $languages[$i]['id'];

              $v_attribute_options_name_var = 'v_attribute_options_name_' . $attribute_options_count . '_' . $lid;

              if (isset($$v_attribute_options_name_var)) {
                $attribute_options_insert_result = "insert into " . $oostable['products_options'] . " (products_options_id, products_options_languages_id, products_options_name) values ('" . (int)$$v_attribute_options_id_var . "', '" . (int)$lid . "', '" . $$v_attribute_options_name_var . "')";

                $attribute_options_insert = $dbconn->Execute($attribute_options_insert_result);
              }
            }
          } else { // update options table, if options already exists
            for ($i=0, $n = count($languages); $i<$n; $i++) {
              $lid = $languages[$i]['id'];

              $v_attribute_options_name_var = 'v_attribute_options_name_' . $attribute_options_count . '_' . $lid;

              if (isset($$v_attribute_options_name_var)) {
                $attribute_options_update_lang_result = "SELECT products_options_name FROM " . $oostable['products_options'] . " WHERE products_options_id = '" . (int)$$v_attribute_options_id_var . "' and products_options_languages_id ='" . (int)$lid . "'";

                $attribute_options_update_lang_values = $dbconn->Execute($attribute_options_update_lang_result);

                // if option name doesn't exist for particular language, insert value
                if ($attribute_options_update_lang_values->RecordCount() <= 0) {
                  $attribute_options_lang_insert_result = "insert into " . $oostable['products_options'] . " (products_options_id, products_options_languages_id, products_options_name) values ('" . (int)$$v_attribute_options_id_var . "', '" . (int)$lid . "', '" . $$v_attribute_options_name_var . "')";

                  $attribute_options_lang_insert = $dbconn->Execute($attribute_options_lang_insert_result);
                } else { // if option name exists for particular language, update table
                  $attribute_options_update_result = "update " . $oostable['products_options'] . " set products_options_name = '" . $$v_attribute_options_name_var . "' WHERE products_options_id ='" . (int)$$v_attribute_options_id_var . "' and products_options_languages_id = '" . (int)$lid . "'";

                  $attribute_options_update = $dbconn->Execute($attribute_options_update_result);
                }
              }
            }
          }
        }
        // option table update end

        // product option values count
        $attribute_values_count = 1;
        $v_attribute_values_id_var = 'v_attribute_values_id_' . $attribute_options_count . '_' . $attribute_values_count;

        while (isset($$v_attribute_values_id_var) && !empty($$v_attribute_values_id_var)) {
          $attribute_values_result = "SELECT products_options_values_name FROM " . $oostable['products_options_values'] . " WHERE products_options_values_id = '" . (int)$$v_attribute_values_id_var . "'";

          $attribute_values_values = $dbconn->Execute($attribute_values_result);

          // options_values table update begin
          if ($attribute_rows == 1) {
            // insert into options_values table if no option exists
            if ($attribute_values_values->RecordCount() <= 0) {
              for ($i=0, $n = count($languages); $i<$n; $i++) {
                $lid = $languages[$i]['id'];

                $v_attribute_values_name_var = 'v_attribute_values_name_' . $attribute_options_count . '_' . $attribute_values_count . '_' . $lid;

                if (isset($$v_attribute_values_name_var)) {
                  $attribute_values_insert_result = "insert into " . $oostable['products_options_values'] . " (products_options_values_id, products_options_values_languages_id, products_options_values_name) values ('" . (int)$$v_attribute_values_id_var . "', '" . (int)$lid . "', '" . $$v_attribute_values_name_var . "')";

                  $attribute_values_insert = $dbconn->Execute($attribute_values_insert_result);
                }
              }


              // insert values to pov2po table
              $attribute_values_pov2po_result = "insert into " . $oostable['products_options_values_to_products_options'] . " (products_options_id, products_options_values_id) values ('" . (int)$$v_attribute_options_id_var . "', '" . (int)$$v_attribute_values_id_var . "')";

              $attribute_values_pov2po = $dbconn->Execute($attribute_values_pov2po_result);
            } else { // update options table, if options already exists
              for ($i=0, $n = count($languages); $i<$n; $i++) {
                $lid = $languages[$i]['id'];

                $v_attribute_values_name_var = 'v_attribute_values_name_' . $attribute_options_count . '_' . $attribute_values_count . '_' . $lid;

                if (isset($$v_attribute_values_name_var)) {
                  $attribute_values_update_lang_result = "SELECT products_options_values_name FROM " . $oostable['products_options_values'] . " WHERE products_options_values_id = '" . (int)$$v_attribute_values_id_var . "' and products_options_values_languages_id ='" . (int)$lid . "'";

                  $attribute_values_update_lang_values = $dbconn->Execute($attribute_values_update_lang_result);

                  // if options_values name doesn't exist for particular language, insert value
                  if ($attribute_values_update_lang_values->RecordCount() <= 0) {
                    $attribute_values_lang_insert_result = "insert into " . $oostable['products_options_values'] . " (products_options_values_id, products_options_values_languages_id, products_options_values_name) values ('" . (int)$$v_attribute_values_id_var . "', '" . (int)$lid . "', '" . $$v_attribute_values_name_var . "')";

                    $attribute_values_lang_insert = $dbconn->Execute($attribute_values_lang_insert_result);
                  } else { // if options_values name exists for particular language, update table
                    $attribute_values_update_result = "update " . $oostable['products_options_values'] . " set products_options_values_name = '" . $$v_attribute_values_name_var . "' WHERE products_options_values_id ='" . (int)$$v_attribute_values_id_var . "' and products_options_values_languages_id = '" . (int)$lid . "'";

                    $attribute_values_update = $dbconn->Execute($attribute_values_update_result);
                  }
                }
              }
            }
          }
          // options_values table update end

          // options_values price update begin
          $v_attribute_values_price_var = 'v_attribute_values_price_' . $attribute_options_count . '_' . $attribute_values_count;

          if (isset($$v_attribute_values_price_var) && ($$v_attribute_values_price_var != '')) {
            $attribute_prices_result = "SELECT options_values_price, price_prefix FROM " . $oostable['products_attributes'] . " WHERE products_id = '" . (int)$v_products_id . "' and options_id ='" . (int)$$v_attribute_options_id_var . "' and options_values_id = '" . (int)$$v_attribute_values_id_var . "'";

            $attribute_prices_values = $dbconn->Execute($attribute_prices_result);

            $attribute_values_price_prefix = ($$v_attribute_values_price_var < 0) ? '-' : '+';

            // options_values_prices table update begin
            // insert into options_values_prices table if no price exists
            if ($attribute_prices_values->RecordCount() <= 0) {
              $attribute_prices_insert_result = "insert into " . $oostable['products_attributes'] . " (products_id, options_id, options_values_id, options_values_price, price_prefix) values ('" . (int)$v_products_id . "', '" . (int)$$v_attribute_options_id_var . "', '" . (int)$$v_attribute_values_id_var . "', '" . (float)$$v_attribute_values_price_var . "', '" . $attribute_values_price_prefix . "')";

              $attribute_prices_insert = $dbconn->Execute($attribute_prices_insert_result);
            } else { // update options table, if options already exists
              $attribute_prices_update_result = "update " . $oostable['products_attributes'] . " set options_values_price = '" . $$v_attribute_values_price_var . "', price_prefix = '" . $attribute_values_price_prefix . "' WHERE products_id = '" . (int)$v_products_id . "' and options_id = '" . (int)$$v_attribute_options_id_var . "' and options_values_id ='" . (int)$$v_attribute_values_id_var . "'";

              $attribute_prices_update = $dbconn->Execute($attribute_prices_update_result);
            }
          }
          // options_values price update end


          // attributes stock add start
          $v_attribute_values_stock_var = 'v_attribute_values_stock_' . $attribute_options_count . '_' . $attribute_values_count;

          if (isset($$v_attribute_values_stock_var) && ($$v_attribute_values_stock_var != '')) {

            $stock_attributes = $$v_attribute_options_id_var.'-'.$$v_attribute_values_id_var;

            $attribute_stock_query = $dbconn->Execute("SELECT products_stock_quantity FROM " . $oostable['products_stock'] . " WHERE products_id = '" . (int)$v_products_id . "' AND products_stock_attributes ='" . $stock_attributes . "'");   

            // insert into products_stock_quantity table if no stock exists
            if ($attribute_stock_query->RecordCount() <= 0) {
              $attribute_stock_insert_query = $dbconn->Execute("INSERT INTO " . $oostable['products_stock'] . " (products_id, products_stock_attributes, products_stock_quantity) VALUES ('" . (int)$v_products_id . "', '" . $stock_attributes . "', '" . (int)$$v_attribute_values_stock_var . "')");

            } else { // update options table, if options already exists
              $attribute_stock_insert_query = $dbconn->Execute("UPDATE " . $oostable['products_stock'] . " SET products_stock_quantity = '" . (int)$$v_attribute_values_stock_var . "' WHERE products_id = '" . (int)$v_products_id . "' AND products_stock_attributes = '" . $stock_attributes . "'");

              // turn on stock tracking on products_options table
              $stock_tracking_query = $dbconn->Execute("UPDATE " . $oostable['products_options'] . " SET products_options_track_stock = '1' WHERE products_options_id = '" . (int)$$v_attribute_options_id_var . "'");

            }
          } // attributes stock add end

          $attribute_values_count++;
          $v_attribute_values_id_var = 'v_attribute_values_id_' . $attribute_options_count . '_' . $attribute_values_count;
        }

        $attribute_options_count++;
        $v_attribute_options_id_var = 'v_attribute_options_id_' . $attribute_options_count;
      }

      $attribute_rows++;
    } // VJ product attribs end

  } else {
    // this record was missing the product_model
    array_walk($items, 'print_el');
    echo "<p class=smallText>No products_model field in record. This line was not imported <br />";
    echo "<br />";
  } // end of row insertion code
}


require 'includes/oos_nice_exit.php';
?>