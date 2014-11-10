<?php
/* ----------------------------------------------------------------------
   $Id: pdf_info.php,v 1.2 2007/11/13 00:45:48 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2009 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   pdf_datasheet_creator v1.1 2003/03/11 13:46:29 ip chilipepper.it
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  session_cache_limiter('private');
  $debug = 'false';

  require 'includes/languages/' . $sLanguage . '/products_pdf_info.php';
  require 'includes/classes/class_fpdf.php';

  //Convertion pixels -> mm
  $imagewidth = SMALL_IMAGE_WIDTH * PDF_TO_MM_FACTOR;
  $imageheight = SMALL_IMAGE_HEIGHT * PDF_TO_MM_FACTOR;

  $products_id = oos_get_product_id($_GET['products_id']);

  $productstable = $oostable['products'];
  $products_descriptiontable = $oostable['products_description'];
  $manufacturerstable = $oostable['manufacturers'];
  $specialstable = $oostable['specials'];
  $sql = "SELECT p.products_id, pd.products_name, pd.products_description, p.products_image,
                 p.products_model, p.products_price, p.products_base_price, p.products_base_unit,
                 p.products_tax_class_id, p.products_units_id, p.products_date_added, p.products_date_available, p.products_status,
                 IF(s.status, s.specials_new_products_price, NULL) AS specials_new_products_price,
                 s.expires_date, m.manufacturers_name
                          FROM $products_descriptiontable pd,
                               $productstable p LEFT JOIN
                               $manufacturerstable m ON p.manufacturers_id = m.manufacturers_id LEFT JOIN
                               $specialstable s ON p.products_id = s.products_id
                          WHERE p.products_status >= '1'
                            AND pd.products_id = p.products_id
                            AND pd.products_languages_id = '" .  intval($nLanguageID) . "'";

  $print_catalog_result = $dbconn->Execute($sql);


  if ($print_catalog_result->RecordCount()) {

    $print_catalog = $print_catalog_result->fields;

    $product_price = '';
    $product_special_price = '';
    $product_discount = 0;
    $product_discount_price = '';
    $base_product_price = '';
    $special_price = '';
    $base_product_special_price = $print_catalog['products_base_price'];


    if ($_SESSION['member']->group['show_price'] == 1 ) {
      $product_price = $oCurrencies->display_price($print_catalog['products_price'], oos_get_tax_rate($print_catalog['products_tax_class_id']));

      if ($special_price = oos_get_products_special_price($print_catalog['products_id'])) {
        $product_special_price = $oCurrencies->display_price($special_price, oos_get_tax_rate($print_catalog['products_tax_class_id']));
      } else {
        $product_discount = min($print_catalog['products_discount_allowed'], $_SESSION['member']->group['discount']);

        if ($product_discount != 0 ) {
          $product_special_price = $print_catalog['products_price']*(100-$product_discount)/100;
          $product_discount_price = $oCurrencies->display_price($special_price, oos_get_tax_rate($print_catalog['products_tax_class_id']));
        }

      }

      if ($print_catalog['products_base_price'] != 1) {
        $base_product_price = $oCurrencies->display_price($print_catalog['products_price'] * $print_catalog['products_base_price'], oos_get_tax_rate($print_catalog['products_tax_class_id']));

        if ($special_price != '') {
          $base_product_special_price = $oCurrencies->display_price($special_price * $print_catalog['products_base_price'], oos_get_tax_rate($print_catalog['products_tax_class_id']));
        }
      }
    }


    if (SHOW_SPECIALS_PRICE_EXPIRES == 'true') {
      if (oos_is_not_null($print_catalog['expires_date'])) {
        $specials_expires = $aLang['text_products_specials_price_expires'] .' '. oos_date_long($print_catalog['expires_date']);
      }
    }

    $print_catalog_array = array('id' => $print_catalog['products_id'],
                                 'name' => $print_catalog['products_name'],
                                 'description' => $print_catalog['products_description'],
                                 'model' => $print_catalog['products_model'],
                                 'image' => $print_catalog['products_image'],
                                 'product_price' => $product_price,
                                 'product_special_price' => $product_special_price,
                                 'max_product_discount' => $product_discount,
                                 'product_discount_price' => $product_discount_price,
                                 'base_product_price' => $base_product_price,
                                 'base_product_special_price' => $base_product_special_price,
                                 'specials_expires' => $specials_expires,
                                 'tax_class_id' => $print_catalog['products_tax_class_id'],
                                 'status'=> $print_catalog['products_status'],
                                 'date_added' => oos_date_long($print_catalog['products_date_added']),
                                 'date_available' => oos_date_long($print_catalog['products_date_available']),
                                 'manufacturer' => $print_catalog['manufacturers_name']);


   $pdf = new PDF('P','mm','A4',$print_catalog['products_name'],'',false);
   $pdf->Open();

   $pdf->SetCompression(true);
   $pdf->SetCreator("Script by OOS [OSIS Online Shop], http://www.oos-shop.de/");

   $pdf->AliasNbPages();
   $pdf->SetDisplayMode("real");
   $pdf->SetTitle($print_catalog['products_name']);
   $pdf->SetAuthor(STORE_NAME);

   $link = $pdf->AddLink();
   $pdf->AddPage();

   if (SHOW_PATH == 'true') {
     $lib = oos_get_product_path($_GET['products_id']);
     $header_color_table = explode(",",HEADER_COLOR_TABLE);
     //Title
     $pdf->SetFont('arial','I',10);
     $pdf->SetFillColor($header_color_table[0], $header_color_table[1], $header_color_table[2]);
     $pdf->Cell(0,5,$lib,0,0,'C',1);
     $pdf->Ln(10);
     //Keep Y position
     $ypos = $pdf->GetY();
   }

   $product_name_color_table = explode(",",PRODUCT_NAME_COLOR_TABLE);
   $pdf->SetFont('helvetica','B',12);
   $pdf->SetFillColor($product_name_color_table[0], $product_name_color_table[1], $product_name_color_table[2]);
   $pdf->MultiCell(0,9,$print_catalog_array['name'],0,'L',1);
   $pdf->Ln(10);

   $imagepath = OOS_IMAGES . $print_catalog_array['image'];
   $name = rtrim(strip_tags($print_catalog_array['name']));
   $model = rtrim(strip_tags($print_catalog_array['model']));
   $description = $print_catalog_array['description'];
   $manufacturer = rtrim(strip_tags($print_catalog_array['manufacturer']));
   $product_price = rtrim(strip_tags($print_catalog_array['product_price']));

   $product_special_price = rtrim(strip_tags($print_catalog_array['product_special_price']));
   $specials_expires = rtrim(strip_tags($print_catalog_array['specials_expires']));
   $tax_class_id = rtrim(strip_tags($print_catalog_array['tax_class_id']));

   if ($print_catalog_array['status'] != 0){
     $date = sprintf($aLang['text_date_added'], $print_catalog_array['date_added']);
   } else {
     $date = sprintf($aLang['text_date_available'], $print_catalog_array['date_available']);
   }
   $data_array = array($imagewidth, $imageheight, $model, $name, $date, $manufacturer, $description, $tax_class_id,
                       $specials_price, $product_price, $imagepath, $specials_expires);



   $totallines = 0;
   for ($i=2;$i<(count($data_array)-1);$i++) {
     $totallines += $pdf->NbLines((180-$imagewidth), $data_array[$i]);
   }

   //5 = cells height
   $h = 5*$totallines."<br>";

   //if products description takes the whole page height goes to new page
   if ($h < 260) {
     $pdf->CheckPageBreak($h);
   }

   if ( (SHOW_IMAGES == 'true') && (strlen($imagepath) && ($imagepath != OOS_IMAGES)) ) {
     //If custom image
     if (PDF_IMAGE_KEEP_PROPORTIONS == 'true' ) {
       $heightwidth = @getimagesize($imagepath);
       $factor = $heightwidth[0]/$heightwidth[1];
       $imagewidth=$imagewidth=MAX_IMAGE_WIDTH*PDF_TO_MM_FACTOR;
       $imageheight=$imagewidth/$factor;
       $pdf->ShowImage($imagewidth,$imageheight,$imagepath);
       $y1 = $pdf->GetY();
     } elseif(strlen($imagewidth)>1 && strlen($imageheight)>1) {
       // If Small Image Width and Small Image Height are defined
       $pdf->ShowImage($imagewidth,$imageheight,$imagepath);
       $y1 = $pdf->GetY();
     } elseif(strlen($imagewidth)>1 && strlen($imageheight)) {
       //If only Small Image Width is defined
       $heightwidth = @getimagesize($imagepath);
       $imagewidth=$imagewidth;
       $imageheight=$heightwidth[1]*PDF_TO_MM_FACTOR;
       $pdf->ShowImage($imagewidth,$imageheight,$imagepath);
       $y1 = $pdf->GetY();
     } elseif(strlen($imagewidth) && strlen($imageheight)>1) {
       //If only Small Image Height is defined
       $heightwidth = @getimagesize($imagepath);
       $imagewidth=$width=$heightwidth[0]*PDF_TO_MM_FACTOR;
       $imageheight=$imageheight;
       $pdf->ShowImage($imagewidth,$imageheight,$imagepath);
       $y1 = $pdf->GetY();
     } else {
       $heightwidth = @getimagesize($imagepath);
       $imagewidth=$heightwidth[0]*PDF_TO_MM_FACTOR;
       $imageheight=$heightwidth[1]*PDF_TO_MM_FACTOR;
       $pdf->ShowImage($imagewidth,$imageheight,$imagepath);
       $y1 = $pdf->GetY();
     }
   } else {
     $imagewidth = $imageheight = 0;
     $y1 = $pdf->GetY();
   }
   $pdf->SetFont('times','',11);

   if (SHOW_MODEL == 'true') {
     $pdf->Cell(3,5,"",0,0);
     $pdf->MultiCell(180-$imagewidth,5,$aLang['text_products_model'] . $model,0,'L');
   }

   if (SHOW_NAME == 'true') {
     $pdf->Cell($imagewidth+3,5,"",0,0);
     $pdf->MultiCell(180-$imagewidth,5,$name,0,'L');
   }

   if (SHOW_MANUFACTURER == 'true') {
     $pdf->Cell($imagewidth+3,5,"",0,0);
     $pdf->MultiCell(180-$imagewidth,10,$aLang['text_products_manufacturer'] . $manufacturer,0,'L');
   }

   if (SHOW_DESCRIPTION == 'true') {

    $pdf->SetLeftMargin($imagewidth+13);

    // change some win codes, and xhtml into html
    $str = array('<br />' => '<br>',
               '<hr />' => '<hr>',
               '&ouml;' => 'ö',
               '&auml;' => 'ä',
               '&uuml;' => 'ü',
               '&Ouml;' => 'Ö',
               '&Auml;' => 'Ä',
               '&Uuml;' => 'Ü',
               '&szlig;' => 'ß',
               '<LI>' => '<li>',
               '</LI>' => '</li>',
               '<P>' => '<p>',
               '</P>' => '</p>',
               '&nbsp;' => ' ',
               '&#380;' => '¿',
               '&amp;' => '&',
               '&lt;' => '<',
               '&gt;' => '>',
               '&#728;' => '¢',
               '&#321;' => '£',
               '&euro;' => '?',
               '&#260;' => '¥',
               '&trade;' => '?',
               '&copy;' => '©',
               '&reg;' => '®',
               '[r]' => '<red>',
               '[/r]' => '</red>',
               '[l]' => '<blue>',
               '[/l]' => '</blue>',
               '&quot;' => '"',
               '&#8220;' => '"',
               '&#8221;' => '"',
               '&#8222;' => '"',
               '&#8230;' => '...',
               '&#8217;' => '\''
               );
    foreach ($str as $from => $to) $description = str_replace($from,$to,$description);

    $pdf->WriteHTML($description, true);
    $pdf->SetLeftMargin(12);
    $pdf->Ln(10);

   }

   if (SHOW_TAX_CLASS_ID == 'true') {
     $pdf->Cell($imagewidth+3,5,"",0,0);
     $pdf->MultiCell(180-$imagewidth,5,$tax_class_id,0,'L');
   }

   if (SHOW_PRICE == 'true') {
     if (strlen($product_special_price) && (SHOW_SPECIALS_PRICE == 'true')) {
       $pdf->Cell($imagewidth+3,5,"",0,0);
       $x = $pdf->GetX();
       $y = $pdf->GetY();
       $pdf->MultiCell(187-$imagewidth- $pdf->rMargin,5,$product_price,0,'L',1);
       $pdf->LineString($x,$y,$product_price,5);
     } else if(strlen($product_price)) {
       $pdf->Cell($imagewidth+3,5,"",0,0);
       $pdf->MultiCell(160-$imagewidth,5,$product_price,0,'L');
     }
   }

   if (SHOW_SPECIALS_PRICE == 'true') {
     $pdf->Cell($imagewidth+3,7,"",0,0);
     $pdf->SetTextColor(200,0,0);
     $pdf->MultiCell(180-$imagewidth,7,$product_special_price,0,'L');
     if (strlen($specials_expires) && (SHOW_SPECIALS_PRICE_EXPIRES == 'true')) {
       $pdf->Cell($imagewidth+3,5,"",0,0);
       $pdf->MultiCell(180-$imagewidth,5,$specials_expires,0,'L');
     }
   }

   $x2 = $pdf->GetX();
   $y2 = $pdf->GetY();

   //if products description does not takes the whole page height
   if ($h<260) {
     $pdf->CalculatedSpace($y1,$y2,$imageheight);
   }


 /*
   if (SHOW_OPTIONS == 'true') {
     $pdf->SetTextColor(0,0,0);
     $x = $pdf->GetX();
     $y = $pdf->GetY();
     $pdf->SetLineWidth(0.5);
     $pdf->SetDrawColor(210,210,210);
     $pdf->Line(40,$y,170,$y);
     $pdf->Ln(5);
     $pdf->SetDrawColor(0,0,0);

     $sql = "SELECT
                 distinct popt.products_options_id, popt.products_options_name
             FROM
                " . $oostable['products_options'] . " popt,
                " . $oostable['products_attributes'] . " patrib
            WHERE
                patrib.products_id='" . intval($_GET['products_id']) . "' AND
                patrib.options_id = popt.products_options_id AND
                popt.products_options_languages_id = '" . intval($nLanguageID) . "'";
     $products_options_name = $dbconn->Execute($sql);

     if ($products_options_name->RecordCount()) {
       $pdf->MultiCell(0,8,$aLang['text_products_options'] . $print_catalog_array['name'] .' :',0,'L',0);
       $pdf->Ln(-5);
     }

     if (PRODUCTS_OPTIONS_SORT_BY_PRICE == 'true') {
       $options_sort_by = ' ORDER BY pa.options_sort_order, pa.options_values_price';
     } else {
       $options_sort_by = ' ORDER BY pa.options_sort_order, pov.products_options_values_name';
     }

     while ($products_options_name_values = $products_options_name->fields) {
       $pdf->Ln(6);
       $pdf->SetFont('helvetica','b',11);
       $pdf->Cell(190,5,$products_options_name_values['products_options_name'],0,0,'L');
       $pdf->Ln();

       $sql = "SELECT pov.products_options_values_id, pov.products_options_values_name,
                      pa.options_values_price, pa.price_prefix
               FROM " . $oostable['products_attributes'] . " pa,
                    " . $oostable['products_options_values'] . " pov
               WHERE pa.products_id = '" . intval($_GET['products_id']) . "'
                 AND pa.options_id = '" . $products_options_name_values['products_options_id'] . "'
                 AND pa.options_values_id = pov.products_options_values_id
                 AND pov.products_options_values_languages_id = '" . intval($nLanguageID) . "'" .
                     $options_sort_by;
       $products_options = $dbconn->Execute($sql);

       $count_options_values = $products_options->RecordCount();
       $count_options = 0;

       $products_options_array = array();

       while ($products_options_values = $products_options->fields) {
         $products_options_array[] = array('id' => $products_options_values['products_options_values_id'], 'text' => $products_options_values['products_options_values_name'],  'price_id' => $products_options_values['products_options_values_id'],   'text2' => $products_options_values['options_values_price']);

         $w=$pdf->GetStringWidth($products_options_values['products_options_values_name'])+2;
         $pdf->SetFont('times','',10);
         $pdf->SetTextColor(0,0,200);
         $option_string = $products_options_values['products_options_values_name'] . $option_value;

         if ( $products_options_values['options_values_price'] != ' 0.0000' && SHOW_OPTIONS_PRICE == 'true') {
           $count_options++; $add_to = ($count_options_values != $count_options ? ',' : '.' );
           $pdf->Write(5,$products_options_values['products_options_values_name']. ' (' . $products_options_values['price_prefix'] . $oCurrencies->display_price($products_options_values['options_values_price'], oos_get_tax_rate($product_info_values['products_tax_class_id'])) . ')' . $add_to);
         } else {
           $count_options++; $add_to = ($count_options_values != $count_options ? ',' : '.' );
           $pdf->Write(5,$products_options_values['products_options_values_name'] . $add_to);
         }
         $pdf->Cell(3,6,"",0,0,'C');
         $pdf->SetTextColor(0,0,0);

         $products_options->MoveNext();
       }
       $products_options_name->MoveNext();
     }
   }
 */

    if (SHOW_DATE_ADDED_AVAILABLE == 'true') {
      //Date available
      $x=$pdf->GetX();
      $y=$pdf->GetY();
      $pdf->Ln(10);
      $pdf->SetFont('arial','I',9);
      $new_color_table=explode(",",FOOTER_CELL_BG_COLOR);
      $pdf->SetFillColor($new_color_table[0], $new_color_table[1], $new_color_table[2]);
      $pdf->MultiCell(0,5,$date,0,'L',0);
    }
  }
  // Prints content to browser

  $pdf->Output();
?>