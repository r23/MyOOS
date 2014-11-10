<?php
/* ----------------------------------------------------------------------
   $Id: pdf_info.php,v 1.1 2007/06/07 16:50:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 - 2005 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  session_cache_limiter('private');
  $debug = 'false';

  require 'includes/languages/' . $sLanguage . '/news_pdf_info.php';
  require 'includes/classes/class_fpdf.php';

  $sql = "SELECT n.news_id, n.news_image, n.news_date_added, n.news_added_by, n.news_status,
                 nd.news_name, nd.news_description, nd.news_url, nd.news_viewed
          FROM " . $oostable['news'] . " n, 
               " . $oostable['news_description'] . " nd 
          WHERE n.news_status = '1' 
            AND n.news_id = '" . intval($_GET['news_id']) . "' 
            AND nd.news_id = n.news_id 
            AND nd.news_languages_id  = '" .  intval($nLanguageID) . "'";
  $print_news_result = $dbconn->Execute($sql);
  if ($print_news_result->RecordCount()) { 
    $print_news = $print_news_result->fields;
    $author = oos_get_news_author_name($print_news['news_added_by']);
    $title = rtrim(strip_tags($print_news['news_name']));
    $description = rtrim($print_news['news_description']);
    $news_image = $print_news['news_image'];
    $y = 50;

    $pdf = new PDF('P','mm','A4',$title,'',false);
    $pdf->Open();
    $pdf->SetCompression(true);
    $pdf->SetCreator("Script by OOS [OSIS Online Shop], http://www.oos-shop.de/");
    $pdf->AliasNbPages();
    $pdf->SetDisplayMode("real");
    $link = $pdf->AddLink();

    $pdf->SetKeywords('News'); 
    $pdf->SetCreator("Script by OOS [OSIS Online Shop], http://www.oos-shop.de/");
    $pdf->SetTitle($title);
    $pdf->SetAuthor($author);

    $pdf->AddPage();
    $pdf->SetLink($link);
    #$pdf->SetFont('helvetica','B',12);

    $news_name_color_table = explode(",",PRODUCT_NAME_COLOR_TABLE);
    $pdf->SetFillColor($news_name_color_table[0], $news_name_color_table[1], $news_name_color_table[2]);
    $pdf->MultiCell(0,9,$title,0,'L',1);
    $pdf->Ln(10);
    $pdf->SetTextColor(0,0,0);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->SetLineWidth(0.5);
    $pdf->SetDrawColor(210,210,210);  

    $file_type = oos_get_extension($news_image);     
    if ($file_type == 'jpg' || $file_type == 'jpeg' || $file_type == .gif') {
      $pdf->SetLink($link);
      $pdf->Image(OOS_IMAGES . $news_image , 20, $y, 40, 0,'', OOS_HTTP_SERVER . OOS_SHOP . 'index.php?mp=' . $aModules['news'] . '&file=' . $aFilename['news_news'] . '&news_id=' . $print_news['news_id']);
    }
    // change some win codes, and xhtml into html
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
               '&euro;' => '', 
               '&#260;' => '¥', 
               '&trade;' => '', 
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
    $pdf->SetLeftMargin(20);
    $pdf->Ln();
    $pdf->Ln(5);
    $pdf->SetDrawColor(0,0,0);

  }
  $pdf->Output();
?>
