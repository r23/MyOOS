<?php
/* ----------------------------------------------------------------------
   $Id: oostables160.php,v 1.1 2007/06/13 16:41:18 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   POST-NUKE Content Management System
   Copyright (C) 2001 by the Post-Nuke Development Team.
   http://www.postnuke.com/
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

function dosql($table, $flds)
{
    global $db;

    $dict = NewDataDictionary($db);

    // $dict->debug = 1;
    $taboptarray = array('mysql' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;', 'REPLACE');

    $sqlarray = $dict->createTableSQL($table, $flds, $taboptarray);
    $dict->executeSqlArray($sqlarray);



    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $table . " " . MADE . '</font>';
}

function idxsql($idxname, $table, $idxflds)
{
    global $db;

    $dict = NewDataDictionary($db);

    $sqlarray = $dict->CreateIndexSQL($idxname, $table, $idxflds);
    $dict->executeSqlArray($sqlarray);
}


$table = $prefix_table . 'products_video';
$flds = "
  video_id I I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL DEFAULT '1' PRIMARY,
  video_source C(255) NULL,
  video_mp4 C(255) NULL,
  video_webm C(255) NULL,
  video_ogv C(255) NULL,  
  video_poster C(255) NULL,
  video_preload C(10) DEFAULT 'auto',
  video_data_setup C(255) NULL,
  video_date_added T,
  video_last_modified T 
";
dosql($table, $flds);


$table = $prefix_table . 'products_video_description';
$flds = "
  video_id I DEFAULT '0' NOTNULL PRIMARY,
  video_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  video_title C(255) NULL,
  video_description X, 
  video_viewed I2 DEFAULT '0'
";
dosql($table, $flds);


$idxname = 'idx_video_title';
$idxflds = 'video_title';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'categories_slider';
$flds = "
  slider_id I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL DEFAULT '0',
  slider_image C(255),
  slider_date_added T,
  slider_last_modified T,
  expires_date T,
  date_status_change T,
  status I1 NOTNULL DEFAULT '1'
";
dosql($table, $flds);
