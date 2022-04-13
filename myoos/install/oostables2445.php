<?php
/* ----------------------------------------------------------------------
   $Id: oostables160.php,v 1.1 2007/06/13 16:41:18 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2022 by the MyOOS Development Team.
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

    $taboptarray = array('mysql' => 'TYPE=MyISAM', 'REPLACE');

    $sqlarray = $dict->CreateTableSQL($table, $flds, $taboptarray);
    $dict->ExecuteSQLArray($sqlarray);

    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $table . " " . MADE . '</font>';
}

function idxsql($idxname, $table, $idxflds)
{
    global $db;

    $dict = NewDataDictionary($db);

    $sqlarray = $dict->CreateIndexSQL($idxname, $table, $idxflds);
    $dict->ExecuteSQLArray($sqlarray);
}


$table = $prefix_table . 'products_price_history';
$flds = "
  products_price_history_id I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL DEFAULT '0',
  old_products_price N '10.4' NOTNULL DEFAULT '0.0000',
  date_added T
";
dosql($table, $flds);

$idxname = 'idx_date_added';
$idxflds = 'date_added';
idxsql($idxname, $table, $idxflds);


